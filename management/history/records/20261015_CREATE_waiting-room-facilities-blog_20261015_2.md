# 設備・備品ブログの本番公開完了

- History: [20261015_CREATE_waiting-room-facilities-blog.md](../20261015_CREATE_waiting-room-facilities-blog.md)
- Record Date: 2026-10-15
- Sequence: 2
- Status: Completed

## 記録

### 結果

- 公開コミット `446eeff283ff6c9024e6189c69d84f4573d3ada1` をGitHub mainへPushした。
- [Actions 38660233043](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/38660233043)成功。PHP3ファイルの構文検証後に12ファイル・3856680 bytesを配信し、12/12のSHA256一致とENTRY_CONTRACT_OKを確認。削除・改名は0件。保護対象のindex.phpと.htaccessは配信対象外。
- 実配信PLAN_TOKEN: `e1225d6f121b4c0ba58d240a7a962a65e49cf40dd834cd0a30615044141718f8`。
- [公開ページ](https://www.55810.com/kagoshima-deliveryhealth-blog-waiting-room-facilities.php)はHTTP 200。title、H1、canonical、index設定、Article、BreadcrumbListがローカル実装と一致し、設備設置・充電器貸し出しの承認文言を確認した。確認用メモは公開文に残っていない。
- 新記事、トップ、ブログ一覧、前編、サイトマップ、画像4点、関連記事・対応エリア6件の合計15URLをHTTP 200で確認。トップ・ブログ一覧・前編には新記事リンクが各1件存在する。
- 原本・ローカル公開画像・本番画像のSHA256は4点とも一致。2枚目はimage/png、他3点はimage/jpeg。
  - `_1.jpg`: `a8af26cc79aa9adee3b44ea72e9183ae968b81aec70c65ddbe2fa853f2a3aa11`
  - `_2.png`: `5a8fa46a7a7822ec880f294c8cd95a14e975c2bef6c4f6dc9ba12f59d6421352`
  - `_3.jpg`: `f49df60a129bbd75bdb298ddedbd391bc2b51f8a8a47d29dddd0993c5ff30917`
  - `_4.jpg`: `890dbb3ce69d1ee4f30fd3b0efe26c9154cc1a07230fd8804b0da891b0edab86`
- 本番PC幅1440px・スマートフォン幅390pxで表示を確認。画像4点はスクロール後にcomplete=true、自然寸法は原本と一致。横はみ出しなし。本番URL由来のコンソールエラーなし（同じ確認タブに残るローカルプレビュー共通JSエラーは本番のものではない）。
- トップのブログ一覧同期時にヘルパーが閉じタグを行より前へ移すため、今回出力の閉じタグを元のラッパー末尾に戻し、既存レイアウトを維持した。共通スクリプトは変更していない。
- 生成管理書を同期。対象ページはstructure=COMPLETE、seo=OK、images=OK、issues=NONE。全体チェックは既存 `other:kagoshima-deliveryhealth-blog-candy_dominant_girls` のサイトマップ指摘が残り、全体成功とは扱っていない。
- 対象外の `candy_dominant_girls` 本文・未追跡画像3点は保存した原本バイトと一致し、今回のCommit/Pushには含めていない。

## 現在

- Remaining Work: None
- Next Action: None
