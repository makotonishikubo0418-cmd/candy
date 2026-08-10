-- =============================================================================
-- 会員マイページ Phase1 テーブル定義（店舗横展開共通）
-- 対象DB: fsg_db（店舗DB）
--
-- 設計方針:
--   - テーブル名は candy 専用にしない（customers_* で全店舗共通）
--   - club_id で店舗を識別（CANDY = 2）
--   - 電話番号のユニークは (club_id, phone) … 店舗ごと別会員
-- Phase1 初回実装: candy_new（MEMBER_CLUB_ID = 2）
-- =============================================================================

SET NAMES utf8mb4;

-- -----------------------------------------------------------------------------
-- customers_accounts … 会員マスタ
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers_accounts` (
  `id`                INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `club_id`           TINYINT UNSIGNED NOT NULL COMMENT '店舗ID（girls_data.club_id と同義）',
  `guest_id`          INT UNSIGNED     NULL DEFAULT NULL COMMENT 'CTI guests.id（未紐づけ時NULL）',
  `phone`             VARCHAR(20)      NOT NULL COMMENT '主電話番号（数字のみ・ハイフンなし）',
  `password_hash`     VARCHAR(255)     NOT NULL COMMENT 'password_hash(PASSWORD_DEFAULT)',
  `nickname`          VARCHAR(64)      NOT NULL DEFAULT '' COMMENT '表示名（通り名）',
  `email`             VARCHAR(255)     NULL DEFAULT NULL COMMENT 'Phase5 メール通知用',
  `status`            ENUM('active','withdrawn') NOT NULL DEFAULT 'active',
  `terms_agreed_at`   DATETIME         NOT NULL COMMENT '利用規約同意日時',
  `privacy_agreed_at` DATETIME         NOT NULL COMMENT 'プライバシーポリシー同意日時',
  `terms_version`     VARCHAR(16)      NOT NULL DEFAULT '1.0' COMMENT '同意時の規約版（店舗別）',
  `last_login_at`     DATETIME         NULL DEFAULT NULL,
  `withdrawn_at`      DATETIME         NULL DEFAULT NULL,
  `created_at`        DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_member_club_phone` (`club_id`, `phone`),
  KEY `idx_member_club_id` (`club_id`),
  KEY `idx_member_guest_id` (`guest_id`),
  KEY `idx_member_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員マスタ（店舗横展開共通）';

-- -----------------------------------------------------------------------------
-- customers_sms_codes … SMS認証コード（Phase1: mock送信）
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers_sms_codes` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `club_id`     TINYINT UNSIGNED NOT NULL,
  `phone`       VARCHAR(20)      NOT NULL COMMENT '送信先（数字のみ）',
  `code`        CHAR(6)          NOT NULL COMMENT '6桁認証コード',
  `purpose`     ENUM('register','password_reset','phone_change') NOT NULL,
  `expires_at`  DATETIME         NOT NULL,
  `used_at`     DATETIME         NULL DEFAULT NULL,
  `attempts`    TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '検証失敗回数',
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sms_club_phone_purpose` (`club_id`, `phone`, `purpose`, `expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員SMS認証コード';

-- -----------------------------------------------------------------------------
-- customers_remember_tokens … ログイン維持（ON=30日）
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers_remember_tokens` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id`   INT UNSIGNED NOT NULL,
  `token_hash`  CHAR(64)     NOT NULL COMMENT 'sha256(生トークン)',
  `expires_at`  DATETIME     NOT NULL,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `revoked_at`  DATETIME     NULL DEFAULT NULL COMMENT 'ログアウト・PW変更・電話変更で無効化',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_remember_token_hash` (`token_hash`),
  KEY `idx_remember_member_id` (`member_id`),
  CONSTRAINT `fk_remember_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ログイン維持トークン';

-- -----------------------------------------------------------------------------
-- customers_sessions … サーバー側セッション
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers_sessions` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id`     INT UNSIGNED NOT NULL,
  `session_token` CHAR(64)     NOT NULL COMMENT 'sha256(生トークン)',
  `expires_at`    DATETIME     NOT NULL,
  `ip_address`    VARCHAR(45)  NULL DEFAULT NULL,
  `user_agent`    VARCHAR(512) NULL DEFAULT NULL,
  `revoked_at`    DATETIME     NULL DEFAULT NULL,
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_session_token` (`session_token`),
  KEY `idx_session_member_id` (`member_id`),
  CONSTRAINT `fk_session_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員セッション';

-- -----------------------------------------------------------------------------
-- customers_audit_logs … 監査ログ
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers_audit_logs` (
  `id`          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `club_id`     TINYINT UNSIGNED NOT NULL,
  `member_id`   INT UNSIGNED     NULL DEFAULT NULL,
  `action`      VARCHAR(64)      NOT NULL COMMENT 'login, logout, register, withdraw, guest_link, phone_change 等',
  `detail`      TEXT             NULL,
  `ip_address`  VARCHAR(45)      NULL DEFAULT NULL,
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_club_id` (`club_id`),
  KEY `idx_audit_member_id` (`member_id`),
  KEY `idx_audit_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員操作監査ログ';

-- -----------------------------------------------------------------------------
-- customers_legal_documents … 利用規約・PP（店舗別・横展開）
-- Phase1: CANDY 用レコードを INSERT して運用開始
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers_legal_documents` (
  `id`           INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `club_id`      TINYINT UNSIGNED NOT NULL,
  `doc_type`     ENUM('terms','privacy') NOT NULL,
  `version`      VARCHAR(16)      NOT NULL DEFAULT '1.0',
  `title`        VARCHAR(255)     NOT NULL DEFAULT '',
  `body`         MEDIUMTEXT       NOT NULL,
  `effective_at` DATETIME         NOT NULL COMMENT '施行日',
  `status`       TINYINT          NOT NULL DEFAULT 1 COMMENT '1=現行 0=旧版',
  `created_at`   DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_legal_club_type_status` (`club_id`, `doc_type`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='店舗別 利用規約・プライバシーポリシー';
