# 旧履歴資料の案件別整理と履歴一覧更新 — 移行・照合結果

- History: [20260920_OPERATION_history-reorganization.md](../20260920_OPERATION_history-reorganization.md)
- Record Date: 2026-09-20
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- ユーザーの実行指示: 旧資料を分析・整理し、HISTORY.mdに従った新規履歴を作り、旧新比較後にHISTORY_LIST.mdを更新する。
- 移行元: `management/history/履歴/` の21ファイル。移行開始前のSHA-256と全21ファイルの内容一致を確認した。
- 作業履歴: 7月前半46件、7月後半30件、8月39件、合計115件。Task IDの重複はない。
- 旧案件台帳29件、旧分類一覧29件（相談3・不具合12・変更14）、予約90件、連絡7件を対応付けた。
- 対象は提供された旧資料の整理であり、旧記録に書かれたサイト、本番、DB、GitHubの状態を今回確認したものではない。

### 決定

- 目的と完了条件が同じ作業は一つの案件へまとめる。日付や実装・公開という段階だけでは分割しない。独立した複数案件をまとめて扱う公開作業は、その公開範囲・結果を一度だけ記録し、個別案件から参照する。
- Start Dateは旧登録IDまたは最初の関連作業日を引き継ぐ。さらに古い登録の有無は不明。初登録日の根拠がないHOTEL-ACCEPTED-IMAGE-PATHは、旧資料の更新日を初登録日と推定せず、今回の登録日を使用する。
- 進捗ファイルのRecord Dateは新規作成日2026-09-20とし、旧作業日は本文で区別する。同日の順序は元の作業・判断の順序で連番を付ける。
- Completedの引き継ぎは当時の対象と完了条件に限定する。実機の未確認事項、対象外事項、公開準備止まりの記録を完了証拠に変えない。未確認の公開や本番状態はVerification Pendingに残す。
- 旧予約のCOMPLETEは予約の終了であり、本番検証完了の根拠として単独では使用しない。
- 原文の依頼・作業結果は意味の変化を避けるため原文で保持し、日本語の案件名・範囲・完了条件・状態判定を付ける。引用内の旧規則は当時の判断として扱い、現行仕様の正本にはしない。
- 以下の対応表は今回の移行照合の結果であり、新しい管理台帳ではない。今後の案件登録はHISTORY_LIST.md、進捗は各案件のrecordsで扱う。

### 対応

案件概要90件、進捗記録121件を新規作成した。件数には今回の移行案件とその完了記録を含む。旧HISTORY_LIST.mdの他プロジェクト用2行は実在するCandy案件一覧へ置き換えた。

**移行元21ファイルの扱い**

