"""Create a local blog scaffold from the executable code in BLOG_TEMPLATE.md."""
from __future__ import annotations

import argparse
import hashlib
import html
import json
import re
import sys
import xml.etree.ElementTree as ET
from datetime import datetime, timedelta, timezone
from pathlib import Path

import candy_page_common as common

ORIGIN = "https://www.55810.com/"
PREFIX = "kagoshima-deliveryhealth-blog-"
PENDING = "CANDY_BLOG_BODY_PENDING"
JSON_RE = re.compile(r'<script type="application/ld\+json">\s*(.*?)\s*</script>', re.S)


def require(condition: bool, message: str) -> None:
    if not condition:
        raise common.PageToolError(message)


def identity(url: str, title: str) -> str:
    match = re.fullmatch(re.escape(ORIGIN + PREFIX) + r"([a-z0-9][a-z0-9_-]*)\.php", url)
    require(match is not None, "URLは正規ホストのブログPHPを指定してください")
    require(bool(title.strip()) and title == title.strip(), "タイトルは空白だけ、または前後空白にできません")
    require(not any(ord(c) < 32 for c in title), "タイトルに制御文字を使用できません")
    return match.group(1)


def code_blocks() -> dict[str, str]:
    path = common.repo_root() / "management/template/BLOG_TEMPLATE.md"
    document = common.read_utf8(path)
    fence = re.escape(chr(96) * 3)
    result = {}
    for key in ("source-html", "entry-php", "dataset-php"):
        pattern = rf"<!-- code:{key} -->\s*{fence}\w+\n(.*?)\n{fence}"
        matches = re.findall(pattern, document, re.S)
        require(len(matches) == 1, f"管理書のコードブロック不整合: {key}")
        result[key] = matches[0] + "\n"
    return result


def json_string(value: str) -> str:
    return json.dumps(value, ensure_ascii=False).replace("<", "\\u003c").replace(">", "\\u003e").replace("&", "\\u0026")


def render_source(block: str, url: str, title: str, slug: str) -> str:
    values = {
        "TITLE_HTML": html.escape(title, quote=True),
        "URL_HTML": html.escape(url, quote=True),
        "TITLE_JSON": json_string(title),
        "URL_JSON": json_string(url),
        "BLOG_CSS_VERSION": hashlib.sha256((common.hp_root() / "css/blog.css").read_bytes()).hexdigest()[:8],
        "RELATED_HTML": common.related_links_html("blog", slug, "bd").replace("<h3 ", "<h2 ").replace("</h3>", "</h2>"),
    }
    tokens = set(re.findall(r"\{\{([A-Z_]+)\}\}", block))
    require(tokens == set(values), "HTML管理コードの置換項目が不足または不明です")
    return re.sub(r"\{\{([A-Z_]+)\}\}", lambda m: values[m.group(1)], block)


def sitemap_urls(source: str) -> list[str]:
    tree = ET.fromstring(source)
    entries = tree.findall("{*}url")
    require(all(len(node.findall("{*}loc")) == 1 for node in entries), "サイトマップのlocは各urlに1件必要です")
    urls = [(node.text or "").strip() for node in tree.findall("{*}url/{*}loc")]
    require(bool(urls) and len(urls) == len(set(urls)), "サイトマップに空URLまたは重複があります")
    require(all(urls), "サイトマップに空URLがあります")
    return urls


