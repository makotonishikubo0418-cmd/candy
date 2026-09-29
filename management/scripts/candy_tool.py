"""Unified command discovery and read-only environment diagnostics."""
from __future__ import annotations

import importlib
import importlib.util
import json
import os
import shutil
import subprocess
import sys

sys.dont_write_bytecode = True
os.environ["PYTHONDONTWRITEBYTECODE"] = "1"
import candy_page_common as common

COMMANDS = {
    "area": {
        **dict.fromkeys(("build", "check", "audit-inputs", "related-write", "related-check"), "candy_area_page"),
        **dict.fromkeys(("target-next", "target-check"), "candy_area_target_gate"),
        **dict.fromkeys(("publish", "publish-next", "resume", "publish-self-test"), "candy_area_publish"),
        "replace-images": "candy_area_image_replace",
        "audit-existing": "candy_existing_audit",
    },
    "hotel": {
        **dict.fromkeys(("build", "check", "self-test"), "candy_hotel_page"),
        **dict.fromkeys(("target-next", "target-check", "direct-check", "audit-inputs"), "candy_hotel_target_gate"),
        **dict.fromkeys(("publish", "publish-next", "resume", "publish-self-test"), "candy_hotel_publish"),
        **dict.fromkeys(("legacy-check", "legacy-convert", "legacy-self-test"), "candy_hotel_text_migration"),
        **dict.fromkeys(("image-plan", "image-render", "image-check", "image-self-test"), "candy_hotel_image"),
        **dict.fromkeys(("image-status", "image-install"), "candy_image_assets"),
        "audit-existing": "candy_existing_audit",
    },
    "site-state": dict.fromkeys(("audit", "preview", "write", "check", "preview-sitemap-lastmod", "sync-sitemap-lastmod"), "candy_site_state"),
}


def doctor(category: str) -> int:
    paths = [common.DOCS_DIR, common.SCRIPTS_DIR, common.HP_ROOT]
    if category == "area":
        paths += [common.DATA_DIR / "CANDY_AREA_RELATED_LINKS.json", common.DOCS_DIR / "CANDY_AREA_105_PAGE_QUEUE.md"]
    missing = [str(path) for path in paths if not path.exists()]
    pillow = importlib.util.find_spec("PIL") is not None
    php = shutil.which("php")
    php_ok = bool(php) and subprocess.run([php, "-d", "short_open_tag=1", "-l"], input="<? broken syntax;", text=True, capture_output=True).returncode != 0
    result = {"python": sys.executable, "python_version": sys.version.split()[0], "pillow": pillow,
              "php": php, "php_short_tag_lint": php_ok, "git": shutil.which("git"),
              "missing_paths": missing, "production": "NOT_VERIFIED"}
    print("ENVIRONMENT_JSON=" + json.dumps(result, ensure_ascii=False))
    ok = not missing and sys.version_info >= (3, 12) and php_ok and bool(result["git"]) and (category == "site-state" or pillow)
    print("RESULT=" + ("READY_LOCAL" if ok else "STOP"))
    return 0 if ok else 2


def main() -> int:
    sys.dont_write_bytecode = True
    os.environ["PYTHONDONTWRITEBYTECODE"] = "1"
    os.environ["GIT_OPTIONAL_LOCKS"] = "0"
    category, *args = sys.argv[1:]
    if category not in COMMANDS:
        raise common.PageToolError("unknown tool category")
    if not args or args[0] in {"help", "--help", "-h"}:
        print(f"candy-{category}: " + " / ".join(["doctor", *COMMANDS[category]]))
        print("COMMAND --help: arguments; audit/doctor/check/preview/--dry-run: read-only.")
        print("build/write/install/convert/publish/resume may change files; publication requires explicit authorization.")
        return 0
    if args[0] == "doctor":
        return doctor(category)
    module_name = COMMANDS[category].get(args[0])
    if module_name is None:
        raise common.PageToolError(f"unknown command: {args[0]}; use --help")
    if module_name == "candy_existing_audit":
        args += ["--category", category]
    sys.argv = [module_name, *args]
    return importlib.import_module(module_name).main()


if __name__ == "__main__":
    try:
        raise SystemExit(main())
    except (common.PageToolError, OSError, ValueError, ImportError) as exc:
        print(f"RESULT=STOP\nREASON={exc}", file=sys.stderr)
        raise SystemExit(2)
