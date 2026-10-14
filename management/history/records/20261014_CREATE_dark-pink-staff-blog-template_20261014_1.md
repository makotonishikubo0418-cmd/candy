# 濃いピンク雛形のファイル作成と静的検証

- History: [20261014_CREATE_dark-pink-staff-blog-template.md](../20261014_CREATE_dark-pink-staff-blog-template.md)
- Record Date: 2026-10-14
- Sequence: 1
- Status: Verification Pending

## 記録

### 対応

- 既存ブログと同一の共通ヘッダー・フッターを使用し、本文・色・画像のみ編集用に設定。
- 入口PHPを既存の`dataset_base.php`読込へ変更し、専用datasetと共通PHPのcase・リンク変換を登録。
- 目次4項目とscene1〜4の本文見出しを一致させ、編集コメント11組を設置。
- 画像は1000×750のSVG仮枠。説明付き画像2件には`nolazy`を設定。
- 雛形はnoindex/nofollow。公開一覧・トップ・サイトマップへの追加、Git操作、DB操作、本番公開は未実施。

### 確認済み事実

- PHP構文3件（入口・専用dataset・共通dataset）は合格。
- HTMLタグ対応、重複IDなし、H1が1件、目次文言・リンク、ローカル資産存在、共通ヘッダー・フッター一致、SVG構造、case・リンク変換各1件、CSS括弧・色・SP分岐の静的チェックは合格。
- ブラウザのローカルURL表示許可が拒否されたため、画面表示確認は行っていない。別経路での表示試行はしていない。

## 現在

- Remaining Work: PC/SPの実表示・画像自然寸法・共通メニュー動作の確認。本番DB依存の通常PHP実行は未確認。
- Next Action: 表示確認が許可される環境で上記を確認する。公開やGit操作には別の明示指示が必要。
