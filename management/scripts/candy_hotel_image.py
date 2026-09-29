#!/usr/bin/env python3
"""Plan, render, and validate CANDY hotel image candidates."""

from __future__ import annotations

import argparse
import hashlib
import io
import json
import os
import re
import tempfile
from dataclasses import dataclass
from pathlib import Path

try:
    from PIL import Image, ImageDraw, ImageFont
except ImportError as exc:  # pragma: no cover - environment failure
    raise SystemExit(
        "Pillow is required. Run this tool through codex\\scripts\\candy-hotel.cmd."
    ) from exc

import candy_hotel_page as hotel_page
import candy_page_common as path_config


CANVAS_SIZE = (1000, 750)
DEFAULT_CROP = "140,100,1000,750"
FONT_PATH = Path(r"C:\Windows\Fonts\arialbd.ttf")
SUBTITLE = "Kagoshima Hotel Information"
JPEG_QUALITY = 92
TITLE_CENTER = (500, 354)
SUBTITLE_CENTER = (500, 407)
CENTER_TOLERANCE = 5
PAIR_CENTER_TOLERANCE = 2


class HotelImageError(RuntimeError):
    pass


@dataclass(frozen=True)
class Identity:
    input_path: Path
    slug: str
    hotel_name_ja: str
    hotel_name_en: str
    address: str
    image1: str
    image2: str
    og_image: str
    source_route: str


def repo_root() -> Path:
    return path_config.REPO_ROOT.resolve()


def repo_path(value: str) -> Path:
    path = Path(value)
    if not path.is_absolute():
        path = repo_root() / path
    return path.resolve()


def relative_repo(path: Path) -> str:
    try:
        return path.resolve().relative_to(repo_root()).as_posix()
    except ValueError:
        return str(path.resolve())


def _is_relative_to(path: Path, parent: Path) -> bool:
    try:
        path.relative_to(parent)
        return True
    except ValueError:
        return False


def validate_english_name(value: str) -> str:
    if not value or value != value.strip():
        raise HotelImageError("HOTEL_NAME_EN must be non-empty with no outer spaces")
    if any(ord(character) < 32 or ord(character) > 126 for character in value):
        raise HotelImageError("HOTEL_NAME_EN must use printable ASCII characters only")
    return value


def clean_address(value: str) -> str:
    value = value.replace("<改行>", " ").replace("\r", " ").replace("\n", " ")
    return re.sub(r"\s+", " ", value).strip()


def load_identity(args: argparse.Namespace) -> Identity:
    input_path = repo_path(args.input)
    input_root = (repo_root() / "Text_hotel_data").resolve()
    if not _is_relative_to(input_path, input_root) or input_path.suffix.lower() != ".txt":
        raise HotelImageError("Target input must be a .txt file under Text_hotel_data")
    try:
        data = hotel_page.parse_hotel_text(input_path)
    except hotel_page.HotelToolError as exc:
        raise HotelImageError(str(exc)) from exc
    hotel_name_en = validate_english_name(args.hotel_name_en)
    return Identity(
        input_path=input_path,
        slug=data.slug,
        hotel_name_ja=data.hotel_name,
        hotel_name_en=hotel_name_en,
        address=clean_address(data.basic.address),
        image1=data.image1,
        image2=data.image2,
        og_image=data.og_image,
        source_route=args.source_route,
    )


def candidate_dir(identity: Identity, value: str | None) -> Path:
    if value:
        output = Path(value)
        if not output.is_absolute():
            output = Path.cwd() / output
    else:
        output = Path(tempfile.gettempdir()) / "candy-hotel-images" / identity.slug
    output = output.resolve()
    if _is_relative_to(output, repo_root()):
        raise HotelImageError("Candidate output directory must be outside the repository")
    return output


def candidate_paths(identity: Identity, output: Path) -> tuple[Path, Path, Path]:
    return (
        output / f"{identity.slug}_1.jpg",
        output / f"{identity.slug}_2.jpg",
        output / f"{identity.slug}_image_manifest.json",
    )


