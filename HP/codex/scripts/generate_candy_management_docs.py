# -*- coding: utf-8 -*-
from __future__ import annotations

import csv
import html
import re
from collections import Counter, defaultdict
from datetime import datetime
from pathlib import Path

SCRIPT = Path(__file__).resolve()
HP = SCRIPT.parents[2]
ROOT = HP.parent
DOCS = HP / "codex" / "docs"
BACKUP = ROOT / "codex_backup"
TS = datetime.now().strftime("%Y-%m-%d %H:%M")

CODE_EXTS = {".php", ".html", ".htm", ".css", ".js", ".json", ".xml"}
IMAGE_EXTS = {".jpg", ".jpeg", ".png", ".gif", ".svg", ".webp", ".ico"}
TEXT_EXTS = {".txt", ".csv", ".md"}
CANDY_DOC_NAMES = [
    "CANDY_MASTER_DOC_INDEX.md",
    "CANDY_OPERATION_BASICS.md",
    "CANDY_HP_STRUCTURE_MAP.md",
    "CANDY_FOLDER_ROLE_MAP.md",
    "CANDY_FULL_FILE_CODE_INVENTORY.md",
    "CANDY_CODE_FILE_STRUCTURE.md",
    "CANDY_NON_CODE_ASSET_INVENTORY.md",
    "CANDY_PAGE_CATEGORY_STRUCTURE.md",
    "CANDY_PAGE_SPEC_INDEX.md",
    "CANDY_FIX_BACKLOG.md",
    "CANDY_CODEX_BACKUP_REMARKS.md",
    "CANDY_EXISTING_DOCS_INVENTORY.md",
    "CANDY_PHASE_RECHECK.md",
]

def read_text(path: Path) -> str:
    for enc in ("utf-8-sig", "utf-8", "cp932", "shift_jis", "latin-1"):
        try:
            return path.read_text(encoding=enc, errors="strict")
        except Exception:
            pass
    return path.read_text(encoding="utf-8", errors="ignore")

def write_doc(name: str, lines: list[str]) -> None:
    DOCS.mkdir(parents=True, exist_ok=True)
    (DOCS / name).write_text("\n".join(lines).rstrip() + "\n", encoding="utf-8")

def rel(path: Path) -> str:
    try:
        return path.relative_to(HP).as_posix()
    except ValueError:
        return path.as_posix()

def esc(value) -> str:
    text = "" if value is None else str(value)
    text = text.replace("\\", "/")
    text = text.replace("|", "\\|")
    text = text.replace("\r", " ").replace("\n", " ")
    text = re.sub(r"\s+", " ", text).strip()
    return text.replace("`", "\\`")

def code_span(value) -> str:
    return "`" + esc(value) + "`"

def ext_label(path: Path) -> str:
    if path.name == ".htaccess":
        return ".htaccess"
    return path.suffix.lower() or "[no_ext]"

def top_label(r: str) -> str:
    return r.split("/", 1)[0] if "/" in r else "[root]"

def is_code_file(path: Path) -> bool:
    return path.name == ".htaccess" or path.suffix.lower() in CODE_EXTS

def file_role(path: Path) -> str:
    r = rel(path)
    top = top_label(r)
    name = path.name
    ext = ext_label(path)
    if name == ".htaccess":
        return "公開ルート設定"
    if name == "create.php":
        return "管理/生成系PHP（認証値は転記禁止）"
    if name == "makeSitemap.php":
        return "サイトマップ生成PHP"
    if top == "[root]" and ext == ".php":
        return "公開入口PHP"
    if top == "source" and ext in (".html", ".htm"):
        return "source HTMLテンプレート"
    if r == "includefile/dataset_base.php":
        return "共通データセット/生成制御"
    if r == "includefile/class.hpgcoder2.php":
        return "置換エンジン"
    if top == "includefile" and name.startswith("dataset_"):
        return "ページ別データセット"
    if top == "includefile":
        return "共通関数/設定補助"
    if top == "css":
        return "公開CSS"
    if top == "js":
        return "公開JavaScript"
    if top in ("imgHtml", "imgCss"):
        return "画像資産"
    if top == "movie":
        return "動画資産"
    if top.startswith("Text_"):
        return "Textデータ（本文転記禁止）"
    if top == "log":
        return "ログ（本文転記禁止）"
    if top == "codex":
        return "Codex管理資料/作業資料"
    if top == ".well-known":
        return "公開検証用隠し領域"
    if ext in IMAGE_EXTS:
        return "画像/表示資産"
    if ext in TEXT_EXTS:
        return "テキスト/CSV/管理資料"
    return "非コード資産"

def folder_role(path: Path) -> str:
    r = rel(path)
    if r == ".":
        return "公開HPルート"
    if r == ".vscode":
        return "編集環境設定"
    if r == "codex":
        return "Codex管理資料"
    if r == "codex/docs":
        return "現行CANDY管理MD"
    if r == "codex/area":
        return "エリアページ管理資料"
    if r == "codex/area/backups":
        return "改修前バックアップ保管"
    if r == "codex/scripts":
        return "管理資料生成スクリプト"
    if r.startswith("codex/reform_20260529"):
        return "過去改修資料"
    if r == "font" or r.startswith("font/"):
        return "子階層資産"
    if r == "source":
        return "HTMLテンプレート"
    if r == "includefile":
        return "共通PHPとdataset"
    if r == "css":
        return "公開CSS"
    if r == "js":
        return "公開JavaScript"
    if r in ("imgHtml", "imgCss") or r.startswith("imgHtml/") or r.startswith("imgCss/"):
        return "画像資産"
    if r == "movie" or r.startswith("movie/"):
        return "動画資産"
    if r.startswith("Text_"):
        return "Textデータ"
    if r == "log":
        return "ログ"
    if r == ".well-known" or r.startswith(".well-known/"):
        return "公開検証用隠し領域"
    return "要確認フォルダ"

def clean_html_text(raw: str) -> str:
    raw = re.sub(r"(?is)<script.*?</script>|<style.*?</style>", " ", raw)
    raw = re.sub(r"(?is)<[^>]+>", " ", raw)
    raw = html.unescape(raw)
    return re.sub(r"\s+", " ", raw).strip()

