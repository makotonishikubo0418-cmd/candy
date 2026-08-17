<?php
/**
 * 公開マイページ入口。
 * 会員サイト統合が無効な間は既存 Cookie お気に入りを表示し、
 * 有効化された場合にだけ新会員マイページへ振り分ける。
 */
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
require_once __DIR__ . '/includefile/member/config.php';

if (MEMBER_SITE_INTEGRATION_ENABLED) {
    require_once __DIR__ . '/includefile/member/bootstrap.php';

    $mdb = new MemberDb($Database);
    $auth = new MemberAuth($mdb);

    if ($auth->getCurrentMember() !== null) {
        header('Location: member_mypage.php');
    } else {
        header('Location: member_login.php');
    }
    exit;
}

include __DIR__ . '/includefile/dataset_base.php';
