# カレー色テーマの追加

- History: [20261015_MODIFY_curry-blog-colors.md](../20261015_MODIFY_curry-blog-colors.md)
- Record Date: 2026-10-15
- Sequence: 1
- Status: Verification Pending

## 記録

### 対応

- blog.cssへblog-theme-curryを追加。主色#b87916、濃色#89550e、薄い背景#fff4d7を使用し、既存スタッフブログの寸法を保持する色変数のみを上書きした。HOMEボタンにも同テーマ内だけの配色を適用。
- retort-curryに同クラスを追加。本文・画像・リンクの変更なし。旧テーマの既存CSS宣言は保持した。
- 全6参照HTMLのCSS更新キーを3865c946へ更新。candy_dominant_girlsの未公開本文は公開対象から除外する。

### 結果

- スマートフォンでカレー色の強調枠・本文を確認。算出CSSで見出し・HOMEの黄土色とブラウンを確認し、横はみ出し0。
- 公開済み記事作成とは別の配色変更案件として記録する。

## 現在

- Remaining Work: 公開差分の検証、Commit/Push、本番反映確認。
- Next Action: 本番配信。
