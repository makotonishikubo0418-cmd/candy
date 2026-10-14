# ブログ共通CSS修正のGitHub限定公開の承認

- History: [20261014_MODIFY_shared-blog-css.md](../20261014_MODIFY_shared-blog-css.md)
- Record Date: 2026-10-14
- Sequence: 4
- Status: Verification Pending

## 記録

### 決定

ユーザーが、`blog.css`、対象4記事のHTML、対応するサイトマップ・生成台帳・履歴をmainへステージ・コミット・Pushする一括操作を許可した。本番への自動反映を止め、GitHubだけを更新する。コミットに `[skip ci]` を付け、ブランチ変更・Pull・本番操作・DB操作は行わない。

### 確認済み事実

- 公開前のローカルmainとlive GitHub mainは、ともに `6e5a9a782ac08c479d7d8de5f18419df5566bc7f`。ステージ済みファイルはない。
- 公開対象はHPの6ファイル（共通CSS、4記事HTML、サイトマップ）と、対応する管理資料19ファイルの計25ファイル。この記録を含む。GitHubへの今回の公開対象にPHP変更は含めない。
- 対象CSSのSHA-256は `8b0b006cb5e760de0fd74a57aefd4603442f0531685bb6ebfae38c26534bf49e`。前記録の8条件のChrome確認結果と対象ファイルは維持されている。
- 生成台帳では、既存スナップショット情報を除いた変更対象は単体テンプレートの追加だけ。CSS参照台帳は4記事の共通CSS参照を反映している。
- 台帳の全体チェックに残る単体テンプレートの既存分類・登録問題は、今回修正しない。GitHub限定公開と本番公開の可否を分けて扱う。
- 公開対象外として、旧テンプレートCSS、テンプレートdataset PHP、INDEXの既存更新日差分、別件SEO監査記録2件を作業ツリーに保持する。

## 現在

- Remaining Work: 許可された25ファイルのステージ・コミット・Push、live GitHub SHAおよび本番デプロイ未実行の確認。
- Next Action: 明示した対象だけを `[skip ci]` コミットでmainへPushする。
