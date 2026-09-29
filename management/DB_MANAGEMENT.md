# DB管理

更新日: 2026-09-19

## 1. 管理対象・原則

### 1.1 目的

本書は、Candy本番DBの構造、依存関係、運用、および変更時の確認基準を管理する。

変動する件数・容量・検査結果は固定値として管理せず、必要時にREAD-ONLYで確認する。


### 1.2 管理対象DB

    Database:
        fsg_db

    Candy店舗Scope:
        CLUBID = 2
        MEMBER_CLUB_ID = 2


### 1.3 関連DB

`cti`:

    Candy会員機能から一部データを参照する。
    本書ではCandyとの依存関係だけを管理する。
    CTI DB自体のSchema・Backup・運用・変更管理は対象外。

`firststar_55810`:

    現行Candyからの利用は確認されていない。
    用途確定まで削除・変更しない。


## 2. 本番DB・接続

### 2.1 DB基本情報

    DBMS:
        MySQL

    Version:
        5.6.36

    Database:
        fsg_db

    Host:
        o4042s-134.kagoya.net

    Port:
        3306


### 2.2 Application DB User

    User:
        firststar

    fsg_db:
        ALL PRIVILEGES

    Global:
        FILE

`firststar` は書込権限を持つため、調査用途では9章のREAD-ONLY経路を使用する。


### 2.3 Candy接続経路

    Candy
      ↓
    includefile/dataset_base.php
      ↓
    /firststar/public_html/group/control/includefile/incfiles_vv.php
      ↓
    /firststar/public_html/group/control/includefile/Sql.php
      ↓
    /firststar/public_html/group/control/includefile/Database.php
      ↓
    mysqli_connect()
      ↓
    fsg_db

接続設定:

    /firststar/public_html/group/control/includefile/Sql.php

接続実装:

    /firststar/public_html/group/control/includefile/Database.php


## 3. Schema・Relation

### 3.1 Physical Foreign Key

    cast_mypage_common_info.id
        → cast_mypage_common_info_read.info_id

    customers_accounts.id
        → customers_email_codes.member_id
        → customers_favorites.member_id
        → customers_favorite_schedule_notices.member_id
        → customers_girl_evaluations.member_id
        → customers_info_mail_log.member_id
        → customers_mypage_info_read.member_id
        → customers_notification_settings.member_id
        → customers_phones.member_id
        → customers_remember_tokens.member_id
        → customers_sessions.member_id

    customers_mypage_info.id
        → customers_info_mail_log.info_id
        → customers_mypage_info_read.info_id


### 3.2 Logical Relation

Foreign Keyが設定されていない主要Relation:

    customers_favorites.girls_id
        → girls_data.id

    customers_girl_evaluations.girls_id
        → girls_data.id


### 3.3 Cross-DB Relation

    fsg_db.customers_accounts.guest_id
        → cti.guests.id

    fsg_db.customers_girl_evaluations.task_id
        → cti.tasks.id

DBを跨ぐためPhysical Foreign Keyは存在しない。


### 3.4 UPDATE・DELETE影響

確認済みPhysical Foreign Key:

    UPDATE:
        RESTRICT

    DELETE:
        CASCADE

`customers_accounts` の物理削除は複数の会員関連Tableへ連鎖する。

MyISAMではForeign Key制約が機能しないため、更新・削除時はApplication側のRelationも確認する。


## 4. DBの重要特性

### 4.1 Engine・Charset・Collation

`fsg_db` はMyISAMとInnoDB、utf8とutf8mb4が混在する。

主要構成:

    Legacy Table:
        主に MyISAM
        utf8_general_ci

    customers_*:
        主に InnoDB
        utf8mb4_general_ci

Database Default:

    character_set_database:
        utf8

    collation_database:
        utf8_general_ci

DB変更時はDatabase Defaultだけで判断せず、対象Table・Column自身のEngine・Charset・Collationを確認する。


### 4.2 SQL・Transaction

    sql_mode:
        NO_ENGINE_SUBSTITUTION

    system_time_zone:
        JST

    transaction_isolation:
        REPEATABLE-READ

    autocommit:
        1

`Database.php` にはCOMMIT / ROLLBACK処理が存在するが、Candy全体が一律にTransaction管理されているとは扱わない。


### 4.3 Connection Character Set

確認済み接続設定:

    character_set_server:
        binary

    collation_server:
        binary

    character_set_client:
        binary

    character_set_connection:
        binary

    character_set_results:
        binary

    collation_connection:
        binary

    max_allowed_packet:
        1,048,576 bytes

`Database.php` 内の `SET NAMES` 等は有効化されていない。

文字化け・文字コード変換・SQL送受信を調査する場合は、対象TableのCharsetと接続時の文字コードを分けて確認する。


