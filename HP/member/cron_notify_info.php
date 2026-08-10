<?php
/**
 * 会員お知らせメール送信バッチ（cron 用）
 *
 * 例: php /path/to/candy_new/member/cron_notify_info.php
 * 推奨: 15分〜1時間ごと
 */
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

require_once dirname(__DIR__) . '/includefile/member/bootstrap.php';

$notify = new MemberNotification(new MemberDb($Database));
$result = $notify->sendPendingInfoMails();

echo date('Y-m-d H:i:s')
    . " sent={$result['sent']} skipped={$result['skipped']} errors={$result['errors']}\n";

$Database->Disconnect();
