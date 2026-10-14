# カレー色の本番反映完了

- History: [20261015_MODIFY_curry-blog-colors.md](../20261015_MODIFY_curry-blog-colors.md)
- Record Date: 2026-10-15
- Sequence: 2
- Status: Completed

## 記録

### 対応

- PC確認時、この記事の本文だけの強調枠が共通グリッドの狭い先頭列に配置される不備を確認。カレーテーマのstrong単独要素だけを全列幅に配置し、読みやすい折り返しに修正した。
- 記録1のCSS更新キー3865c946は修正途中の値。最終値は975a2497。全6参照HTMLと公式ブログテンプレートに整合するキーを設定した（テンプレートを含め6ファイル）。
- 最終CSSのSHA256: 975a24970b1d650ab2e651e94972d81b6b7f1ad4c4c7bf5ea2afd8df3f804c7a。

### 結果

- 公開コミット: d527a69cca4069adf4280c2f51d54c318a9c8acb。GitHub mainへのPushを確認。
- [Actions run 38652424068](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/38652424068)は成功。8ファイル・252072 bytesを配信し、8/8のSHA256検証、バックアップ整理を含む配信処理とENTRY_CONTRACT_OKを確認。削除0件。
- 本番計画トークン: e033db56355c199f22cc40b42c934a0a7c8a52c71bcbfa26068a9f80746dfb29。配信対象はblog.css、sitemap.xml、6参照HTML。
- [公開記事](https://www.55810.com/kagoshima-deliveryhealth-blog-retort-curry.php)はHTTP 200。blog-theme-curryの反映を確認。CSSの版指定あり／なしの両URLでHTTP 200とローカルSHA256一致を確認。
- 公開5記事のCSS参照キー975a2497をHTTPで確認。テンプレートを含む6ソースは配信ログで検証済み。
- 本番のPC幅・スマートフォン幅でカレー色と強調枠を目視確認。実測innerWidthは1309px／354px。アクセント#b87916、見出しの黄土色からブラウンへのグラデーション、本文領域の横はみ出し0を確認。
- 色変更と強調枠修正はカレーテーマ内に限定。他記事の既存CSS宣言・本文は変更していない。candy_dominant_girlsの別作業中本文と未追跡画像3点は公開コミットに含めず、ローカルに保持。
- 既存のcandy_dominant_girlsに関するサイト状態チェックのsitemap_lastmod_or_structure指摘は本件の対象外であり、全体チェック成功として扱っていない。

## 現在

- Remaining Work: None
- Next Action: None
