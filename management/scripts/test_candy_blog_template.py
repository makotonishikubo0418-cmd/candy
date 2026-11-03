"""Scaffold integration regressions using disposable local files only."""
from __future__ import annotations

import argparse
import contextlib
import io
import html
import json
import re
import shutil
import tempfile
import unittest
from pathlib import Path
from html.parser import HTMLParser
from types import SimpleNamespace
from unittest.mock import patch

import candy_blog_template as template
import candy_page_common as common
import candy_blog_page as page


class TopBlogDOM(HTMLParser):
    """Independent test oracle: inspect element parents, not production offsets."""
    def __init__(self, source):
        super().__init__()
        self.root = {"tag": "root", "attrs": {}, "parent": None}
        self.stack = [self.root]
        self.nodes = []
        self.feed(source)

    def handle_starttag(self, tag, attrs):
        node = {"tag": tag, "attrs": dict(attrs), "parent": self.stack[-1]}
        self.nodes.append(node)
        if tag not in {"br", "img", "hr", "input", "meta", "link", "source", "wbr"}:
            self.stack.append(node)

    def handle_endtag(self, tag):
        if len(self.stack) < 2 or self.stack[-1]["tag"] != tag:
            raise AssertionError("Invalid test DOM closing tag: " + tag)
        self.stack.pop()

    def assert_frame(self, test, expected_hrefs):
        frames = [n for n in self.nodes if "lp_0_55_35" in n["attrs"].get("class", "").split()]
        test.assertEqual(len(frames), 1)
        frame = frames[0]
        links = [n for n in self.nodes if n["tag"] == "a" and n["attrs"].get("href", "").startswith("./kagoshima-deliveryhealth-blog-")]
        test.assertEqual([n["attrs"]["href"] for n in links], expected_hrefs)
        for link in links:
            test.assertIs(link["parent"]["parent"], frame)
        buttons = [n for n in self.nodes if n["attrs"].get("href") == "./blog.php"]
        test.assertEqual(len(buttons), 1)
        test.assertIs(buttons[0]["parent"]["parent"], frame["parent"])
        test.assertEqual(len(self.stack), 1)