def parse_crop(value: str, source_size: tuple[int, int]) -> tuple[int, int, int, int]:
    if value.lower() == "auto":
        width, height = source_size
        if width < CANVAS_SIZE[0] or height < CANVAS_SIZE[1]:
            raise HotelImageError(
                f"Source is smaller than {CANVAS_SIZE[0]}x{CANVAS_SIZE[1]}: {source_size}"
            )
        return (
            (width - CANVAS_SIZE[0]) // 2,
            (height - CANVAS_SIZE[1]) // 2,
            CANVAS_SIZE[0],
            CANVAS_SIZE[1],
        )
    try:
        values = tuple(int(part.strip()) for part in value.split(","))
    except ValueError as exc:
        raise HotelImageError(f"Invalid crop: {value}") from exc
    if len(values) != 4:
        raise HotelImageError(f"Crop must be x,y,width,height: {value}")
    x, y, width, height = values
    if (width, height) != CANVAS_SIZE:
        raise HotelImageError(
            f"Crop must produce {CANVAS_SIZE[0]}x{CANVAS_SIZE[1]} without stretching"
        )
    source_width, source_height = source_size
    if x < 0 or y < 0 or x + width > source_width or y + height > source_height:
        raise HotelImageError(f"Crop exceeds source bounds: {value} source={source_size}")
    return values


def _font(size: int) -> ImageFont.FreeTypeFont:
    if not FONT_PATH.is_file():
        raise HotelImageError(f"Arial Bold font is missing: {FONT_PATH}")
    return ImageFont.truetype(str(FONT_PATH), size)


def choose_title_font(
    text: str,
    *,
    start_size: int,
    maximum_width: int,
) -> tuple[ImageFont.FreeTypeFont, int, int]:
    probe = ImageDraw.Draw(Image.new("RGB", (1, 1)))
    for size in range(start_size, 27, -2):
        font = _font(size)
        bbox = probe.textbbox((0, 0), text, font=font)
        width = bbox[2] - bbox[0]
        if width <= maximum_width:
            return font, size, width
    raise HotelImageError("HOTEL_NAME_EN does not fit at the minimum 28 px size")


def draw_centered(
    draw: ImageDraw.ImageDraw,
    text: str,
    font: ImageFont.FreeTypeFont,
    center: tuple[int, int],
) -> tuple[list[int], list[float]]:
    bbox = draw.textbbox((0, 0), text, font=font)
    x = round(center[0] - (bbox[0] + bbox[2]) / 2)
    y = round(center[1] - (bbox[1] + bbox[3]) / 2)
    draw.text((x, y), text, font=font, fill=(255, 255, 255))
    placed = [x + bbox[0], y + bbox[1], x + bbox[2], y + bbox[3]]
    measured = [
        (placed[0] + placed[2]) / 2,
        (placed[1] + placed[3]) / 2,
    ]
    return placed, measured


def render_image(
    source_path: Path,
    crop_value: str,
    title: str,
    *,
    image_number: int,
) -> tuple[bytes, dict[str, object]]:
    if not source_path.is_file():
        raise HotelImageError(f"Source image is missing: {source_path}")
    try:
        with Image.open(source_path) as source:
            source_format = source.format or "UNKNOWN"
            source_size = source.size
            crop = parse_crop(crop_value, source_size)
            x, y, width, height = crop
            canvas = source.convert("RGB").crop((x, y, x + width, y + height))
    except (OSError, ValueError) as exc:
        raise HotelImageError(f"Source image cannot be read: {source_path}") from exc

    if image_number == 1:
        start_size, subtitle_size, maximum_width = 48, 32, 840
    elif image_number == 2:
        start_size, subtitle_size, maximum_width = 42, 28, 760
    else:  # pragma: no cover - internal contract
        raise HotelImageError(f"Invalid image number: {image_number}")

    draw = ImageDraw.Draw(canvas)
    title_font, title_size, title_width = choose_title_font(
        title,
        start_size=start_size,
        maximum_width=maximum_width,
    )
    subtitle_font = _font(subtitle_size)
    title_bbox, title_center = draw_centered(draw, title, title_font, TITLE_CENTER)
    subtitle_bbox, subtitle_center = draw_centered(
        draw, SUBTITLE, subtitle_font, SUBTITLE_CENTER
    )

    output = io.BytesIO()
    canvas.save(
        output,
        format="JPEG",
        quality=JPEG_QUALITY,
        subsampling=2,
        optimize=False,
        progressive=False,
    )
    data = output.getvalue()
    record = {
        "image_number": image_number,
        "source_path": str(source_path.resolve()),
        "source_format": source_format,
        "source_size": list(source_size),
        "crop": list(crop),
        "output_size": list(CANVAS_SIZE),
        "output_format": "JPEG",
        "output_mode": "RGB",
        "jpeg_quality": JPEG_QUALITY,
        "title_size": title_size,
        "title_width": title_width,
        "title_bbox": title_bbox,
        "title_center": title_center,
        "subtitle_size": subtitle_size,
        "subtitle_bbox": subtitle_bbox,
        "subtitle_center": subtitle_center,
        "sha256": hashlib.sha256(data).hexdigest(),
        "bytes": len(data),
    }
    return data, record