def extract_tag_text(text: str, tag: str) -> str:
    m = re.search(rf"(?is)<{tag}[^>]*>(.*?)</{tag}>", text)
    return clean_html_text(m.group(1)) if m else ""

def has_placeholder(text: str) -> bool:
    return bool(re.search(r"aaaaaaaa|placeholder|未設定|○○|ダミー", text or "", re.I))

def page_category(name: str) -> str:
    stem = Path(name).stem
    if name == "create.php":
        return "管理/生成"
    if name == "makeSitemap.php":
        return "サイトマップ生成"
    if stem.startswith("kagoshima-deliveryhealth-area-"):
        return "エリア詳細"
    if stem.startswith("kagoshima-deliveryhealth-blog-"):
        return "ブログ詳細"
    if stem.startswith("kagoshima-deliveryhealth-hotel-"):
        return "ホテル詳細"
    return "主要/通常"

def page_role(name: str, title: str, h1: str) -> str:
    stem = Path(name).stem
    if name == "create.php":
        return "認証付きページ作成機能。公開ページの通常改修対象ではなく、値の転記は禁止。"
    if name == "makeSitemap.php":
        return "現在ホストを起点にURLを収集し、XMLサイトマップを出力する処理。"
    if name in ("main.php", "page.php", "test.php"):
        return "dataset_base.phpを読む薄い入口候補。表示内容は共通処理側に依存する。"
    if stem.startswith("kagoshima-deliveryhealth-area-"):
        tail = stem.replace("kagoshima-deliveryhealth-area-", "")
        if title and not has_placeholder(title + h1):
            return "対応エリア詳細ページ"
        return f"対応エリア詳細ページ（slug: {tail} / 表示名未確定）"
    if stem.startswith("kagoshima-deliveryhealth-blog-"):
        return "SEOブログ詳細ページ"
    if stem.startswith("kagoshima-deliveryhealth-hotel-"):
        return "ホテル詳細ページ"
    if stem == "index":
        return "トップページ / サイト入口"
    if stem == "girls":
        return "女の子詳細プロフィールページ"
    if stem == "girls_list":
        return "女の子一覧ページ"
    if stem == "schedule":
        return "出勤スケジュールページ"
    if stem == "hotel":
        return "ホテル一覧ページ"
    if stem == "area":
        return "対応エリア一覧ページ"
    if stem == "blog":
        return "ブログ一覧ページ"
    if stem == "contact":
        return "問い合わせ/連絡導線候補"
    if stem == "news":
        return "NEWS/お知らせページ"
    if title:
        return title[:80]
    return "コード上不明 / 要オーナー確認"

def common_block(stats: dict) -> list[str]:
    keys = [
        ("全フォルダ", stats["folder_count"]),
        ("全ファイル", stats["file_count"]),
        ("コード/設定ファイル", stats["code_count"]),
        ("非コードファイル", stats["non_code_count"]),
        ("codex配下ファイル", stats["codex_count"]),
        ("codex/docs配下ファイル", stats["docs_count"]),
        ("管理MD(CANDY_*.md)", stats["candy_doc_count"]),
        ("ルート直下PHP", stats["root_php_count"]),
        ("source HTML", stats["source_html_count"]),
        ("includefile PHP", stats["includefile_php_count"]),
        ("dataset_*.php", stats["dataset_all_count"]),
        ("Text_*_data配下ファイル", stats["text_data_count"]),
        ("log配下ファイル", stats["log_count"]),
    ]
    out = ["## 共通集計", "", f"集計時点: {TS}", "", "| 項目 | 件数 |", "|---|---:|"]
    out += [f"| {k} | {v} |" for k, v in keys]
    out += ["", "注記: `全フォルダ` は `[root]` を除く実フォルダ数。フォルダ台帳は管理行として `[root]` を追加するため、台帳行数は `全フォルダ + 1`。"]
    return out

