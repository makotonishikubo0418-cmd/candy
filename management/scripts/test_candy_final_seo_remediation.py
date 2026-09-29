#!/usr/bin/env python3
"""Regression checks for the 2026-08-19 final SEO remediation."""

from __future__ import annotations

import json
import re
import sys
from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
HP_ROOT = ROOT / "HP"
SCRIPT_DIR = Path(__file__).resolve().parent
sys.path.insert(0, str(SCRIPT_DIR))

import candy_site_state  # noqa: E402


def read(path: Path) -> str:
    return path.read_text(encoding="utf-8")


def require(condition: bool, message: str) -> None:
    if not condition:
        raise AssertionError(message)


source_files = sorted((HP_ROOT / "source").glob("*.html"))
love_el_url = "https://www.love-el-kirishima.com/"
require(
    not any(love_el_url in read(path) for path in source_files),
    "the confirmed 404 Love-El URL must not remain in source HTML",
)
wrong_love_el_image_link = re.compile(
    r'<a href="https://www\.wife-est\.com/"[^>]*>\s*<picture>(?:(?!</a>).)*?loveel_kirishima_sp\.jpg',
    re.I | re.S,
)
require(
    not any(wrong_love_el_image_link.search(read(path)) for path in source_files),
    "a Love-El image must not link to another shop",
)
expected_shop_image_links = {
    "after5.jpg": "https://www.ol-h.com/",
    "beloved.jpg": "https://www.candy-beloved.com/",
    "danzuma.jpg": "https://www.danzuma.com/",
    "hitozuma.jpg": "https://www.wife-est.com/",
    "kurobara.jpg": "https://www.kurobara-queen.com/",
    "loveel.jpg": "https://www.love-el-kagosima.com/",
    "loveel_kirishima.jpg": "",
    "reborn.jpg": "https://www.reborn-kg.com/",
    "schannel.jpg": "http://firststar.kir.jp/group/s-chan/",
    "yokihi.jpg": "https://www.yo-kihi.com/",
}
campaign_image_blocks = re.compile(r'<div class="campaign-img-wrap">(.*?)</div>', re.I | re.S)
for source_path in source_files:
    for block in campaign_image_blocks.findall(read(source_path)):
        image_match = re.search(r'<img\s+src="[^"]*/shop/([^"?]+)"', block, re.I)
        if not image_match or image_match.group(1) not in expected_shop_image_links:
            continue
        link_match = re.search(r'<a\s+href="([^"]+)"', block, re.I)
        actual_link = link_match.group(1) if link_match else ""
        expected_link = expected_shop_image_links[image_match.group(1)]
        require(
            actual_link == expected_link,
            f"shop image link mismatch in {source_path.name}: {image_match.group(1)} -> {actual_link}",
        )

inactive_profiles = {
    "ramu": (975, "kagoshima-deliveryhealth-blog-glamourgirl.html"),
    "yuano": (1443, "kagoshima-deliveryhealth-blog-petitegirl.html"),
}
ledger = json.loads(read(ROOT / "codex/data/CANDY_GIRL_INFORMATION.json"))
records = {item["key"]: item for item in ledger["women"]}
for key, (profile_no, source_name) in inactive_profiles.items():
    source = read(HP_ROOT / "source" / source_name)
    require(f"girls.php?no={profile_no}" not in source, f"inactive profile link remains for {key}")
    require(records[key]["profile_no"] is None, f"inactive profile number remains active for {key}")
    require(records[key]["profile_url"] == "./girls_list.php", f"inactive profile fallback is invalid for {key}")

bad_rakuten_url = "40385.html（楽天トラベル）"
require(bad_rakuten_url not in read(HP_ROOT / "source/kagoshima-deliveryhealth-hotel-kisyabahotel.html"), "malformed Rakuten URL remains in hotel source")
require(bad_rakuten_url not in read(ROOT / "Text_hotel_data/きしゃばホテル.txt"), "malformed Rakuten URL remains in hotel input")

dataset_base = read(HP_ROOT / "includefile/dataset_base.php")
require("str_replace('index.html', 'index.php'" not in dataset_base, "global external index.html rewrite remains")
require("preg_replace_callback" in dataset_base and "(?:\\.\\/|\\/)?index" in dataset_base, "local index conversion is missing")
for source_name in (
    "kagoshima-deliveryhealth-area-ariyadacho.html",
    "kagoshima-deliveryhealth-area-chuokoshinmachi.html",
    "kagoshima-deliveryhealth-area-izumicho.html",
    "kagoshima-deliveryhealth-area-uearatacho.html",
):
    require("/index.html" in read(HP_ROOT / "source" / source_name), f"external index.html URL changed in {source_name}")

debug_markers = (
    "no=1241",
    "[candy][HpgCoder]",
    "[candy][dataset_girls]",
    "DEBUG rep03010093eot",
    "[candyTile] PHP",
    "console.log",
)
debug_sources = (
    HP_ROOT / "includefile/class.hpgcoder2.php",
    HP_ROOT / "includefile/dataset_base.php",
    HP_ROOT / "includefile/dataset_girls.php",
    HP_ROOT / "includefile/dataset_index.php",
    HP_ROOT / "source/girls.html",
)
combined_debug_source = "\n".join(read(path) for path in debug_sources)
for marker in debug_markers:
    require(marker not in combined_debug_source, f"public debug marker remains: {marker}")

state = candy_site_state.collect()
require(not state["missing_by"], "a confirmed asset reference is missing")
require(not state["unreferenced"], "an asset still lacks a confirmed static or runtime referrer")
require(
    not any(any(str(issue).startswith("JSON-LD") for issue in row["issues"]) for row in state["seo"]),
    "a public source contains invalid JSON-LD",
)

print("FINAL_SEO_REMEDIATION=PASS")
print(f"source_files={len(source_files)}")
print(f"assets={len(state['assets'])}")
print("unreferenced=0")