class TopBlogStructureTests(unittest.TestCase):
    INFO = '<div class="lp_5 lm_0_auto w_130 center bg_p fs_xs fc_w">BLOG INFO</div>'
    FRAME = '<div class="lp_0_55_35 w_1050 lm_0_auto bg_f">'
    BUTTON = '<div class="center"><a href="./blog.php" class="bt-pk-xl lm_40_0_85">ブログ一覧はコチラ</a></div>'

    @classmethod
    def fixtures(cls, count):
        # Expected rows are defined here, never by the synchronizer under test.
        names = [(f"kagoshima-deliveryhealth-blog-fixture-{n}.php", f"記事{n} & 案内") for n in range(count)]
        blog = cls.INFO + "\n" + "\n".join(f'<div><a href="./{href}">{html.escape(title)}</a></div>' for href, title in names) + "\n" + common.CATEGORY_INDEX_TERMINAL_CTA
        rows = []
        for n, (href, title) in enumerate(names[-15:]):
            style = "lp_20_0 fs_sm2" if n == 0 else "lp_14_0 fs_sm2 bd_t"
            rows.append(f'\t\t\t<div class="{style}"><a href="./{href}" class="fade">{html.escape(title)}</a></div>')
        top = ('<!-- unchanged before -->\n<!-- スタッフブログ START -->\n<h2>スタッフブログ</h2>\n' + cls.FRAME + "\n" + cls.INFO + "\n" + "\n".join(rows) + "\n\t\t\t</div>\n" + cls.BUTTON + '\n<!-- スタッフブログ END -->\n<!-- unchanged after -->')
        return blog, top, names

    def test_registry_boundaries_and_repeat_sync(self):
        for count in (0, 1, 14, 15, 16, 20):
            with self.subTest(count=count):
                blog, top, names = self.fixtures(count)
                self.assertEqual(common.blog_registry_alignment_errors(blog, top), [])
                result = common.synchronize_blog_top_index(top, blog)
                TopBlogDOM(result).assert_frame(self, ["./" + name for name, _ in names[-15:]])
                self.assertEqual(common.blog_registry_alignment_errors(blog, result), [])
                self.assertEqual(common.synchronize_blog_top_index(result, blog), result)
                self.assertTrue(result.startswith('<!-- unchanged before -->'))
                self.assertTrue(result.endswith('<!-- unchanged after -->'))

    def test_broken_fixed_fixtures_are_rejected(self):
        blog, top, _ = self.fixtures(15)
        early_close = top.replace(self.INFO + "\n", self.INFO + "\n</div>\n").replace("\n\t\t\t</div>\n" + self.BUTTON, "\n" + self.BUTTON)
        broken = {
            "early close": early_close,
            "duplicate frame": top.replace(self.FRAME, self.FRAME + "</div>\n" + self.FRAME),
            "missing close": top.replace("\n\t\t\t</div>\n" + self.BUTTON, "\n" + self.BUTTON),
            "extra close": top.replace(self.BUTTON, "</div>\n" + self.BUTTON),
            "button inside": top.replace("\n\t\t\t</div>\n" + self.BUTTON, "\n" + self.BUTTON + "</div>"),
            "duplicate button": top.replace(self.BUTTON, self.BUTTON * 2),
            "duplicate info": top.replace(self.INFO, self.INFO * 2),
            "duplicate section": top + top,
        }
        for label, source in broken.items():
            with self.subTest(label=label):
                self.assertTrue(common.blog_registry_alignment_errors(blog, source))
                with self.assertRaises(common.PageToolError):
                    common.synchronize_blog_top_index(source, blog)

    def test_counts_order_and_names_must_match(self):
        blog, top, _ = self.fixtures(14)
        for altered in (top.replace("fixture-0.php", "fixture-1.php"), top.replace("記事0", "別名"), self.fixtures(15)[1]):
            self.assertTrue(common.blog_registry_alignment_errors(blog, altered))