def build_analysis():
    for n in ("CANDY_OPERATION_BASICS.md", "CANDY_FIX_BACKLOG.md"):
        p = DOCS / n
        if not p.exists():
            p.write_text("\n", encoding="utf-8")
    all_files = sorted([p for p in HP.rglob("*") if p.is_file()], key=lambda p: rel(p).lower())
    all_dirs = sorted([p for p in HP.rglob("*") if p.is_dir()], key=lambda p: rel(p).lower())
    code_files = [p for p in all_files if is_code_file(p)]
    non_code_files = [p for p in all_files if not is_code_file(p)]
    root_php = sorted(HP.glob("*.php"), key=lambda p: p.name.lower())
    source_html = sorted((HP / "source").glob("*.html"), key=lambda p: p.name.lower()) if (HP / "source").exists() else []
    includefile_php = sorted((HP / "includefile").glob("*.php"), key=lambda p: p.name.lower()) if (HP / "includefile").exists() else []
    dataset_all = sorted((HP / "includefile").glob("dataset_*.php"), key=lambda p: p.name.lower()) if (HP / "includefile").exists() else []
    text_data = [p for p in all_files if top_label(rel(p)).startswith("Text_")]
    log_files = [p for p in all_files if top_label(rel(p)) == "log"]
    codex_files = [p for p in all_files if rel(p) == "codex" or rel(p).startswith("codex/")]
    docs_files = [p for p in all_files if rel(p).startswith("codex/docs/")]
    candy_docs = [p for p in all_files if rel(p).startswith("codex/docs/CANDY_") and p.suffix.lower() == ".md"]
    db = read_text(HP / "includefile" / "dataset_base.php") if (HP / "includefile" / "dataset_base.php").exists() else ""
    switch_map = {}
    for m in re.finditer(r"case\s+['\"]([^'\"]+\.html)['\"]\s*:\s*include\s*\(\s*INCLUDE_DIR\s*\.\s*['\"]([^'\"]+\.php)['\"]\s*\)", db, re.S):
        switch_map[m.group(1)] = m.group(2)
    class_text = read_text(HP / "includefile" / "class.hpgcoder2.php") if (HP / "includefile" / "class.hpgcoder2.php").exists() else ""
    rep_tokens = set(re.findall(r"rep\d+eot", class_text))
    funcs = re.findall(r"function\s+([A-Za-z0-9_]+)\s*\(", class_text)

    root_rows = []
    for i, php in enumerate(root_php, 1):
        stem = php.stem
        src = HP / "source" / f"{stem}.html"
        ds = HP / "includefile" / f"dataset_{stem}.php"
        php_text = read_text(php)
        src_text = read_text(src) if src.exists() else ""
        title = extract_tag_text(src_text, "title")
        h1 = extract_tag_text(src_text, "h1")
        combo = " / ".join([x for x in (title, h1) if x])
        ph = has_placeholder(combo)
        switch_ds = switch_map.get(f"{stem}.html", "")
        notes = []
        if not src.exists(): notes.append("source HTMLなし")
        if not ds.exists(): notes.append("個別datasetなし")
        if "dataset_base.php" not in php_text: notes.append("dataset_base非経由")
        if ph: notes.append("title/H1にplaceholder残り")
        if re.search(r"rep\d+eot", src_text): notes.append("動的置換トークンあり")
        if php.name == "create.php": notes.append("認証値あり。値は資料化しない")
        root_rows.append({
            "no": i, "php": php.name, "category": page_category(php.name),
            "role": page_role(php.name, title, h1),
            "title": title, "h1": h1, "source": f"source/{stem}.html" if src.exists() else "-",
            "dataset": f"includefile/dataset_{stem}.php" if ds.exists() else "-",
            "dataset_base": "yes" if "dataset_base.php" in php_text else "no",
            "source_exists": src.exists(), "dataset_exists": ds.exists(),
            "switch_dataset": switch_ds or "-", "switch_registered": bool(switch_ds),
            "placeholder": ph, "notes": " / ".join(notes) if notes else "確認済み",
        })
    source_without_php = [p for p in source_html if not (HP / (p.stem + ".php")).exists()]
    switch_missing = [p for p in source_html if p.name not in switch_map]
    missing_source = [r for r in root_rows if not r["source_exists"]]
    missing_dataset = [r for r in root_rows if not r["dataset_exists"]]
    placeholder_rows = [r for r in root_rows if r["placeholder"]]
    stats = {
        "folder_count": len(all_dirs), "file_count": len(all_files),
        "code_count": len(code_files), "non_code_count": len(non_code_files),
        "codex_count": len(codex_files), "docs_count": len(docs_files),
        "candy_doc_count": len(candy_docs), "root_php_count": len(root_php),
        "source_html_count": len(source_html), "includefile_php_count": len(includefile_php),
        "dataset_all_count": len(dataset_all), "text_data_count": len(text_data), "log_count": len(log_files),
        "dataset_switch_count": len(switch_map), "rep_switch_count": len(rep_tokens), "hpg_func_count": len(funcs),
        "missing_source_count": len(missing_source), "source_without_php_count": len(source_without_php),
        "dataset_base_nonload_count": len([r for r in root_rows if r["dataset_base"] == "no"]),
        "missing_dataset_count": len(missing_dataset), "switch_missing_count": len(switch_missing),
        "placeholder_count": len(placeholder_rows),
    }
    return {
        "files": all_files, "dirs": all_dirs, "code_files": code_files, "non_code_files": non_code_files,
        "root_rows": root_rows, "source_html": source_html, "source_without_php": source_without_php,
        "switch_missing": switch_missing, "missing_source": missing_source, "missing_dataset": missing_dataset,
        "placeholder_rows": placeholder_rows, "switch_map": switch_map, "stats": stats,
    }

def doc_master(a):
    s = a["stats"]
    lines = ["# CANDY 管理資料インデックス", "", "CANDYプロジェクトのHP構成、ページ内容、ページ内の作り方構成をたどる入口です。"]
    lines += common_block(s)
    lines += ["", "## 最初に見る順", "", "| 順 | 資料 | 役割 |", "|---:|---|---|"]
    order = [
        (1, "HP/AGENTS.md", "最上位ルール"),
        (2, "HP/codex/docs/CANDY_MASTER_DOC_INDEX.md", "管理資料の入口"),
        (3, "HP/codex/docs/CANDY_OPERATION_BASICS.md", "改修前に確認する運用情報"),
        (4, "HP/codex/docs/CANDY_HP_STRUCTURE_MAP.md", "HP全体構成"),
        (5, "HP/codex/docs/CANDY_FOLDER_ROLE_MAP.md", "フォルダ別役割"),
        (6, "HP/codex/docs/CANDY_FULL_FILE_CODE_INVENTORY.md", "全フォルダ/全ファイル台帳"),
        (7, "HP/codex/docs/CANDY_CODE_FILE_STRUCTURE.md", "PHP/HTML/dataset/CSS/JS構成"),
        (8, "HP/codex/docs/CANDY_NON_CODE_ASSET_INVENTORY.md", "画像/Text/ログ/DB等の非コード台帳"),
        (9, "HP/codex/docs/CANDY_PAGE_CATEGORY_STRUCTURE.md", "カテゴリ別ページ構成"),
        (10, "HP/codex/docs/CANDY_PAGE_SPEC_INDEX.md", "ページごとの役割と構成"),
        (11, "HP/codex/docs/CANDY_FIX_BACKLOG.md", "要確認差分の処理バックログ"),
        (12, "HP/codex/docs/CANDY_CODEX_BACKUP_REMARKS.md", "codex_backup由来の備考"),
        (13, "HP/codex/docs/CANDY_EXISTING_DOCS_INVENTORY.md", "残存資料/削除済み旧資料の説明"),
        (14, "HP/codex/docs/CANDY_PHASE_RECHECK.md", "フェーズ再確認と未実施フェーズ"),
    ]
    for no, p, role in order:
        lines.append(f"| {no} | {code_span(p)} | {esc(role)} |")
    lines += ["", "## 管理階層", "", "```text", "AGENTS.md", "  └─ 全体構成", "      └─ ページ内容", "          └─ 各ページごとのページ内構成", "```"]
    lines += ["", "## 運用注意", "", "- 管理資料の数値は共通集計の時点で統一する。", "- PHP/HTML/CSS/JS/画像/ログ/DB/Text_* 本体は、この整理では変更しない。", "- 認証値、DB値、ログ本文、hidden値は資料へ転記しない。"]
    return lines

