# CTA追加と公開前検証

- History: [20261030_MODIFY_double-work-cta.md](../20261030_MODIFY_double-work-cta.md)
- Record Date: 2026-10-30
- Sequence: 1
- Status: In Progress

## 記録

- 本文終了マーカー直後、関連記事より前に参考記事と同じCTA構造を1件追加した。文章は「本業との両立もOK！あなたのペースでCandyのダブルワークを始めてみませんか？」。
- 「Candyの求人情報を見る」は参考記事と同じ `https://kyusyu-okinawa.qzin.jp/candy98/?v=official`。「待機中の設備・備品を見る」は既存記事の `https://www.55810.com/kagoshima-deliveryhealth-blog-waiting-room-facilities.php`。
- 挿入したCTAバイト列を除去すると変更前HTMLと完全一致することを検証した。既存本文、画像2枚、画像コメント、太字、改行、内部リンク、SEO・JSON-LDを保持し、共通CSSは変更していない。
- ローカルブラウザーでPC・スマートフォン幅390pxの表示を確認し、参考記事とCTAの計算済みCSS（幅、余白、背景、角丸、文字、ボタン配置・寸法）が一致した。CTAは1件、横方向のはみ出しなし。
- テンプレート検証は `RESULT=CHECK_OK BODY=EDITED_NOT_REVIEWED`。サイト状態検証は `CHECK=OK documents=10`、対象記事はstructure=COMPLETE、seo=OK、images=OK、issues=NONE。
- サイトマップlastmodプレビューは261件中changed=0。同日更新のためサイトマップ変更は不要。変更に伴う生成管理情報6ファイルを更新した。

## 現在

- Remaining Work: mainへのコミット・Push、Actions本番公開、本番表示・内容確認、完了履歴保存。
- Next Action: 対象差分を保存して本番公開する。