def atomic_write(path: Path, data: bytes) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    temporary = path.with_name(path.name + ".tmp")
    temporary.write_bytes(data)
    os.replace(temporary, path)


def render_pair(
    identity: Identity,
    output: Path,
    earth_source: Path,
    maps_source: Path,
    earth_crop: str,
    maps_crop: str,
    *,
    overwrite: bool,
) -> dict[str, object]:
    output = output.resolve()
    if _is_relative_to(output, repo_root()):
        raise HotelImageError("Candidate output directory must be outside the repository")
    image1_path, image2_path, manifest_path = candidate_paths(identity, output)
    existing = [path for path in (image1_path, image2_path, manifest_path) if path.exists()]
    if existing and not overwrite:
        raise HotelImageError(
            "Candidate output already exists; pass --overwrite to replace only external candidates: "
            + ", ".join(str(path) for path in existing)
        )

    image1_bytes, image1_record = render_image(
        earth_source, earth_crop, identity.hotel_name_en, image_number=1
    )
    image2_bytes, image2_record = render_image(
        maps_source, maps_crop, identity.hotel_name_en, image_number=2
    )
    if image1_record["sha256"] == image2_record["sha256"]:
        raise HotelImageError("Rendered pair has identical SHA-256 values")

    image1_record["output_path"] = str(image1_path)
    image2_record["output_path"] = str(image2_path)
    manifest: dict[str, object] = {
        "schema": "candy-hotel-image-candidate-v1",
        "source_route": identity.source_route,
        "target_text": relative_repo(identity.input_path),
        "canonical_slug": identity.slug,
        "hotel_name_ja": identity.hotel_name_ja,
        "hotel_name_en": identity.hotel_name_en,
        "hotel_name_en_characters": len(identity.hotel_name_en),
        "hotel_name_en_sha256": hashlib.sha256(
            identity.hotel_name_en.encode("utf-8")
        ).hexdigest(),
        "address": identity.address,
        "search_query": f"{identity.hotel_name_ja} {identity.address}",
        "subtitle": SUBTITLE,
        "font_path": str(FONT_PATH),
        "font_name": "Arial Bold",
        "title_target_center": list(TITLE_CENTER),
        "subtitle_target_center": list(SUBTITLE_CENTER),
        "candidate_output_directory": str(output),
        "expected_accepted_paths": [
            f"Text_hotel_data/画像データ/{identity.slug}_1.jpg",
            f"Text_hotel_data/画像データ/{identity.slug}_2.jpg",
        ],
        "expected_public_paths": [
            f"HP/imgHtml/new_202601/hotel/{identity.slug}_1.jpg",
            f"HP/imgHtml/new_202601/hotel/{identity.slug}_2.jpg",
        ],
        "target_text_image_paths": [identity.image1, identity.image2],
        "target_text_og_image": identity.og_image,
        "images": [image1_record, image2_record],
        "visual_gates": "REQUIRED",
    }
    manifest_bytes = (
        json.dumps(manifest, ensure_ascii=False, indent=2) + "\n"
    ).encode("utf-8")

    output.mkdir(parents=True, exist_ok=True)
    atomic_write(image1_path, image1_bytes)
    atomic_write(image2_path, image2_bytes)
    atomic_write(manifest_path, manifest_bytes)
    return manifest


def _center_pass(measured: list[float], expected: tuple[int, int]) -> bool:
    return (
        abs(measured[0] - expected[0]) <= CENTER_TOLERANCE
        and abs(measured[1] - expected[1]) <= CENTER_TOLERANCE
    )


