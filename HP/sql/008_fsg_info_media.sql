-- =============================================================================
-- FSG仕様: お知らせに表示日・画像・動画・HTMLコメントを追加
-- 004_phase4_member.sql 実行後に投入
-- =============================================================================

SET NAMES utf8mb4;

ALTER TABLE `customers_mypage_info`
  ADD COLUMN `display_date` DATE NULL COMMENT '一覧表示日' AFTER `category`,
  ADD COLUMN `image_url` VARCHAR(512) NULL COMMENT '画像URL' AFTER `body`,
  ADD COLUMN `video_url` VARCHAR(512) NULL COMMENT '動画URL' AFTER `image_url`,
  ADD COLUMN `html_comment` MEDIUMTEXT NULL COMMENT 'HTMLコメント' AFTER `video_url`;
