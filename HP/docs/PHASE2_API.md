# Phase2 API（利用履歴・女の子評価）

Phase1 の認証（セッション Cookie）が必要です。

## 前提

- `sql/002_phase2_member.sql` を `001_phase1_member.sql` 実行後に投入
- 利用履歴は CTI DB `tasks` を `guest_id` + `club_id=2` で参照
- `guest_id` 未連携の会員は履歴空（ログイン時に CTI 照合）

## fno=201 利用履歴一覧

**POST** `member/api.php?fno=201`

```json
{ "page": 1, "per_page": 20 }
```

**レスポンス（data）**

| キー | 説明 |
|------|------|
| items | 履歴配列 |
| total | 件数 |
| page / per_page | ページング |
| guest_linked | CTI 連携有無 |

**items 各要素**

| キー | 説明 |
|------|------|
| task_id | CTI tasks.id |
| date, start, end | 日時（start/end は 6時起点 HH:MM） |
| course | コース分数 |
| cast_id, girls_id | キャスト・掲載ID |
| girl_name, girl_no | 表示名（girls_data） |
| stat | 2=予約, 0=完了 等 |
| can_evaluate | 評価可能か（stat=0 かつ掲載中の女の子） |
| evaluation | 評価済みなら `{ rating, comment, updated_at }` |

**除外条件（CTI）**

- `end_stat IN (1,2,3)`（キャンセル・チェンジ・削除）
- `stat=0 AND end_stat=1`

## fno=202 女の子評価保存

**POST** `member/api.php?fno=202`

```json
{
  "task_id": 12345,
  "rating": 5,
  "comment": "任意コメント"
}
```

- `rating`: 1〜5
- 同一 `task_id` は再送信で更新（UPSERT）
- 完了タスク（`stat=0`）のみ評価可

**エラー例**

| status | 内容 |
|--------|------|
| -2 | 未連携・履歴なし・評価不可・バリデーション |
| -3 | 未ログイン |

## デプロイ時の注意

1. Web サーバーから CTI DB への接続（`config.php` の `MEMBER_CTI_*`）
2. `customers_girl_evaluations` テーブル未作成時は fno=202 が失敗
3. 同一 `cast_id` に複数 `girls_data` がある場合は最新 ID を使用
4. 掲載終了（`girls_data.status≠1`）の女の子は名前非表示・評価不可