def validate_manifest(
    manifest: dict[str, object],
    manifest_path: Path,
    *,
    identity: Identity | None,
) -> list[str]:
    errors: list[str] = []
    if manifest.get("schema") != "candy-hotel-image-candidate-v1":
        errors.append("manifest schema mismatch")
    if manifest.get("subtitle") != SUBTITLE:
        errors.append("fixed subtitle mismatch")
    if manifest.get("font_path") != str(FONT_PATH) or manifest.get("font_name") != "Arial Bold":
        errors.append("font identity mismatch")
    if manifest.get("title_target_center") != list(TITLE_CENTER):
        errors.append("title target center mismatch")
    if manifest.get("subtitle_target_center") != list(SUBTITLE_CENTER):
        errors.append("subtitle target center mismatch")
    hotel_name_en = str(manifest.get("hotel_name_en", ""))
    if manifest.get("hotel_name_en_characters") != len(hotel_name_en):
        errors.append("HOTEL_NAME_EN character-count mismatch")
    if manifest.get("hotel_name_en_sha256") != hashlib.sha256(
        hotel_name_en.encode("utf-8")
    ).hexdigest():
        errors.append("HOTEL_NAME_EN SHA-256 mismatch")
    output = manifest_path.parent.resolve()
    if _is_relative_to(output, repo_root()):
        errors.append("candidate output is inside the repository")
    if identity:
        expected_values = {
            "source_route": identity.source_route,
            "target_text": relative_repo(identity.input_path),
            "canonical_slug": identity.slug,
            "hotel_name_ja": identity.hotel_name_ja,
            "hotel_name_en": identity.hotel_name_en,
            "address": identity.address,
        }
        for key, expected in expected_values.items():
            if manifest.get(key) != expected:
                errors.append(f"{key} mismatch")
        if manifest.get("target_text_image_paths") != [identity.image1, identity.image2]:
            errors.append("target Text image paths mismatch")
        if manifest.get("target_text_og_image") != identity.og_image:
            errors.append("target Text OGP image mismatch")

    images = manifest.get("images")
    if not isinstance(images, list) or len(images) != 2:
        return errors + ["manifest must contain exactly two images"]
    hashes: list[str] = []
    centers: dict[str, list[list[float]]] = {"title": [], "subtitle": []}
    for index, record in enumerate(images, 1):
        if not isinstance(record, dict):
            errors.append(f"image {index} record is invalid")
            continue
        output_path = Path(str(record.get("output_path", ""))).resolve()
        if output_path.parent != output:
            errors.append(f"image {index} output directory mismatch")
        expected_slug = str(manifest.get("canonical_slug", ""))
        if output_path.name != f"{expected_slug}_{index}.jpg":
            errors.append(f"image {index} filename mismatch")
        if not output_path.is_file():
            errors.append(f"image {index} is missing")
            continue
        payload = output_path.read_bytes()
        digest = hashlib.sha256(payload).hexdigest()
        hashes.append(digest)
        if digest != record.get("sha256"):
            errors.append(f"image {index} SHA-256 mismatch")
        if record.get("bytes") != len(payload):
            errors.append(f"image {index} byte-count mismatch")
        try:
            with Image.open(output_path) as image:
                if image.format != "JPEG":
                    errors.append(f"image {index} format is not JPEG")
                if image.size != CANVAS_SIZE:
                    errors.append(f"image {index} size mismatch: {image.size}")
                if image.mode != "RGB":
                    errors.append(f"image {index} mode is not RGB")
        except OSError:
            errors.append(f"image {index} is unreadable")
        if record.get("jpeg_quality") != JPEG_QUALITY:
            errors.append(f"image {index} quality record mismatch")
        if record.get("output_size") != list(CANVAS_SIZE):
            errors.append(f"image {index} output-size record mismatch")
        if record.get("output_format") != "JPEG" or record.get("output_mode") != "RGB":
            errors.append(f"image {index} output record mismatch")
        title_center = record.get("title_center")
        subtitle_center = record.get("subtitle_center")
        if not isinstance(title_center, list) or len(title_center) != 2:
            errors.append(f"image {index} title center is invalid")
        else:
            centers["title"].append(title_center)
            if not _center_pass(title_center, TITLE_CENTER):
                errors.append(f"image {index} title center is outside tolerance")
        if not isinstance(subtitle_center, list) or len(subtitle_center) != 2:
            errors.append(f"image {index} subtitle center is invalid")
        else:
            centers["subtitle"].append(subtitle_center)
            if not _center_pass(subtitle_center, SUBTITLE_CENTER):
                errors.append(f"image {index} subtitle center is outside tolerance")
        maximum_width = 840 if index == 1 else 760
        if not isinstance(record.get("title_width"), int) or record["title_width"] > maximum_width:
            errors.append(f"image {index} title width exceeds the maximum")
        if not isinstance(record.get("title_size"), int) or not 28 <= record["title_size"] <= (48 if index == 1 else 42):
            errors.append(f"image {index} title size is invalid")

    if len(hashes) == 2 and hashes[0] == hashes[1]:
        errors.append("pair SHA-256 values are identical")
    for label, values in centers.items():
        if len(values) == 2 and (
            abs(values[0][0] - values[1][0]) > PAIR_CENTER_TOLERANCE
            or abs(values[0][1] - values[1][1]) > PAIR_CENTER_TOLERANCE
        ):
            errors.append(f"pair {label} centers differ by more than 2 px")
    return errors


