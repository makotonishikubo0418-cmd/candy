# ブログ一覧枠閉じタグ修正と表示確認

- History: [`20261123_PROBLEM_blog-list-frame-collapse.md`](../20261123_PROBLEM_blog-list-frame-collapse.md)
- Record Date: 2026-11-23
- Sequence: 2
- Status: Completed

## 記録

### 対応

- `HP/source/blog.html` のみ変更。183行目にあった `w_1050` 親の `</div>` を、最終記事（`30s-40s-job`）の直後・お問い合わせボタン直前へ移動した。
- 記事タイトル・リンク・表示順、ボタン配置、他ファイルは変更していない。

### 結果

- DOM追跡: 記事リンク54件すべてが `div.lp_0_55_35.w_1050` 内。枠外の記事兄弟は0件。ボタン `#button_4` は枠外（`lp_0_7` 直下）。`div` 開閉は一致。
- ローカル表示（`source/blog.html` を HP ルート相対で配信）:
  - PC（viewport 約1614px）: 枠幅1050px。移籍記事と指名と本指名記事の行幅は同値（940px）。11月21日追加4記事は枠内。
  - SP（390×844 エミュレーション）: 枠幅376px（幅100%相当）。4記事は枠内。横スクロールなし。ボタンは枠外・中央寄せ。
- Git操作・本番公開は未実施。

## 現在

- Remaining Work: None（ローカル修正と表示確認）
- Next Action: None（GitHub反映・本番公開は別途の明示指示が必要）
