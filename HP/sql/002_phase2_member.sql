-- =============================================================================
-- 会員マイページ Phase2 テーブル定義（利用履歴は CTI tasks 参照・テーブル不要）
-- 001_phase1_member.sql 実行後に投入
-- =============================================================================

SET NAMES utf8mb4;

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
