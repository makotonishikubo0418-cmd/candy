#!/usr/bin/env python3
"""Compatibility entrypoint for the former CANDY document generator.

The generation source of truth is candy_site_state.py. New work must use
candy-site-state.cmd directly.
"""

from __future__ import annotations

import argparse

from candy_site_state import collect, preview, render_all, write


def main() -> int:
    parser = argparse.ArgumentParser(description="Compatibility wrapper for candy-site-state")
    parser.add_argument("--preview", action="store_true")
    args = parser.parse_args()
    print("COMPATIBILITY_WRAPPER=candy_site_state.py")
    rendered = render_all(collect())
    return preview(rendered) if args.preview else write(rendered)


if __name__ == "__main__":
    raise SystemExit(main())