## 5. Schema・Data Integrity管理

### 5.1 Schemaの基準

`fsg_db` 全体を完全再構築できる単一のMigration管理基盤は確認されていない。

Git管理下のCREATE / ALTER SQLも、`fsg_db` 全体の完全なSchema正本とは扱わない。

管理上の基準:

    現在Schema:
        本番DBをREAD-ONLYで確認

    変更定義:
        Git管理SQL / Application Source

    復旧:
        Backup / Export

SQLファイルが存在するだけで、本番適用済みとは判断しない。


### 5.2 Schema変更時の確認

変更対象について必要な範囲だけ確認する。

    Column定義
    CREATE定義
    PRIMARY KEY
    INDEX / UNIQUE
    Foreign Key / Logical Relation
    UPDATE / DELETE影響
    Engine
    Charset / Collation
    使用Program

対象外Tableまで不要な全件比較を行わない。


### 5.3 Data Integrity

必要時に以下をREAD-ONLYで確認する。

    Account → CTI Guest 孤児
    Favorite → Girl 孤児
    Favorite / Girl club_id 不一致
    Evaluation → Girl 孤児
    Evaluation / Girl club_id 不一致
    Evaluation → CTI Task 孤児
    Evaluation / Task club_id 不一致

検査結果は固定値として本書に保持せず、必要な調査・変更時に再取得する。


### 5.4 再構築

現在確認済みのGit管理SQLだけで `fsg_db` 全体を完全再構築できるとは扱わない。

再構築には少なくとも以下が必要。

    現行Schema
    Data Backup
    Git管理SQL
    Application Source
    DB Connection設定
    必要な初期Data


## 6. Backup・Restore・Rollback

### 6.1 Backup

確認済み手段:

    phpMyAdmin Export
    /usr/bin/mysqldump

`fsg_db` はInnoDBとMyISAMが混在するため、`--single-transaction` だけでDB全体の完全な整合性Backupになるとは扱わない。

本番変更前に、変更内容に適した対象TableまたはDB全体のBackup方法を確定する。


### 6.2 Restore

確認済み手段:

    phpMyAdmin Import
    /usr/bin/mysql

本番Restoreは未実証。

Restore手段が存在することと、本番で復旧可能であることを同一扱いしない。


### 6.3 Rollback

自動Migration Ledgerは確認されていない。

本番変更前に、変更内容に応じてRollback方法を確定する。

    逆SQL
    対象Table Restore
    DB Restore
    Program Rollback

Rollback方法を確定できない変更は実施しない。


## 7. READ-ONLY確認・本番DB変更

### 7.1 READ-ONLY基本方針

本番DBの調査・確認は専用READ-ONLY Launcherを使用する。

正式配置先:

    C:\Codex\FSG\Candy\management\script\Invoke-LiveDbRead.ps1

Launcherは専用SSH経路からServer Wrapperを経由し、許可されたDB参照Actionだけを実行する。

任意Shell、任意SQL、DB変更操作は許可しない。


### 7.2 許可Action

    SelfTest
    Status
    Tables
    Schema
    Create
    Indexes
    Count
    Rows

`Rows`:

    Limit:
        1 ～ 200

    Offset:
        0 ～ 1,000,000

必要なTable・件数だけ取得し、不要な個人情報・認証情報・秘密情報を取得・記録しない。


### 7.3 接続確認

Launcherは実行前に接続先Identityを確認する。

期待値:

    DATABASE():
        fsg_db

    CURRENT_USER():
        firststar@153.127.232.193

    Host:
        o4042s-134.kagoya.net

    Port:
        3306

一致しない場合は正常扱いしない。


### 7.4 READ-ONLY基盤の確認状態

2026-09-19実機確認済み。

    SelfTest:
        EXITCODE=0

    Status:
        EXITCODE=0

    Tables:
        EXITCODE=0

    Schema:
        EXITCODE=0

    Create:
        EXITCODE=0

    Indexes:
        EXITCODE=0

    Count:
        EXITCODE=0

    Rows:
        EXITCODE=0

異常系確認:

    存在しないTable:
        EXITCODE=1

拒否確認:

    任意Command:
        DENIED

    書込Command:
        DENIED

正常処理とDB処理失敗をEXITCODEで区別できることを確認済み。

`Rows` の日本語文字化けはLauncherのSSH出力文字コード処理を修正し、正常表示を確認済み。


### 7.5 本番DB変更条件

本番DB変更前に以下を確定する。

    変更対象
    現在Schema
    Relation・削除影響
    Applicationへの影響
    Backup
    Rollback

変更後は、変更内容に応じて以下を確認する。

    Schema
    必要なData
    Application動作
    関連する連携・定期処理・Log

確認していない内容を正常・完了として扱わない。