| 旧資料 | 移行・保持・除外の判断 |
|---|---|
| [CANDY_GIRLS_INVALID_NO_BEHAVIOR.md](../履歴/CANDY_GIRLS_INVALID_NO_BEHAVIOR.md) | 固有の事実・判断・原因・境界を案件へ統合し、実行結果は同じ案件の作業記録と照合。単純な変更パス一覧・子資料目次・重複する結果は旧原本を参照し、新しい重複記録を作らない。 対応先: [女性番号不正時の別人物表示の修正](../20260816_PROBLEM_girls-invalid-number.md) |
| [CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md](../履歴/CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md) | 固有の事実・判断・原因・境界を案件へ統合し、実行結果は同じ案件の作業記録と照合。単純な変更パス一覧・子資料目次・重複する結果は旧原本を参照し、新しい重複記録を作らない。 対応先: [女性プロフィールのSEO出力改善](../20260815_PROBLEM_girls-profile-seo.md) |
| [CANDY_INTERNAL_PATH_ACCESS_CONTROL.md](../履歴/CANDY_INTERNAL_PATH_ACCESS_CONTROL.md) | 固有の事実・判断・原因・境界を案件へ統合し、実行結果は同じ案件の作業記録と照合。単純な変更パス一覧・子資料目次・重複する結果は旧原本を参照し、新しい重複記録を作らない。 対応先: [内部ディレクトリへのHTTPアクセス制御](../20260817_PROBLEM_internal-path-access.md) |
| [CANDY_MANAGEMENT_SYSTEM_REBUILD.md](../履歴/CANDY_MANAGEMENT_SYSTEM_REBUILD.md) | 固有の事実・判断・原因・境界を案件へ統合し、実行結果は同じ案件の作業記録と照合。単純な変更パス一覧・子資料目次・重複する結果は旧原本を参照し、新しい重複記録を作らない。 対応先: [管理体系の再構築](../20260812_MODIFY_management-system-rebuild.md) |
| [CANDY_MANAGEMENT_SYSTEM_REPAIR.md](../履歴/CANDY_MANAGEMENT_SYSTEM_REPAIR.md) | 固有の事実・判断・原因・境界を案件へ統合し、実行結果は同じ案件の作業記録と照合。単純な変更パス一覧・子資料目次・重複する結果は旧原本を参照し、新しい重複記録を作らない。 対応先: [案件履歴とGitHub公開状態の照合](../20260820_OPERATION_github-publication-reconciliation.md) / [管理体系の不整合修正と技術資料の分類](../20260814_MODIFY_management-system-repair.md) |
| [CANDY_RECORD_HISTORY_STRUCTURE.md](../履歴/CANDY_RECORD_HISTORY_STRUCTURE.md) | 固有の事実・判断・原因・境界を案件へ統合し、実行結果は同じ案件の作業記録と照合。単純な変更パス一覧・子資料目次・重複する結果は旧原本を参照し、新しい重複記録を作らない。 対応先: [相談・不具合・変更の履歴導線整備](../20260816_MODIFY_record-history-structure.md) |
| [CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md](../履歴/CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md) | 固有の事実・判断・原因・境界を案件へ統合し、実行結果は同じ案件の作業記録と照合。単純な変更パス一覧・子資料目次・重複する結果は旧原本を参照し、新しい重複記録を作らない。 対応先: [リポジトリ全体のSEO調査](../20260718_INVESTIGATE_repository-seo-audit.md) |
| [CASE_HISTORY.md](../履歴/CASE_HISTORY.md) | 分類入口としての構成判断は履歴導線整備案件へ統合。旧一覧自体は重複台帳として再作成しない。 対応先: [相談・不具合・変更の履歴導線整備](../20260816_MODIFY_record-history-structure.md) |
| [CASE_REGISTRY.md](../履歴/CASE_REGISTRY.md) | 全29案件を新しい案件概要へ対応付ける。公開済みと本番確認済みを分け、次対応と証拠の限界を進捗記録へ引き継ぐ。 対応先: 下記の作業・案件対応表に全件記載。 |
| [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) | 全行を旧案件IDで照合し、該当案件へ統合。分類入口として重複する一覧は作成しない。 対応先: 下記の作業・案件対応表に全件記載。 |
| [CODEX_COMMUNICATION.md](../履歴/CODEX_COMMUNICATION.md) | 7連絡を関連する作業単位へ統合。旧指示の失効・訂正とSEO調査の未確認事項を保持する。 対応先: 下記の作業・案件対応表に全件記載。 |
| [CODE_STRUCTURE.md](../履歴/CODE_STRUCTURE.md) | 廃止済み互換入口だけで固有の作業結果がないため、独立した履歴案件を作成しない。旧ファイルを保持する。 |
| [CONSULTATION_HISTORY.md](../履歴/CONSULTATION_HISTORY.md) | 全行を旧案件IDで照合し、該当案件へ統合。分類入口として重複する一覧は作成しない。 対応先: [エリア入力Textの全件分類と記録](../20260718_INVESTIGATE_area-text-classification.md) / [指示書51ファイルの整合性監査](../20260726_INVESTIGATE_instruction-audit.md) / [リポジトリ全体のSEO調査](../20260718_INVESTIGATE_repository-seo-audit.md) |
| [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) | 全行を旧案件IDで照合し、該当案件へ統合。分類入口として重複する一覧は作成しない。 対応先: 下記の作業・案件対応表に全件記載。 |
| [PROJECT_STATUS.md](../履歴/PROJECT_STATUS.md) | 固有の未解決事項HOTEL-ACCEPTED-IMAGE-PATHを案件登録。会員機能とSEOの未確認状態は既存案件へ統合。現行仕様の説明、未指定の制作順序、参照先だけのブログ例外・生成課題は履歴案件を推測作成せず、旧資料と本記録に所在・限界を保持する。 対応先: [採用元画像だけがあるホテルの自動公開経路の不足](../20260920_PROBLEM_hotel-accepted-image-gap.md) / [開発中の会員機能の公開範囲制限](../20260817_PROBLEM_member-development-isolation.md) / [リポジトリ全体のSEO調査](../20260718_INVESTIGATE_repository-seo-audit.md) |
| [TASK_LOG.md](../履歴/TASK_LOG.md) | 期間一覧は新しい案件一覧に置き換える。承認根拠2件は対応する作業記録へ移す。旧件数の誤差は本記録に残す。 対応先: [エリア入力Textの3件修正](../20260716_PROBLEM_area-text-three-fixes.md) / [旧ファイル86項目の退避と削除差分の確認](../20260716_OPERATION_legacy-file-relocation.md) |
| [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) | 全作業行の依頼・対応・結果・補足／未確認事項を対応する進捗記録へ保持。 対応先: 下記の作業・案件対応表に全件記載。 |
| [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) | 全作業行の依頼・対応・結果・補足／未確認事項を対応する進捗記録へ保持。 対応先: 下記の作業・案件対応表に全件記載。 |
| [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) | 全作業行の依頼・対応・結果・補足／未確認事項を対応する進捗記録へ保持。 対応先: 下記の作業・案件対応表に全件記載。 |
| [TASK_RESERVATIONS.md](../履歴/TASK_RESERVATIONS.md) | 全90予約を対応する作業記録の担当・期間・範囲・旧予約状態に統合。作業終了済みの予約台帳を別途再作成しない。 対応先: 下記の作業・案件対応表に全件記載。 |
| [指示書監査.md](../履歴/指示書監査.md) | 監査方法・4指摘・判定除外・機械検査・未確認事項を調査記録へ移す。51ファイルのハッシュ付き読了台帳は大量の監査補助資料として旧原本に保持し、再掲しない。 対応先: [指示書51ファイルの整合性監査](../20260726_INVESTIGATE_instruction-audit.md) |

**115作業の全件対応（原文4項目と日付・IDを保持）**

