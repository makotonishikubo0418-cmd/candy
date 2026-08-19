<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

header('X-Robots-Tag: noindex, nofollow');

function candyMovieIframeNotFound()
{
	http_response_code(404);
	$notFoundFile = __DIR__ . '/404.html';
	if (is_file($notFoundFile)) {
		readfile($notFoundFile);
	}
	exit;
}

$midsProvided = array_key_exists('mids', $_GET);
$midgProvided = array_key_exists('midg', $_GET);

// 店舗動画または女性動画のどちらか一方だけを受け付ける。
if ($midsProvided === $midgProvided) {
	candyMovieIframeNotFound();
}

$movieId = $midsProvided ? $_GET['mids'] : $_GET['midg'];
if (!is_scalar($movieId)) {
	candyMovieIframeNotFound();
}

$movieId = trim((string)$movieId);
if ($movieId === '' || !ctype_digit($movieId) || (int)$movieId <= 0) {
	candyMovieIframeNotFound();
}

$_GET['mids'] = $midsProvided ? $movieId : '';
$_GET['midg'] = $midgProvided ? $movieId : '';

//データセット基本ファイル読込
include("/home/firststar/public_html/group/candy/includefile/dataset_base.php");


?>
