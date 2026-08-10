<?php
/**
 * 会員マイページ（新）テスト用入口 — 通常ナビには未掲載
 * http://firststar.kir.jp/group_test/candy/customers/
 *
 * 既存 mypage.php（Cookie お気に入り）とは別系統です。
 */
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
require_once dirname(__DIR__) . '/includefile/member/bootstrap.php';

$mdb = new MemberDb($Database);
$auth = new MemberAuth($mdb);

if ($auth->getCurrentMember() !== null) {
    header('Location: ../member_mypage.php');
} else {
    header('Location: ../member_login.php');
}
exit;
