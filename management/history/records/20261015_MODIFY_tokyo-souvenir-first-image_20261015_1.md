# 修正版画像の取得と反映

- History: [20261015_MODIFY_tokyo-souvenir-first-image.md](../20261015_MODIFY_tokyo-souvenir-first-image.md)
- Record Date: 2026-10-15
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- 指定タブ先頭のinlineObjectId kix.ng0pi3agw4puからJPEG原本を取得。1000×750、392065 bytes。画像を目視確認し、既存の説明・寸法と整合。
- 新SHA256: 35c02055d4379c49b8ff9b1b19d16fcddfed5dc79bb88f0c5d28ff69f2cae433。旧SHA256: e991bb9a98bf29a87e2a0bd4b4b492d9b9db16801b973d2bb7f61dd836c09b00。

### 対応

- HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-tokyo-souvenir_1.jpgを同名置換。公開用の旧版別名ファイルは作成しない。
- 記事HTMLの本文・OGP・JSON-LD計3参照と入力Textの2参照へ?v=35c02055を設定。画像2・3と本文を維持。
- Git履歴の旧画像を復旧元とし、配信は通常のトランザクション方式を使用する。

## 現在

- Remaining Work: GitHub公開、Actions・本番SHA256・PC/SP実表示確認。
- Next Action: 対象差分のみを公開する。
