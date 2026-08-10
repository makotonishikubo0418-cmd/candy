-- =============================================================================
-- 会員マイページ Phase6 会員情報仕様（電話複数・誕生日・プロフィール拡張）
-- Phase1〜5 投入後に実行
-- =============================================================================

SET NAMES utf8mb4;

-- 誕生日（任意）
ALTER TABLE `customers_accounts`
  ADD COLUMN `birthday` DATE NULL DEFAULT NULL COMMENT '生年月日（任意）' AFTER `nickname`;

-- 電話番号（最大3件・主電話1件）
CREATE TABLE IF NOT EXISTS `customers_phones` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `club_id`     TINYINT UNSIGNED NOT NULL,
  `member_id`   INT UNSIGNED     NOT NULL,
  `phone`       VARCHAR(20)      NOT NULL COMMENT '数字のみ・ハイフンなし',
  `is_primary`  TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '1=主電話（ログイン用）',
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_member_phone_club` (`club_id`, `phone`),
  KEY `idx_member_phones_member` (`member_id`, `is_primary`),
  CONSTRAINT `fk_member_phones_member` FOREIGN KEY (`member_id`) REFERENCES `customers_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員電話番号（最大3件）';

-- 既存会員の主電話を移行
INSERT INTO `customers_phones` (`club_id`, `member_id`, `phone`, `is_primary`)
SELECT `club_id`, `id`, `phone`, 1
FROM `customers_accounts`
WHERE `phone` != ''
  AND NOT EXISTS (
    SELECT 1 FROM `customers_phones` p WHERE p.member_id = customers_accounts.id AND p.is_primary = 1
  );

-- SMS purpose に phone_add を追加
ALTER TABLE `customers_sms_codes`
  MODIFY COLUMN `purpose` ENUM('register','password_reset','phone_change','phone_add') NOT NULL;
