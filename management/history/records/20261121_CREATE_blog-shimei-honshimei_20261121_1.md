# 指名と本指名ブログのローカル作成と検証

- History: [`20261121_CREATE_blog-shimei-honshimei.md`](../20261121_CREATE_blog-shimei-honshimei.md)
- Record Date: 2026-11-21
- Sequence: 1
- Status: Verification Pending

## 記録

### 確認済み事実

- slug `shimei-honshimei` の同一公開ファイルは作業開始時点で存在しなかった。画像3点はローカルに存在し、各 1000×750 だった。
- ポイント色 `#8D3F55` の既存テーマはなく、共通 `HP/css/blog.css` へ `blog-theme-wine-red` を追加した。参照HTMLの `blog.css?v=` を SHA-256 先頭8桁 `6ff2ad50` に揃えた。記事別CSSは作成していない。
- 公開PHP、source HTML、dataset、dataset_base、blog一覧末尾登録、TOP最新15件、sitemap、生成管理書を同一単位で更新した。
- ブログカテゴリは一般。固定CTAは「女の子一覧」「本日の出勤情報」。FAQPage / 女の子紹介 ItemList は原稿にないため未出力。
- 機械検証（HTML/JSON-LD/shared/TOP整合）と `candy-site-state check --target shimei-honshimei` は合格した。
- ローカル実表示では、記事のPC/SPで画像 naturalWidth/Height > 0、アクセント `#8D3F55`、CTA2ボタン、指定リンク、横スクロールなしを確認した。TOPブログ欄でも本件が末尾15件に含まれ、PC/SPで横スクロールなしを確認した。

### 決定

- 未提供の著者・日付は Article JSON-LD に含めない。
- 本文内のブログ詳細リンクは関連記事検証対象外とするため `class="fade"` を付けない。

### 対応

- ローカル作成・検証まで完了。次に許可済みの git add / commit / push と本番公開確認を行う。

### 結果

- ローカル一式は作成済み。GitHub反映と本番公開は未実施。

## 現在

- Remaining Work: git add / commit / push、本番デプロイ確認、本番URLのHTTP/表示確認、完了進捗記録
- Next Action: 対象差分のみをステージして commit / push し、Actions と本番URLを確認する