def print_plan(identity: Identity, output: Path, earth_crop: str, maps_crop: str) -> None:
    image1_path, image2_path, manifest_path = candidate_paths(identity, output)
    print(f"IMAGE_PLAN_OK={identity.slug}")
    print(f"SOURCE_ROUTE={identity.source_route}")
    print(f"TARGET_TEXT={relative_repo(identity.input_path)}")
    print(f"HOTEL_NAME_JA={identity.hotel_name_ja}")
    print(f"HOTEL_NAME_EN={identity.hotel_name_en}")
    print(f"ADDRESS={identity.address}")
    print(f"SEARCH_QUERY={identity.hotel_name_ja} {identity.address}")
    print(f"CANONICAL_SLUG={identity.slug}")
    print(f"EARTH_CROP={earth_crop}")
    print(f"MAPS_CROP={maps_crop}")
    print(f"CANDIDATE_IMAGE_1={image1_path}")
    print(f"CANDIDATE_IMAGE_2={image2_path}")
    print(f"CANDIDATE_MANIFEST={manifest_path}")
    print(
        "EXPECTED_ACCEPTED_PAIR="
        f"Text_hotel_data/画像データ/{identity.slug}_1.jpg,"
        f"Text_hotel_data/画像データ/{identity.slug}_2.jpg"
    )
    print(
        "EXPECTED_PUBLIC_PAIR="
        f"HP/imgHtml/new_202601/hotel/{identity.slug}_1.jpg,"
        f"HP/imgHtml/new_202601/hotel/{identity.slug}_2.jpg"
    )


def command_plan(args: argparse.Namespace) -> int:
    identity = load_identity(args)
    output = candidate_dir(identity, args.output_dir)
    print_plan(identity, output, args.earth_crop, args.maps_crop)
    return 0


def command_render(args: argparse.Namespace) -> int:
    identity = load_identity(args)
    output = candidate_dir(identity, args.output_dir)
    earth_source = Path(args.earth_source).resolve()
    maps_source = Path(args.maps_source).resolve()
    manifest = render_pair(
        identity,
        output,
        earth_source,
        maps_source,
        args.earth_crop,
        args.maps_crop,
        overwrite=args.overwrite,
    )
    image1, image2, manifest_path = candidate_paths(identity, output)
    errors = validate_manifest(manifest, manifest_path, identity=identity)
    if errors:
        raise HotelImageError("; ".join(errors))
    print(f"IMAGE_RENDER_STATUS=CANDIDATES_CREATED")
    print(f"CANONICAL_SLUG={identity.slug}")
    print(f"CANDIDATE_IMAGE_1={image1}")
    print(f"CANDIDATE_IMAGE_2={image2}")
    print(f"CANDIDATE_MANIFEST={manifest_path}")
    print(f"IMAGE_1_SHA256={manifest['images'][0]['sha256']}")
    print(f"IMAGE_2_SHA256={manifest['images'][1]['sha256']}")
    print("PAIR_HASH_DIFFERENCE=PASS")
    print("DETERMINISTIC_FILE_GATES=PASS")
    print("VISUAL_GATES=REQUIRED")
    return 0


def command_check(args: argparse.Namespace) -> int:
    identity = load_identity(args)
    manifest_path = Path(args.manifest).resolve()
    if not manifest_path.is_file():
        raise HotelImageError(f"Candidate manifest is missing: {manifest_path}")
    try:
        manifest = json.loads(manifest_path.read_text(encoding="utf-8"))
    except (UnicodeDecodeError, json.JSONDecodeError) as exc:
        raise HotelImageError(f"Candidate manifest is invalid: {manifest_path}") from exc
    errors = validate_manifest(manifest, manifest_path, identity=identity)
    if errors:
        print("IMAGE_CHECK=FAIL")
        for error in errors:
            print(f"ERROR={error}")
        return 1
    print("IMAGE_CHECK=PASS")
    print(f"CANONICAL_SLUG={identity.slug}")
    print("PAIR_HASH_DIFFERENCE=PASS")
    print("DETERMINISTIC_FILE_GATES=PASS")
    print("VISUAL_GATES=REQUIRED")
    return 0


