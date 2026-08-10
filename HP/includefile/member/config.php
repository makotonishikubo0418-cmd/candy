<?php

/**

 * 会員マイページ — 店舗設定（CANDY 初回実装）

 * 横展開時は MEMBER_CLUB_ID / MEMBER_COOKIE_PREFIX のみ変更

 */

define('MEMBER_CLUB_ID', 2);

define('MEMBER_CLUB_NAME', 'CANDY');

define('MEMBER_COOKIE_PREFIX', 'candy');



define('MEMBER_SESSION_TTL', 86400);       // セッション有効秒（24h）

define('MEMBER_REMEMBER_DAYS', 30);

define('MEMBER_SMS_TTL', 600);             // SMSコード有効秒（10分）

define('MEMBER_SMS_MAX_ATTEMPTS', 5);

// 認証情報は config.sms.local.php に記載（リポジトリに載せない）
$__memberSmsLocal = __DIR__ . '/config.sms.local.php';
if (is_file($__memberSmsLocal)) {
    require $__memberSmsLocal;
}

// Media SMS CONSOLE（個別送信）。true=画面にコード表示のみ / false=実SMS送信
if (!defined('MEMBER_SMS_MOCK')) {
    define('MEMBER_SMS_MOCK', false);
}
if (!defined('MEMBER_SMS_API_URL')) {
    define('MEMBER_SMS_API_URL', 'https://www.sms-console.jp/api/');
}
if (!defined('MEMBER_SMS_USERNAME')) {
    define('MEMBER_SMS_USERNAME', '');
}
if (!defined('MEMBER_SMS_PASSWORD')) {
    define('MEMBER_SMS_PASSWORD', '');
}
if (!defined('MEMBER_SMS_BODY_TEMPLATE')) {
    define(
        'MEMBER_SMS_BODY_TEMPLATE',
        "【CANDY】認証コードは {code} です。\n認証はこちら: {url}\n有効期限は10分です。心当たりがない場合は破棄してください。"
    );
}

define('MEMBER_PASSWORD_MIN_LEN', 8);
define('MEMBER_PASSWORD_MAX_LEN', 20);

define('MEMBER_RESET_TTL', 900);           // PW再設定トークン（15分）



define('MEMBER_EMAIL_TTL', 600);           // メール認証コード有効秒（10分）

define('MEMBER_EMAIL_MAX_ATTEMPTS', 5);

define('MEMBER_MAIL_MOCK', true);          // true=ログファイル出力 / false=mb_send_mail

define('MEMBER_MAIL_FROM', 'noreply@55810.com');



define('MEMBER_INCLUDE_DIR', __DIR__);

define('CANDY_NEW_ROOT', dirname(dirname(__DIR__)));

define('MEMBER_MAIL_LOG_DIR', CANDY_NEW_ROOT . '/log/member_mail');

// false=既存サイト（ナビ・Cookieお気に入り）に手を入れない。true=ログイン/マイページ差し替え＋プロフィールお気に入りAPI連携。
define('MEMBER_SITE_INTEGRATION_ENABLED', true);

// 本番 CTI DB（cti/flat/config.php と同値）

define('MEMBER_CTI_HOST', 'localhost');

define('MEMBER_CTI_DB', 'cti');

define('MEMBER_CTI_USER', 'firststar');

define('MEMBER_CTI_PASS', '01855');


