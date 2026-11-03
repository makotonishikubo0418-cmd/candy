# 新人とベテラン比較ブログの完成HTML作成

- Type: CREATE
- Start Date: 2026-11-03

## 目的

指定原稿・画像・プロフィールリンクから、`newcomer-veteran` ブログの完成HTMLをローカルに反映し、SEO・関連記事・表示まで検証する。

## 対象範囲

`HP/source/kagoshima-deliveryhealth-blog-newcomer-veteran.html` の本文・メタ・JSON-LD・画像・内部リンク、生成管理情報の同期と履歴。Git状態変更、DB操作、本番公開は含まない。

## 完了条件

- 原稿内容を欠落なく反映し、指定画像・プロフィールリンクを参考ページ同仕様で設置する。
- robots=index、canonical、OGP、Article、BreadcrumbList、関連記事3+エリア3を整合させる。
- FAQPageはFAQなしのため出力しない。女の子ItemListは台帳PUBLIC不足のため出力しない。
- ローカル検証とPC/SP表示確認、履歴記録を完了する。

## 初期情報

- URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-newcomer-veteran.php`
- 先の指示テンプレで完成時URLが誤って `dormitory` と記載されていたが、ユーザー訂正により本URLで進行。
- ポイント色: ラベンダーパープル（`blog-theme-purple`）
- 画像2: `kagoshima-deliveryhealth-blog-newcomer-veteran_2.jpg`（「あなたはどっち派？🤭」の直後）
- テンプレート一式は先行案件 `20261103_CREATE_newcomer-veteran-blog-template` で準備済み。