| 旧Task ID | 旧資料・行 | 新しい進捗記録 | 予約の対応行 |
|---|---|---|---|
| `TASK-20260716-AREA-IMAGE-SPEC-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 32行目 | [20260716_MODIFY_area-image-production-rules_20260920_1.md](20260716_MODIFY_area-image-production-rules_20260920_1.md) | 114 |
| `TASK-20260716-AREA-TEXT-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 30行目 | [20260716_PROBLEM_area-text-three-fixes_20260920_1.md](20260716_PROBLEM_area-text-three-fixes_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260716-CLEANUP-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 29行目 | [20260716_OPERATION_legacy-file-relocation_20260920_1.md](20260716_OPERATION_legacy-file-relocation_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260716-MGMT-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 28行目 | [20260716_MODIFY_initial-management-layout_20260920_1.md](20260716_MODIFY_initial-management-layout_20260920_1.md) | 104 |
| `TASK-20260716-MGMT-002` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 31行目 | [20260716_MODIFY_initial-management-layout_20260920_2.md](20260716_MODIFY_initial-management-layout_20260920_2.md) | 105 |
| `TASK-20260716-MGMT-003` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 33行目 | [20260716_MODIFY_initial-management-layout_20260920_3.md](20260716_MODIFY_initial-management-layout_20260920_3.md) | 106 |
| `TASK-20260716-MGMT-004` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 34行目 | [20260716_MODIFY_git-workflow-efficiency_20260920_1.md](20260716_MODIFY_git-workflow-efficiency_20260920_1.md) | 107 |
| `TASK-20260716-MGMT-005` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 35行目 | [20260716_MODIFY_area-target-selection_20260920_1.md](20260716_MODIFY_area-target-selection_20260920_1.md) | 108 |
| `TASK-20260716-MGMT-006` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 36行目 | [20260716_MODIFY_area-target-selection_20260920_2.md](20260716_MODIFY_area-target-selection_20260920_2.md) | 109 |
| `TASK-20260716-MGMT-007` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 37行目 | [20260716_MODIFY_area-target-selection_20260920_3.md](20260716_MODIFY_area-target-selection_20260920_3.md) | 110 |
| `TASK-20260716-MGMT-008` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 38行目 | [20260716_MODIFY_hotel-target-selection_20260920_1.md](20260716_MODIFY_hotel-target-selection_20260920_1.md) | 111 |
| `TASK-20260716-MGMT-009` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 39行目 | [20260716_MODIFY_initial-management-layout_20260920_4.md](20260716_MODIFY_initial-management-layout_20260920_4.md) | 112 |
| `TASK-20260716-MGMT-010` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 40行目 | [20260716_MODIFY_initial-management-layout_20260920_5.md](20260716_MODIFY_initial-management-layout_20260920_5.md) | 113 |
| `TASK-20260716-MGMT-011` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 41行目 | [20260716_MODIFY_git-safety-protocol_20260920_1.md](20260716_MODIFY_git-safety-protocol_20260920_1.md) | 115 |
| `TASK-20260717-AREA-HOTEL-GUIDE-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 60行目 | [20260717_MODIFY_area-hotel-guide_20260920_1.md](20260717_MODIFY_area-hotel-guide_20260920_1.md) | 102 |
| `TASK-20260717-GITHUB-SYNC-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 26行目 | [20260716_MODIFY_initial-management-layout_20260920_8.md](20260716_MODIFY_initial-management-layout_20260920_8.md) | 100 |
| `TASK-20260717-LOCAL-WORKSPACE-DOCS-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 25行目 | [20260716_MODIFY_initial-management-layout_20260920_7.md](20260716_MODIFY_initial-management-layout_20260920_7.md) | 99 |
| `TASK-20260717-SCRIPTS-LOCAL-PATHS-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 24行目 | [20260717_PROBLEM_scripts-workspace-paths_20260920_1.md](20260717_PROBLEM_scripts-workspace-paths_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260717-STRUCTURE-DOCS-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 27行目 | [20260716_MODIFY_initial-management-layout_20260920_6.md](20260716_MODIFY_initial-management-layout_20260920_6.md) | 101 |
| `TASK-20260717-SUMMARY-RULE-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 61行目 | [20260717_MODIFY_response-summary-rule_20260920_1.md](20260717_MODIFY_response-summary-rule_20260920_1.md) | 103 |
| `CANDY-HP-MD-MANAGEMENT-20260718` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 23行目 | [20260718_MODIFY_markdown-management_20260920_1.md](20260718_MODIFY_markdown-management_20260920_1.md) | 97 |
| `CANDY-MARKDOWN-COMMIT-PUSH-20260718` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 21行目 | [20260718_MODIFY_markdown-english_20260920_2.md](20260718_MODIFY_markdown-english_20260920_2.md) | 95 |
| `CANDY-MARKDOWN-ENGLISH-STANDARDIZATION-20260718` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 22行目 | [20260718_MODIFY_markdown-english_20260920_1.md](20260718_MODIFY_markdown-english_20260920_1.md) | 96 |
| `TASK-20260718-AREA-09-COMPLETED-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 57行目 | [20260718_INVESTIGATE_area-text-classification_20260920_2.md](20260718_INVESTIGATE_area-text-classification_20260920_2.md) | 93 |
| `TASK-20260718-AREA-ARATA-BACKUP-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 59行目 | [20260718_OPERATION_arata-source-backup_20260920_1.md](20260718_OPERATION_arata-source-backup_20260920_1.md) | 98 |
| `TASK-20260718-AREA-IMAGE-GITHUB-SYNC-009` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 43行目 | [20260718_OPERATION_area-images-github_20260920_1.md](20260718_OPERATION_area-images-github_20260920_1.md) | 78 |
| `TASK-20260718-AREA-IMAGE-INSTRUCTIONS-REWRITE-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 51行目 | [20260716_MODIFY_area-image-production-rules_20260920_3.md](20260716_MODIFY_area-image-production-rules_20260920_3.md) | 86 |
| `TASK-20260718-AREA-IMAGE-LOCAL-PATH-CORRECTION-004` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 48行目 | [20260716_MODIFY_area-image-production-rules_20260920_5.md](20260716_MODIFY_area-image-production-rules_20260920_5.md) | 83 |
| `TASK-20260718-AREA-IMAGE-RUNBOOK-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 52行目 | [20260716_MODIFY_area-image-production-rules_20260920_2.md](20260716_MODIFY_area-image-production-rules_20260920_2.md) | 87 |
| `TASK-20260718-AREA-IMAGE-TEMPLATE-ENFORCEMENT-002` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 50行目 | [20260716_MODIFY_area-image-production-rules_20260920_4.md](20260716_MODIFY_area-image-production-rules_20260920_4.md) | 85 |
| `TASK-20260718-AREA-IMAGE-TEXT-IDENTITY-GATE-006` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 46行目 | [20260716_MODIFY_area-image-production-rules_20260920_6.md](20260716_MODIFY_area-image-production-rules_20260920_6.md) | 81 |
| `TASK-20260718-AREA-JIGENJI-JIYUGAOKA-PUBLISH-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 55行目 | [20260718_CREATE_area-two-page-publication_20260920_2.md](20260718_CREATE_area-two-page-publication_20260920_2.md) | 91 |
| `TASK-20260718-AREA-RELATED-LINKS-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 54行目 | [20260718_MODIFY_area-related-links_20260920_1.md](20260718_MODIFY_area-related-links_20260920_1.md) | 90 |
| `TASK-20260718-AREA-SEO-CONTACT-GITHUB-SYNC-010` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 19行目 | [20260718_OPERATION_area-seo-contact-publication_20260920_1.md](20260718_OPERATION_area-seo-contact-publication_20260920_1.md) | 77 |
| `TASK-20260718-AREA-TEXT-FULL-INVENTORY-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 58行目 | [20260718_INVESTIGATE_area-text-classification_20260920_1.md](20260718_INVESTIGATE_area-text-classification_20260920_1.md) | 94 |
| `TASK-20260718-AREA-TWO-PAGE-PUBLISH-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 56行目 | [20260718_CREATE_area-two-page-publication_20260920_1.md](20260718_CREATE_area-two-page-publication_20260920_1.md) | 92 |
| `TASK-20260718-JONANCHO-IMAGE-INSTALL-003` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 49行目 | [20260718_MODIFY_jonancho-image_20260920_1.md](20260718_MODIFY_jonancho-image_20260920_1.md) | 84 |
| `TASK-20260718-REMAINING-THREE-AREA-IMAGES-008` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 44行目 | [20260718_MODIFY_three-area-images_20260920_1.md](20260718_MODIFY_three-area-images_20260920_1.md) | 79 |
| `TASK-20260718-REPOSITORY-SEO-AUDIT-REPORT-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 53行目 | [20260718_INVESTIGATE_repository-seo-audit_20260920_1.md](20260718_INVESTIGATE_repository-seo-audit_20260920_1.md) | 89 |
| `TASK-20260718-SEO-AUDIT-GITHUB-SYNC-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 20行目 | [20260718_INVESTIGATE_repository-seo-audit_20260920_2.md](20260718_INVESTIGATE_repository-seo-audit_20260920_2.md) | 88 |
| `TASK-20260718-SHINAYASHIKICHO-IMAGE-CREATION-005` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 47行目 | [20260718_MODIFY_shinayashikicho-image_20260920_1.md](20260718_MODIFY_shinayashikicho-image_20260920_1.md) | 82 |
| `TASK-20260718-SHINAYASHIKICHO-TITLE-CORRECTION-007` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 45行目 | [20260718_MODIFY_shinayashikicho-image_20260920_2.md](20260718_MODIFY_shinayashikicho-image_20260920_2.md) | 80 |
| `TASK-20260719-CATEGORY-SITEMAP-GITHUB-SYNC-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 18行目 | [20260719_MODIFY_category-sitemap-publication_20260920_1.md](20260719_MODIFY_category-sitemap-publication_20260920_1.md) | 76 |
| `TASK-20260719-PRODUCTION-RUNTIME-PATH-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 17行目 | [20260719_PROBLEM_production-runtime-path_20260920_1.md](20260719_PROBLEM_production-runtime-path_20260920_1.md) | 75 |
| `TASK-20260720-ACCUMULATED-SEO-PRODUCTION-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 16行目 | [20260720_OPERATION_accumulated-seo-production_20260920_1.md](20260720_OPERATION_accumulated-seo-production_20260920_1.md) | 74 |
| `TASK-20260720-INUSAKOCHO-NORMALIZATION-001` | [TASK_LOG_2026_07_01_20.md](../履歴/TASK_LOG_2026_07_01_20.md) 42行目 | [20260720_PROBLEM_inusakocho-normalization_20260920_1.md](20260720_PROBLEM_inusakocho-normalization_20260920_1.md) | 73 |
| `TASK-20260721-HP-UNUSED-IMAGE-CLEANUP-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 26行目 | [20260721_OPERATION_unused-image-cleanup_20260920_1.md](20260721_OPERATION_unused-image-cleanup_20260920_1.md) | 72 |
| `TASK-20260722-AREA-IMAGE-REPLACEMENT-AUTOMATION-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 22行目 | [20260722_CREATE_area-image-replacement_20260920_1.md](20260722_CREATE_area-image-replacement_20260920_1.md) | 67 |
| `TASK-20260722-DATASET-BASE-REGISTRATION-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 23行目 | [20260722_MODIFY_dataset-base-registration_20260920_1.md](20260722_MODIFY_dataset-base-registration_20260920_1.md) | 69 |
| `TASK-20260722-HOTEL-PHASE-RUNBOOK-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 44行目 | [20260722_MODIFY_hotel-phase-runbook_20260920_1.md](20260722_MODIFY_hotel-phase-runbook_20260920_1.md) | 66 |
| `TASK-20260722-REMOVE-IMAGE-RIGHTS-RULES-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 45行目 | [20260722_MODIFY_image-rule-retirement_20260920_1.md](20260722_MODIFY_image-rule-retirement_20260920_1.md) | 68 |
| `TASK-20260722-SEO-HOST-BOUNDARY-RECORD-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 25行目 | [20260722_MODIFY_direct-host-index-control_20260920_1.md](20260722_MODIFY_direct-host-index-control_20260920_1.md) | 71 |
| `TASK-20260722-SPECIAL-PAGE-RETIREMENT-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 24行目 | [20260722_MODIFY_special-page-retirement_20260920_1.md](20260722_MODIFY_special-page-retirement_20260920_1.md) | 70 |
| `TASK-20260723-HOTEL-DIRECT-TEXT-ROUTE-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 43行目 | [20260723_CREATE_hotel-direct-text_20260920_1.md](20260723_CREATE_hotel-direct-text_20260920_1.md) | 65 |
| `TASK-20260723-HOTEL-IMAGE-ASSET-MANAGEMENT-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 40行目 | [20260723_MODIFY_hotel-image-management_20260920_1.md](20260723_MODIFY_hotel-image-management_20260920_1.md) | 62 |
| `TASK-20260723-HOTEL-IMAGE-BULK-NORMALIZATION-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 20行目 | [20260723_MODIFY_hotel-image-bulk-normalization_20260920_1.md](20260723_MODIFY_hotel-image-bulk-normalization_20260920_1.md) | 57 |
| `TASK-20260723-HOTEL-IMAGE-GITHUB-SYNC-002` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 19行目 | [20260723_MODIFY_hotel-image-bulk-normalization_20260920_2.md](20260723_MODIFY_hotel-image-bulk-normalization_20260920_2.md) | 56 |
| `TASK-20260723-HOTEL-IMAGE-RENDER-OPTIMIZATION-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 37行目 | [20260723_MODIFY_hotel-image-renderer_20260920_1.md](20260723_MODIFY_hotel-image-renderer_20260920_1.md) | 58 |
| `TASK-20260723-HOTEL-INPUT-REPAIR-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 39行目 | [20260723_PROBLEM_hotel-input-repair_20260920_1.md](20260723_PROBLEM_hotel-input-repair_20260920_1.md) | 60 |
| `TASK-20260723-HOTEL-LEGACY-NORMALIZATION-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 38行目 | [20260723_MODIFY_hotel-legacy-text-normalization_20260920_1.md](20260723_MODIFY_hotel-legacy-text-normalization_20260920_1.md) | 59 |
| `TASK-20260723-HOTEL-LEGACY-TEXT-MIGRATION-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 42行目 | [20260723_CREATE_hotel-legacy-converter_20260920_1.md](20260723_CREATE_hotel-legacy-converter_20260920_1.md) | 64 |
| `TASK-20260723-HOTEL-SPARSE-SELF-TEST-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 41行目 | [20260723_MODIFY_hotel-sparse-text-tests_20260920_1.md](20260723_MODIFY_hotel-sparse-text-tests_20260920_1.md) | 63 |
| `TASK-20260723-HOTEL-WORK-GITHUB-SYNC-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 21行目 | [20260723_OPERATION_hotel-tooling-publication_20260920_1.md](20260723_OPERATION_hotel-tooling-publication_20260920_1.md) | 61 |
| `TASK-20260724-ACCUMULATED-HOTEL-GITHUB-SYNC-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 32行目 | [20260724_OPERATION_hotel-accumulated-publication_20260920_1.md](20260724_OPERATION_hotel-accumulated-publication_20260920_1.md) | 51 |
| `TASK-20260724-BBPARK-HOTEL-RETIRE-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 34行目 | [20260724_MODIFY_bbpark-retirement_20260920_1.md](20260724_MODIFY_bbpark-retirement_20260920_1.md) | 53 |
| `TASK-20260724-HOTEL-IMAGE1-WIDER-CLEAN-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 33行目 | [20260724_MODIFY_hotel-wide-images_20260920_1.md](20260724_MODIFY_hotel-wide-images_20260920_1.md) | 52 |
| `TASK-20260724-HOTEL-TERMINAL-CTA-IMAGE-SPEC-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 35行目 | [20260724_MODIFY_hotel-terminal-cta_20260920_1.md](20260724_MODIFY_hotel-terminal-cta_20260920_1.md) | 54 |
| `TASK-20260724-HOTEL-THREE-PAGE-PUBLISH-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 36行目 | [20260724_CREATE_hotel-three-page-publication_20260920_1.md](20260724_CREATE_hotel-three-page-publication_20260920_1.md) | 55 |
| `TASK-20260725-CURRENT-CHANGES-GITHUB-SYNC-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 27行目 | [20260725_OPERATION_july25-accumulated-publication_20260920_1.md](20260725_OPERATION_july25-accumulated-publication_20260920_1.md) | 46 |
| `TASK-20260725-CURRENT-CHANGES-GITHUB-SYNC-002` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 17行目 | [20260725_MODIFY_instruction-hierarchy_20260920_3.md](20260725_MODIFY_instruction-hierarchy_20260920_3.md) | 44 |
| `TASK-20260725-FC2-LINK-PRODUCTION-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 29行目 | [20260725_MODIFY_fc2-links_20260920_1.md](20260725_MODIFY_fc2-links_20260920_1.md) | 48 |
| `TASK-20260725-HOTEL-H1-COLOR-CONSISTENCY-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 31行目 | [20260725_PROBLEM_hotel-h1-color_20260920_1.md](20260725_PROBLEM_hotel-h1-color_20260920_1.md) | 50 |
| `TASK-20260725-HOTEL-H1-GITHUB-SYNC-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 30行目 | [20260725_PROBLEM_hotel-h1-color_20260920_2.md](20260725_PROBLEM_hotel-h1-color_20260920_2.md) | 49 |
| `TASK-20260725-INSTRUCTION-HIERARCHY-REMEDIATION-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 16行目 | [20260725_MODIFY_instruction-hierarchy_20260920_2.md](20260725_MODIFY_instruction-hierarchy_20260920_2.md) | 43 |
| `TASK-20260725-ROOT-AGENTS-CONSOLIDATION-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 18行目 | [20260725_MODIFY_instruction-hierarchy_20260920_1.md](20260725_MODIFY_instruction-hierarchy_20260920_1.md) | 45 |
| `TASK-20260725-SITEMAP-LASTMOD-RELIABILITY-001` | [TASK_LOG_2026_07_21_31.md](../履歴/TASK_LOG_2026_07_21_31.md) 28行目 | [20260725_PROBLEM_sitemap-lastmod_20260920_1.md](20260725_PROBLEM_sitemap-lastmod_20260920_1.md) | 47 |
| `TASK-20260804-FSG-CANDY-WORK-ROUTING-RELOCATION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 54行目 | [20260804_MODIFY_work-routing-location_20260920_1.md](20260804_MODIFY_work-routing-location_20260920_1.md) | 42 |
| `TASK-20260804-INDEX-BANNER-WIDTH-FIX-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 52行目 | [20260804_PROBLEM_index-banner-width_20260920_1.md](20260804_PROBLEM_index-banner-width_20260920_1.md) | 40 |
| `TASK-20260804-INDEX-SECTION-IMAGES-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 53行目 | [20260804_MODIFY_index-section-images_20260920_1.md](20260804_MODIFY_index-section-images_20260920_1.md) | 41 |
| `TASK-20260805-CANDY-LOCAL-ROUTING-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 51行目 | [20260804_MODIFY_work-routing-location_20260920_2.md](20260804_MODIFY_work-routing-location_20260920_2.md) | 39 |
| `TASK-20260806-AREA-4PAGE-PUBLICATION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 48行目 | [20260806_CREATE_four-area-publication_20260920_1.md](20260806_CREATE_four-area-publication_20260920_1.md) | 37 |
| `TASK-20260806-RENEWAL-ENTRY-CONTRACT-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 50行目 | [20260806_MODIFY_renewal-entry-contract_20260920_1.md](20260806_MODIFY_renewal-entry-contract_20260920_1.md) | 38 |
| `TASK-20260806-RENEWAL-ENTRY-CONTRACT-002` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 49行目 | [20260806_MODIFY_renewal-entry-contract_20260920_2.md](20260806_MODIFY_renewal-entry-contract_20260920_2.md) | 旧予約一覧に該当行なし |
| `TASK-20260808-MANAGEMENT-RESPONSIBILITY-REMEDIATION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 47行目 | [20260808_MODIFY_management-responsibility_20260920_1.md](20260808_MODIFY_management-responsibility_20260920_1.md) | 36 |
| `TASK-20260812-CANDY-MANAGEMENT-SYSTEM-REBUILD-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 46行目 | [20260812_MODIFY_management-system-rebuild_20260920_1.md](20260812_MODIFY_management-system-rebuild_20260920_1.md) | 35 |
| `TASK-20260813-DATASET-BASE-GROUP-TEST-PATH-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 45行目 | [20260813_PROBLEM_test-dataset-path_20260920_1.md](20260813_PROBLEM_test-dataset-path_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260814-MANAGEMENT-SYSTEM-FINAL-CORRECTION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 43行目 | [20260814_MODIFY_management-system-repair_20260920_2.md](20260814_MODIFY_management-system-repair_20260920_2.md) | 旧予約一覧に該当行なし |
| `TASK-20260814-MANAGEMENT-SYSTEM-REPAIR-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 44行目 | [20260814_MODIFY_management-system-repair_20260920_1.md](20260814_MODIFY_management-system-repair_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260814-MEMBER-TECHNICAL-REFERENCE-AUDIT-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 42行目 | [20260814_MODIFY_management-system-repair_20260920_3.md](20260814_MODIFY_management-system-repair_20260920_3.md) | 旧予約一覧に該当行なし |
| `TASK-20260815-BREADCRUMB-CLOSURE-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 39行目 | [20260815_PROBLEM_breadcrumb-closure_20260920_1.md](20260815_PROBLEM_breadcrumb-closure_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260815-BREADCRUMB-SYNC-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 40行目 | [20260815_PROBLEM_breadcrumb-detail-sync_20260920_1.md](20260815_PROBLEM_breadcrumb-detail-sync_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260815-GIRLS-PROFILE-SEO-PLAN-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 41行目 | [20260815_PROBLEM_girls-profile-seo_20260920_1.md](20260815_PROBLEM_girls-profile-seo_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260815-GITHUB-PUBLISH-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 38行目 | [20260815_OPERATION_aug15-github-publication_20260920_1.md](20260815_OPERATION_aug15-github-publication_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260816-GIRLS-INVALID-NO-RECORD-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 37行目 | [20260816_PROBLEM_girls-invalid-number_20260920_1.md](20260816_PROBLEM_girls-invalid-number_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260816-GIRLS-PROFILE-SEO-IMPLEMENTATION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 35行目 | [20260815_PROBLEM_girls-profile-seo_20260920_2.md](20260815_PROBLEM_girls-profile-seo_20260920_2.md) | 旧予約一覧に該当行なし |
| `TASK-20260816-RECORD-HISTORY-STRUCTURE-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 36行目 | [20260816_MODIFY_record-history-structure_20260920_1.md](20260816_MODIFY_record-history-structure_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260817-INTERNAL-PATH-ACCESS-CONTROL-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 34行目 | [20260817_PROBLEM_internal-path-access_20260920_1.md](20260817_PROBLEM_internal-path-access_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260817-INTERNAL-PATH-ACCESS-DEPLOY-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 33行目 | [20260817_PROBLEM_internal-path-access_20260920_2.md](20260817_PROBLEM_internal-path-access_20260920_2.md) | 旧予約一覧に該当行なし |
| `TASK-20260817-INTERNAL-PATH-PRODUCTION-CONFIRMATION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 32行目 | [20260817_PROBLEM_internal-path-access_20260920_3.md](20260817_PROBLEM_internal-path-access_20260920_3.md) | 旧予約一覧に該当行なし |
| `TASK-20260817-MEMBER-DEVELOPMENT-ISOLATION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 31行目 | [20260817_PROBLEM_member-development-isolation_20260920_1.md](20260817_PROBLEM_member-development-isolation_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260818-CREATE-RETIREMENT-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 26行目 | [20260818_MODIFY_create-retirement_20260920_1.md](20260818_MODIFY_create-retirement_20260920_1.md) | 30 |
| `TASK-20260818-GIRL-INFORMATION-MANAGEMENT-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 27行目 | [20260818_MODIFY_girl-information_20260920_1.md](20260818_MODIFY_girl-information_20260920_1.md) | 31 |
| `TASK-20260818-HOTEL-IMAGE-MYPAGE-LOG-CLEANUP-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 28行目 | [20260818_OPERATION_hotel-mypage-cleanup-publication_20260920_1.md](20260818_OPERATION_hotel-mypage-cleanup-publication_20260920_1.md) | 32 |
| `TASK-20260818-HOTEL-UNPUBLISHED-PUBLIC-COPY-RULE-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 29行目 | [20260818_MODIFY_hotel-unpublished-image-rule_20260920_1.md](20260818_MODIFY_hotel-unpublished-image-rule_20260920_1.md) | 33 |
| `TASK-20260818-LOCAL-RESIDUE-CLEANUP-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 24行目 | [20260818_OPERATION_local-residue-cleanup_20260920_1.md](20260818_OPERATION_local-residue-cleanup_20260920_1.md) | 28 |
| `TASK-20260818-SERVER-LOCAL-RECONCILIATION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 25行目 | [20260818_MODIFY_server-local-reconciliation_20260920_1.md](20260818_MODIFY_server-local-reconciliation_20260920_1.md) | 29 |
| `TASK-20260818-UNUSED-GIT-DATA-CLEANUP-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 30行目 | [20260818_OPERATION_unused-git-data_20260920_1.md](20260818_OPERATION_unused-git-data_20260920_1.md) | 34 |
| `TASK-20260819-DEPLOY-EXCLUSION-MOVIE-RECOVERY-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 20行目 | [20260819_MODIFY_deploy-exclusions-movies_20260920_1.md](20260819_MODIFY_deploy-exclusions-movies_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260819-EXPECTED-EXCEPTION-CLASSIFICATION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 21行目 | [20260819_PROBLEM_expected-exceptions_20260920_1.md](20260819_PROBLEM_expected-exceptions_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260819-FINAL-SEO-REMEDIATION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 17行目 | [20260819_PROBLEM_final-seo-remediation_20260920_1.md](20260819_PROBLEM_final-seo-remediation_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260819-GIRLS-INVALID-NO-FIX-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 23行目 | [20260816_PROBLEM_girls-invalid-number_20260920_2.md](20260816_PROBLEM_girls-invalid-number_20260920_2.md) | 旧予約一覧に該当行なし |
| `TASK-20260819-MOVIE-IFRAME-INVALID-INPUT-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 22行目 | [20260819_PROBLEM_movie-invalid-input_20260920_1.md](20260819_PROBLEM_movie-invalid-input_20260920_1.md) | 旧予約一覧に該当行なし |
| `TASK-20260819-STALE-ACME-TOKEN-CLEANUP-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 19行目 | [20260819_MODIFY_stale-acme-tokens_20260920_1.md](20260819_MODIFY_stale-acme-tokens_20260920_1.md) | 27 |
| `TASK-20260819-UNUSED-ASSET-BROKEN-LINK-FIX-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 18行目 | [20260819_PROBLEM_unused-assets-broken-links_20260920_1.md](20260819_PROBLEM_unused-assets-broken-links_20260920_1.md) | 26 |
| `TASK-20260820-GITHUB-PUBLICATION-STATE-RECONCILIATION-001` | [TASK_LOG_2026_08.md](../履歴/TASK_LOG_2026_08.md) 16行目 | [20260820_OPERATION_github-publication-reconciliation_20260920_1.md](20260820_OPERATION_github-publication-reconciliation_20260920_1.md) | 旧予約一覧に該当行なし |

**旧台帳29案件・3分類一覧の全件対応**

| 旧Case ID | 新しい案件概要 | 旧分類一覧 |
|---|---|---|
| `CANDY-FINAL-SEO-REMEDIATION-20260819` | [最終SEO監査で確定した不具合の修正](../20260819_PROBLEM_final-seo-remediation.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-UNUSED-ASSET-BROKEN-LINK-FIX-20260819` | [未使用公開資産4件の整理とリンク不整合の修正](../20260819_PROBLEM_unused-assets-broken-links.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-STALE-ACME-TOKEN-CLEANUP-20260819` | [期限切れACME検証ファイル33件の整理](../20260819_MODIFY_stale-acme-tokens.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-DEPLOY-EXCLUSION-MOVIE-RECOVERY-20260819` | [公開除外条件の修正と動画4件のGit管理復元](../20260819_MODIFY_deploy-exclusions-movies.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-EXPECTED-EXCEPTION-CLASSIFICATION-20260819` | [意図した特殊構造・同一内容の誤検知解消](../20260819_PROBLEM_expected-exceptions.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-MOVIE-IFRAME-INVALID-INPUT-20260819` | [動画iframeの不正入力応答の修正](../20260819_PROBLEM_movie-invalid-input.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-LOCAL-RESIDUE-CLEANUP-20260818` | [ローカル残存ファイル5件の整理](../20260818_OPERATION_local-residue-cleanup.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-SERVER-LOCAL-RECONCILIATION-20260818` | [サーバーとローカルのファイル照合・復元](../20260818_MODIFY_server-local-reconciliation.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-CREATE-RETIREMENT-20260818` | [旧ページ生成機能createの廃止](../20260818_MODIFY_create-retirement.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-GIRL-INFORMATION-MANAGEMENT-20260818` | [女性情報・公開画像状態の管理整備](../20260818_MODIFY_girl-information.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-HOTEL-UNPUBLISHED-PUBLIC-COPY-REMOVAL-20260818` | [未公開ホテル画像の公開コピー96枚の削除](../20260818_OPERATION_hotel-unpublished-image-removal.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-MYPAGE-DEBUG-LOG-REMOVAL-20260818` | [mypageのCookieデバッグ記録の停止](../20260818_PROBLEM_mypage-debug-log.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-HOTEL-UNPUBLISHED-PUBLIC-COPY-20260818` | [未公開ホテル画像の保持・公開規則の整備](../20260818_MODIFY_hotel-unpublished-image-rule.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-UNUSED-GIT-DATA-CLEANUP-20260818` | [未使用Git管理データ55件の整理](../20260818_OPERATION_unused-git-data.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-MEMBER-DEVELOPMENT-ISOLATION-20260817` | [開発中の会員機能の公開範囲制限](../20260817_PROBLEM_member-development-isolation.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-INTERNAL-PATH-ACCESS-20260817` | [内部ディレクトリへのHTTPアクセス制御](../20260817_PROBLEM_internal-path-access.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-RECORD-HISTORY-20260816` | [相談・不具合・変更の履歴導線整備](../20260816_MODIFY_record-history-structure.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-GIRLS-INVALID-NO-20260816` | [女性番号不正時の別人物表示の修正](../20260816_PROBLEM_girls-invalid-number.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-BREADCRUMB-CLOSURE-20260815` | [パンくず全体の整合性修正](../20260815_PROBLEM_breadcrumb-closure.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-BREADCRUMB-SYNC-20260815` | [詳細6ページのパンくず同期](../20260815_PROBLEM_breadcrumb-detail-sync.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-GIRLS-SEO-20260815` | [女性プロフィールのSEO出力改善](../20260815_PROBLEM_girls-profile-seo.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-MGMT-REPAIR-20260814` | [管理体系の不整合修正と技術資料の分類](../20260814_MODIFY_management-system-repair.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-TEST-PATH-20260813` | [テスト環境の共通処理パス修正](../20260813_PROBLEM_test-dataset-path.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-MGMT-20260812` | [管理体系の再構築](../20260812_MODIFY_management-system-rebuild.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-INCIDENT-20260713` | [7月13日の事象整理と改善検討](../20260713_INVESTIGATE_july13-incident.md) | [DEFECT_RESPONSE_HISTORY.md](../履歴/DEFECT_RESPONSE_HISTORY.md) |
| `CANDY-SEO-AUDIT-20260718` | [リポジトリ全体のSEO調査](../20260718_INVESTIGATE_repository-seo-audit.md) | [CONSULTATION_HISTORY.md](../履歴/CONSULTATION_HISTORY.md) |
| `CANDY-AREA-CLASS-20260720` | [エリア入力Textの全件分類と記録](../20260718_INVESTIGATE_area-text-classification.md) | [CONSULTATION_HISTORY.md](../履歴/CONSULTATION_HISTORY.md) |
| `CANDY-HOTEL-HANDOFF-20260723` | [ホテル画像69組の統一と公開](../20260723_MODIFY_hotel-image-bulk-normalization.md) | [CHANGE_HISTORY.md](../履歴/CHANGE_HISTORY.md) |
| `CANDY-INSTRUCTION-AUDIT-20260726` | [指示書51ファイルの整合性監査](../20260726_INVESTIGATE_instruction-audit.md) | [CONSULTATION_HISTORY.md](../履歴/CONSULTATION_HISTORY.md) |

**7連絡の対応**

| 旧連絡ID | 元の行 | 統合先の進捗記録 |
|---|---|---|
| `COMM-20260718-016` | [CODEX_COMMUNICATION.md](../履歴/CODEX_COMMUNICATION.md) 17行目 | [20260720_OPERATION_accumulated-seo-production_20260920_1.md](20260720_OPERATION_accumulated-seo-production_20260920_1.md) |
| `COMM-20260716-003` | [CODEX_COMMUNICATION.md](../履歴/CODEX_COMMUNICATION.md) 23行目 | [20260716_OPERATION_legacy-file-relocation_20260920_1.md](20260716_OPERATION_legacy-file-relocation_20260920_1.md) |
| `COMM-20260723-020` | [CODEX_COMMUNICATION.md](../履歴/CODEX_COMMUNICATION.md) 24行目 | [20260723_MODIFY_hotel-image-bulk-normalization_20260920_1.md](20260723_MODIFY_hotel-image-bulk-normalization_20260920_1.md) |
| `COMM-20260716-004` | [CODEX_COMMUNICATION.md](../履歴/CODEX_COMMUNICATION.md) 25行目 | [20260722_MODIFY_image-rule-retirement_20260920_1.md](20260722_MODIFY_image-rule-retirement_20260920_1.md) |
| `COMM-20260716-002` | [CODEX_COMMUNICATION.md](../履歴/CODEX_COMMUNICATION.md) 26行目 | [20260716_MODIFY_initial-management-layout_20260920_7.md](20260716_MODIFY_initial-management-layout_20260920_7.md) |
| `COMM-20260716-011` | [CODEX_COMMUNICATION.md](../履歴/CODEX_COMMUNICATION.md) 27行目 | [20260716_MODIFY_initial-management-layout_20260920_7.md](20260716_MODIFY_initial-management-layout_20260920_7.md) |
| `COMM-20260717-015` | [CODEX_COMMUNICATION.md](../履歴/CODEX_COMMUNICATION.md) 28行目 | [20260717_PROBLEM_scripts-workspace-paths_20260920_1.md](20260717_PROBLEM_scripts-workspace-paths_20260920_1.md) |

### 結果

- 全21ファイルの扱いを記録し、115作業・29旧案件・90予約・7連絡の対応漏れとID重複がないことを確認した。新規案件として登録したホテル自動公開の不足と、今回の移行自体を含めて全概要を一覧化した。
- 案件概要の必須項目、Type・Start Dateとファイル名の一致、進捗の必須項目・状態・日付・連番、一覧の1案件1行と開始日降順、生成したローカルリンクの参照先を検証した。
- 各作業行の依頼・対応・確認結果・補足／未確認事項が対応先に保持されること、90予約の補助情報が同じ作業に保持されることを機械照合した。
- 旧資料21ファイルは変更していない。HISTORY.mdとAGENTS.mdも変更していない。

**不一致・未確認事項の引き継ぎ**

1. 旧TASK_LOG.mdは8月37件・合計113件を記載するが、実際は8月39件・合計115件。今回の移行件数は実行数を数えた結果を使用した。2026-08-20の旧照合行にある「112 prior task rows」は当時の記述として保持し、今回の件数へ書き換えていない。
2. 2026-07-18のSEO監査は本文・ログの12件とFinding Registerの11件が不一致。SEO-02がない理由は不明。元資料の値を保持し、監査案件にも差を記載した。
3. `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md`、`CANDY_AREA_TEXT_INPUT_CLASSIFICATION.md`、ホテル画像引き継ぎの`HANDOFF_README.md`等の参照先原本は移行元21ファイル内にない。存在や全文内容を確認したとは扱わず、今回取得できた台帳・作業記録・連絡の範囲を保持した。
4. PROJECT_STATUS.mdから参照される詳細バックログ、エリア制作待ち一覧、ホテル入力分類、ブログ例外、生成状態の全内容は移行元にない。参照先だけを根拠に個別不具合や現在件数を作らない。HOTEL-ACCEPTED-IMAGE-PATHは本文に具体的な未解決内容があるため独立して引き継いだ。
5. Commit・Pushの最終結果が完了報告側にあると書かれ、その報告が今回の資料にない作業は確認待ちとした。GitHub Publishedを本番確認済みに置き換えていない。
6. 旧資料内の外部・旧相対参照は当時の所在を示す文字列として保持し、新たな有効リンクと混同しない。旧資料内のリンク切れそのものは原本不変のため修正していない。

**原本不変の照合値（SHA-256）**

| 旧資料 | 移行前後一致のSHA-256 |
|---|---|
| CANDY_GIRLS_INVALID_NO_BEHAVIOR.md | `AD85883252BFD5002B7604FA7A91DF212CF4ECE7C188765E0D2F049D0C66B17F` |
| CANDY_GIRLS_PROFILE_SEO_REMEDIATION.md | `A35208C7B76DB77EFE7C5314A5AAF71E6C549BBB1B37FB6BB6CD8A580065FEDF` |
| CANDY_INTERNAL_PATH_ACCESS_CONTROL.md | `8F30CBA32793D4A4AE589FEE7619D756863211EB8C5C35CD1B172B680A767B8C` |
| CANDY_MANAGEMENT_SYSTEM_REBUILD.md | `FB61E468E09F8C85A7E5250259B76193E730FB4F612BB67F1063174AEE1F51F1` |
| CANDY_MANAGEMENT_SYSTEM_REPAIR.md | `7EA319DAE7D16EE27E880E33E61E1B9E35D58FEC878F8C89C90A313338734AC4` |
| CANDY_RECORD_HISTORY_STRUCTURE.md | `33E51379D08C1848D8523C55A1846CA02486FD08FFF325E774FF6D0A09E38822` |
| CANDY_REPOSITORY_SEO_AUDIT_2026-07-18.md | `99AF334735CD9C973C5D32B6EFE9057C9EE1623F96B81AE70DEA5FEF4D368CCC` |
| CASE_HISTORY.md | `AB8FD33A85504DCD95AAC95670AF39F8F61C73A310A3D222A8EEFD0EDCDCAA2C` |
| CASE_REGISTRY.md | `BE3742421C003941362199CA87E6D7EF88C57FB6DD4CA6202CA96B21856B312A` |
| CHANGE_HISTORY.md | `BA6DB487F42982F1099D8178AD79D32C49DC49FF53AD61FB83947849FE974C70` |
| CODEX_COMMUNICATION.md | `077F993D92DE285933A1B5DF1412AE9066FC8E576B11D156C1008D39EC316E48` |
| CODE_STRUCTURE.md | `6FD6F04A98EE326B7278104ECABDF5C3D3A01ACD9AF23DDF7B30254287F70E69` |
| CONSULTATION_HISTORY.md | `543D30BBF69105DFAFE7D506AC1D9B9C70AAAA3C7ECD32C25E8EE43B4B249F30` |
| DEFECT_RESPONSE_HISTORY.md | `277E51884C88C1E285D6F17DF741B39B86B2FC808F4272E0427F537BF377E989` |
| PROJECT_STATUS.md | `F889F645BEE2E067294DC4175E95A144900800DCE8F302DC9D3D299721C18F8B` |
| TASK_LOG.md | `25EB13BF57B780F6417EA20140693FCAC75567AE4EFB4467DE283D27396A7339` |
| TASK_LOG_2026_07_01_20.md | `0AB36342050AC85BE46AA50D27CFC1445DB586A8ECF86A798F90AF1FB1D26A3C` |
| TASK_LOG_2026_07_21_31.md | `C1963AA14224B00D82CC1322CE526439650BEA51B90D4F5517CD3BEEE2473CDA` |
| TASK_LOG_2026_08.md | `ADEEB5889D1A33A41799FB4F13147DD0D51E2431BD044F49C6192E65273E5530` |
| TASK_RESERVATIONS.md | `3839207C68E36A380F1D66DE1C651B178C27A64F3BBE49320F33CDADDCFBA2BC` |
| 指示書監査.md | `CD96F495B924069147BAB131A76BEF65714A99C50E74AF789F4BAC40DB947418` |

## 現在

- Remaining Work: None
- Next Action: None
