#!/usr/bin/env python3
"""Build and validate one CANDY blog page from Text_blog_data."""

from __future__ import annotations

import argparse
import html
import json
import re
import sys
from dataclasses import dataclass
from pathlib import Path

import candy_page_common as common


class BlogToolError(common.PageToolError):
    pass


@dataclass(frozen=True)
class Article:
    heading: str
    subtitle: str
    description: str


@dataclass(frozen=True)
class Voice:
    heading: str
    subtitle: str
    description: str


@dataclass(frozen=True)
class BlogBlock:
    kind: str
    heading: str
    value: object


@dataclass(frozen=True)
class GirlTemplate:
    key: str
    name: str
    title: str
    image: str
    url: str
    block: str


@dataclass(frozen=True)
class BlogData:
    input_path: Path
    slug: str
    title: str
    meta_description: str
    canonical: str
    og_image: str
    image1: str
    image2: str
    page_title: str
    subtitle_h1: str
    description_h1: str
    blocks: list[BlogBlock]
    requested_girls: list[str]
    requested_girl_count: int


def parse_voices(section: list[str]) -> list[Voice]:
    starts = [i for i, value in enumerate(section) if value.strip() == "scene_"]
    voices: list[Voice] = []
    for offset, start in enumerate(starts):
        end = starts[offset + 1] if offset + 1 < len(starts) else len(section)
        subtitle = next((i for i in range(start + 1, end) if section[i].strip() == "subtitle_"), None)
        description = next((i for i in range((subtitle or start) + 1, end) if section[i].strip() == "description_"), None)
        if subtitle is None or description is None:
            raise BlogToolError("お客様の声のscene/subtitle/descriptionが揃っていません")
        heading = "\n".join(item.strip() for item in section[start + 1 : subtitle] if item.strip())
        subtitle_value = "\n".join(item.strip() for item in section[subtitle + 1 : description] if item.strip())
        description_value = "\n".join(item.strip() for item in section[description + 1 : end] if item.strip() and not common.is_separator(item))
        voices.append(
            Voice(
                common.ensure_value("お客様の声scene", heading),
                common.ensure_value("お客様の声subtitle", subtitle_value),
                common.ensure_value("お客様の声description", description_value),
            )
        )
    return voices


def parse_requested_girls(section: list[str]) -> tuple[list[str], int]:
    text = "\n".join(section)
    count_match = re.search(r"([0-9０-９]+)人選出", text)
    count = int(count_match.group(1).translate(str.maketrans("０１２３４５６７８９", "0123456789"))) if count_match else 0
    explicit = common.labeled_value(section, "女の子指定")
    keys = [item for item in re.split(r"[,、\s]+", explicit) if re.fullmatch(r"[a-z0-9_]+", item)]
    keys.extend(item for item in re.findall(r"<!--\s*([a-z0-9_]+)\s*-->", text) if item not in keys)
    return keys, count


