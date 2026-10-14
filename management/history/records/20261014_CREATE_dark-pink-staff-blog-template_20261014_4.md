# girl-choice仕様へのテンプレート修正

- History: [20261014_CREATE_dark-pink-staff-blog-template.md](../20261014_CREATE_dark-pink-staff-blog-template.md)
- Record Date: 2026-10-14
- Sequence: 4
- Status: Verification Pending

## 記録

### 決定

ユーザーは `kagoshima-deliveryhealth-blog-girl-choice.html` と同じ仕様で色だけ濃いピンクに変更し、本文は編集位置を示す仮文章にするよう指示した。独自仕様の追加は禁止。今回の範囲はローカル修正であり、Git操作・本番公開・トップやブログ一覧への追加は行わない。

### 対応

- 対象source HTMLをgirl-choiceのHTML構造・class・寸法・レスポンシブ指定に合わせた。目次と11セクション、画像3枠、FAQ4項目、カード、CTA、関連記事・対応エリアは同じ構造とし、本文・画像説明・編集用リンクは仮内容に置き換えた。
- 専用CSSは `blog-girl-choice.css` の色値だけを変更。独自class、独自レイアウト、独自スマートフォン上書きは残していない。
- CSS更新キーを `?v=f40f8500` に設定。共通CSS2枚の参照と読込順序は参照ページと同一。
- HTMLの `<base>` と専用datasetのbase削除処理を取り除いた。専用datasetはgirl-choiceのファイルと同一内容。
- 既存の未完成雛形用noindex/nofollowと仮画像は維持。公開PHP、共通CSS、dataset_base、参照ページ、トップ、ブログ一覧、サイトマップは変更していない。

### 結果

- 色値を除いた専用CSS全体の一致を確認。
- 本文HTMLの要素構造、class、ID、画像寸法が参照ページと一致。編集対象の文言・リンク先・仮画像は比較対象から区別した。
- 目次11件と対応見出しが一致し、H1は1件、ID重複なし。画像3件は1000×750、nolazy、既存仮画像を参照。
- Article・BreadcrumbList・FAQPageはJSON解析に成功し、見出し・FAQの仮文章が表示内容と一致。
- 公開PHPおよび専用datasetのPHP構文チェックに合格。
- 参照HTML、参照CSS、公開PHP、共通登録、一覧等は未変更。GitHub・本番には今回の修正を反映していない。

## 現在

- Remaining Work: PC/SPの実ブラウザ表示は未確認。今回のローカル修正は構造・CSS・PHPの静的検証まで実施済み。本番は前回公開した旧テンプレートのまま。
- Next Action: 実表示の確認。GitHub同期または本番更新は、その具体的な操作が指示された場合だけ行う。