def registry_errors(blog: str, index: str, sitemap: str) -> list[str]:
    errors = common.blog_registry_alignment_errors(blog, index)
    errors.extend(common.category_index_terminal_cta_errors("blog", blog))
    entries = common.blog_registry_links(blog, top_page=False)
    existing = {p.name for p in common.hp_root().glob(PREFIX + "*.php")}
    if {name for name, _ in entries} != existing:
        errors.append("ブログ一覧と実在するブログPHPの集合が一致しません")
    urls = sitemap_urls(sitemap)
    mapped = {u.removeprefix(ORIGIN) for u in urls if u.startswith(ORIGIN + PREFIX)}
    if mapped != existing:
        errors.append("ブログPHPとサイトマップのブログURL集合が一致しません")
    base = common.read_utf8(common.hp_root() / "includefile/dataset_base.php")
    for name in sorted(existing):
        slug = name[len(PREFIX):-4]
        if not all(path.is_file() for path in common.bundle_paths("blog", slug)):
            errors.append("既存ブログのPHP・HTML・dataset不足: " + name)
        source_name = name[:-4] + ".html"
        case = f"case '{source_name}':"
        include = f"include(INCLUDE_DIR . 'dataset_{name}');"
        conversion = f"$source = str_replace('{source_name}', '{name}', $source);"
        if any(base.count(fragment) != 1 for fragment in (case, include, conversion)):
            errors.append("既存ブログの共通PHP登録不足または重複: " + name)
    # Current indexes have no per-article structured list. Never silently ignore
    # a future list schema, including a list nested inside @graph.
    for payload in JSON_RE.findall(blog):
        data = json.loads(payload)
        def has_article_list(node):
            if isinstance(node, dict):
                return node.get("@type") in ("ItemList", "Blog") or "blogPost" in node or any(has_article_list(v) for v in node.values())
            return isinstance(node, list) and any(has_article_list(v) for v in node)
        if has_article_list(data):
            errors.append("ブログ一覧の記事列挙JSON-LDに対応した同期処理が必要です")
    errors.extend(common.validate_json_ld(blog))
    errors.extend(common.validate_json_ld(index))
    return errors


def render_sitemap(source: str, url: str) -> str:
    source = common.update_sitemap(source, url)
    today = datetime.now(timezone(timedelta(hours=9))).date().isoformat()
    targets = {url, ORIGIN, ORIGIN + "blog.php"}
    found = set()

    def replace(match):
        block = match.group(0)
        loc = re.search(r"<loc>\s*(.*?)\s*</loc>", block)
        if loc and html.unescape(loc.group(1)) in targets:
            found.add(html.unescape(loc.group(1)))
            if "<lastmod>" in block:
                block = re.sub(r"<lastmod>.*?</lastmod>", f"<lastmod>{today}</lastmod>", block)
            else:
                block = block.replace("</loc>", f"</loc>\n    <lastmod>{today}</lastmod>", 1)
        return block

    result = re.sub(r"<url>.*?</url>", replace, source, flags=re.S)
    require(found == targets, "TOP・ブログ一覧・新記事のサイトマップ登録が揃っていません")
    sitemap_urls(result)
    return result


def validate_source(source: str, url: str, title: str) -> list[str]:
    errors = common.validate_html_common(source, url, [])
    og_images = re.findall(r'<meta property="og:image" content="([^"]+)">', source)
    if len(og_images) != 1 or not og_images[0].startswith(ORIGIN):
        errors.append("OGP画像はサイト内の実在する絶対URLを1件指定してください")
    elif not (common.hp_root() / html.unescape(og_images[0]).removeprefix(ORIGIN).split("?")[0]).is_file():
        errors.append("OGP画像ファイルがありません")
    escaped = html.escape(title, quote=True)
    for fragment in (f"<title>{escaped}</title>", f'<meta property="og:title" content="{escaped}">',
                     f'<meta property="og:url" content="{url}">', '<meta name="robots" content="index">'):
        if source.count(fragment) != 1:
            errors.append("メタ情報不一致: " + fragment)
    headings = re.findall(r"<h1\b[^>]*>(.*?)</h1>", source, re.S)
    if len(headings) != 1 or common.strip_tags(headings[0]) != title:
        errors.append("H1不一致")
    if not errors:
        blocks = [json.loads(p) for p in JSON_RE.findall(source)]
        articles = [b for b in blocks if isinstance(b, dict) and b.get("@type") == "Article"]
        crumbs = [b for b in blocks if isinstance(b, dict) and b.get("@type") == "BreadcrumbList"]
        if len(articles) != 1 or articles[0].get("headline") != title or articles[0].get("url") != url:
            errors.append("Articleのタイトル・URL不一致")
        expected = [
            {"@type": "ListItem", "position": 1, "name": "TOP", "item": ORIGIN},
            {"@type": "ListItem", "position": 2, "name": "ブログ一覧", "item": ORIGIN + "blog.php"},
            {"@type": "ListItem", "position": 3, "name": title, "item": url},
        ]
        if len(crumbs) != 1 or crumbs[0].get("itemListElement") != expected:
            errors.append("BreadcrumbList不一致")
    for asset in re.findall(r'(?:href|src)="\./([^"#?]+)(?:\?[^"]*)?"', source):
        if asset and not (common.hp_root() / asset).is_file():
            errors.append("参照先不足: " + asset)
    version = hashlib.sha256((common.hp_root() / "css/blog.css").read_bytes()).hexdigest()[:8]
    if f'./css/blog.css?v={version}' not in source:
        errors.append("blog.css更新キー不一致")
    return errors