class BlogTemplateTests(unittest.TestCase):
    def setUp(self):
        self.temporary = tempfile.TemporaryDirectory(prefix="candy-blog-template-")
        self.addCleanup(self.temporary.cleanup)
        self.root = Path(self.temporary.name)
        self.hp = self.root / "HP"
        original = common.repo_root()
        paths = [original / "management/template/BLOG_TEMPLATE.md"]
        paths += list((original / "HP").glob("*.php"))
        paths += list((original / "HP/source").glob("kagoshima-deliveryhealth-blog-*.html"))
        paths += list((original / "HP/includefile").glob("dataset_kagoshima-deliveryhealth-blog-*.php"))
        paths += [original / "HP/source" / f"kagoshima-deliveryhealth-area-{slug}.html"
                  for slug in common.RELATED_AREA_SLUGS]
        paths += [original / "HP" / p for p in (
            "source/blog.html", "source/index.html", "sitemap.xml", "includefile/dataset_base.php",
            "css/blog.css", "css/default.css", "source/style.css", "js/common.js",
            "js/amadare_webapp2.4.php", "favicon.ico", "imgHtml/new_202601/sample.jpg")]
        for source in paths:
            target = self.root / source.relative_to(original)
            target.parent.mkdir(parents=True, exist_ok=True)
            shutil.copyfile(source, target)
        self.addCleanup(patch.stopall)
        patch.object(common, "REPO_ROOT", self.root).start()
        patch.object(common, "HP_ROOT", self.hp).start()
        # Repair only test-fixture inputs, without altering the real checkout.
        blog = common.read_utf8(self.hp / "source/blog.html")
        # Preserve the actual input. Tests must not repair it using the SUT.
        sitemap = common.read_utf8(self.hp / "sitemap.xml")
        for name, _ in common.blog_registry_links(blog, top_page=False):
            sitemap = common.update_sitemap(sitemap, template.ORIGIN + name)
        (self.hp / "sitemap.xml").write_text(sitemap, encoding="utf-8")
        self.url = template.ORIGIN + template.PREFIX + "template_test.php"
        self.title = '検証用ブログ「A & B」"タイトル"'

    def snapshot(self):
        return {p.relative_to(self.root): p.read_bytes() for p in self.root.rglob("*") if p.is_file()}

    def create(self, dry_run=False):
        with contextlib.redirect_stdout(io.StringIO()):
            return template.run(argparse.Namespace(command="create", url=self.url, title=self.title, dry_run=dry_run))

    def test_full_bundle_php_json_and_registry_integration(self):
        before_map = common.read_utf8(self.hp / "sitemap.xml")
        self.assertEqual(self.create(), 0)
        template.check(self.url, self.title)
        source = common.read_utf8(common.bundle_paths("blog", "template_test")[1])
        blocks = [json.loads(p) for p in template.JSON_RE.findall(source)]
        self.assertEqual([b["@type"] for b in blocks], ["Article", "BreadcrumbList"])
        self.assertEqual(blocks[0]["headline"], self.title)
        self.assertIn(template.PENDING, source)
        self.assertNotIn("staff-blog-template-placeholder", source)
        self.assertEqual(set(template.sitemap_urls(common.read_utf8(self.hp / "sitemap.xml"))),
                         set(template.sitemap_urls(before_map)) | {self.url})
        self.assertTrue(all(p.is_file() for p in common.bundle_paths("blog", "template_test")))
        with contextlib.redirect_stdout(io.StringIO()) as output:
            template.run(argparse.Namespace(command="check", url=self.url, title=self.title))
        self.assertIn("BODY=PENDING", output.getvalue())

    def test_dry_run_no_writes(self):
        before = self.snapshot()
        self.create(dry_run=True)
        self.assertEqual(before, self.snapshot())

    def test_existing_file_and_registration_are_never_overwritten(self):
        self.create()
        source = common.bundle_paths("blog", "template_test")[1]
        source.write_text(source.read_text(encoding="utf-8") + "\n<!-- user edit -->", encoding="utf-8")
        before = self.snapshot()
        with self.assertRaisesRegex(common.PageToolError, "既存ファイル"):
            self.create()
        self.assertEqual(before, self.snapshot())

    def test_invalid_urls_and_titles_stop_before_writes(self):
        before = self.snapshot()
        for url in (self.url + "?a=1", self.url + "#x", self.url.replace("https:", "http:"),
                    self.url.replace("55810.com", "example.com"), self.url.replace("template_test", "../escape")):
            with self.subTest(url=url), self.assertRaises(common.PageToolError):
                template.plan(url, self.title)
        for title in ("", " ", " leading", "line\nbreak"):
            with self.subTest(title=title), self.assertRaises(common.PageToolError):
                template.plan(self.url, title)
        self.assertEqual(before, self.snapshot())

    def test_stale_top_or_sitemap_is_not_silently_repaired(self):
        for filename in ("source/index.html", "sitemap.xml"):
            path = self.hp / filename
            original = path.read_bytes()
            content = path.read_text(encoding="utf-8")
            content = content.replace("kagoshima-deliveryhealth-blog-tallbeautygirl.php", "kagoshima-deliveryhealth-blog-missing.php")
            path.write_text(content, encoding="utf-8")
            before = self.snapshot()
            with self.subTest(filename=filename), self.assertRaisesRegex(common.PageToolError, "既存登録"):
                template.plan(self.url, self.title)
            self.assertEqual(before, self.snapshot())
            path.write_bytes(original)

    def test_latest_fifteen_after_multiple_additions(self):
        # Exercise the full transaction repeatedly, with real PHP lint in the
        # single-bundle test rather than starting WSL for each fixture addition.
        for number in range(5):
            url = template.ORIGIN + template.PREFIX + f"new_test_{number}.php"
            title = f"追加記事{number}"
            outputs, before = template.plan(url, title)
            common.write_transaction(outputs, lambda: template.check(url, title), expected=before)
        blog = common.blog_registry_links(common.read_utf8(self.hp / "source/blog.html"), top_page=False)
        top = common.blog_registry_links(common.read_utf8(self.hp / "source/index.html"), top_page=True)
        self.assertGreater(len(blog), 15)
        self.assertEqual(top, blog[-15:])
        self.assertEqual(len(top), 15)

    def test_failed_validation_restores_exact_bytes(self):
        outputs, before = template.plan(self.url, self.title)
        snapshot = self.snapshot()
        def reject():
            raise common.PageToolError("injected failure")
        with self.assertRaisesRegex(common.PageToolError, "injected failure"):
            common.write_transaction(outputs, reject, expected=before)
        self.assertEqual(snapshot, self.snapshot())

    def test_title_cannot_break_json_script(self):
        blocks = template.code_blocks()
        title = '引用 </script><script>alert("x")</script> & {{URL_HTML}}'
        source = template.render_source(blocks["source-html"], self.url, title, "template_test")
        parsed = [json.loads(p) for p in template.JSON_RE.findall(source)]
        self.assertEqual(parsed[0]["headline"], title)
        self.assertNotIn('<script>alert("x")', source)
        self.assertIn("{{URL_HTML}}", parsed[0]["headline"])

    def test_php_lint_failure_stops_before_any_write(self):
        before = self.snapshot()
        with patch.object(common, "lint_planned_php", side_effect=common.PageToolError("planned PHP lint UNAVAILABLE")):
            with self.assertRaisesRegex(common.PageToolError, "UNAVAILABLE"):
                self.create()
        self.assertEqual(before, self.snapshot())

    def test_unhandled_index_json_list_stops_before_any_write(self):
        path = self.hp / "source/blog.html"
        content = path.read_text(encoding="utf-8")
        content = content.replace("</head>", '<script type="application/ld+json">{"@context":"https://schema.org","@type":"ItemList","itemListElement":[]}</script></head>')
        path.write_text(content, encoding="utf-8")
        before = self.snapshot()
        with self.assertRaisesRegex(common.PageToolError, "記事列挙JSON-LD"):
            template.plan(self.url, self.title)
        self.assertEqual(before, self.snapshot())

    def _break_top_blog_frame(self, index_html: str) -> str:
        info = '<div class="lp_5 lm_0_auto w_130 center bg_p fs_xs fc_w">BLOG INFO</div>'
        first_row = '\t\t\t<div class="lp_20_0 fs_sm2">'
        self.assertIn(info + "\n" + first_row, index_html)
        broken = index_html.replace(
            info + "\n" + first_row,
            info + "\n\t\t</div>\n" + first_row,
            1,
        )
        button = '<div class="center"><a href="./blog.php" class="bt-pk-xl lm_40_0_85">ブログ一覧はコチラ</a></div>'
        # Drop the real frame close that remains immediately before the list button.
        close_and_button = re.search(r"\r?\n[ \t]*</div>(\r?\n[ \t]*" + re.escape(button) + r")", broken)
        self.assertIsNotNone(close_and_button)
        return broken[: close_and_button.start()] + close_and_button.group(1) + broken[close_and_button.end() :]

    def test_broken_top_blog_frame_is_detected_and_blocks_sync(self):
        index_path = self.hp / "source/index.html"
        blog = common.read_utf8(self.hp / "source/blog.html")
        original = index_path.read_text(encoding="utf-8")
        broken = self._break_top_blog_frame(original)
        self.assertNotEqual(original, broken)
        errors = common.blog_registry_alignment_errors(blog, broken)
        self.assertTrue(any("枠" in item for item in errors), errors)
        with self.assertRaisesRegex(common.PageToolError, "枠"):
            common.synchronize_blog_top_index(broken, blog)
        index_path.write_text(broken, encoding="utf-8")
        before = self.snapshot()
        with self.assertRaises(common.PageToolError):
            template.plan(self.url, self.title)
        with self.assertRaises(common.PageToolError):
            page.registry_sources(SimpleNamespace(slug="template_test", canonical=self.url, title=self.title))
        self.assertEqual(before, self.snapshot())

    def test_normal_build_validation_failure_restores_bytes(self):
        data = SimpleNamespace(slug="template_test", canonical=self.url, title=self.title)
        before = self.snapshot()
        with patch.object(page, "parse_blog_text", return_value=data), \
             patch.object(page, "render_source", return_value=("<html>test</html>", [])), \
             patch.object(page, "validate_rendered", side_effect=[[], ["injected failure"]]), \
             patch.object(common, "shared_validation", return_value=[]), \
             patch.object(common, "php_lint", return_value=("OK", [])):
            with self.assertRaisesRegex(page.BlogToolError, "injected failure"):
                page.run_build(SimpleNamespace(input="unused.txt", force=False, dry_run=False))
        self.assertEqual(before, self.snapshot())

    def test_top_blog_sync_keeps_entries_inside_frame(self):
        blog = common.read_utf8(self.hp / "source/blog.html")
        index_path = self.hp / "source/index.html"
        before_outside = self._outside_staff_blog(index_path.read_text(encoding="utf-8"))
        synced = common.synchronize_blog_top_index(index_path.read_text(encoding="utf-8"), blog)
        index_path.write_text(synced, encoding="utf-8")
        frame = common.locate_top_blog_frame(synced)
        self.assertEqual(len(frame["frame_links"]), 15)
        self.assertEqual(common.blog_registry_links(synced, top_page=True), common.blog_registry_links(blog, top_page=False)[-15:])
        self.assertEqual([], common.blog_registry_alignment_errors(blog, synced))
        self.assertEqual(before_outside, self._outside_staff_blog(synced))
        section = synced.split("<!-- スタッフブログ START -->")[1].split("<!-- スタッフブログ END -->")[0]
        TopBlogDOM(section).assert_frame(self, ["./" + href for href, _ in common.blog_registry_links(blog, top_page=False)[-15:]])

    def test_top_blog_sync_is_idempotent_and_preserves_outside(self):
        blog = common.read_utf8(self.hp / "source/blog.html")
        index_path = self.hp / "source/index.html"
        first = common.synchronize_blog_top_index(index_path.read_text(encoding="utf-8"), blog)
        outside = self._outside_staff_blog(first)
        second = common.synchronize_blog_top_index(first, blog)
        self.assertEqual(first, second)
        self.assertEqual(outside, self._outside_staff_blog(second))

    def test_multiple_blog_additions_keep_top_frame_structure(self):
        for number in range(3):
            url = template.ORIGIN + template.PREFIX + f"frame_test_{number}.php"
            title = f"枠検証記事{number}"
            outputs, before = template.plan(url, title)
            common.write_transaction(outputs, lambda url=url, title=title: template.check(url, title), expected=before)
        blog = common.read_utf8(self.hp / "source/blog.html")
        index = common.read_utf8(self.hp / "source/index.html")
        self.assertEqual([], common.blog_registry_alignment_errors(blog, index))
        frame = common.locate_top_blog_frame(index)
        self.assertEqual(len(frame["frame_links"]), 15)
        self.assertEqual(common.blog_registry_links(index, top_page=True), common.blog_registry_links(blog, top_page=False)[-15:])

    def _outside_staff_blog(self, index_html: str) -> str:
        start = index_html.find("<!-- スタッフブログ START -->")
        end = index_html.find("<!-- スタッフブログ END -->", start)
        self.assertGreaterEqual(start, 0)
        self.assertGreater(end, start)
        return index_html[:start] + index_html[end:]


if __name__ == "__main__":
    unittest.main()
