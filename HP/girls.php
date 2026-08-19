<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

// 女性番号がない入口アクセスは、女性一覧へ恒久転送する。
if (!array_key_exists('no', $_GET) || (is_scalar($_GET['no']) && trim((string) $_GET['no']) === '')) {
	header('Location: girls_list.php', true, 301);
	exit;
}

// 配列など、女性番号として扱えない入力は404にする。
if (!is_scalar($_GET['no'])) {
	http_response_code(404);
	$notFoundPage = __DIR__ . '/404.html';
	if (is_readable($notFoundPage)) {
		readfile($notFoundPage);
	}
	exit;
}

//データセット基本ファイル読込
include("/home/firststar/public_html/group/candy/includefile/dataset_base.php");


?>