def plan(url: str, title: str) -> tuple[dict[Path, str], dict[Path, bytes | None]]:
    slug = identity(url, title)
    hp = common.hp_root()
    public, source, dataset = common.bundle_paths("blog", slug)
    paths = [public, source, dataset, hp / "includefile/dataset_base.php",
             hp / "source/blog.html", hp / "source/index.html", hp / "sitemap.xml"]
    before = common.snapshot_paths(paths)
    require(all(before[p] is None for p in paths[:3]), "同一URLの既存ファイルがあります。上書きしません")
    for path in paths[3:]:
        require(before[path] is not None, "必要ファイル不足: " + str(path))
    base, blog, index, sitemap = [before[p].decode("utf-8-sig").replace("\r\n", "\n") for p in paths[3:]]
    stem = PREFIX + slug
    require(stem + ".html" not in base and stem + ".php" not in base, "同一slugの共通PHP登録があります")
    require(url not in sitemap and stem + ".php" not in blog and stem + ".php" not in index, "同一URLの既存登録があります")
    errors = registry_errors(blog, index, sitemap)
    require(not errors, "既存登録の不整合:\n- " + "\n- ".join(errors))
    blocks = code_blocks()
    rendered = render_source(blocks["source-html"], url, title, slug)
    errors = validate_source(rendered, url, title)
    require(not errors, "生成前検証失敗:\n- " + "\n- ".join(errors))
    new_blog, new_index = common.update_blog_registries(blog, index, slug, title)
    outputs = dict(zip(paths, [
        blocks["entry-php"], rendered, blocks["dataset-php"],
        common.update_dataset_base(base, "blog", slug),
        new_blog, new_index, render_sitemap(sitemap, url),
    ]))
    return outputs, before


def check(url: str, title: str) -> None:
    slug = identity(url, title)
    hp = common.hp_root()
    paths = common.bundle_paths("blog", slug)
    require(all(p.is_file() for p in paths), "記事ファイルが揃っていません")
    errors = validate_source(common.read_utf8(paths[1]), url, title)
    errors.extend(common.shared_validation("blog", slug, url))
    blog = common.read_utf8(hp / "source/blog.html")
    errors.extend(registry_errors(blog, common.read_utf8(hp / "source/index.html"), common.read_utf8(hp / "sitemap.xml")))
    entries = common.blog_registry_links(blog, top_page=False)
    if (PREFIX + slug + ".php", title) not in entries:
        errors.append("一覧のタイトル不一致")
    require(not errors, "検証失敗:\n- " + "\n- ".join(errors))


def run(args: argparse.Namespace) -> int:
    if args.command == "create":
        outputs, before = plan(args.url, args.title)
        common.lint_planned_php(outputs)
        if args.dry_run:
            print("RESULT=DRY_RUN_OK")
        else:
            common.write_transaction(outputs, lambda: check(args.url, args.title), expected=before)
            print("RESULT=SCAFFOLD_OK BODY=PENDING")
        print("FILES=" + ",".join(p.relative_to(common.repo_root()).as_posix() for p in outputs))
    else:
        check(args.url, args.title)
        paths = common.bundle_paths("blog", identity(args.url, args.title))
        common.lint_planned_php({p: common.read_utf8(p) for p in (paths[0], paths[2], common.hp_root() / "includefile/dataset_base.php")})
        state = "PENDING" if PENDING in common.read_utf8(paths[1]) else "EDITED_NOT_REVIEWED"
        print("RESULT=CHECK_OK BODY=" + state)
    return 0


def main() -> int:
    parser = argparse.ArgumentParser(description="URL・タイトルから管理書のブログテンプレート一式を作成")
    commands = parser.add_subparsers(dest="command", required=True)
    for name in ("create", "check"):
        command = commands.add_parser(name)
        command.add_argument("--url", required=True)
        command.add_argument("--title", required=True)
        if name == "create":
            command.add_argument("--dry-run", action="store_true")
    try:
        return run(parser.parse_args())
    except (common.PageToolError, OSError, ValueError, ET.ParseError) as exc:
        print(f"RESULT=STOP\nREASON={exc}", file=sys.stderr)
        return 2


if __name__ == "__main__":
    raise SystemExit(main())
