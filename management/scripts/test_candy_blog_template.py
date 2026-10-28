"""Scaffold integration regressions using disposable local files only."""
from __future__ import annotations

import argparse
import contextlib
import io
import json
import shutil
import tempfile
import unittest
from pathlib import Path
from unittest.mock import patch

import candy_blog_template as template
import candy_page_common as common


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
        index = common.synchronize_blog_top_index(common.read_utf8(self.hp / "source/index.html"), blog)
        (self.hp / "source/index.html").write_text(index, encoding="utf-8")
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


if __name__ == "__main__":
    unittest.main()
