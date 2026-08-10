-- =============================================================================
-- 会員マイページ Phase4 テーブル定義（お知らせ）
-- Phase1 テーブル投入後に実行
-- =============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `customers_mypage_info` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `club_id`       TINYINT UNSIGNED NOT NULL,
  `title`         VARCHAR(255) NOT NULL,
  `body`          MEDIUMTEXT   NOT NULL,
  `category`      VARCHAR(64)  NULL DEFAULT NULL,
  `publish_from`  DATETIME     NULL DEFAULT NULL,
  `publish_to`    DATETIME     NULL DEFAULT NULL,
  `sort_order`    INT          NOT NULL DEFAULT 0,
  `status`        TINYINT      NOT NULL DEFAULT 1 COMMENT '1=公開 0=非公開',
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_info_club_status_sort` (`club_id`, `status`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員マイページお知らせ';

CREATE TABLE IF NOT EXISTS `customers_mypage_info_read` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id`   INT UNSIGNED NOT NULL,
  `info_id`     INT UNSIGNED NOT NULL,
  `read_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_info_read` (`member_id`, `info_id`),
  CONSTRAINT `fk_info_read_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_info_read_info` FOREIGN KEY (`info_id`) REFERENCES `customers_mypage_info` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='お知らせ既読';
