<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
require_once __DIR__ . '/includefile/member/bootstrap.php';

$mdb = new MemberDb($Database);
$auth = new MemberAuth($mdb);
member_require_login($auth);

include __DIR__ . '/includefile/dataset_base.php';
