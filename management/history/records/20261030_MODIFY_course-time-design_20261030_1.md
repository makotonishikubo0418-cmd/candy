# コース時間記事のデザイン調整とローカル検証

- History: [20261030_MODIFY_course-time-design.md](../20261030_MODIFY_course-time-design.md)
- Record Date: 2026-10-30
- Sequence: 1
- Status: In Progress

## 記録

### 対応

- 共通 `HP/css/blog.css` に `blog-theme-antique-gold` を追加。既存レイアウトを維持して、見出し、目次、装飾線、画像説明、CTA、HOMEボタン、FAQ展開色をゴールド系にした。サイト共通ヘッダー・フッターのブランド色は変更していない。
- 対象表だけに `blog-table-spacious` を適用。PCのセル余白18px 24px・行高1.8、スマートフォン14px 12px・行高1.85とし、列幅と交互の背景色を設定した。
- 冒頭の「導入｜」だけを削除した。
- 正規CSS管理規則に従い、正式テンプレートを含む全12参照HTMLの更新キーを `b2f448fc` に同期。対象記事以外の11HTMLは更新キー以外に変更がないことを機械比較した。
- サイトマップの更新日9件を同期し、生成管理情報6ファイルを更新した。

### 結果

- 対象HTMLはCSS更新キー、テーマクラス、表クラス、「導入｜」削除の4点を逆変換すると開始時コミットと全文一致。表の全文、その他本文、画像、リンク、H1・title・og:title・JSON-LDを保持した。
- 既存CSSの宣言削除・変更はなく、追加クラスを使用する対象記事だけに新配色・表設定が適用される。
- CSS SHA-256先頭8文字と全参照更新キーが一致。記事checkは `CHECK_OK BODY=EDITED_NOT_REVIEWED`、生成管理書10文書は `CHECK=OK`、対象記事はstructure=COMPLETE、seo=OK、images=OK、issues=NONE。
- ローカルプレビューのCSS viewport 1366×900と390×844で表・CTAを目視確認。表幅はスマートフォン339.2px、横はみ出しなし。表の折り返しとセル余白、見出し装飾、FAQ展開色を確認した。
- FAQ展開時の文字色はrgb(118,87,46)、基本アクセントは #A27B43。導入表記の削除も表示で確認した。
- PHP・画像ファイル・既存記事本文・デプロイスクリプトは変更していない。

## 現在

- Remaining Work: GitHub mainへのコミット・Pushと同期確認、完了履歴保存。
- Next Action: `[skip ci]` を付けてGitHubに保存し、本番公開は起動しない。