def command_self_test(_args: argparse.Namespace) -> int:
    with tempfile.TemporaryDirectory(prefix="candy-hotel-image-self-test-") as directory:
        root = Path(directory)
        earth = root / "earth.png"
        maps = root / "maps.png"
        earth_image = Image.new("RGB", (1280, 960), (25, 45, 75))
        earth_draw = ImageDraw.Draw(earth_image)
        for x in range(0, 1280, 80):
            earth_draw.rectangle((x, 100, x + 50, 900), fill=(60 + x % 120, 75, 90))
        earth_image.save(earth)
        maps_image = Image.new("RGB", (1280, 960), (70, 85, 65))
        maps_draw = ImageDraw.Draw(maps_image)
        for y in range(0, 960, 60):
            maps_draw.line((0, y, 1280, y + 240), fill=(160, 160, 150), width=16)
        maps_image.save(maps)
        identity = Identity(
            input_path=repo_root() / "Text_hotel_data" / "self-test.txt",
            slug="selftesthotel",
            hotel_name_ja="セルフテストホテル",
            hotel_name_en="SELF TEST HOTEL WITH LONG NAME",
            address="〒000-0000 Test Address",
            image1="./imgHtml/new_202601/hotel/selftesthotel_1.jpg",
            image2="./imgHtml/new_202601/hotel/selftesthotel_2.jpg",
            og_image="https://www.55810.com/imgHtml/new_202601/hotel/selftesthotel_1.jpg",
            source_route="DIRECT_TEXT",
        )
        output = root / "candidate"
        manifest = render_pair(
            identity,
            output,
            earth,
            maps,
            DEFAULT_CROP,
            DEFAULT_CROP,
            overwrite=False,
        )
        manifest_path = candidate_paths(identity, output)[2]
        errors = validate_manifest(manifest, manifest_path, identity=identity)
        if errors:
            raise HotelImageError("self-test validation failed: " + "; ".join(errors))
        try:
            render_pair(
                identity,
                output,
                earth,
                maps,
                DEFAULT_CROP,
                DEFAULT_CROP,
                overwrite=False,
            )
        except HotelImageError as exc:
            if "already exists" not in str(exc):
                raise
        else:
            raise HotelImageError("self-test overwrite gate failed")
        try:
            render_pair(
                identity,
                repo_root() / "candidate",
                earth,
                maps,
                DEFAULT_CROP,
                DEFAULT_CROP,
                overwrite=False,
            )
        except HotelImageError as exc:
            if "outside the repository" not in str(exc):
                raise
        else:
            raise HotelImageError("self-test repository-output gate failed")
    print("HOTEL_IMAGE_SELF_TEST=PASS")
    return 0


def add_identity_arguments(parser: argparse.ArgumentParser) -> None:
    parser.add_argument("--input", required=True)
    parser.add_argument("--hotel-name-en", required=True)
    parser.add_argument(
        "--source-route",
        choices=("DIRECT_TEXT", "PHASE_PREPARED"),
        required=True,
    )


def create_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(
        description="Plan, render, and validate CANDY hotel image candidates"
    )
    subparsers = parser.add_subparsers(dest="command", required=True)

    plan = subparsers.add_parser("image-plan")
    add_identity_arguments(plan)
    plan.add_argument("--output-dir")
    plan.add_argument("--earth-crop", default=DEFAULT_CROP)
    plan.add_argument("--maps-crop", default=DEFAULT_CROP)
    plan.set_defaults(func=command_plan)

    render = subparsers.add_parser("image-render")
    add_identity_arguments(render)
    render.add_argument("--earth-source", required=True)
    render.add_argument("--maps-source", required=True)
    render.add_argument("--output-dir")
    render.add_argument("--earth-crop", default=DEFAULT_CROP)
    render.add_argument("--maps-crop", default=DEFAULT_CROP)
    render.add_argument("--overwrite", action="store_true")
    render.set_defaults(func=command_render)

    check = subparsers.add_parser("image-check")
    add_identity_arguments(check)
    check.add_argument("--manifest", required=True)
    check.set_defaults(func=command_check)

    self_test = subparsers.add_parser("image-self-test")
    self_test.set_defaults(func=command_self_test)
    return parser


def main() -> int:
    parser = create_parser()
    args = parser.parse_args()
    try:
        return int(args.func(args))
    except HotelImageError as exc:
        print(f"HOTEL_IMAGE_STOP={exc}")
        return 1


if __name__ == "__main__":
    raise SystemExit(main())
