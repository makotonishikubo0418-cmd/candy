from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
ENTRY = (ROOT / "HP" / "movie_iframe.php").read_text(encoding="utf-8")
DATASET = (ROOT / "HP" / "includefile" / "dataset_movie_iframe.php").read_text(
    encoding="utf-8"
)
SOURCE = (ROOT / "HP" / "source" / "movie_iframe.html").read_text(
    encoding="utf-8"
)
SITEMAP = (ROOT / "HP" / "sitemap.xml").read_text(encoding="utf-8")
CALLER = (ROOT / "HP" / "includefile" / "dataset_movie.php").read_text(
    encoding="utf-8"
)


def require(condition: bool, message: str) -> None:
    if not condition:
        raise AssertionError(message)


checks = [
    (
        "header('X-Robots-Tag: noindex, nofollow');" in ENTRY,
        "movie_iframe.php must emit the noindex,nofollow response header",
    ),
    (
        "$midsProvided === $midgProvided" in ENTRY,
        "movie_iframe.php must reject missing and double movie selectors",
    ),
    (
        "!ctype_digit($movieId)" in ENTRY and "(int)$movieId <= 0" in ENTRY,
        "movie_iframe.php must accept only positive numeric movie IDs",
    ),
    (
        "http_response_code(404);" in ENTRY
        and "__DIR__ . '/404.html'" in ENTRY
        and "readfile($notFoundFile);" in ENTRY,
        "invalid requests must return the existing 404 body with HTTP 404",
    ),
    (
        "$hasPlayableMovie = !empty($filedata[1]) || !empty($filedata[2]) || !empty($filedata[3]);"
        in DATASET
        and "if (!$hasPlayableMovie)" in DATASET
        and "candyMovieIframeNotFound();" in DATASET,
        "a published mp4, ogv, or webm record must be required before rendering",
    ),
    (
        '<meta name="robots" content="noindex,nofollow">' in SOURCE,
        "the playback HTML source must retain noindex,nofollow",
    ),
    (
        "movie_iframe.php" not in SITEMAP,
        "movie_iframe.php must remain absent from sitemap.xml",
    ),
    (
        "movie_iframe.php?mids=" in CALLER and "movie_iframe.php?midg=" in CALLER,
        "movie.php must retain both supported playback routes",
    ),
]

for condition, message in checks:
    require(condition, message)

print(f"MOVIE_IFRAME_BEHAVIOR_OK assertions={len(checks)}")