def doc_hp_structure(a):
    s = a["stats"]
    lines = ["# CANDY HP 全体構成", "", "HP配下を、公開入口、テンプレート、dataset、資産、管理資料に分けて管理します。"]
    lines += common_block(s)
    lines += ["", "## 主要構成", "", "| 階層 | 役割 | 件数/状態 |", "|---|---|---:|"]
    top_counts = Counter(top_label(rel(p)) for p in a["files"])
    rows = [
        ("[root]", "公開入口PHPとルート設定", top_counts.get("[root]", 0)),
        ("source", "HTMLテンプレート", top_counts.get("source", 0)),
        ("includefile", "共通PHPとdataset", top_counts.get("includefile", 0)),
        ("css", "公開CSS", top_counts.get("css", 0)),
        ("js", "公開JavaScript", top_counts.get("js", 0)),
        ("imgHtml", "HTML用画像", top_counts.get("imgHtml", 0)),
        ("imgCss", "CSS用画像", top_counts.get("imgCss", 0)),
        ("movie", "動画資産", top_counts.get("movie", 0)),
        ("Text_*_data", "テキストデータ", s["text_data_count"]),
        ("log", "ログ。本文転記禁止", top_counts.get("log", 0)),
        ("codex", "Codex管理資料", top_counts.get("codex", 0)),
    ]
    for top, role, cnt in rows:
        lines.append(f"| {esc(top)} | {esc(role)} | {cnt} |")
    lines += ["", "## 基本生成構造", "", "```text", "ルート直下PHP", "  -> includefile/dataset_base.php", "  -> source/同名.html", "  -> includefile/dataset_同名.php", "  -> includefile/class.hpgcoder2.php", "  -> HTML出力", "```"]
    lines += ["", "## 要確認", "", "- PHP実行、DB接続、ブラウザ表示は未確認。", "- 公開方式と本番URLは CANDY_OPERATION_BASICS.md で未確認項目として管理する。"]
    return lines

def doc_folder_map(a):
    s = a["stats"]
    lines = ["# CANDY フォルダ役割マップ", "", "全フォルダを役割別に管理する資料です。"]
    lines += common_block(s)
    lines += ["", "## フォルダ台帳", "", "| No | フォルダ | 親 | 直下ファイル | 直下フォルダ | 配下ファイル | 役割 |", "|---:|---|---|---:|---:|---:|---|"]
    dirs = [HP] + a["dirs"]
    for i, d in enumerate(dirs, 1):
        r = "[root]" if d == HP else rel(d)
        parent = "-" if d == HP else ("[root]" if d.parent == HP else rel(d.parent))
        direct_files = len([p for p in d.iterdir() if p.is_file()]) if d.exists() else 0
        direct_dirs = len([p for p in d.iterdir() if p.is_dir()]) if d.exists() else 0
        recursive_files = len([p for p in d.rglob("*") if p.is_file()]) if d.exists() else 0
        lines.append(f"| {i} | {esc(r)} | {esc(parent)} | {direct_files} | {direct_dirs} | {recursive_files} | {esc(folder_role(d))} |")
    return lines

def doc_full_inventory(a):
    s = a["stats"]
    lines = ["# CANDY 全フォルダ・全ファイル台帳", "", "HP配下の全ファイルを1件ずつ管理する台帳です。"]
    lines += common_block(s)
    jp = "Text_hotel_data/グリーンリッチホテル鹿児島天文館.txt"
    jp_exists = (HP / jp).exists()
    lines += ["", "## エスケープ確認", "", "| 観点 | 対象 | 結果 |", "|---|---|---|", f"| 日本語ファイル名 | {code_span(jp)} | {'存在確認済み' if jp_exists else '未確認'} |", "| 表内パス | スラッシュ区切りで出力し、パイプは表崩れ防止のためエスケープ | 確認対象 |"]
    lines += ["", "## フォルダ台帳", "", "| No | フォルダ | 役割 | 配下ファイル |", "|---:|---|---|---:|"]
    for i, d in enumerate([HP] + a["dirs"], 1):
        r = "[root]" if d == HP else rel(d)
        rec = len([p for p in d.rglob("*") if p.is_file()]) if d.exists() else 0
        lines.append(f"| {i} | {esc(r)} | {esc(folder_role(d))} | {rec} |")
    lines += ["", "## ファイル台帳", "", "<!-- FILE_TABLE_START -->", "| No | パス | トップ階層 | 拡張子 | 分類 | サイズ(byte) | 役割 |", "|---:|---|---|---|---|---:|---|"]
    for i, p in enumerate(a["files"], 1):
        r = rel(p)
        kind = "code" if is_code_file(p) else "non-code"
        lines.append(f"| {i} | {esc(r)} | {esc(top_label(r))} | {esc(ext_label(p))} | {kind} | {p.stat().st_size} | {esc(file_role(p))} |")
    lines += ["<!-- FILE_TABLE_END -->"]
    return lines