def parse_blog_text(path: Path) -> BlogData:
    lines = common.read_utf8(path).splitlines()
    scene_start = next((i for i, value in enumerate(lines) if common.SCENE_RE.fullmatch(value.strip())), len(lines))
    title = common.ensure_value("title", common.labeled_value(lines, "title", 0, scene_start))
    description = common.ensure_value("description", common.labeled_value(lines, "description", 0, scene_start))
    canonical = common.ensure_value("canonical", common.labeled_value(lines, "canonical", 0, scene_start))
    og_image = common.ensure_value("image", common.labeled_value(lines, "image", 0, scene_start))
    image1 = common.ensure_value("img_1", common.value_after_prefix(common.labeled_value(lines, "img_1", 0, scene_start), "src"))
    image2 = common.ensure_value("img_2", common.value_after_prefix(common.labeled_value(lines, "img_2"), "src"))
    page_title = common.ensure_value(
        "page_title_h1",
        common.labeled_value(lines, "page_title_h1 / パンくずリスト", 0, scene_start, stop_labels=("subtitle_h1", "description_h1")),
    )
    subtitle_h1 = common.ensure_value("subtitle_h1", common.labeled_value(lines, "subtitle_h1", 0, scene_start, stop_labels=("description_h1",)))
    description_h1 = common.ensure_value("description_h1", common.labeled_value(lines, "description_h1", 0, scene_start))
    slug = common.slug_from_canonical(canonical, "blog")

    blocks: list[BlogBlock] = []
    requested: list[str] = []
    requested_count = 0
    for section in common.split_scenes(lines):
        marker = section[0]
        if "店長おすすめ" in marker or "女の子" in marker:
            heading = common.first_scene_heading(section)
            requested, requested_count = parse_requested_girls(section)
            blocks.append(BlogBlock("girls", heading, None))
        elif "お客様の声" in marker:
            voices = parse_voices(section)
            if not voices:
                raise BlogToolError("お客様の声が0件です")
            blocks.append(BlogBlock("voices", "お客様の声", voices))
        elif "FAQ" in marker:
            faqs = common.repeated_pairs(section)
            if not faqs:
                raise BlogToolError("FAQが0件です")
            blocks.append(BlogBlock("faq", "FAQ", faqs))
        else:
            heading = common.first_scene_heading(section)
            subtitle = common.ensure_value(f"{heading}:subtitle", common.labeled_value(section, "subtitle_", stop_labels=("description_",)))
            desc = common.ensure_value(f"{heading}:description", common.labeled_value(section, "description_"))
            kind = "summary" if "まとめ" in marker or "まとめ" in heading else "article"
            blocks.append(BlogBlock(kind, heading, Article(heading, subtitle, desc)))
    kinds = [item.kind for item in blocks]
    missing = [name for name in ("girls", "voices", "faq", "summary") if name not in kinds]
    if missing:
        raise BlogToolError("必須ブロック不足: " + ", ".join(missing))
    if kinds.count("girls") != 1 or kinds.count("voices") != 1 or kinds.count("faq") != 1 or kinds.count("summary") != 1:
        raise BlogToolError("girls/voices/FAQ/まとめは各1セクション必要です")
    return BlogData(
        path,
        slug,
        title,
        description,
        canonical,
        og_image,
        image1,
        image2,
        page_title,
        subtitle_h1,
        description_h1,
        blocks,
        requested,
        requested_count,
    )


def strip_tags(value: str) -> str:
    return html.unescape(re.sub(r"<[^>]+>", "", value)).strip()


def load_girl_templates(path: Path) -> dict[str, GirlTemplate]:
    source = common.read_utf8(path)
    matches = list(re.finditer(r"(?ms)^\s*<!-- ([a-z0-9_]+) -->\s*\n(?P<block>\s*<li\b.*?^\s*</li>)", source))
    templates: dict[str, GirlTemplate] = {}
    for match in matches:
        key = match.group(1)
        block = f"\t\t\t\t\t\t<!-- {key} -->\n{match.group('block')}"
        button = re.search(r'<a href="(\./girls\.php\?no=[^"]+)" class="bt-pk-m">\s*([^<]+?)\s+詳細</a>', block)
        heading = re.search(r"<h3[^>]*>(.*?)</h3>", block, re.S)
        image = re.search(r'<img src="(\./imgHtml/[^"]+)"', block)
        if not button or not heading or not image:
            continue
        templates[key] = GirlTemplate(key, button.group(2).strip(), strip_tags(heading.group(1)), image.group(1), button.group(1), block)
    if not templates:
        raise BlogToolError("template_girls.htmlに女の子ブロックがありません")
    return templates


def existing_girl_keys(data: BlogData, templates: dict[str, GirlTemplate]) -> list[str]:
    path = common.hp_root() / "source" / f"kagoshima-deliveryhealth-blog-{data.slug}.html"
    if not path.is_file():
        return []
    source = common.read_utf8(path)
    start = source.find("<!-- 店長おすすめの女の子 START -->")
    end = source.find("<!-- 店長おすすめの女の子 END -->", start)
    if start < 0 or end < 0:
        return []
    return [item for item in re.findall(r"<!--\s*([a-z0-9_]+)\s*-->", source[start:end]) if item in templates]


def resolve_girls(data: BlogData) -> list[GirlTemplate]:
    templates = load_girl_templates(common.hp_root() / "source" / "template_girls.html")
    keys = data.requested_girls or existing_girl_keys(data, templates)
    if not keys:
        raise BlogToolError("女の子を推測選出できません。元txtへ「女の子指定」とtemplate keyを記載してください")
    if len(keys) != len(set(keys)):
        raise BlogToolError("女の子指定が重複しています")
    if data.requested_girl_count and len(keys) != data.requested_girl_count:
        raise BlogToolError(f"女の子指定件数不一致: expected={data.requested_girl_count} actual={len(keys)}")
    missing = [key for key in keys if key not in templates]
    if missing:
        raise BlogToolError("template_girls key不足: " + ", ".join(missing))
    return [templates[key] for key in keys]


