<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
require_once __DIR__ . '/includefile/member/bootstrap.php';

$mdb = new MemberDb($Database);
$auth = new MemberAuth($mdb);
if ($auth->getCurrentMember() !== null) {
    header('Location: member_mypage.php');
    exit;
}

include __DIR__ . '/includefile/dataset_base.php';