def doc_code_structure(a):
    s = a["stats"]
    lines = ["# CANDY HP コード構成管理", "", "PHP、HTML、CSS、JS、htaccess、JSON、XMLの構成を管理します。"]
    lines += common_block(s)
    lines += ["", "## Phase B 判定", "", "| 項目 | 件数 |", "|---|---:|",
              f"| コード/設定ファイル総数 | {s['code_count']} |", f"| HP直下PHP | {s['root_php_count']} |",
              f"| source直下HTML | {s['source_html_count']} |", f"| includefile PHP | {s['includefile_php_count']} |",
              f"| dataset_*.php | {s['dataset_all_count']} |", f"| dataset_base switch登録 | {s['dataset_switch_count']} |",
              f"| HpgCoder rep switch数 | {s['rep_switch_count']} |", f"| HpgCoder func数 | {s['hpg_func_count']} |"]
    lines += ["", "## 生成構造", "", "| 段階 | 役割 | 管理対象 |", "|---:|---|---|",
              "| 1 | HP直下PHPが入口になる | `*.php` |", "| 2 | 共通処理を読む | `includefile/dataset_base.php` |",
              "| 3 | PHP名からsource HTMLを見る | `source/同名.html` |", "| 4 | dataset_baseのswitchでページ別datasetを読む | `includefile/dataset_*.php` |",
              "| 5 | class.hpgcoder2.phpが置換トークンを処理する | `HpgCoder` |", "| 6 | CSS/JS/画像/動画等を参照して公開ページを構成する | `css`, `js`, `imgHtml`, `imgCss`, `movie` |"]
    lines += ["", "## 要確認差分", "", "| 観点 | 件数 | 対象 |", "|---|---:|---|"]
    lines.append(f"| source HTMLなしの直下PHP | {s['missing_source_count']} | {esc(', '.join(r['php'] for r in a['missing_source']))} |")
    lines.append(f"| 直下PHPなしのsource HTML | {s['source_without_php_count']} | {esc(', '.join('source/' + p.name for p in a['source_without_php']))} |")
    lines.append(f"| dataset_base未読込の直下PHP | {s['dataset_base_nonload_count']} | {esc(', '.join(r['php'] for r in a['root_rows'] if r['dataset_base'] == 'no'))} |")
    lines.append(f"| 対応dataset未確認の直下PHP | {s['missing_dataset_count']} | {esc(', '.join(r['php'] for r in a['missing_dataset']))} |")
    lines.append(f"| switch未登録のsource HTML | {s['switch_missing_count']} | {esc(', '.join('source/' + p.name for p in a['switch_missing']))} |")
    lines += ["", "## HP直下PHPとHTML/dataset対応", "", "| No | PHP | 役割 | dataset_base | source HTML | HTML有無 | switch dataset | dataset有無 |", "|---:|---|---|---|---|---|---|---|"]
    for r in a["root_rows"]:
        lines.append(f"| {r['no']} | {esc(r['php'])} | {esc(r['role'])} | {r['dataset_base']} | {esc(r['source'])} | {'yes' if r['source_exists'] else 'no'} | {esc(r['switch_dataset'])} | {'yes' if r['dataset_exists'] else 'no'} |")
    lines += ["", "## コードファイル一覧", "", "| No | パス | 拡張子 | 役割 |", "|---:|---|---|---|"]
    for i, p in enumerate(a["code_files"], 1):
        lines.append(f"| {i} | {esc(rel(p))} | {esc(ext_label(p))} | {esc(file_role(p))} |")
    return lines

def doc_non_code(a):
    s = a["stats"]
    lines = ["# CANDY 非コード資産台帳", "", "画像、動画、Textデータ、ログ、DBファイル、管理MDなど、コード/設定以外を管理します。"]
    lines += common_block(s)
    lines += ["", "## 非コード種別サマリー", "", "| 種別 | 件数 |", "|---|---:|"]
    c = Counter(file_role(p) for p in a["non_code_files"])
    for k, v in sorted(c.items()):
        lines.append(f"| {esc(k)} | {v} |")
    lines += ["", "## 非コードファイル一覧", "", "| No | パス | トップ階層 | 拡張子 | サイズ(byte) | 役割 |", "|---:|---|---|---|---:|---|"]
    for i, p in enumerate(a["non_code_files"], 1):
        r = rel(p)
        lines.append(f"| {i} | {esc(r)} | {esc(top_label(r))} | {esc(ext_label(p))} | {p.stat().st_size} | {esc(file_role(p))} |")
    return lines

def doc_page_category(a):
    s = a["stats"]
    lines = ["# CANDY ページカテゴリ別構成", "", "カテゴリごとにページ構成を管理する資料です。"]
    lines += common_block(s)
    cat = defaultdict(list)
    for r in a["root_rows"]:
        cat[r["category"]].append(r)
    lines += ["", "## カテゴリ別件数", "", "| カテゴリ | 件数 |", "|---|---:|"]
    for k in sorted(cat):
        lines.append(f"| {esc(k)} | {len(cat[k])} |")
    lines += ["", "## カテゴリ別ページ", ""]
    for k in sorted(cat):
        lines += [f"### {k}", "", "| PHP | source HTML | dataset | 状態 |", "|---|---|---|---|"]
        for r in cat[k]:
            state = " / ".join(["sourceあり" if r["source_exists"] else "sourceなし", "datasetあり" if r["dataset_exists"] else "datasetなし", "switch登録あり" if r["switch_registered"] else "switch未登録"])
            lines.append(f"| {esc(r['php'])} | {esc(r['source'])} | {esc(r['dataset'])} | {esc(state)} |")
        lines.append("")
    return lines

def special_php_rows():
    return [
        ("main.php", "dataset_base.phpを読み込む薄い入口ファイル。ファイル内に独自表示ロジックは見えず、出力は共通処理側に依存する。", "外部URLから呼ばれる可能性あり。手動専用とは断定不可。", "要確認"),
        ("page.php", "dataset_base.phpを読み込む汎用入口候補。個別dataset_page.phpは存在するが、ページ用途はコード上だけでは確定しない。", "外部URLから呼ばれる可能性あり。手動専用とは断定不可。", "要確認"),
        ("test.php", "dataset_base.phpを読み込むテスト名の入口候補。dataset_test.phpは存在するが、公開扱いか検証用かはコード上不明。", "外部URLから呼ばれる可能性あり。手動専用とは断定不可。", "要確認"),
        ("makeSitemap.php", "現在ホストを起点にURLを収集し、XMLサイトマップを出力する処理。testモードでは収集結果の確認出力を行う。", "URL実行で動作する生成系処理。手動実行にも使われる可能性あり。", "要確認"),
        ("create.php", "POST/GETを受け、source HTMLやdataset等の作成に関わる認証付き生成機能。認証値・秘密値は資料へ転記しない。", "外部URLからアクセス可能だが認証前提の管理/生成機能。", "変更禁止"),
    ]