def faq_pairs(data: BlogData) -> list[tuple[str, str]]:
    block = next(item for item in data.blocks if item.kind == "faq")
    return list(block.value)  # type: ignore[arg-type]


def structured_data(data: BlogData, girls: list[GirlTemplate]) -> list[dict[str, object]]:
    breadcrumb = {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "TOP", "item": "https://www.55810.com/"},
            {"@type": "ListItem", "position": 2, "name": "ブログ一覧", "item": "https://www.55810.com/blog.php"},
            {"@type": "ListItem", "position": 3, "name": data.page_title, "item": data.canonical},
        ],
    }
    faq = {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {"@type": "Question", "name": question, "acceptedAnswer": {"@type": "Answer", "text": answer}}
            for question, answer in faq_pairs(data)
        ],
    }
    item_list = {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "name": next(item.heading for item in data.blocks if item.kind == "girls"),
        "numberOfItems": len(girls),
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": index,
                "item": {
                    "@type": "Person",
                    "name": girl.name,
                    "image": "https://www.55810.com/" + girl.image.removeprefix("./"),
                    "url": "https://www.55810.com/" + girl.url.removeprefix("./"),
                },
            }
            for index, girl in enumerate(girls, 1)
        ],
    }
    return [breadcrumb, faq, item_list]


def block_heading(block: BlogBlock) -> str:
    if block.kind == "voices":
        return "お客様の声"
    if block.kind == "faq":
        return "FAQ"
    return strip_tags(block.heading.replace("<改行>", " "))


def render_article(article: Article, scene: int, first: bool) -> str:
    heading_class = "lp_40_0 fs_xxl fc_p" if first else "lp_38_0 bd_t fs_xxl fc_p"
    return (
        f'\t\t\t\t<h2 class="{heading_class}" id="scene{scene}">{common.htext(article.heading)}</h2>\n'
        f'\t\t\t\t<div class="lpt_20 bd_t fs_l" id="subtitle_{scene}">{common.htext(article.subtitle)}</div>\n'
        f'\t\t\t\t<div class="lp_15_0_30 fs_md3" id="description_{scene}">{common.htext(article.description)}</div>'
    )


def render_girls(block: BlogBlock, girls: list[GirlTemplate], scene: int) -> str:
    blocks = "\n".join(item.block for item in girls)
    return (
        "<!-- 店長おすすめの女の子 START -->\n"
        '\t\t\t<div class="lm_full bg_g1">\n'
        f'\t\t\t\t<div class="titleimg_1 nolazy" role="img" aria-label="{common.hattr(strip_tags(block.heading))}"></div>\n'
        f'\t\t\t\t<h2 class="lp_50_0_20 fs_xxl center" id="scene{scene}">{common.htext(block.heading)}</h2>\n'
        '\t\t\t\t<div class="lm_0_auto w_1080"><ul class="campaign-list" role="list">\n'
        + blocks
        + "\n\t\t\t\t</ul>\n"
        f'\t\t\t\t<div class="lp_30_0 fs_md3 center" id="description_{scene}">女の子一覧、または出勤状況は下記よりご確認ください。</div>\n'
        '\t\t\t\t<div class="lpb_75 center"><a href="./girls_list.php" class="bt-pk-l lmr_17">女の子一覧</a><a href="./schedule.php" class="bt-pk-l">出勤情報</a></div>\n'
        "\t\t\t\t</div>\n\t\t\t</div>\n<!-- 店長おすすめの女の子 END -->"
    )


def render_voices(voices: list[Voice], scene: int) -> str:
    lines = [f'\t\t\t\t<h2 class="lp_50_0_38 fs_xxl fc_p" id="scene{scene}">お客様の声</h2>']
    for index, voice in enumerate(voices, 1):
        extra = " lmt_20" if index > 1 else ""
        lines.extend(
            [
                f'\t\t\t\t<div class="lp_0_20{extra} bd">',
                f'\t\t\t\t<h3 class="lp_25_0 fs_l" id="scene{scene}_{index}"><span class="lmr_10">&#128172;</span>{common.htext(voice.heading)}</h3>',
                f'\t\t\t\t<div class="lpt_20 bd_t fs_l" id="subtitle_{scene}_{index}">{common.htext(voice.subtitle)}</div>',
                f'\t\t\t\t<div class="lp_15_0_30 fs_md3" id="description_{scene}_{index}">{common.htext(voice.description)}</div>',
                "\t\t\t\t</div>",
            ]
        )
    return "\n".join(lines)


