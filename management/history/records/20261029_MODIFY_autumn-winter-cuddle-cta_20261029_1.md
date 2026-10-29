# 秋冬ブログの正式CTA適用と表示比較

- History: [20261029_MODIFY_autumn-winter-cuddle-cta.md](../20261029_MODIFY_autumn-winter-cuddle-cta.md)
- Record Date: 2026-10-29
- Sequence: 1
- Status: Completed

## 記録

### 対応

- girl-choice記事の「NEXT STEP」の文章と2ボタンHTMLを再利用した。
- CTAにのみblog-theme-pinkを指定し、共通blog.cssの既存変数・宣言を再利用できるセレクターを追加した。goldのCTA指定は部品単位のpink指定を除外し、本文のgoldテーマを維持した。
- 共通CSSのデザイン値は変更せず、参照HTML10件のキャッシュ識別子を9dc575f4に統一した。

### 結果

- ローカルブラウザーで対象CTAとgirl-choiceのCTAを比較。PC・スマートフォンとも、全CTA要素の文章・リンク・色・背景・枠線・角丸・影・余白・文字寸法・行間・ボタン配置の比較値が一致した。
- PCはclientWidth/scrollWidthが1265/1265、スマートフォンは375/375。両ボタンは同一行で、スマートフォンの高さは56px。
- 本文アクセントは#d59c22を保持。差分検証で対象HTMLのCTAとCSSキャッシュ値以外が不変、他の参照HTMLはキャッシュ値のみの変更、CSS宣言値の集合が不変であることを確認した。
- Gitステージ・コミット・Push・本番反映は実施していない。本番の修正後表示は未確認。

## 現在

- Remaining Work: ローカル修正・表示検証に残作業なし。本番反映は今回の実施範囲外。
- Next Action: 公開指示がある場合、現行Git・本番管理書に従って反映する。
