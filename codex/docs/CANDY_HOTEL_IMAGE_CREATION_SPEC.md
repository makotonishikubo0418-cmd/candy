# CANDY Hotel Image Creation Specification

- Updated: 2026-07-16
- Target: Images for hotel pages
- Status: canonical document

## 1. Requirements

A hotel page requires two images.

```text
HP/imgHtml/new_202601/hotel/<slug>_1.jpg
HP/imgHtml/new_202601/hotel/<slug>_2.jpg
```

`img_1` and `img_2` in the input text file use:

```text
./imgHtml/new_202601/hotel/<slug>_1.jpg
./imgHtml/new_202601/hotel/<slug>_2.jpg
```

The OGP image MUST match `img_1`.

## 2. Image-Source Handling

Do not produce an image when storage, modification, commercial-publication, or required-attribution conditions for its source cannot be verified.

Do not save, modify, or publish Google Maps, hotel official-site, reservation-site, social-media, or third-party-posted images unless their applicable usage conditions are verified.

## 3. Post-Creation Validation

- The filename matches the canonical slug.
- `_1` and `_2` are different images.
- Both actual files exist.
- Files match `image`, `img_1`, and `img_2` in the input text file.
- Do not run `publish` while images are missing.

## 4. Currently Verified Images

As of 2026-07-16, hotel images were verified for only these three pages:

| Slug | Images |
|---|---|
| greenrichkagoshimatenmonkan | `_1.jpg`, `_2.jpg` |
| hotelm | `_1.jpg`, `_2.jpg` |
| villacosta500 | `_1.jpg`, `_2.jpg` |

Do not select another hotel input for new production while its images are absent.
