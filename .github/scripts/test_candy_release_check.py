from __future__ import annotations

import importlib.util
import sys
from pathlib import Path


SCRIPT = Path(__file__).with_name("candy_release_check.py").resolve()
REPOSITORY_ROOT = SCRIPT.parents[2]
PRODUCTION_WORKFLOW = REPOSITORY_ROOT / ".github" / "workflows" / "candy-production-deploy.yml"
HTACCESS_WORKFLOW = REPOSITORY_ROOT / ".github" / "workflows" / "candy-htaccess-deploy.yml"
PUBLISHERS = (
    REPOSITORY_ROOT / "codex" / "scripts" / "candy_area_publish.py",
    REPOSITORY_ROOT / "codex" / "scripts" / "candy_hotel_publish.py",
    REPOSITORY_ROOT / "codex" / "scripts" / "candy_category_publish.py",
)


def load_module():
    spec = importlib.util.spec_from_file_location("candy_release_check_tested", SCRIPT)
    assert spec and spec.loader
    module = importlib.util.module_from_spec(spec)
    sys.modules[spec.name] = module
    spec.loader.exec_module(module)
    return module


def response_map(module) -> dict[str, tuple[int, str, dict[str, str], bytes]]:
    body = (
        '<html><head><title>鹿児島 デリヘル キャンディ</title>'
        '<link rel="canonical" href="https://www.55810.com"></head>'
        '<body><h1>鹿児島 デリヘル キャンディ</h1></body></html>'
    ).encode("utf-8")
    return {
        module.PUBLIC_ROOT: (200, module.PUBLIC_ROOT, {}, body),
        module.PUBLIC_INDEX: (301, module.PUBLIC_INDEX, {"Location": module.PUBLIC_ROOT}, b""),
        "http://www.55810.com/": (
            301,
            "http://www.55810.com/",
            {"Location": module.PUBLIC_ROOT},
            b"",
        ),
        "https://55810.com/": (
            301,
            "https://55810.com/",
            {"Location": module.PUBLIC_ROOT},
            b"",
        ),
        "http://55810.com/": (
            301,
            "http://55810.com/",
            {"Location": module.PUBLIC_ROOT},
            b"",
        ),
        module.DIRECT_ROOT: (
            200,
            module.DIRECT_ROOT,
            {"X-Robots-Tag": "noindex"},
            body,
        ),
    }


def expect_failure(module, responses, expected: str) -> None:
    try:
        module.verify_entry_contract(lambda url: responses[url])
    except RuntimeError as exc:
        assert expected in str(exc), str(exc)
    else:
        raise AssertionError(f"invalid entry contract was accepted: {expected}")


def main() -> int:
    module = load_module()
    responses = response_map(module)
    requested: list[str] = []

    def fetch(url: str):
        requested.append(url)
        return responses[url]

    module.verify_entry_contract(fetch)
    assert requested == [
        module.PUBLIC_ROOT,
        module.PUBLIC_INDEX,
        "http://www.55810.com/",
        "https://55810.com/",
        "http://55810.com/",
        module.DIRECT_ROOT,
    ]

    external_redirect = response_map(module)
    external_redirect[module.PUBLIC_ROOT] = (
        301,
        module.PUBLIC_ROOT,
        {"Location": "https://www.cityheaven.net/example/"},
        b"",
    )
    expect_failure(module, external_redirect, "root_200")

    wrong_index = response_map(module)
    wrong_index[module.PUBLIC_INDEX] = (
        301,
        module.PUBLIC_INDEX,
        {"Location": "https://www.cityheaven.net/example/"},
        b"",
    )
    expect_failure(module, wrong_index, "index_redirect")

    public_noindex = response_map(module)
    public_status, public_final, _headers, public_body = public_noindex[module.PUBLIC_ROOT]
    public_noindex[module.PUBLIC_ROOT] = (
        public_status,
        public_final,
        {"X-Robots-Tag": "noindex"},
        public_body,
    )
    expect_failure(module, public_noindex, "public_indexable")

    direct_without_noindex = response_map(module)
    direct_without_noindex[module.DIRECT_ROOT] = (
        200,
        module.DIRECT_ROOT,
        {},
        b"",
    )
    expect_failure(module, direct_without_noindex, "direct_host_noindex")

    for workflow in (PRODUCTION_WORKFLOW, HTACCESS_WORKFLOW):
        workflow_text = workflow.read_text(encoding="utf-8")
        assert "python3 .github/scripts/test_candy_release_check.py" in workflow_text
        assert "python3 .github/scripts/candy_release_check.py --entry-only" in workflow_text

    publisher_texts = [publisher.read_text(encoding="utf-8") for publisher in PUBLISHERS]
    assert "verify_entry_contract()" in publisher_texts[0]
    assert "shared.verify_entry_contract()" in publisher_texts[1]
    assert "release.verify_entry_contract()" in publisher_texts[2]
    assert all("EXPECTED_REDIRECT" not in text for text in publisher_texts)

    print("RELEASE_ENTRY_CONTRACT_TESTS: passed")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
