# 指名替えブログの作成と公開

- Type: CREATE
- Start Date: 2026-11-05

## 目的

指名替えのブログ記事を、指定原稿と管理書に従ってローカル作成・検証し、許可済みの GitHub 反映と本番公開まで完了する。

## 対象範囲

- 公開URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-shimei-change.php`
- slug: `shimei-change`
- タイトル: 指名替えは失礼？女の子の本音を鹿児島デリヘルキャンディが紹介！
- ポイント色: ローズピンク（`blog-theme-rose-pink`、基準色 `#B65B86`）
- 画像: `HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-shimei-change_1.jpg`、`_2.jpg`、`_3.jpg`
- 公開PHP、source HTML、dataset、`dataset_base.php`、ブログ一覧、TOP最新15件、サイトマップ、共通 `blog.css` のテーマ追加と参照ハッシュ更新
- 実施範囲に明示された git add・commit・push と本番公開
- 女の子紹介ブロックは対象外。M・A・Nは原稿上のインタビューであり、台帳のプロフィールへ結び付けない
- 指定範囲外の既存記事本文、既存の未コミット変更、DB操作は対象外

## 完了条件

- ローカル一式が正式テンプレート／ブログ仕様に整合し、SEO・JSON-LD・画像・一覧・TOP・サイトマップが同期している
- PC・スマートフォンで記事とTOPブログ欄を実表示確認している
- 許可済み範囲で GitHub に Push し、本番公開後の HTTP／表示を確認している
- 履歴に経緯と結果を記録している

## 初期情報

- ユーザーから公開URL、タイトル、ポイント色ローズピンク（`#B65B86`）、原文、画像3点、女の子一覧へのリンク、実施範囲（ローカル作成から本番公開）が指定された
- 著者名・公開日は原文未提供のため追加しない
- FAQ見出しは原文にない。インタビューの質問は本文として掲載し、FAQPageは作らない
