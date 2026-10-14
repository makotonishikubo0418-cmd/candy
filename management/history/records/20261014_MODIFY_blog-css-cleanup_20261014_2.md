# GitHubおよび本番公開の実行承認

- History: [20261014_MODIFY_blog-css-cleanup.md](../20261014_MODIFY_blog-css-cleanup.md)
- Record Date: 2026-10-14
- Sequence: 2
- Status: Verification Pending

## 記録

### 決定

- ユーザーは当初のGitHub限定指示を訂正し、確認への回答で「GitHubへPushし、本番サイトにも反映する」と明示した。今回のCSS整理と正式ブログテンプレート更新、関連管理書・履歴・生成台帳をコミットしmainへPush、本番へ反映する。
- 別作業で変更されたcandy_dominant_girlsの記事本文は公開対象に含めない。同HTMLは今回変更したblog.css更新キーだけをコミットし、本文の作業中変更をローカルに保持する。公開用生成台帳はコミット対象のHTMLに合わせる。

### 確認済み事実

- 開始時のmainとlive GitHub mainは `da9e8b7f746109126b8175a80868c244d1987437` で一致し、ステージ済み変更はなかった。
- mainのHP変更Pushが本番workflowを起動する。対象はCSS1件とsource HTML5件のアップロード、旧専用CSS4件の削除。保護されたindex.php・.htaccessやDBは対象外。
- 旧CSS削除はworkflowの削除対象リスト・plan token・退避復元機構を通す。公開後にActions、対象URL、CSSハッシュ、旧CSS削除を確認する。

## 現在

- Remaining Work: コミット、Push、Actionsおよび本番検証。
- Next Action: 公開対象の差分を分離し、デプロイ前検証を実施する。
