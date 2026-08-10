-- =============================================================================
-- FSG仕様: お気に入り女の子の出勤通知
-- =============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `customers_favorite_schedule_notices` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `club_id`     TINYINT UNSIGNED NOT NULL,
  `member_id`   INT UNSIGNED NOT NULL,
  `girls_id`    INT UNSIGNED NOT NULL,
  `schedule_date` DATE NOT NULL,
  `schedule_label` VARCHAR(64) NULL,
  `is_read`     TINYINT NOT NULL DEFAULT 0,
  `mail_sent`   TINYINT NOT NULL DEFAULT 0,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_fav_notice` (`member_id`, `girls_id`, `schedule_date`),
  KEY `idx_fav_notice_member_read` (`member_id`, `is_read`),
  CONSTRAINT `fk_fav_notice_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='お気に入り出勤通知';
