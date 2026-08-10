-- =============================================================================
-- FSG仕様: 女の子評価を6項目化し、登録後修正不可に対応
-- 002_phase2_member.sql 実行後に投入
-- rating = 総合満足度（既存列を流用）
-- =============================================================================

SET NAMES utf8mb4;

ALTER TABLE `customers_girl_evaluations`
  ADD COLUMN `rating_service` TINYINT UNSIGNED NULL COMMENT '接客対応 1-5' AFTER `rating`,
  ADD COLUMN `rating_friendliness` TINYINT UNSIGNED NULL COMMENT '親しみやすさ 1-5' AFTER `rating_service`,
  ADD COLUMN `rating_cleanliness` TINYINT UNSIGNED NULL COMMENT '清潔感 1-5' AFTER `rating_friendliness`,
  ADD COLUMN `rating_match` TINYINT UNSIGNED NULL COMMENT '掲載情報との一致度 1-5' AFTER `rating_cleanliness`,
  ADD COLUMN `rating_repeat` TINYINT UNSIGNED NULL COMMENT 'リピート希望度 1-5' AFTER `rating_match`;
