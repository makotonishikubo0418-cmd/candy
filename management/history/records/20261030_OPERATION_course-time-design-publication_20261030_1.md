# コース時間記事の本番反映結果と表示検証

- History: [20261030_OPERATION_course-time-design-publication.md](../20261030_OPERATION_course-time-design-publication.md)
- Record Date: 2026-10-30
- Sequence: 1
- Status: Verification Pending

## 記録

### 結果

- 対象 `74f6082dd1294518ea6db352c07597cf31a2352f`、比較元 `6d6742febd8929b55c2e81ec9f01ceda672dda13` の手動preview [41023847439](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41023847439) が成功した。
- 共通ブログCSS、サイトマップ、正式テンプレートを含む参照HTML12件の計14ファイル、433,784 bytes、削除・名前変更0件。PLAN_TOKEN `77e02a3be4c97ae9c2b14a87947cc9a38c96dc3add77a61f50c6f4ce88832b73`。
- 同じ計画によるdeploy [41023920922](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/41023920922) はsuccess。事前検査・最終ファイル14件のSHA-256検証・バックアップ後処理が成功し、`ENTRY_CONTRACT_OK` を確認した。保護対象index.php・.htaccessの変更はない。
- 公開URL: https://www.55810.com/kagoshima-deliveryhealth-blog-course-time.php
- 対象を含む共通CSS参照11記事はHTTP 200。公開main要素は各ローカルHTMLと一致し、全記事が新CSS更新キー `b2f448fc` を参照した。CSSの通常URLと更新キー付きURLは両方HTTP 200、公開バイトは対象コミットと一致した。サイトマップHTTP 200・対象URL登録を確認した。
- 他10記事には新テーマ・表クラスが存在せず、新規CSSの適用対象外。既存CSS宣言が変更されていないことは前案件の検証結果を再利用した。
- 代表の短時間バイト記事は公開前後のPC・スマートフォンで各148要素の色、背景、フォント、行高、余白、枠色、幅、高さが完全一致した。新CSS参照も確認した。
- 対象記事の本番PC表示で表の余白・ゴールド配色を目視確認した。計測CSS viewport幅1422px、セル余白18px 24px、横はみ出しなし。
- 本番スマートフォンのDOM計測は幅434px、表幅380.89px、セル余白14px 12px、横はみ出しなし。基本アクセント #A27B43、冒頭の「導入｜」削除を確認し、FAQの展開・色rgb(118,87,46)を確認した。
- スマートフォンの画面キャプチャが白紙となり、公開後のスマートフォン画像の目視確認は未完了。HTTPとDOMは正常に取得できているため、白紙キャプチャだけから本番ページの描画不良とは判断していない。ローカルのPC・スマートフォン画像確認は前案件で完了済みだが、本番目視確認の代替として完了扱いにはしない。
- ブラウザーviewportを解除し、確認用タブを閉じた。確認済みGitHub反映やローカル検証は繰り返さず、履歴だけを `[skip ci]` で保存する。

## 現在

- Remaining Work: 本番スマートフォン表示の画像による目視確認。
- Next Action: 正常に画像取得できるブラウザー環境で、対象記事のスマートフォン表示を確認する。本番反映自体は成功済みのため再デプロイしない。
