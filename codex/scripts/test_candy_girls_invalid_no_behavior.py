from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
ENTRY = (ROOT / "HP" / "girls.php").read_text(encoding="utf-8")
DATASET = (ROOT / "HP" / "includefile" / "dataset_girls.php").read_text(
    encoding="utf-8"
)
NOT_FOUND = (ROOT / "HP" / "404.html").read_text(encoding="utf-8")


checks = {
    "missing no redirects to girls list with 301": (
        "array_key_exists('no', $_GET)" in ENTRY
        and "header('Location: girls_list.php', true, 301);" in ENTRY
    ),
    "malformed no returns 404": (
        "if (!is_scalar($_GET['no']))" in ENTRY
        and "http_response_code(404);" in ENTRY
    ),
    "unknown no returns 404": (
        'if($gid == "")' in DATASET
        and "http_response_code(404);" in DATASET
        and "readfile($notFoundPage);" in DATASET
    ),
    "first active woman fallback is absent": (
        '$gid = $girldata["id"][0];' not in DATASET
    ),
    "404 response is excluded from indexing": (
        '<meta name="robots" content="noindex,follow">' in NOT_FOUND
    ),
}

failures = [name for name, passed in checks.items() if not passed]
if failures:
    print("CANDY_GIRLS_INVALID_NO_TEST=FAIL")
    for failure in failures:
        print(f"- {failure}")
    raise SystemExit(1)

print(f"CANDY_GIRLS_INVALID_NO_TEST=PASS assertions={len(checks)}")