def doc_page_spec(a):
    s = a["stats"]
    lines = ["# CANDY ページ仕様インデックス", "", "ルート直下PHPを1件も漏らさず、ページごとの役割・対応HTML・対応dataset・未確認点を管理します。"]
    lines += common_block(s)
    lines += ["", "## ページ件数", "", "| 項目 | 件数 |", "|---|---:|",
              f"| ルート直下PHP | {s['root_php_count']} |", f"| source HTMLあり | {s['root_php_count'] - s['missing_source_count']} |",
              f"| source HTMLなし | {s['missing_source_count']} |", f"| 個別datasetあり | {s['root_php_count'] - s['missing_dataset_count']} |",
              f"| 個別datasetなし | {s['missing_dataset_count']} |", f"| dataset_base読込あり | {s['root_php_count'] - s['dataset_base_nonload_count']} |",
              f"| dataset_base読込なし | {s['dataset_base_nonload_count']} |", f"| title/H1 placeholder残り | {s['placeholder_count']} |"]
    lines += ["", "## 特殊PHP確定", "", "| PHP | 何をするファイルか | 呼ばれ方 | 削除・変更可否 |", "|---|---|---|---|"]
    for row in special_php_rows():
        lines.append("| " + " | ".join(esc(x) for x in row) + " |")
    lines += ["", "## ページ別役割表", "", "| No | PHP | 分類 | 役割 | title / H1 | source HTML | dataset | 状態 | 注意 |", "|---:|---|---|---|---|---|---|---|---|"]
    for r in a["root_rows"]:
        th = []
        if r["title"]: th.append("title: " + r["title"])
        if r["h1"]: th.append("H1: " + r["h1"])
        status = " / ".join(["sourceあり" if r["source_exists"] else "sourceなし", "datasetあり" if r["dataset_exists"] else "datasetなし", "dataset_base読込あり" if r["dataset_base"] == "yes" else "dataset_base読込なし"])
        lines.append(f"| {r['no']} | {esc(r['php'])} | {esc(r['category'])} | {esc(r['role'])} | {esc('<br>'.join(th) if th else '-')} | {esc(r['source'])} | {esc(r['dataset'])} | {esc(status)} | {esc(r['notes'])} |")
    return lines

def doc_operation(a):
    s = a["stats"]
    lines = ["# CANDY 運用基礎", "", "改修前に必ず確認する運用情報です。不明な項目は未確認とし、確認方法を併記します。"]
    lines += common_block(s)
    lines += ["", "## 1. 公開方式", "", "未確認 / 確認方法: オーナーまたはサーバー管理者に、現在のNASフォルダが直接公開なのか、別サーバーへFTP/rsync等でアップロードしているのかを確認する。", "", "確認済みの事実: PHP内のinclude先にはサーバー上の絶対パスが使われているため、NASパスだけで公開方式は断定しない。"]
    lines += ["", "## 2. 本番URL / PHPバージョン / Webサーバー", "", "| 項目 | 状態 | 確認方法 |", "|---|---|---|", "| 本番URL | 未確認 | オーナー確認、またはサーバー設定/公開中URLを確認 |", "| PHPバージョン | 未確認 | 本番サーバーの管理画面またはphpinfo相当の安全な確認手段で確認 |", "| Webサーバー種別 | 未確認 | 本番サーバーの管理画面、レスポンスヘッダ、契約情報で確認 |"]
    lines += ["", "## 3. 動作確認手順", "", "未確認 / 確認方法: テスト環境の有無をオーナー確認する。テスト環境がある場合は、改修前バックアップ、差分確認、テスト環境反映、主要ページ表示確認、フォーム/動的ページ確認、ログ/DB値の非転記確認の順で行う。", "", "テスト環境がない場合: 本番反映前にバックアップを取り、反映ファイルを限定し、反映直後にトップ、一覧、詳細、問い合わせ、サイトマップを確認する。"]
    lines += ["", "## 4. 変更禁止/要承認ファイル", "", "| ファイル | 判定 | 理由 |", "|---|---|---|",
              "| `create.php` | 変更禁止 | 認証値とファイル生成処理に関わる。値は転記禁止。 |",
              "| `includefile/dataset_base.php` | 要承認 | 全ページ生成の共通入口で、DB/外部設定読込にも関わる。 |",
              "| `.htaccess` | 要承認 | 公開ルート設定。URL/アクセス制御へ影響する。 |",
              "| `includefile/class.hpgcoder2.php` | 要承認 | 置換エンジン。多数ページに影響する。 |",
              "| `includefile/dataset_*.php` | 要承認 | ページ別表示データ。変更範囲確認が必要。 |",
              "| `source/system.html` | 要承認 | 決済/外部連携/hidden値を含む可能性。値は転記禁止。 |",
              "| `log`配下 | 変更禁止 | ログ本文は転記禁止。削除も承認なし禁止。 |"]
    lines += ["", "## 5. DB接続定義の所在", "", "所在のみ記載。値は記載しない。", "", "| 種別 | パス | 状態 |", "|---|---|---|", "| require元 | `includefile/dataset_base.php` | 確認済み |", "| 外部設定候補 | `/home/firststar/public_html/group/control/includefile/incfiles_vv.php` | 未確認 / 確認方法: 本番サーバー上で所在と役割を確認。値は転記しない。 |", "| セッション設定候補 | `/home/firststar/public_html/group_test/control/includefile/setting_session_vv.php` | 未確認 / 確認方法: 本番サーバー上で所在と役割を確認。値は転記しない。 |"]
    lines += ["", "## 6. バックアップ手順", "", "既存慣例: `HP/codex/area/backups` に、対象ファイル名 + 変更理由 + 日付の形で保存された実績あり。", "", "推奨ルール: 改修前に `HP/codex/area/backups/<元ファイル名>.before-<作業名>-YYYYMMDD.<ext>` の形式で管理コピーを作る。", "", "未確認 / 確認方法: 今後の正式な保管先を `HP/codex/area/backups` 継続でよいかオーナー確認する。"]
    lines += ["", "## 7. 管理資料の再生成手順", "", r"正本: `\\192.168.1.3\disk1\FSG_SEO\candy\HP\codex\scripts\generate_candy_management_docs.py`", "", "実行コマンド:", "", "```powershell", r'python "\\192.168.1.3\disk1\FSG_SEO\candy\HP\codex\scripts\generate_candy_management_docs.py"', "```", "", "Python未導入環境: PATH上の `python` または `py` を導入する。Codex環境では `Invoke-CandyDocsMaintenance.ps1` がCodexランタイムPythonへフォールバックする。", "必要バージョン/依存: Python 3.9以上。標準ライブラリのみ使用し、外部パッケージ依存なし。", "補足: `HP/codex/scripts/Invoke-CandyDocsMaintenance.ps1` は互換用ラッパーのみ。管理資料の生成・出力ロジックは正本Pythonに集約する。"]
    return lines

