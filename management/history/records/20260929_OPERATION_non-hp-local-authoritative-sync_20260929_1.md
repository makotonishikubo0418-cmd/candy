# HP以外の同期対象確定

- History: [20260929_OPERATION_non-hp-local-authoritative-sync.md](../20260929_OPERATION_non-hp-local-authoritative-sync.md)
- Record Date: 2026-09-29
- Sequence: 1
- Status: In Progress

## 記録

### 確認済み事実

- GitHub mainを取得し、同期元コミット `a206c67fa3faaff31767b94a5c0eb9167eea9637` を確定した。
- 本案件の履歴追加前は、HP以外のローカルファイルが1,795件、GitHub側が1,573件。追加は `management/` の1,007件、同一パスの内容更新は `AGENTS.md` の1件、削除は旧 `codex/` 784件と `docs/rules/GIT_RULES.md` 1件だった。
- HPの保持対象ツリーは `520914e3b6b92e67c012dd8513143f770089ac88`。
- ローカルファイルを正本とする指示が対象・方向を明示しており、今回の追加・更新・削除の反映はその指示範囲に含まれる。

### 決定

- GitHub最新mainを親にして同期コミットを作成し、既存のコミット履歴を保持して通常のPushで反映する。
- 作業用の別インデックスでHP以外だけを登録し、HPツリー不変と対象ファイルのハッシュ一致を確認する。Push後にローカルmainと通常インデックスを反映済みコミットへ合わせ、作業ファイルの内容は維持する。
- 既存 `AGENTS.md`、`.gitignore`、`.github/` などの内容は今回のために改変せず、ローカルの内容を反映する。管理資料は明示された同期範囲として登録する。
- `codex/README.md` と `codex/WORK_ROUTING.md` はローカルに存在しない。旧管理書の旧パス保護・復元記述を理由に、ユーザーが正本と指定した現在のローカル構成を旧構成へ戻さない。
- 本番Actionsが旧 `codex/` の削除にも反応するため、同期コミットへ `[skip ci]` を付け、本番操作を起動しない。

### 未実施・既存の制約

- `.github/` のワークフローは現時点で旧 `codex/scripts/` などを参照している。これはローカルにある既存状態であり、今回の同期で参照先の改修は行わない。今後のHPデプロイ前には参照先の整合確認が必要。
- 本記録時点ではPush後の照合は未実施。

## 現在

- Remaining Work: 対象ファイルを登録し、HP不変・対象一致を検証したうえでPushし、反映結果を照合する。
- Next Action: 同期コミットの作成とGitHub mainへの反映。
