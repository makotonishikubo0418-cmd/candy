# 店舗名表記統一の本番公開完了

- History: [20261031_OPERATION_blog-store-name-publication.md](../20261031_OPERATION_blog-store-name-publication.md)
- Record Date: 2026-10-31
- Sequence: 1
- Status: Completed

## 記録

### 対応

- ユーザーの明示指示に基づき、ローカル修正の差分を最終確認し、対象のコード・現行原稿・生成管理情報・履歴33ファイルをmainへ保存した。公開コミットは `789404bdce95df128aee75f1fcd5b04e25ef7516`。作業前のmainは `24b1f868586806919685286f5693cee934110a73`。
- 最終確認では、表示用店舗名の置換以外の本文、タグ構造、太字、改行、表、CTA、URL、画像パス、リンク先、CSS、識別子を保持していることを確認した。対象外コード・原稿867ファイルのハッシュは一致した。現行原稿6ファイルはGitHub保存対象であり、本番転送対象には含めていない。
- 既存の未追跡画像 `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-fun-conversation_1.jpg` は対象外として追加・公開せず保持した。

### 結果

- [GitHub Actions 41186433267](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41186433267) は成功。2026-10-31 17:55 JSTに、12記事とブログ一覧・TOPのHTML14ファイルについて転送とSHA-256一致を確認した。削除・名前変更は0件。転送処理は一時バックアップの後処理失敗時に成功を返さない実装であり、今回の成功結果は後処理の完了も示す。
- 公開入口検査は `ENTRY_CONTRACT_OK`。rootのHTTP 200、title・canonical・H1、公開インデックス制御、各種リダイレクトと直接ホストのnoindex検査を通過した。
- 公開14ページはすべてHTTP 200。12記事のmain本文、title、description、OGP、robots、JSON-LDが修正版と一致し、対象表示領域のCandy残存は0。ブログ一覧18件・TOP最新15件の表示名、リンク先、順序もローカルと一致した。12記事の公開URLがサイトマップに存在することを確認した。
- 本番ブラウザーで12記事のPC・スマートフォン表示を確認し、本文画像33点の読み込み、自然寸法、参照先と各記事2件のCTAを確認した。代表記事の画面も目視確認した。英語のブランドデザイン表記、共通著作権表記、ロゴ・画像内の文字は保持した。
- `candy_dominant_girls` の既存 `X-Robots-Tag: noindex,nofollow` は維持され、他の対象11記事には同ヘッダーがないことを確認した。既存のSEO方針は変更していない。
- 短時間バイト記事の既存FAQ検査警告は修正せず、[別件の調査待ち記録](20261031_PROBLEM_short-time-job-faq-seo-warning_20261031_1.md)に保存した。本件の新たな不整合とは区別する。
- 公開結果の履歴のみを後続の `[skip ci]` コミットでmainへ保存し、本番コードの追加変更や再転送を行わない。

## 現在

- Remaining Work: None
- Next Action: None
