-- =============================================================================
-- 会員マイページ Phase2以降 テーブル定義（店舗横展開共通）
-- Phase2 単体投入は sql/002_phase2_member.sql を使用（評価テーブルのみ）
-- 対象DB: fsg_db
-- =============================================================================

SET NAMES utf8mb4;

-- -----------------------------------------------------------------------------
-- Phase2: 女の子評価（cast_work_evaluations とは別＝会員→女の子）
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers_girl_evaluations` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `club_id`     TINYINT UNSIGNED NOT NULL,
  `member_id`   INT UNSIGNED NOT NULL,
  `girls_id`    INT UNSIGNED NOT NULL COMMENT 'girls_data.id',
  `cast_id`     INT UNSIGNED NOT NULL COMMENT 'cast_mast.id',
  `task_id`     INT UNSIGNED NOT NULL COMMENT 'CTI tasks.id',
  `rating`      TINYINT UNSIGNED NOT NULL COMMENT '1-5',
  `comment`     TEXT         NULL,
  `status`      TINYINT      NOT NULL DEFAULT 1 COMMENT '1=有効 0=削除',
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_eval_member_task` (`member_id`, `task_id`),
  KEY `idx_eval_club_id` (`club_id`),
  KEY `idx_eval_girls_id` (`girls_id`),
  CONSTRAINT `fk_eval_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員による女の子評価';

-- -----------------------------------------------------------------------------
-- Phase3: お気に入り
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers_favorites` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `club_id`     TINYINT UNSIGNED NOT NULL,
  `member_id`   INT UNSIGNED NOT NULL,
  `girls_id`    INT UNSIGNED NOT NULL,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_fav_member_girls` (`member_id`, `girls_id`),
  KEY `idx_fav_club_id` (`club_id`),
  CONSTRAINT `fk_fav_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員お気に入り女の子';

-- -----------------------------------------------------------------------------
-- Phase4: お知らめ（control 管理画面・shop_mypage_info とは別）
-- -----------------------------------------------------------------------------
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員マイページお知らめ';

CREATE TABLE IF NOT EXISTS `customers_mypage_info_read` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id`   INT UNSIGNED NOT NULL,
  `info_id`     INT UNSIGNED NOT NULL,
  `read_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_info_read` (`member_id`, `info_id`),
  CONSTRAINT `fk_info_read_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_info_read_info` FOREIGN KEY (`info_id`) REFERENCES `customers_mypage_info` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='お知らめ既読';
