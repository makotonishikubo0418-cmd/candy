-- =============================================================================
-- 会員マイページ Phase3 テーブル定義（お気に入り）
-- 001_phase1_member.sql 実行後に投入
-- =============================================================================

SET NAMES utf8mb4;

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
