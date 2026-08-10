-- =============================================================================
-- 会員マイページ Phase5 テーブル定義（メール・通知）
-- Phase1 テーブル投入後に実行（customers_accounts.email は Phase1 で定義済み）
-- =============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `customers_email_codes` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `club_id`     TINYINT UNSIGNED NOT NULL,
  `member_id`   INT UNSIGNED NOT NULL,
  `email`       VARCHAR(255) NOT NULL,
  `code`        CHAR(6)      NOT NULL,
  `purpose`     ENUM('email_set') NOT NULL DEFAULT 'email_set',
  `expires_at`  DATETIME     NOT NULL,
  `used_at`     DATETIME     NULL DEFAULT NULL,
  `attempts`    TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email_codes_member` (`member_id`, `purpose`, `expires_at`),
  CONSTRAINT `fk_email_codes_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='メール認証コード';

CREATE TABLE IF NOT EXISTS `customers_notification_settings` (
  `member_id`           INT UNSIGNED NOT NULL,
  `club_id`             TINYINT UNSIGNED NOT NULL,
  `notify_mypage_info`  TINYINT      NOT NULL DEFAULT 0 COMMENT '1=お知らせメールON',
  `created_at`          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`member_id`),
  KEY `idx_notify_club_info` (`club_id`, `notify_mypage_info`),
  CONSTRAINT `fk_notify_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員メール通知設定';

CREATE TABLE IF NOT EXISTS `customers_info_mail_log` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id`   INT UNSIGNED NOT NULL,
  `info_id`     INT UNSIGNED NOT NULL,
  `sent_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_info_mail` (`member_id`, `info_id`),
  KEY `idx_info_mail_info` (`info_id`),
  CONSTRAINT `fk_info_mail_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_info_mail_info` FOREIGN KEY (`info_id`) REFERENCES `customers_mypage_info` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='お知らせメール送信済み';
