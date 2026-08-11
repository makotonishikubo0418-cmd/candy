<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

// Development-only girl profile preview. This entry point must never be indexed.
header('X-Robots-Tag: noindex, nofollow', true);
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0', true);

define('CANDY_GIRLS_PAGE_CONTENT_FRONT', true);
require_once __DIR__ . '/includefile/candy_girls_page_content.php';

$candyGirlsPreviewHash = 'bd87fcb3c99894ac22d2f2e75d0827ddd751548f24217b6d45216919d0e58035';
$candyGirlsPreviewToken = isset($_GET['preview']) && is_string($_GET['preview']) ? $_GET['preview'] : '';
$candyGirlsIsLocal = PHP_SAPI === 'cli-server' && isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'), true);
if (!$candyGirlsIsLocal && !candyGirlsPageHashEquals($candyGirlsPreviewHash, hash('sha256', $candyGirlsPreviewToken))) {
	http_response_code(404);
	echo 'Not Found';
	exit;
}

$candyGirlsConn = null;
$candyGirlsDatabase = null;
$candyGirlsControlLoader = '/home/firststar/public_html/group/control/includefile/incfiles_vv.php';
if (is_file($candyGirlsControlLoader)) {
	require_once $candyGirlsControlLoader;
	if (class_exists('Database') && isset($DSN)) {
		$candyGirlsDatabase = new Database($DSN);
		$candyGirlsConn = isset($candyGirlsDatabase->Conn) ? $candyGirlsDatabase->Conn : null;
	}
} elseif ($candyGirlsIsLocal && getenv('CANDY_GIRLS_TEST_LOCAL_DB') === '1') {
	require_once 'C:/Codex/FSG/control/includefile/Sql.php';
	$candyGirlsConn = @mysqli_connect($DSN['host'], $DSN['user'], $DSN['password'], $DSN['dbname']);
}

$candyGirlsId = 4617;
$candyGirlsPageData = candyGirlsPageLoadContent($candyGirlsConn, $candyGirlsId, 0);
$candyGirlsNewDayHour = defined('NEWDAY_TIME') ? NEWDAY_TIME : 6;
$candyGirlsScheduleRows = candyGirlsPageLoadScheduleRows($candyGirlsConn, $candyGirlsId, $candyGirlsNewDayHour);
$candyGirlsMovie = array('sources' => array(array('src' => './movie/grmov0041571692_pc.mp4', 'type' => 'video/mp4')), 'poster' => '');
$candyGirlsTestSections = candyGirlsPageRender($candyGirlsPageData, $candyGirlsScheduleRows, $candyGirlsMovie);
if ($candyGirlsConn && empty($candyGirlsPageData['content'])) {
	http_response_code(503);
	echo 'Preview data is unavailable.';
	exit;
}
if ($candyGirlsIsLocal && isset($_GET['sections_only']) && $_GET['sections_only'] === '1') {
	echo '<!DOCTYPE html><html lang="ja"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><link rel="stylesheet" href="./css/girls_page_content.css?v=20260811"></head><body style="margin:0;overflow-x:hidden">' . $candyGirlsTestSections . '<script>document.documentElement.setAttribute("data-inner-width",String(window.innerWidth));</script></body></html>';
	exit;
}

// Rebuild the current girls page from its front-end source only.
// The production dataset, database, session, log, favorites and analytics are not executed.
$candyGirlsSourcePath = __DIR__ . '/source/girls.html';
if (!is_readable($candyGirlsSourcePath)) {
	http_response_code(500);
	echo 'Design preview source is unavailable.';
	exit;
}

$candyGirlsTestHtml = file_get_contents($candyGirlsSourcePath);
if ($candyGirlsTestHtml === false) {
	http_response_code(500);
	echo 'Design preview source could not be read.';
	exit;
}

$candyProfileText = <<<'HTML'
これは・・・・<br>
圧巻の神スタイル姫★★★<br><br>
弾力いっぱいの大きなＦカップおっぱい<br>
リアルフィギュアな華奢なウエスト<br>
キュッと上がったお尻<br><br>
かわいい整ったお顔に、柔らかで穏やかな明るい性格。写真だけでは伝わらない魅力も感じてください。
HTML;

$candyCanonicalUrl = 'https://www.55810.com/girls.php?no=1459';
$candyMainImage = 'https://image.can-diary.com/2/gl_w/4824_4.jpg';
$candyDetailImageLeft = 'https://image.can-diary.com/2/gl_h/4824_5.jpg';
$candyDetailImageRight = 'https://image.can-diary.com/2/gl_h/4824_1.jpg';
$candyGalleryImage1 = 'https://image.can-diary.com/2/gl_h/4824_4.jpg';
$candyGalleryImage2 = 'https://image.can-diary.com/2/gl_h/4824_3.jpg';
$candyGalleryImage3 = 'https://image.can-diary.com/2/gl_h/4824_2.jpg';

