<?php
/**
 * お気に入り出勤通知バッチ
 * 例: php member/cron_notify_favorite_schedule.php
 */
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

require_once dirname(__DIR__) . '/includefile/member/bootstrap.php';

$svc = new MemberFavoriteNotify(new MemberDb($Database));
$result = $svc->processToday();

echo date('Y-m-d H:i:s')
    . " created={$result['created']} mailed={$result['mailed']} errors={$result['errors']}\n";

$Database->Disconnect();
