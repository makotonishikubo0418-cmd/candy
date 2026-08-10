-- CANDY 会員マイページお知らせ 初期データ（任意）
SET NAMES utf8mb4;

INSERT INTO `customers_mypage_info` (`club_id`, `title`, `body`, `category`, `sort_order`, `status`)
SELECT 2,
  '会員マイページをご利用ください',
  '会員マイページでは、利用履歴の確認・女の子の評価・お気に入り登録ができます。ご不明点は店舗までお問い合わせください。',
  'お知らせ',
  0,
  1
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `customers_mypage_info` WHERE `club_id` = 2 AND `title` = '会員マイページをご利用ください'
);