$candyPreviewValues = array(
	'rep00010320eot' => 'レイカ',
	'rep00010321eot' => '26',
	'rep00010322eot' => '163',
	'rep00010323eot' => '85',
	'rep00010324eot' => 'F',
	'rep00010325eot' => '57',
	'rep00010326eot' => '85',
	'rep00010329eot' => 'ピュアな瞳に射抜かれる♡',
	'rep00010330eot' => $candyProfileText,
	'rep00010331eot' => 'REIKA',
	'rep00010354eot' => 'CLOSED TODAY',
	'rep00010630eot' => '0',
	'rep00010640eot' => '',
	'rep00010641eot' => '',
	'rep00010642eot' => '',
	'rep01010003eot' => $candyGalleryImage1,
	'rep01010004eot' => $candyGalleryImage2,
	'rep01010005eot' => $candyGalleryImage3,
	'rep01010006eot' => '',
	'rep01010007eot' => $candyMainImage,
	'rep01010008eot' => '',
	'rep01010009eot' => $candyDetailImageLeft,
	'rep01010010eot' => $candyDetailImageRight,
	'rep01010011eot' => '',
	'rep01010012eot' => '',
	'rep01010013eot' => '',
	'rep01010014eot' => '',
	'rep01010015eot' => '',
	'rep01010016eot' => '',
	'rep01010017eot' => '',
	'rep01010019eot' => '',
	'rep01010020eot' => '',
	'rep01010021eot' => '',
	'rep01010022eot' => '',
	'rep01010023eot' => '',
	'rep01010024eot' => '',
	'rep01010025eot' => '',
	'rep01010026eot' => '',
	'rep01010280eot' => '',
	'rep01010310eot' => '',
	'rep01010311eot' => '',
	'rep01010312eot' => '',
	'rep01010321eot' => '',
	'rep03010090eot' => '#',
	'rep03010091eot' => '#',
	'rep03010092eot' => $candyCanonicalUrl,
	'rep03010093eot' => 'https://www.cityheaven.net/kagoshima/A4601/A460102/newcandy/reviews/?girlid=65660115'
);

$candyGirlsTestHtml = strtr($candyGirlsTestHtml, $candyPreviewValues);
$candyGirlsTestHtml = preg_replace('/rep[0-9A-Za-z_]+eot/', '', $candyGirlsTestHtml);

// Keep the test entry point out of search results while retaining the production canonical URL.
$candyGirlsTestHtml = preg_replace(
	'/<meta\s+name=["\']robots["\']\s+content=["\'][^"\']*["\']\s*\/?>/i',
	'<meta name="robots" content="noindex, nofollow">',
	$candyGirlsTestHtml,
	1
);
$candyGirlsTestHtml = str_replace(
	'"url": "https://www.55810.com/girls.php"',
	'"url": "' . $candyCanonicalUrl . '"',
	$candyGirlsTestHtml
);
$candyGirlsTestHtml = str_replace(
	'"name": "キャンディ在籍女性"',
	'"name": "レイカ"',
	$candyGirlsTestHtml
);
$candyGirlsTestHtml = str_replace(
	'"item": "https://www.55810.com/girls.php"',
	'"item": "' . $candyCanonicalUrl . '"',
	$candyGirlsTestHtml
);

// Disable production analytics, access tracking, favorite processing and external diary loading.
$candyGirlsTestHtml = preg_replace(
	'/<!-- Google tag \(gtag\.js\) START -->.*?<!-- Google tag \(gtag\.js\) END -->/s',
	'<!-- Analytics disabled on girls_test.php -->',
	$candyGirlsTestHtml
);
$candyGirlsTestHtml = preg_replace(
	'#<script\b[^>]*src=["\'][^"\']*(?:amadare_webapp2\.4\.php|amadareWebApp2\.6\.js|amadareAccess\.1\.0\.js|love2\.js)[^"\']*["\'][^>]*>\s*</script>#i',
	'',
	$candyGirlsTestHtml
);
$candyGirlsTestHtml = preg_replace(
	'#<script>\s*// windowロードイベント.*?</script>#s',
	'',
	$candyGirlsTestHtml
);
$candyGirlsTestHtml = preg_replace(
	'#<iframe[^>]+src=["\']https://can-diary\.com/love\.html["\'][^>]*>\s*</iframe>#i',
	'',
	$candyGirlsTestHtml
);
$candyGirlsTestHtml = preg_replace('/^\s*window\.onload\s*=\s*CookieWrite\([^\r\n]*$/m', '', $candyGirlsTestHtml);
$candyGirlsTestHtml = preg_replace('/\s+onClick="[^"]*amadareAcRec[^"]*"/i', '', $candyGirlsTestHtml);
$candyGirlsTestHtml = preg_replace('/\s+onclick="[^"]*SetLovePt[^"]*"/i', '', $candyGirlsTestHtml);

// Preserve the current girls page and add only the test stylesheet.
$candyGirlsTestStyle = '<link href="./css/girls_test.css?v=20260810" rel="stylesheet" type="text/css">';
if (!$candyGirlsIsLocal || !isset($_GET['css']) || $_GET['css'] !== 'production') {
	$candyGirlsTestHtml = str_replace('</head>', $candyGirlsTestStyle . "\n</head>", $candyGirlsTestHtml);
}
$candyGirlsTestHtml = str_replace('<body>', '<body class="girls-test-integrated-preview">', $candyGirlsTestHtml);

// Insert directly after the PC/SP photo galleries, inside the existing girls-page main area.
$candyInsertCount = 0;
$candyGirlsTestHtml = preg_replace(
	'/(\s*<\/div>\s*)(<!-- PC版日記 -->)/',
	"\n" . $candyGirlsTestSections . "\n" . '$1$2',
	$candyGirlsTestHtml,
	1,
	$candyInsertCount
);

if ($candyInsertCount !== 1) {
	http_response_code(500);
	echo 'Design preview insertion point was not found.';
	exit;
}

if ($candyGirlsIsLocal && isset($_GET['focus']) && $_GET['focus'] === 'schedule') {
	$candyGirlsTestHtml = str_replace('</body>', '<script>window.setTimeout(function(){var target=document.getElementById("girls-test-schedule");if(target){target.scrollIntoView();}},500);</script></body>', $candyGirlsTestHtml);
}

echo $candyGirlsTestHtml;