def render_faq(pairs: list[tuple[str, str]], scene: int) -> str:
    lines = [f'\t\t\t\t<h2 class="lp_38_0 bd_tb fs_xxl fc_p" id="scene{scene}">FAQ</h2>']
    for index, (question, answer) in enumerate(pairs, 1):
        border = "bd_tb" if index == len(pairs) else ("" if index == 1 else "bd_t")
        class_name = f' class="faq-item {border}"' if border else ' class="faq-item"'
        lines.extend(
            [
                f"\t\t\t\t<div{class_name}>",
                f'\t\t\t\t<div class="faq-question fs_md2" id="subtitle_{scene}_{index}">{common.htext(question)}</div>',
                f'\t\t\t\t<div class="faq-answer fs_md2" id="description_{scene}_{index}">{common.htext(answer)}</div>',
                "\t\t\t\t</div>",
            ]
        )
    return "\n".join(lines)


def render_main(data: BlogData, girls: list[GirlTemplate]) -> str:
    toc = "<br>\n".join(
        f'\t\t\t\t\t<a href="#scene{index}" class="fade">{html.escape(block_heading(block))}</a>'
        for index, block in enumerate(data.blocks, 1)
    )
    parts = [
        '\t\t<div id="main" class="main">',
        '\t\t\t<div class="bg_g3"><div class="pcOnly lm_0_auto w_1000 lp_13_0 fc_g"><nav aria-label="パンくずリスト"><ol class="bread_list f_xxs">'
        '<li><a href="./" class="fc_b">TOP</a></li><li class="lm_0_10">&gt;</li><li><a href="./blog.php" class="fc_b">ブログ一覧</a></li>'
        f'<li class="lm_0_10">&gt;</li><li><span>{common.htext(data.page_title)}</span></li></ol></nav></div></div>',
        f'\t\t\t<div class="lpt_headerimg center" id="img_1"><img src="{common.hattr(data.image1)}" class="img_1 nolazy" alt="{common.hattr(data.page_title)}"></div>',
        '\t\t\t<div class="lm_0_auto w_1000 lp_0_7">',
        f'\t\t\t\t<h1 class="lp_50_0_40 center fs_xxl fc_p" id="page_title_h1">{common.htext(data.page_title)}</h1>',
        f'\t\t\t\t<div class="lpt_20 bd_t fs_l" id="subtitle_h1">{common.htext(data.subtitle_h1)}</div>',
        f'\t\t\t\t<div class="lp_15_0_30 fs_md3" id="description_h1">{common.htext(data.description_h1)}</div>',
        '\t\t\t\t<div class="lp_40 bd"><h3 class="lpb_10 fs_l">目次</h3><div class="fs_md3">',
        toc,
        '\t\t\t\t</div></div>',
        '\t\t\t\t<div class="lm_40_0_75 center"><a href="./girls_list.php" class="bt-pk-l lmr_17">女の子一覧</a><a href="./schedule.php" class="bt-pk-l">出勤情報</a></div>',
        "\t\t\t</div>",
        f'\t\t\t<div class="lpt_40 center" id="img_2"><img src="{common.hattr(data.image2)}" class="img_1 nolazy" loading="lazy" alt="{common.hattr(data.page_title)}"></div>',
        '\t\t\t<div class="lm_0_auto w_1000 lp_0_7">',
    ]
    first_article = True
    wrapper_open = True
    for scene, block in enumerate(data.blocks, 1):
        if block.kind == "girls":
            if wrapper_open:
                parts.append("\t\t\t</div>")
                wrapper_open = False
            parts.append(render_girls(block, girls, scene))
            parts.append('\t\t\t<div class="lm_0_auto w_1000 lp_0_7">')
            wrapper_open = True
        elif block.kind in {"article", "summary"}:
            parts.append(render_article(block.value, scene, first_article))  # type: ignore[arg-type]
            first_article = False
        elif block.kind == "voices":
            parts.append(render_voices(block.value, scene))  # type: ignore[arg-type]
        elif block.kind == "faq":
            parts.append(render_faq(block.value, scene))  # type: ignore[arg-type]
    parts.extend([common.related_links_html("blog", data.slug, "bd"), '\t\t\t\t<div class="lm_40_0_75 center"><a href="./" class="bt-pk-xl">HOME</a></div>'])
    if wrapper_open:
        parts.append("\t\t\t</div>")
    parts.append("\t\t</div>")
    return "\n".join(parts)


