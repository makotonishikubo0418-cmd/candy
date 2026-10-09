"""Offline regressions. No Git mutations, network, or production writes."""
from __future__ import annotations

import argparse
from contextlib import redirect_stdout, redirect_stderr
import io
import json
import os
import shutil
from pathlib import Path
import subprocess
import sys
import tempfile
import unittest
from unittest.mock import patch

from PIL import Image

import candy_area_page as area
import candy_area_target_gate as area_gate
import candy_hotel_page as hotel
import candy_hotel_publish as publisher
import candy_hotel_target_gate as hotel_gate
import candy_image_assets as assets
import candy_page_common as common
import candy_site_state as state
import candy_tool as tool
import candy_existing_audit as existing


class RecoveryTests(unittest.TestCase):
    def test_current_paths(self):
        self.assertEqual(common.DOCS_DIR, common.REPO_ROOT / "management/specs")
        self.assertTrue(area.RELATED_LINKS_PATH.is_file())
        self.assertTrue(area_gate.QUEUE_PATH.is_file())
        self.assertTrue(all(path.parent == state.GENERATED_DIR for path in common.site_state_output_paths()))

    def test_existing_audit_keeps_invalid_duplicate_and_unreadable_input_visible(self):
        with tempfile.TemporaryDirectory() as directory:
            root = Path(directory)
            hp = root / "HP"
            (hp / "source").mkdir(parents=True)
            (hp / "kagoshima-deliveryhealth-area-town.php").write_text("<?php", encoding="utf-8")
            good, bad, unreadable = [root / name for name in ("good.txt", "bad.txt", "unreadable.txt")]
            bad.write_text("https://www.55810.com/kagoshima-deliveryhealth-area-town.php", encoding="utf-8")
            unreadable.write_bytes(b"\xff")
            def parse(path):
                if path == good:
                    return argparse.Namespace(slug="town")
                raise common.PageToolError("invalid input")
            with patch.object(common, "REPO_ROOT", root), patch.object(common, "HP_ROOT", hp), \
                 patch.object(common, "text_input_paths", return_value=[good, bad, unreadable]), \
                 patch.object(area, "parse_area_text", side_effect=parse), \
                 patch.object(area, "load_shop_templates", return_value={}), \
                 patch.object(common, "shared_validation", return_value=[]), \
                 patch.object(common, "php_lint", return_value=("PASSED", [])), redirect_stdout(io.StringIO()) as output:
                rows = existing.collect("area")
            self.assertEqual(len(rows), 1)
            self.assertEqual(rows[0]["input_status"], "DUPLICATE")
            self.assertEqual(len(rows[0]["input_issues"]), 1)
            self.assertIn("INPUT_STOP=", output.getvalue())

    def test_recursive_inputs_and_empty_stop(self):
        with tempfile.TemporaryDirectory(prefix="Candy 日本語 ") as directory:
            root = Path(directory)
            with self.assertRaises(common.PageToolError):
                common.text_input_paths(root)
            nested = root / "分類" / "01_間違い無し"
            nested.mkdir(parents=True)
            (nested / "町.txt").write_text("input", encoding="utf-8")
            (root / "direct.txt").write_text("input", encoding="utf-8")
            self.assertEqual(len(common.text_input_paths(root)), 2)

    def test_missing_invalid_duplicate_queue(self):
        with tempfile.TemporaryDirectory() as directory:
            queue = Path(directory) / "queue.md"
            with patch.object(area_gate, "QUEUE_PATH", queue):
                with self.assertRaises(common.PageToolError):
                    area_gate.ready_queue_rows()
                queue.write_text("broken", encoding="utf-8")
                with self.assertRaises(common.PageToolError):
                    area_gate.ready_queue_rows()
                row = "| 1 | 町 | `town` | READY_CANDIDATE | note |\n"
                queue.write_text(row + row, encoding="utf-8")
                with self.assertRaises(common.PageToolError):
                    area_gate.ready_queue_rows()

    def test_all_dispatched_commands_offer_help(self):
        with tempfile.TemporaryDirectory(prefix="Candy 日本語 ") as directory:
            for category, commands in tool.COMMANDS.items():
                for command in commands:
                    with self.subTest(category=category, command=command):
                        result = subprocess.run([sys.executable, "-B", str(common.SCRIPTS_DIR / "candy_tool.py"), category, command, "--help"],
                                                cwd=directory, capture_output=True, text=True, encoding="utf-8")
                        self.assertEqual(result.returncode, 0, result.stderr)
                self.assertFalse(list(Path(directory).iterdir()))

    def test_batch_dry_run_never_locks(self):
        with patch.object(sys, "argv", ["publisher", "publish-next", "--dry-run"]), \
             patch.object(publisher, "publish_lock", side_effect=AssertionError("must not lock")), \
             patch.object(publisher, "publish_next_batch", return_value=0) as batch:
            self.assertEqual(publisher.main(), 0)
            batch.assert_called_once_with(1, dry_run=True, verbose_candidates=False)

    def test_transaction_second_write_failure_restores_exact_bytes(self):
        with tempfile.TemporaryDirectory() as directory:
            first, second = Path(directory) / "first", Path(directory) / "second"
            original = b"\xef\xbb\xbfexisting\r\n"
            first.write_bytes(original)
            writer = common.atomic_write_bytes
            def fail_second(path, value):
                if path == second:
                    raise OSError("injected write failure")
                writer(path, value)
            with patch.object(common, "atomic_write_bytes", side_effect=fail_second):
                with self.assertRaises(OSError):
                    common.write_transaction({first: "new\n", second: "new"}, lambda: None)
            self.assertEqual(first.read_bytes(), original)
            self.assertFalse(second.exists())

    def test_transaction_postcheck_rollback_and_concurrent_preservation(self):
        with tempfile.TemporaryDirectory() as directory:
            first = Path(directory) / "first"
            first.write_bytes(b"original")
            def fail():
                raise ValueError("postcheck")
            with self.assertRaises(ValueError):
                common.write_transaction({first: "new"}, fail)
            self.assertEqual(first.read_bytes(), b"original")
            def concurrent_change():
                first.write_bytes(b"someone else's change")
                raise ValueError("postcheck")
            with self.assertRaisesRegex(common.PageToolError, "preserved"):
                common.write_transaction({first: "new"}, concurrent_change)
            self.assertEqual(first.read_bytes(), b"someone else's change")

    def test_transaction_rejects_changed_plan_and_is_idempotent(self):
        with tempfile.TemporaryDirectory() as directory:
            first = Path(directory) / "first"
            first.write_bytes(b"old\r\n")
            before = common.snapshot_paths([first])
            first.write_bytes(b"changed")
            with self.assertRaises(common.PageToolError):
                common.write_transaction({first: "new"}, lambda: None, expected=before)
            self.assertEqual(first.read_bytes(), b"changed")
            first.write_bytes(b"new\r\n")
            with patch.object(common, "atomic_write_bytes", side_effect=AssertionError("idempotent write")):
                common.write_transaction({first: "new\n"}, lambda: None)

    def test_bad_php_is_rejected_before_write(self):
        with self.assertRaises(common.PageToolError):
            common.lint_planned_php({Path("bad.php"): "<? broken syntax;"})

    def image_fixture(self, root):
        accepted = [root / f"accepted_{i}.jpg" for i in (1, 2)]
        public = [root / f"public_{i}.jpg" for i in (1, 2)]
        for path, color in zip(accepted, ("red", "blue")):
            Image.new("RGB", (1000, 750), color).save(path, "JPEG")
        return accepted, public

    def test_image_matrix(self):
        with tempfile.TemporaryDirectory() as directory:
            accepted, public = self.image_fixture(Path(directory))
            self.assertEqual(assets.inspect_pair(accepted, public)["state"], "ACCEPTED_SOURCE_PRESENT")
            assets.install_pair(accepted, public)
            self.assertEqual(assets.inspect_pair(accepted, public)["state"], "INSTALLED_LOCAL")
            public[1].write_bytes(accepted[0].read_bytes())
            self.assertEqual(assets.inspect_pair(accepted, public)["state"], "STOP")
            Image.new("RGB", (1000, 750), "green").save(public[1], "JPEG")
            self.assertEqual(assets.inspect_pair(accepted, public)["state"], "REVIEW")
            for path in accepted:
                path.unlink()
            self.assertEqual(assets.inspect_pair(accepted, public)["state"], "LEGACY_PUBLIC_ONLY")
            public[0].unlink()
            self.assertEqual(assets.inspect_pair(accepted, public)["state"], "STOP")
            public[1].unlink()
            self.assertEqual(assets.inspect_pair(accepted, public)["state"], "MISSING")

    def test_local_install_needs_acceptance_but_not_publication_permission(self):
        with tempfile.TemporaryDirectory() as directory:
            root = Path(directory)
            accepted, public = self.image_fixture(root)
            data = argparse.Namespace(slug="test", image1="./public_1.jpg", image2="./public_2.jpg")
            with patch.object(common, "REPO_ROOT", root), patch.object(common, "HP_ROOT", root), \
                 patch.object(common, "TEXT_HOTEL_DIR", root), \
                 patch.object(hotel, "parse_hotel_text", return_value=data), \
                 patch.object(assets, "pair_paths", return_value=(accepted, public)), redirect_stdout(io.StringIO()):
                base = ["images", "image-install", "--input", str(root / "test.txt")]
                with patch.object(sys, "argv", base + ["--dry-run"]):
                    self.assertEqual(assets.main(), 0)
                self.assertFalse(any(path.exists() for path in public))
                with patch.object(sys, "argv", base):
                    with self.assertRaisesRegex(common.PageToolError, "image-result-pass"):
                        assets.main()
                self.assertFalse(any(path.exists() for path in public))
                with patch.object(sys, "argv", base + ["--image-result-pass"]):
                    self.assertEqual(assets.main(), 0)
                self.assertEqual([path.read_bytes() for path in accepted], [path.read_bytes() for path in public])
                self.assertFalse((root / ".git").exists())

    def test_image_integrity_dimensions_and_mode(self):
        with tempfile.TemporaryDirectory() as directory:
            accepted, public = self.image_fixture(Path(directory))
            for mode, size in (("RGB", (20, 10)), ("L", (1000, 750))):
                Image.new(mode, size).save(accepted[1], "JPEG")
                self.assertEqual(assets.inspect_pair(accepted, public)["state"], "STOP")
            accepted[1].write_bytes(b"broken")
            self.assertEqual(assets.inspect_pair(accepted, public)["state"], "STOP")

    def test_cross_hotel_duplicate_is_rejected(self):
        with tempfile.TemporaryDirectory() as directory:
            accepted, public = self.image_fixture(Path(directory))
            (Path(directory) / "anotherhotel_1.jpg").write_bytes(accepted[0].read_bytes())
            self.assertEqual(assets.inspect_pair(accepted, public, check_other_hotels=True)["state"], "STOP")

    def test_changed_accepted_image_stops_before_install(self):
        with tempfile.TemporaryDirectory() as directory:
            accepted, public = self.image_fixture(Path(directory))
            status = assets.inspect_pair(accepted, public)
            Image.new("RGB", (1000, 750), "green").save(accepted[1], "JPEG")
            with self.assertRaisesRegex(common.PageToolError, "changed after validation"):
                assets.install_pair(accepted, public, expected_hashes=status["hashes"])
            self.assertFalse(any(path.exists() for path in public))

    def test_image_install_second_failure_rolls_back_without_overwrite(self):
        with tempfile.TemporaryDirectory() as directory:
            accepted, public = self.image_fixture(Path(directory))
            original_link = os.link
            def fail_second(src, dst):
                if dst == public[1]:
                    raise OSError("second copy failure")
                original_link(src, dst)
            with patch.object(assets.os, "link", side_effect=fail_second):
                with self.assertRaises(OSError):
                    assets.install_pair(accepted, public)
            self.assertFalse(any(path.exists() for path in public))
            self.assertEqual(len(list(Path(directory).iterdir())), 2)
            public[0].write_bytes(b"user file")
            with self.assertRaises(common.PageToolError):
                assets.install_pair(accepted, public)
            self.assertEqual(public[0].read_bytes(), b"user file")

    def test_pending_images_never_promoted_to_ready(self):
        self.assertEqual(hotel_gate.category_from_blockers([hotel_gate.BLOCK_IMAGE_PENDING]), hotel_gate.IMAGE_PENDING)
        self.assertNotEqual(hotel_gate.IMAGE_PENDING, hotel_gate.READY)
        result = hotel_gate.Result(common.TEXT_HOTEL_DIR / "test.txt", hotel_gate.IMAGE_PENDING, [], [hotel_gate.BLOCK_IMAGE_PENDING], "test", "test")
        with patch.object(hotel_gate, "scan_inputs", return_value=[result]), redirect_stdout(io.StringIO()) as output:
            self.assertEqual(hotel_gate.command_direct_check(argparse.Namespace(input=str(result.path))), 0)
            self.assertIn("READY_FOR_IMAGE_INSTALLATION", output.getvalue())

    def test_visible_shop_block_without_comment(self):
        source = '<li><div class="campaign-img-candy nolazy"></div><table><tr><td>移動時間</td><td>10分</td></tr><tr><td>交通費</td><td>無料</td></tr></table></li>'
        self.assertEqual(area.shop_keys(source), ["candy"])
        self.assertEqual(area.extract_existing_shop(source, "candy"), ("10分", "無料"))
        self.assertIsNone(area.extract_existing_shop(source + source, "candy"))

    def test_picture_shop_identity_and_ambiguity(self):
        source = '<li><picture><source srcset="./imgHtml/new_202601/shop/candy_sp.jpg"><img src="./imgHtml/new_202601/shop/candy_pc.jpg"></picture></li>'
        self.assertEqual(area.shop_keys(source), ["candy"])
        self.assertEqual(area.shop_keys(source.replace("candy_pc", "reborn_pc")), ["AMBIGUOUS_SHOP"])

    def test_deployed_image_mismatch_stops_before_page_write(self):
        data = hotel.parse_hotel_text(common.TEXT_HOTEL_DIR / "CoCo CLASS.txt")
        with patch.object(publisher.shared, "http_fetch", return_value=(200, "wrong URL", {}, b"wrong")):
            with self.assertRaisesRegex(publisher.PublishError, "deployment not verified"):
                publisher.verify_predeployed_images(data, "a" * 40)

    def test_real_build_and_repeat_in_isolated_non_git_fixture(self):
        original_root = common.REPO_ROOT
        with tempfile.TemporaryDirectory(prefix="Candy build 日本語 ") as directory:
            root = Path(directory)
            shutil.copytree(common.HP_ROOT, root / "HP")
            docs = root / "management/specs"
            docs.mkdir(parents=True)
            shutil.copyfile(area_gate.QUEUE_PATH, docs / area_gate.QUEUE_PATH.name)
            area_input = next(path for path in common.TEXT_AREA_DIR.rglob("*.txt") if path.name == "皆与志町_テンプレート.txt")
            cases = (
                (hotel, original_root / "Text_hotel_data/ビジネスホテル　アトリエ.txt"),
                (area, area_input),
            )
            with patch.object(common, "REPO_ROOT", root), patch.object(common, "HP_ROOT", root / "HP"), patch.object(common, "DOCS_DIR", docs):
                for module, input_path in cases:
                    with self.subTest(module=module.__name__), redirect_stdout(io.StringIO()):
                        args = argparse.Namespace(input=str(input_path), force=True, dry_run=False, no_docs=True)
                        self.assertEqual(module.run_build(args), 0)
                        self.assertEqual(module.run_check(argparse.Namespace(input=str(input_path), require_php=True)), 0)
                        if module is hotel:
                            hotel_entries = hotel.hotel_registry_links(
                                common.read_utf8(root / "HP/source/hotel.html"),
                                top_page=False,
                            )
                            top_entries = hotel.hotel_registry_links(
                                common.read_utf8(root / "HP/source/index.html"),
                                top_page=True,
                            )
                            self.assertEqual(len(top_entries), hotel.HOTEL_TOP_LATEST_LIMIT)
                            self.assertEqual(top_entries, hotel_entries[-hotel.HOTEL_TOP_LATEST_LIMIT:])
                            self.assertEqual(
                                top_entries[-1][0],
                                "kagoshima-deliveryhealth-hotel-businesshotelatelier.php",
                            )
                        before = {path: path.read_bytes() for path in (root / "HP").rglob("*") if path.is_file()}
                        self.assertEqual(module.run_build(args), 0)
                        self.assertEqual(before, {path: path.read_bytes() for path in (root / "HP").rglob("*") if path.is_file()})
            self.assertFalse((root / ".git").exists())

    def test_hotel_existing_first_entry_and_top_name(self):
        data = hotel.parse_hotel_text(common.TEXT_HOTEL_DIR / "ヴィラコスタ500.txt")
        listing = hotel.update_hotel_list(common.read_utf8(common.HP_ROOT / "source/hotel.html"), data)
        top = hotel.update_hotel_top_index(
            common.read_utf8(common.HP_ROOT / "source/index.html"),
            data,
            listing,
        )
        self.assertEqual(hotel.hotel_registry_alignment_errors(listing, top), [])
        self.assertEqual(hotel.update_hotel_top_index(top, data, listing), top)
        self.assertEqual(hotel.update_hotel_list(listing, data), listing)
        self.assertEqual(
            hotel.hotel_registry_links(top, top_page=True),
            hotel.hotel_registry_links(listing, top_page=False)[-hotel.HOTEL_TOP_LATEST_LIMIT:],
        )

    def test_hotel_top_keeps_only_latest_fifteen_in_registry_order(self):
        entries = [
            (f"kagoshima-deliveryhealth-hotel-test-{index:02d}.php", f"ホテル{index:02d}")
            for index in range(1, 18)
        ]
        listing = "\n".join(
            f'<a href="./{href}" class="fade">{name}</a>'
            for href, name in entries
        )
        top_fixture = (
            '<!-- 対応ホテル情報 START -->\n'
            '\t<div class="lp_0_55_40 w_1050 lm_0_auto bg_f">\n'
            '\t\t<div class="lp_5 lm_0_auto w_130 center bg_p fs_xs fc_w">HOTEL INFO</div>\n'
            '\t\t<div class="center"><a href="./hotel.php" class="bt-pk-xl">ホテル情報一覧</a></div>\n'
            '\t</div>\n'
            '<!-- 対応ホテル情報 END -->'
        )
        top = hotel.synchronize_hotel_top_index(top_fixture, listing)
        self.assertEqual(hotel.hotel_registry_links(top, top_page=True), entries[-15:])
        self.assertNotIn(entries[0][0], top)
        self.assertEqual(hotel.hotel_registry_alignment_errors(listing, top), [])
        self.assertEqual(hotel.synchronize_hotel_top_index(top, listing), top)

    def test_blog_top_keeps_only_latest_fifteen_in_registry_order(self):
        entries = [
            (f"kagoshima-deliveryhealth-blog-test-{index:02d}.php", f"ブログ{index:02d}")
            for index in range(1, 18)
        ]
        listing = (
            '<div class="blog-list">\n'
            '<div class="lp_5 lm_0_auto w_130 center bg_p fs_xs fc_w">BLOG INFO</div>\n'
            + "\n".join(
                f'<div class="lp_20_0 fs_md3 bd_t"><a href="./{href}" class="fade">{name}</a></div>'
                for href, name in entries
            )
            + "\n</div>\n"
            + common.CATEGORY_INDEX_TERMINAL_CTA
            + "\n</div>\n"
            + common.MAIN_CONTENT_END_MARKER
        )
        top_fixture = (
            '<!-- スタッフブログ START -->\n'
            '\t<div class="lp_5 lm_0_auto w_130 center bg_p fs_xs fc_w">BLOG INFO</div>\n'
            '\t<div class="center"><a href="./blog.php" class="bt-pk-xl">ブログ一覧はコチラ</a></div>\n'
            '<!-- スタッフブログ END -->'
        )
        top = common.synchronize_blog_top_index(top_fixture, listing)
        self.assertEqual(common.blog_registry_links(top, top_page=True), entries[-15:])
        self.assertNotIn(entries[0][0], top)
        self.assertEqual(common.blog_registry_alignment_errors(listing, top), [])
        self.assertEqual(common.synchronize_blog_top_index(top, listing), top)
        updated_listing, updated_top = common.update_blog_registries(
            listing,
            top,
            "test-18",
            "ブログ18",
        )
        updated_entries = entries + [("kagoshima-deliveryhealth-blog-test-18.php", "ブログ18")]
        self.assertEqual(common.blog_registry_links(updated_listing, top_page=False), updated_entries)
        self.assertEqual(common.blog_registry_links(updated_top, top_page=True), updated_entries[-15:])
        self.assertEqual(common.blog_registry_alignment_errors(updated_listing, updated_top), [])


if __name__ == "__main__":
    unittest.main(verbosity=2)
