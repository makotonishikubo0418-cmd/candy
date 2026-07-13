# AGENTS.md

## 1. 役割と適用範囲

このファイルは `makotonishikubo0418-cmd/candy` 全体の最上位ルールである。基準ブランチは `main`。

ローカルパスは PC ごとに変わる。過去資料の固定パスではなく、現在開いている Git ルート、実ファイル、現在の Git 状態を使用する。HP 配下では、このファイルに加えて `HP/AGENTS.md` を適用する。両者が矛盾する場合は作業を止め、矛盾箇所を報告する。

AGENTS.md は重要ルールと作業別導線だけを持つ。件数、調査結果、個別仕様は対応する正本資料または実ファイルで確認する。

## 2. 作業開始時に必ず行うこと

1. このファイルを読む。
2. HP が対象なら `HP/AGENTS.md` を読む。
3. `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` から、今回の作業に対応する資料だけを選んで読む。
4. Git ルート、ブランチ、remote、作業ツリー、対象ファイルの現物を確認する。
5. 次を短く示してから実行する。

```text
AGENTS.md check:
- 適用ルール・参照資料
- 作業種別
- 今回やること
- 今回やらないこと
```

「以前確認した」「管理資料に書いてある」は今回の確認にならない。変化し得る事実は実物で再確認する。

## 3. 絶対ルール

### 3.1 事実と報告

- 未実行を実行済み、未確認を確認済み、途中を完了と報告しない。
- 推測は推測と明記する。
- ローカル変更、Commit、Push、Actions、本番反映、HTTP、ブラウザ表示を別の状態として報告する。
- ブラウザで確認していない場合は「表示確認済み」と書かない。
- DB、外部サービス、PC/SP、JavaScript console も実際に確認した項目だけを報告する。
- 「監視中」は、実プロセスまたは実行先を継続確認できる場合だけ使用する。

### 3.2 範囲と既存変更

- ユーザー指定範囲を勝手に拡張しない。
- ついで修正、整理、削除、移動、リネーム、リファクタをしない。
- ユーザーまたは他 PC の変更を上書きしない。
- dirty・未追跡があるだけで一律停止しない。今回の対象と重なるかを確認する。
- 対象と既存変更が重なり、内容を安全に保持できない場合は停止する。
- 一回の作業ファイル数に固定上限を設けない。必要なら 100 ファイル以上を扱う。

### 3.3 権限

次はユーザーの明示指示なしに行わない。

- Commit、Push、Pull に伴う競合解消
- 本番反映、サーバー変更、サーバーファイル削除
- DB 変更
- noindex/index の変更
- 既存ファイルの削除、移動、リネーム
- GitHub Actions の手動実行

`git reset --hard`、`git clean`、force push、無断の merge/rebase は行わない。

### 3.4 秘密情報

認証値、パスワード、API キー、DB 接続値、決済値、ログ本文、個人情報を、チャット、Markdown、Commit メッセージ、差分説明へ転記しない。必要な場合は値を出さず、所在と対応要否だけを報告する。

### 3.5 「全て」「100%」

ユーザーが「全て」「全ファイル」「100%」と指定した場合、次を満たさなければ完了と報告しない。

1. 対象母集団を列挙する。
2. 対象件数と除外条件を示す。
3. 全対象を同じ基準で検査する。
4. 成功、失敗、未確認、接続先制限を分けて集計する。
5. 未確認が一件でもあれば「100%確認済み」と書かない。

## 4. 作業別の必読資料

最初に `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` を開き、下表の資料へ進む。全資料の機械的な通読は不要。

| 作業 | 正本・入口 |
|---|---|
| 今日の経緯・再発防止 | `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` |
| 通常の新規ページ生成 | `CANDY_PAGE_GENERATION_GOVERNANCE.md` |
| area 制作・105ページ・画像 | `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` |
| 構造・生成経路の調査 | `CANDY_HP_STRUCTURE_MAP.md`、`CANDY_CODE_FILE_STRUCTURE.md` |
| 既存ページの修正 | `CANDY_OPERATION_BASICS.md` と対象カテゴリ資料 |
| 全ファイル・リンク検証 | `CANDY_VERIFICATION_PLAN.md` |
| 本番移行・サーバー | `CANDY_PRODUCTION_MIGRATION_MASTER.md` |
| Git 操作 | このファイルの第5節 |

ファイル件数や現在状態は表の古い数値を再利用せず、実ファイルから取得する。

## 5. Git 運用

### 5.1 開始前

```powershell
git remote -v
git branch --show-current
git status --short --branch
```

- 対象 branch、remote、既存変更を確認する。
- 安全に取得できる場合だけ `git fetch origin` を行う。
- Pull が必要で、作業ツリーと履歴が安全な場合だけ `git pull --ff-only origin main` を行う。
- fast-forward できない、競合する、対象変更と既存変更が重なる場合は停止する。

### 5.2 終了前

```powershell
git status --short
git diff --stat
git diff --check
```

- 指定ファイル以外を stage しない。
- Commit は明示指示がある場合だけ行う。
- Push 前に `fetch` し、remote 更新と Push 対象を再確認する。
- Push は明示指示がある場合だけ行う。
- `main` への Push だけでは本番 Actions は起動しない。本番反映は手動 `workflow_dispatch` の preview と deploy を別操作で行う。
- Commit、Push、本番 preview、本番 deploy はそれぞれ別の明示指示を必要とする。Push 指示を Actions 実行許可として扱わない。
- 本番 deploy は preview の対象件数・`PLAN_TOKEN`・対象SHAが一致し、確認文言がある場合だけ実行する。

## 6. STOP 条件

次の場合は推測で進めず停止する。

- 適用する AGENTS.md を読めない、または内容が矛盾する
- 対象、変更範囲、branch、完了条件が特定できない
- 既存変更を失う可能性がある
- Pull が fast-forward で完了しない、または conflict が発生した
- 指示外の削除、移動、DB、本番操作が必要になる
- 秘密値、ログ本文、個人情報を転記する必要が生じる
- 全件確認の母集団を特定できない
- 本番 index の転送維持条件を確認できない

## 7. 完了報告

結論を最初に、必要な項目だけ短く書く。

```text
結論:
確認済み:
実施内容:
変更ファイル:
未確認・未実施:
次に必要な操作:
```

禁止表現: 「一通り確認」「たぶん」「おそらく問題ない」「だいたい理解」「完了」だけの報告。

完了と書く場合は、対象と証拠を併記する。Commit、Push、本番、ブラウザ表示が未実施なら明記する。