def render_source(data: BlogData) -> tuple[str, list[GirlTemplate]]:
    girls = resolve_girls(data)
    source = common.render_template_shell(
        common.hp_root() / "source" / "template_kagoshima-deliveryhealth-blog.html",
        title=data.title,
        description=data.meta_description,
        canonical=data.canonical,
        og_image=data.og_image,
        structured_data=structured_data(data, girls),
        main_html=render_main(data, girls),
    )
    return source, girls


def validate_rendered(data: BlogData, source: str, girls: list[GirlTemplate]) -> list[str]:
    errors = common.validate_html_common(source, data.canonical, [data.image1, data.image2] + [item.image for item in girls])
    scenes = [int(value) for value in re.findall(r'\bid="scene(\d+)"', source)]
    if scenes != list(range(1, len(data.blocks) + 1)):
        errors.append(f"scene連番不整合: {scenes}")
    toc = re.findall(r'<a href="#scene(\d+)" class="fade">(.*?)</a>', source, re.S)
    expected_toc = [(str(index), block_heading(block)) for index, block in enumerate(data.blocks, 1)]
    actual_toc = [(number, strip_tags(label)) for number, label in toc]
    if actual_toc != expected_toc:
        errors.append("目次とsceneが一致しません")
    faq_scene = next(index for index, block in enumerate(data.blocks, 1) if block.kind == "faq")
    if len(re.findall(rf'id="subtitle_{faq_scene}_\d+"', source)) != len(faq_pairs(data)):
        errors.append("FAQ本文件数不整合")
    actual_keys = [item for item in re.findall(r"<!--\s*([a-z0-9_]+)\s*-->", source) if item in {girl.key for girl in girls}]
    if actual_keys != [girl.key for girl in girls]:
        errors.append("女の子順不整合")
    if source.count('"@type": "FAQPage"') != 1 or source.count('"@type": "ItemList"') != 1:
        errors.append("JSON-LD種別不整合")
    return errors


def registry_sources(data: BlogData) -> tuple[str, str, str, str]:
    hp = common.hp_root()
    base = common.update_dataset_base(common.read_utf8(hp / "includefile" / "dataset_base.php"), "blog", data.slug)
    sitemap = common.update_sitemap(common.read_utf8(hp / "sitemap.xml"), data.canonical)
    blog, index = common.update_blog_registries(
        common.read_utf8(hp / "source" / "blog.html"),
        common.read_utf8(hp / "source" / "index.html"),
        data.slug,
        data.title,
    )
    return base, sitemap, blog, index


def run_build(args: argparse.Namespace) -> int:
    input_path = Path(args.input)
    if not input_path.is_absolute():
        input_path = common.repo_root() / input_path
    data = parse_blog_text(input_path)
    source, girls = render_source(data)
    errors = validate_rendered(data, source, girls)
    if errors:
        raise BlogToolError("生成前検証失敗:\n- " + "\n- ".join(errors))
    public_path, source_path, dataset_path = common.bundle_paths("blog", data.slug)
    existing = [path for path in (public_path, source_path, dataset_path) if path.exists()]
    if existing and not args.force and not args.dry_run:
        raise BlogToolError("既存ファイルがあります。上書きは--forceが必要: " + ", ".join(str(path) for path in existing))
    base, sitemap, blog_list, index = registry_sources(data)
    if args.dry_run:
        print(f"RESULT=DRY_RUN_OK category=blog slug={data.slug}")
        print(f"COUNTS scenes={len(data.blocks)} girls={len(girls)} voices={len(next(item.value for item in data.blocks if item.kind == 'voices'))} faq={len(faq_pairs(data))}")
        return 0
    hp = common.hp_root()
    writes = {
        public_path: common.public_php_content(),
        source_path: source,
        dataset_path: common.dataset_content(),
        hp / "includefile" / "dataset_base.php": base,
        hp / "sitemap.xml": sitemap,
        hp / "source" / "blog.html": blog_list,
        hp / "source" / "index.html": index,
    }
    for path, content in writes.items():
        common.atomic_write(path, content)
    actual_errors = validate_rendered(data, common.read_utf8(source_path), girls)
    actual_errors.extend(common.shared_validation("blog", data.slug, data.canonical))
    php_status, php_errors = common.php_lint([public_path, dataset_path, hp / "includefile" / "dataset_base.php"])
    actual_errors.extend(php_errors)
    if actual_errors:
        raise BlogToolError("書込後検証失敗:\n- " + "\n- ".join(actual_errors))
    print(f"RESULT=BUILD_OK category=blog slug={data.slug}")
    print("FILES=" + ",".join(path.relative_to(common.repo_root()).as_posix() for path in writes))
    print(f"COUNTS scenes={len(data.blocks)} girls={len(girls)} faq={len(faq_pairs(data))}")
    print(f"PHP_LINT={php_status}")
    return 0


