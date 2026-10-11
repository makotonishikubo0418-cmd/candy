# SEO関連管理書の旧パス参照修正完了

- History: [20261011_MODIFY_legacy-management-path-references.md](../20261011_MODIFY_legacy-management-path-references.md)
- Record Date: 2026-10-11
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- `CANDY_SEO_SPEC.md`、`CANDY_MASTER_DOC_INDEX.md`、`CANDY_FIX_BACKLOG.md` に旧 `codex/` レイアウト参照が合計10件残っていた。
- 現在の管理構成、SEO管理書、生成状態、スクリプト、データ、履歴の正規参照先は `management/INDEX.md` から特定できた。

### 決定

- 対象3管理書の旧参照だけを現在の管理構成へ置換する。
- 管理書監査の自動化は変更しない。

### 対応

- ルーティング参照を `management/INDEX.md` へ統一した。
- データ、スクリプト、生成状態、履歴の参照を現在の `management/` 配下へ修正した。
- 対象3管理書の `Updated` を2026-10-11に設定した。

### 結果

- 対象3管理書の旧 `codex/` 参照は0件になった。
- 置換後の参照先はすべて実在した。
- `git diff --check` と生成状態チェック `CHECK=OK documents=10` に合格した。

## 現在

- Remaining Work: None
- Next Action: None
