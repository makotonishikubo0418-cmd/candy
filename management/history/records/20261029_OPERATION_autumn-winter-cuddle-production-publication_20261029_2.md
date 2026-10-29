# 表示不備2点の原因特定と限定修正

- History: [20261029_OPERATION_autumn-winter-cuddle-production-publication.md](../20261029_OPERATION_autumn-winter-cuddle-production-publication.md)
- Record Date: 2026-10-29
- Sequence: 2
- Status: Verification Pending

## 記録

### 決定

ユーザーは説明文・Markdown混入とスマートフォン横スクロールの最小修正、PC/SP検証後のコミット・Push・再公開を許可した。本文・タイトル・太字・絵文字・オレンジ色・画像・内部リンク・SEO・PHP・JSON-LDと他ページのデザインを維持する。

### 原因と対応

- DOCTYPEの前の編集説明・Markdown開始記号とHTML末尾の終了記号を除去した。前置きによってBackCompatだった描画モードがCSS1Compatへ戻った。
- 不要文字除去後も、goldテーマのスマートフォン用100vwが縦スクロールバーの幅を含むため、表示可能幅375pxに対して内容390pxの横はみ出しが残った。
- 対象記事だけに `blog-container-width` を指定し、共通blog.cssに当該クラス限定の幅補正を追加した。記事と本文枠は親要素の100%、導入枠はauto幅とし、既存の配色・余白ルールを保持した。
- 既存CSSルールは変更していない。管理書所定のキャッシュ更新のため、blog.cssの全参照HTMLのバージョンを同期する。

### ローカル検証

- スマートフォン設定幅390px・320px、PC設定幅1365pxで確認。実測の表示可能幅とscrollWidthはそれぞれ375/375、305/305、1350/1350で一致し、横スクロールなし。
- 不要文字の非表示、CSS1Compat、記事画像1000×750の読み込み、アクセント色 `#d59c22` の維持を確認した。
- HTMLは不要な文書外文字の除去、限定クラス追加、CSSバージョン以外を変更していないことを元のコミット内容と比較した。
- 他の記事はCSSバージョンだけの更新とし、限定クラスは対象記事のみが使用する。

## 現在

- Remaining Work: 管理情報更新、コミット・Push、本番再公開、PC/SPの本番反映確認と完了記録。
- Next Action: 検証済み修正を通常Pushの自動デプロイで反映する。