def backlog_rows(a):
    rows = []
    for r in a["missing_source"]:
        cause = "通常ページではない入口" if r["php"] in ("main.php", "page.php", "test.php", "makeSitemap.php") else "source未作成または削除残り"
        plan = "要オーナー判断" if r["php"] in ("main.php", "page.php", "test.php", "makeSitemap.php") else "直す"
        rows.append((r["php"], "source HTMLなし", cause, plan, "未着手"))
    for p in a["switch_missing"]:
        path = "source/" + p.name
        cause = "テンプレ用途のため未登録の可能性" if p.name.startswith("template_") else "dataset_base登録漏れまたは動的読込対象外"
        plan = "放置可" if p.name.startswith("template_") else "要オーナー判断"
        rows.append((path, "dataset_base switch未登録", cause, plan, "未着手"))
    for r in a["missing_dataset"]:
        rows.append((r["php"], "対応dataset未確認", "通常ページではない入口またはdataset未作成", "要オーナー判断", "未着手"))
    for r in a["placeholder_rows"]:
        rows.append((r["php"], "title/H1 placeholder残り", "テンプレ未展開ページ", "直す", "未着手"))
    return rows

def doc_fix_backlog(a):
    s = a["stats"]
    rows = backlog_rows(a)
    lines = ["# CANDY 修正バックログ", "", "CANDY_CODE_FILE_STRUCTURE.md と CANDY_PAGE_SPEC_INDEX.md の要確認差分を1件1行で管理します。"]
    lines += common_block(s)
    lines += ["", "## 統合方針", "", f"重複統合なし。問題種別が違うものは同じファイルでも別行で残す。現在の行数は {len(rows)} 行。", "", "## バックログ", "", "| No | 対象ファイル | 問題 | 推定原因 | 処理案 | 状態 |", "|---:|---|---|---|---|---|"]
    for i, row in enumerate(rows, 1):
        target, problem, cause, plan, state = row
        lines.append(f"| {i} | {esc(target)} | {esc(problem)} | {esc(cause)} | {esc(plan)} | {esc(state)} |")
    return lines

def doc_backup_remarks(a):
    s = a["stats"]
    lines = ["# CANDY codex_backup 備考", "", "過去Codexが作成した資料から、今回の管理資料と別に残す価値がある情報を整理します。現行仕様としては扱わず、再確認前提の備考です。"]
    lines += common_block(s)
    backup_docs = []
    if (BACKUP / "docs").exists():
        backup_docs = sorted((BACKUP / "docs").glob("*"), key=lambda p: p.name.lower())
    lines += ["", "## codex_backup/docs 一覧", "", "| No | ファイル | サイズ(byte) | 備考 |", "|---:|---|---:|---|"]
    useful = {
        "PAGE_LIST.md": "旧ページ一覧。現行ファイルと照合する時だけ参照。",
        "SITE_STRUCTURE.md": "旧サイト構成の説明。現行構成とは必ず再照合。",
        "TECHNICAL_ANALYSIS.md": "旧技術分析。datasetや生成構造の観点確認に使える。",
        "UNKNOWN_AND_RISK_LIST.md": "旧リスク一覧。未確認項目の拾い漏れ確認に使える。",
        "CONTENT_ANALYSIS.md": "旧コンテンツ分析。ページ内容確認の補助。",
    }
    for i, p in enumerate(backup_docs, 1):
        lines.append(f"| {i} | {esc('codex_backup/docs/' + p.name)} | {p.stat().st_size} | {esc(useful.get(p.name, '旧資料。現行仕様としては再確認が必要。'))} |")
    area = HP / "codex" / "area"
    area_files = sorted(area.glob("*"), key=lambda p: p.name.lower()) if area.exists() else []
    lines += ["", "## HP/codex/area の使える情報", "", "| ファイル | 使える情報 |", "|---|---|"]
    for p in area_files:
        if p.is_file():
            note = "エリアページ整理の旧管理資料。現行差分の確認に使える。"
            if p.name == "AREA_PAGE_MASTER.csv":
                try:
                    with p.open("r", encoding="utf-8-sig", newline="") as f:
                        rows = list(csv.reader(f))
                    note = f"エリアページ管理CSV。行数 {len(rows)}。"
                except Exception:
                    note = "エリアページ管理CSV。行数は読み取り未確認。"
            lines.append(f"| {esc(rel(p))} | {esc(note)} |")
    return lines

