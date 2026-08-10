<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
ini_set('display_errors', '0');

require_once dirname(__DIR__) . '/includefile/member/bootstrap.php';

ob_start();

register_shutdown_function(function () {
    $err = error_get_last();
    if ($err === null || !in_array($err['type'], array(E_ERROR, E_PARSE, E_COMPILE_ERROR, E_USER_ERROR), true)) {
        return;
    }
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
    }
    echo json_encode(array(
        'status' => -1,
        'message' => 'サーバーエラーが発生しました',
    ), JSON_UNESCAPED_UNICODE);
});

$fno = isset($_REQUEST['fno']) ? trim($_REQUEST['fno']) : '';
if ($fno === '') {
    ob_end_clean();
    MemberUtil::jsonResponse(-1, 'fno is required');
}

try {
    $api = new MemberApi($Database);
    $api->handle($fno);
} catch (Exception $e) {
    ob_end_clean();
    MemberUtil::jsonResponse(-1, 'サーバーエラーが発生しました');
} catch (Throwable $e) {
    ob_end_clean();
    MemberUtil::jsonResponse(-1, 'サーバーエラーが発生しました');
}
