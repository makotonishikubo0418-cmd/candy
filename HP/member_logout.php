<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
require_once __DIR__ . '/includefile/member/bootstrap.php';

$mdb = new MemberDb($Database);
$auth = new MemberAuth($mdb);
$auth->logout($auth->getCurrentMember());

header('Location: member_login.php');
exit;