def run_check(args: argparse.Namespace) -> int:
    input_path = Path(args.input)
    if not input_path.is_absolute():
        input_path = common.repo_root() / input_path
    data = parse_blog_text(input_path)
    public_path, source_path, dataset_path = common.bundle_paths("blog", data.slug)
    missing = [str(path) for path in (public_path, source_path, dataset_path) if not path.is_file()]
    if missing:
        raise BlogToolError("生成ファイル不足: " + ", ".join(missing))
    girls = resolve_girls(data)
    errors = validate_rendered(data, common.read_utf8(source_path), girls)
    errors.extend(common.shared_validation("blog", data.slug, data.canonical))
    php_status, php_errors = common.php_lint([public_path, dataset_path, common.hp_root() / "includefile" / "dataset_base.php"])
    errors.extend(php_errors)
    if args.require_php and php_status == "UNAVAILABLE":
        errors.append("PHP CLIがありません")
    if errors:
        raise BlogToolError("検証失敗:\n- " + "\n- ".join(errors))
    print(f"RESULT=CHECK_OK category=blog slug={data.slug} PHP_LINT={php_status}")
    return 0


def run_audit_inputs(args: argparse.Namespace) -> int:
    paths = sorted(common.TEXT_BLOG_DIR.glob("*.txt"))
    failures: list[str] = []
    passed = 0
    for path in paths:
        try:
            data = parse_blog_text(path)
            girls = resolve_girls(data)
            if args.render:
                source, girls = render_source(data)
                errors = validate_rendered(data, source, girls)
                if errors:
                    raise BlogToolError("; ".join(errors))
            passed += 1
            print(f"INPUT_OK={path.name}|slug={data.slug}|scenes={len(data.blocks)}|girls={len(girls)}|faq={len(faq_pairs(data))}")
        except (BlogToolError, common.PageToolError) as exc:
            failures.append(f"{path.name}: {exc}")
    print(f"INPUTS={len(paths)} PASSED={passed} STOPPED={len(failures)}")
    for failure in failures:
        print(f"INPUT_STOP={failure}")
    return 1 if failures else 0


def create_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="CANDY blog page builder/validator")
    commands = parser.add_subparsers(dest="command", required=True)
    build = commands.add_parser("build")
    build.add_argument("--input", required=True)
    build.add_argument("--dry-run", action="store_true")
    build.add_argument("--force", action="store_true")
    build.set_defaults(func=run_build)
    check = commands.add_parser("check")
    check.add_argument("--input", required=True)
    check.add_argument("--require-php", action="store_true")
    check.set_defaults(func=run_check)
    audit = commands.add_parser("audit-inputs")
    audit.add_argument("--render", action="store_true")
    audit.set_defaults(func=run_audit_inputs)
    return parser


def main() -> int:
    if hasattr(sys.stdout, "reconfigure"):
        sys.stdout.reconfigure(encoding="utf-8", errors="backslashreplace")
    if hasattr(sys.stderr, "reconfigure"):
        sys.stderr.reconfigure(encoding="utf-8", errors="backslashreplace")
    args = create_parser().parse_args()
    try:
        return args.func(args)
    except (BlogToolError, common.PageToolError, OSError, json.JSONDecodeError) as exc:
        print(f"RESULT=STOP\nREASON={exc}", file=sys.stderr)
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