def doc_existing_docs(a):
    s = a["stats"]
    lines = ["# CANDY 残存管理資料説明", "", "HP/codex/docs 配下に残すCANDY資料と、削除済み旧資料の扱いを説明します。"]
    lines += common_block(s)
    lines += ["", "## 残す資料", "", "| No | 資料 | 説明 |", "|---:|---|---|"]
    desc = {
        "CANDY_MASTER_DOC_INDEX.md": "管理資料の入口。読む順を管理。",
        "CANDY_OPERATION_BASICS.md": "改修前に必要な運用情報。",
        "CANDY_HP_STRUCTURE_MAP.md": "HP全体構成。",
        "CANDY_FOLDER_ROLE_MAP.md": "全フォルダの役割。",
        "CANDY_FULL_FILE_CODE_INVENTORY.md": "全フォルダ/全ファイル台帳。",
        "CANDY_CODE_FILE_STRUCTURE.md": "コード/設定ファイル構成。",
        "CANDY_NON_CODE_ASSET_INVENTORY.md": "非コード資産台帳。",
        "CANDY_PAGE_CATEGORY_STRUCTURE.md": "カテゴリ別ページ構成。",
        "CANDY_PAGE_SPEC_INDEX.md": "ページごとの役割と構成。",
        "CANDY_FIX_BACKLOG.md": "要確認差分の処理バックログ。",
        "CANDY_CODEX_BACKUP_REMARKS.md": "旧Codex資料の備考。",
        "CANDY_EXISTING_DOCS_INVENTORY.md": "残存資料説明。",
        "CANDY_PHASE_RECHECK.md": "フェーズ再確認。",
    }
    docs = sorted((DOCS).glob("CANDY_*.md"), key=lambda p: p.name.lower())
    for i, p in enumerate(docs, 1):
        lines.append(f"| {i} | {esc(rel(p))} | {esc(desc.get(p.name, 'CANDY管理資料'))} |")
    old = ["AGENTS.md", "CODEX_MANAGEMENT_GUIDE.md", "CODEX_SITE_OVERVIEW.md", "CONTENT_ANALYSIS.md", "PAGE_LIST.md", "SITE_STRUCTURE.md", "TECHNICAL_ANALYSIS.md", "UI_DESIGN_ANALYSIS.md", "UNKNOWN_AND_RISK_LIST.md"]
    lines += ["", "## 削除済み旧資料", "", "以下はHP/codex/docsからは削除済み。必要な場合はcodex_backup/docsの旧資料として、現行ファイルと再照合してから使う。", "", "| ファイル | 状態 |", "|---|---|"]
    for n in old:
        lines.append(f"| {esc(n)} | HP/codex/docsから削除済み / codex_backup/docsに旧資料あり |")
    return lines

def doc_phase_recheck(a):
    s = a["stats"]
    lines = ["# CANDY フェーズ再確認", "", "ここまでの作業をフェーズ単位で再確認します。"]
    lines += common_block(s)
    lines += ["", "## 完了フェーズ", "", "| フェーズ | 内容 | 成果物 |", "|---|---|---|",
              "| Phase 0 | AGENTS.mdをCANDY前提に確認し、管理資料作成ルールを確定 | `HP/AGENTS.md` |",
              "| Phase A | 既存資料とcodex_backupを確認し、使える情報を備考化 | `CANDY_CODEX_BACKUP_REMARKS.md` |",
              "| Phase B | HP全体構成、フォルダ、全ファイル、コード/非コード台帳を整理 | `CANDY_HP_STRUCTURE_MAP.md`, `CANDY_FOLDER_ROLE_MAP.md`, `CANDY_FULL_FILE_CODE_INVENTORY.md`, `CANDY_CODE_FILE_STRUCTURE.md`, `CANDY_NON_CODE_ASSET_INVENTORY.md` |",
              "| Phase C | ページカテゴリとページ別役割を整理 | `CANDY_PAGE_CATEGORY_STRUCTURE.md`, `CANDY_PAGE_SPEC_INDEX.md` |",
              "| Phase D | 監査不合格箇所を修正し、生成スクリプトから資料を再生成 | `HP/codex/scripts/generate_candy_management_docs.py` と `CANDY_*.md` |",
              "| Phase E | 改修前の運用基礎と差分バックログを新規作成 | `CANDY_OPERATION_BASICS.md`, `CANDY_FIX_BACKLOG.md` |"]
    lines += ["", "## 未実施フェーズ", "", "| フェーズ | 内容 | 前提条件 |", "|---|---|---|",
              "| Phase F | 本番URL/公開方式/PHPバージョン/Webサーバー確認 | オーナーまたはサーバー管理者から運用情報を得る |",
              "| Phase G | ブラウザ表示・PHP実行・DB接続を含む検証 | テスト環境または本番確認手順の承認 |",
              "| Phase H | CANDY_FIX_BACKLOG.md の各行を処理 | 処理案についてオーナー判断を得る |",
              "| Phase I | 実改修 | 変更対象・バックアップ・反映手順の承認 |"]
    lines += ["", "## 件数差分の記録", "", "| 記録 | 内容 |", "|---|---|", f"| 再集計時点 | {TS} |", f"| 現在統一値 | 全ファイル {s['file_count']} / コード・設定 {s['code_count']} / codex配下 {s['codex_count']} |", "| 旧資料差分1件の正体 | 旧CANDY_HP_STRUCTURE_MAPは出力資料自身または後続CANDY管理資料の作成前に集計されており、後続資料では管理MDが1件増えた状態だった。現在は新規2資料と生成スクリプトを含め同一時点で再集計済み。 |"]
    lines += ["", "## 削除済み", "", "| 対象 | 状態 |", "|---|---|", "| 旧docs資料9件 | HP/codex/docsから削除済み。codex_backup/docsに旧資料あり。 |"]
    return lines

def write_all(a):
    docs = {
        "CANDY_MASTER_DOC_INDEX.md": doc_master(a),
        "CANDY_OPERATION_BASICS.md": doc_operation(a),
        "CANDY_HP_STRUCTURE_MAP.md": doc_hp_structure(a),
        "CANDY_FOLDER_ROLE_MAP.md": doc_folder_map(a),
        "CANDY_FULL_FILE_CODE_INVENTORY.md": doc_full_inventory(a),
        "CANDY_CODE_FILE_STRUCTURE.md": doc_code_structure(a),
        "CANDY_NON_CODE_ASSET_INVENTORY.md": doc_non_code(a),
        "CANDY_PAGE_CATEGORY_STRUCTURE.md": doc_page_category(a),
        "CANDY_PAGE_SPEC_INDEX.md": doc_page_spec(a),
        "CANDY_FIX_BACKLOG.md": doc_fix_backlog(a),
        "CANDY_CODEX_BACKUP_REMARKS.md": doc_backup_remarks(a),
        "CANDY_EXISTING_DOCS_INVENTORY.md": doc_existing_docs(a),
        "CANDY_PHASE_RECHECK.md": doc_phase_recheck(a),
    }
    for name in CANDY_DOC_NAMES:
        write_doc(name, docs[name])

if __name__ == "__main__":
    analysis = build_analysis()
    write_all(analysis)
    print(f"written={len(CANDY_DOC_NAMES)} files={analysis['stats']['file_count']} code={analysis['stats']['code_count']} codex={analysis['stats']['codex_count']} backlog={len(backlog_rows(analysis))}")




