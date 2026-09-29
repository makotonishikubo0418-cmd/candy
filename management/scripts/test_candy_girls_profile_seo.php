<?php

define('CANDY_GIRLS_PAGE_CONTENT_FRONT', true);
require_once dirname(dirname(__DIR__)) . '/HP/includefile/candy_girls_page_content.php';

$failures = array();
$assertionCount = 0;

function candyGirlsSeoTestAssert($condition, $message)
{
	global $failures, $assertionCount;
	$assertionCount++;
	if (!$condition) {
		$failures[] = $message;
	}
}

function candyGirlsSeoTestAssertSame($expected, $actual, $message)
{
	candyGirlsSeoTestAssert($expected === $actual, $message . ' expected=' . var_export($expected, true) . ' actual=' . var_export($actual, true));
}

function candyGirlsSeoTestFindType($items, $type)
{
	foreach ($items as $item) {
		if (isset($item['@type']) && $item['@type'] === $type) {
			return $item;
		}
	}
	return array();
}

function candyGirlsSeoTestScheduleRows()
{
	$rows = array();
	for ($i = 0; $i < 7; $i++) {
		$rows[] = array(
			'label' => 'DAY' . $i,
			'datetime' => '2026-08-' . sprintf('%02d', 16 + $i),
			'date' => '8/' . (16 + $i),
			'time' => 'お休み',
			'note' => '次回出勤をご確認ください',
			'off' => true,
			'weekday' => $i
		);
	}
	return $rows;
}

$requiredFunctions = array(
	'candyGirlsPageBuildVisibility',
	'candyGirlsPageBuildDescription',
	'candyGirlsPageNormalizePublicImageFilename',
	'candyGirlsPageSelectProfileImage',
	'candyGirlsPageOrderProfileImageCandidates',
	'candyGirlsPageBuildSeoData',
	'candyGirlsPageBuildStructuredData',
	'candyGirlsPageJsonLdScript',
	'candyGirlsPageApplySeo'
);
foreach ($requiredFunctions as $requiredFunction) {
	if (!function_exists($requiredFunction)) {
		$failures[] = 'missing helper: ' . $requiredFunction;
	}
}
if (count($failures) > 0) {
	fwrite(STDERR, "CANDY_GIRLS_PROFILE_SEO_TEST=FAIL\n- " . implode("\n- ", $failures) . "\n");
	exit(1);
}

$scheduleRows = candyGirlsSeoTestScheduleRows();

candyGirlsSeoTestAssertSame('profile.jpg', candyGirlsPageNormalizePublicImageFilename('profile.JPG'), 'public JPG extension must be lowercase');
candyGirlsSeoTestAssertSame('profile.jpeg', candyGirlsPageNormalizePublicImageFilename('profile.JPEG'), 'public JPEG extension must be lowercase');
candyGirlsSeoTestAssertSame('profile.png', candyGirlsPageNormalizePublicImageFilename('profile.PNG'), 'public PNG extension must be lowercase');
candyGirlsSeoTestAssertSame('profile.mp4', candyGirlsPageNormalizePublicImageFilename('profile.mp4'), 'non-image extensions must remain unchanged');
candyGirlsSeoTestAssertSame('profile', candyGirlsPageNormalizePublicImageFilename('profile'), 'extensionless filenames must remain unchanged');

if (PHP_SAPI === 'cli-server') {
	$pageData = array('content' => array(), 'qa' => array());
	$movieData = array('sources' => array(), 'poster' => '');
	$fixtureVisibility = candyGirlsPageBuildVisibility($pageData, $scheduleRows, $movieData);
	$fixtureSeo = candyGirlsPageBuildSeoData(
		'エミ',
		'https://www.55810.com/girls.php?no=9',
		$fixtureVisibility,
		'',
		'https://www.55810.com/imgHtml/new_202601/sample.jpg'
	);
	$headTemplate = '<title>rep09000004eot</title><meta name="description" content="rep09000005eot"><meta property="og:title" content="rep09000004eot"><meta property="og:image" content="rep09000006eot"><meta property="og:description" content="rep09000005eot">rep09000007eot';
	$renderedHead = candyGirlsPageApplySeo($headTemplate, $fixtureSeo);
	$renderedContent = candyGirlsPageRender($pageData, $scheduleRows, $movieData, $fixtureVisibility);
	$css = file_get_contents(dirname(dirname(__DIR__)) . '/HP/css/girls_page_content.css');
	header('Content-Type: text/html; charset=UTF-8');
	echo '<!doctype html><html lang="ja"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">' . $renderedHead . '<style>body{margin:0;background:#fff;color:#222;font-family:Arial,sans-serif}.fixture-wrap{max-width:1000px;margin:0 auto;padding:24px 16px}h1{font-size:24px;text-align:center}</style><style>' . $css . '</style></head><body><main class="fixture-wrap"><h1>' . candyGirlsPageEscape($fixtureSeo['h1']) . '</h1>' . $renderedContent . '</main></body></html>';
	exit(0);
}

$fullPageData = array(
	'content' => array(
		'manager_comment_title' => '店長タイトル',
		'manager_comment_body' => '店長本文',
		'profile_hobby' => '映画',
		'profile_favorite_food' => '',
		'profile_favorite_color' => '',
		'profile_strength' => '',
		'profile_specialty' => '',
		'profile_day_off' => '',
		'sales_point_title' => '魅力',
		'sales_point_body' => ''
	),
	'qa' => array(
		array('question' => '質問', 'answer' => '回答'),
		array('question' => ' ', 'answer' => '非表示')
	)
);
$movie = array(
	'sources' => array(array('src' => 'https://media.example/profile.mp4', 'type' => 'video/mp4')),
	'poster' => ''
);

$visibility = candyGirlsPageBuildVisibility($fullPageData, $scheduleRows, $movie);
candyGirlsSeoTestAssertSame(true, $visibility['schedule'], 'all-off weekly schedule must remain visible');
candyGirlsSeoTestAssertSame(true, $visibility['movie'], 'usable profile movie must be visible');
candyGirlsSeoTestAssertSame(true, $visibility['manager'], 'manager content must be visible');
candyGirlsSeoTestAssertSame(true, $visibility['qa'], 'valid QA must be visible');
candyGirlsSeoTestAssertSame(true, $visibility['profile'], 'nonempty detail profile must be visible');
candyGirlsSeoTestAssertSame(true, $visibility['sales'], 'sales point must be visible');
candyGirlsSeoTestAssertSame(1, count($visibility['qa_rows']), 'blank QA must not qualify');

$fullDescription = 'エミのプロフィール・週間出勤情報を掲載。プロフィール動画、店長紹介コメント、本人Q&A、詳細プロフィール、セールスポイントも確認できます。鹿児島デリヘル「キャンディ」公式。';
candyGirlsSeoTestAssertSame($fullDescription, candyGirlsPageBuildDescription('エミ', $visibility), 'full deterministic description');
candyGirlsSeoTestAssertSame(candyGirlsPageBuildDescription('エミ', $visibility), candyGirlsPageBuildDescription('エミ', $visibility), 'same inputs must produce the same description');

$emptyVisibility = candyGirlsPageBuildVisibility(array('content' => array(), 'qa' => array()), $scheduleRows, array('sources' => array(), 'poster' => ''));
candyGirlsSeoTestAssertSame('エミのプロフィール・週間出勤情報を掲載。鹿児島デリヘル「キャンディ」公式。', candyGirlsPageBuildDescription('エミ', $emptyVisibility), 'schedule-only deterministic description');

$profileImage = candyGirlsPageSelectProfileImage(array(
	array('filename' => 'profile.mp4', 'url' => 'http://media.example/profile.mp4'),
	array('filename' => 'dmy_h.jpg', 'url' => 'http://img.example/dmy_h.jpg'),
	array('filename' => 'emi_01.jpg', 'url' => 'http://img.example/emi_01.jpg')
));
candyGirlsSeoTestAssertSame('https://img.example/emi_01.jpg', $profileImage, 'first real profile image must be selected as HTTPS');
candyGirlsSeoTestAssertSame('', candyGirlsPageSelectProfileImage(array(
	array('filename' => 'sample.jpg', 'url' => 'https://www.55810.com/imgHtml/new_202601/sample.jpg'),
	array('filename' => 'profile.webm', 'url' => 'https://media.example/profile.webm')
)), 'dummy, fallback, and video assets must not become Person.image');
$horizontalCandidates = array(array('filename' => 'emi_w.jpg', 'url' => 'https://img.example/emi_w.jpg'));
$verticalCandidates = array(array('filename' => 'emi_h.jpg', 'url' => 'https://img.example/emi_h.jpg'));
candyGirlsSeoTestAssertSame('https://img.example/emi_h.jpg', candyGirlsPageSelectProfileImage(candyGirlsPageOrderProfileImageCandidates($horizontalCandidates, $verticalCandidates, false)), 'hidden horizontal image must be excluded when the main media is a movie');
candyGirlsSeoTestAssertSame('https://img.example/emi_w.jpg', candyGirlsPageSelectProfileImage(candyGirlsPageOrderProfileImageCandidates($horizontalCandidates, $verticalCandidates, true)), 'visible main horizontal image must be preferred');

$fallbackImage = 'https://www.55810.com/imgHtml/new_202601/sample.jpg';
$canonical = 'https://www.55810.com/girls.php?no=9';
$seo = candyGirlsPageBuildSeoData('エミ', $canonical, $visibility, $profileImage, $fallbackImage);
candyGirlsSeoTestAssertSame('エミのプロフィール・出勤情報｜鹿児島デリヘル キャンディ', $seo['title'], 'approved title');
candyGirlsSeoTestAssertSame($seo['title'], $seo['og_title'], 'title and og:title must match');
candyGirlsSeoTestAssertSame($fullDescription, $seo['description'], 'approved description');
candyGirlsSeoTestAssertSame($seo['description'], $seo['og_description'], 'description and og:description must match');
candyGirlsSeoTestAssertSame($profileImage, $seo['og_image'], 'real image must be og:image');
candyGirlsSeoTestAssertSame($profileImage, $seo['person_image'], 'real image must be Person.image');

$structured = candyGirlsPageBuildStructuredData($seo);
$profilePage = candyGirlsSeoTestFindType($structured, 'ProfilePage');
$breadcrumb = candyGirlsSeoTestFindType($structured, 'BreadcrumbList');
candyGirlsSeoTestAssertSame('エミのプロフィール・出勤情報', $profilePage['name'], 'ProfilePage must be woman-specific');
candyGirlsSeoTestAssertSame($canonical, $profilePage['url'], 'ProfilePage URL must be canonical');
candyGirlsSeoTestAssertSame('エミ', $profilePage['mainEntity']['name'], 'Person name must be woman-specific');
candyGirlsSeoTestAssertSame($canonical, $profilePage['mainEntity']['url'], 'Person URL must be canonical');
candyGirlsSeoTestAssertSame($profileImage, $profilePage['mainEntity']['image'], 'Person image must be real profile image');
candyGirlsSeoTestAssertSame('エミのプロフィール・出勤情報', $breadcrumb['itemListElement'][2]['name'], 'Breadcrumb final name must match H1');
candyGirlsSeoTestAssertSame($canonical, $breadcrumb['itemListElement'][2]['item'], 'Breadcrumb final URL must be canonical');

$noImageSeo = candyGirlsPageBuildSeoData('ユリ', 'https://www.55810.com/girls.php?no=1479', $emptyVisibility, '', $fallbackImage);
$noImageProfilePage = candyGirlsSeoTestFindType(candyGirlsPageBuildStructuredData($noImageSeo), 'ProfilePage');
candyGirlsSeoTestAssertSame($fallbackImage, $noImageSeo['og_image'], 'common image must be og:image fallback');
candyGirlsSeoTestAssertSame('', $noImageSeo['person_image'], 'missing real image must leave Person.image empty');
candyGirlsSeoTestAssert(!isset($noImageProfilePage['mainEntity']['image']), 'Person.image must be omitted when no real profile image exists');

$specialName = "テスト\"'&<>\n</script>";
$specialSeo = candyGirlsPageBuildSeoData($specialName, $canonical, $emptyVisibility, '', $fallbackImage);
$jsonLdScript = candyGirlsPageJsonLdScript(candyGirlsPageBuildStructuredData($specialSeo));
candyGirlsSeoTestAssert(strpos($jsonLdScript, '</script>') === strrpos($jsonLdScript, '</script>'), 'JSON payload must not contain a literal closing script tag');
candyGirlsSeoTestAssert(preg_match('#<script type="application/ld\+json">(.*)</script>#s', $jsonLdScript, $jsonMatch) === 1, 'JSON-LD script wrapper');
$decoded = isset($jsonMatch[1]) ? json_decode($jsonMatch[1], true) : null;
candyGirlsSeoTestAssert(is_array($decoded), 'JSON-LD must parse');
if (is_array($decoded)) {
	$decodedProfile = candyGirlsSeoTestFindType($decoded, 'ProfilePage');
	candyGirlsSeoTestAssertSame($specialName, $decodedProfile['mainEntity']['name'], 'special characters must round-trip through JSON-LD');
}

$previousErrorLog = ini_get('error_log');
ini_set('error_log', 'NUL');
$invalidUtf8Seo = candyGirlsPageBuildSeoData("\xB1\x31", $canonical, $emptyVisibility, '', $fallbackImage);
candyGirlsSeoTestAssertSame('', candyGirlsPageJsonLdScript(candyGirlsPageBuildStructuredData($invalidUtf8Seo)), 'JSON encoding failure must omit the structured-data block');
$invalidUtf8Head = candyGirlsPageApplySeo('before-rep09000007eot-after', $invalidUtf8Seo);
candyGirlsSeoTestAssertSame('before--after', $invalidUtf8Head, 'JSON encoding failure must remove the structured-data token without malformed output');
ini_set('error_log', $previousErrorLog);

$fixture = '<title>rep09000004eot</title><meta name="description" content="rep09000005eot"><meta property="og:title" content="rep09000004eot"><meta property="og:image" content="rep09000006eot"><meta property="og:description" content="rep09000005eot">rep09000007eot';
$renderedHead = candyGirlsPageApplySeo($fixture, $specialSeo);
candyGirlsSeoTestAssert(strpos($renderedHead, 'rep0900000') === false, 'SEO tokens must all be replaced');
candyGirlsSeoTestAssert(strpos($renderedHead, '&quot;') !== false && strpos($renderedHead, '&lt;') !== false && strpos($renderedHead, '&amp;') !== false, 'HTML metadata must be attribute-safe');

$scheduleHtml = candyGirlsPageRender(array('content' => array(), 'qa' => array()), $scheduleRows, array('sources' => array(), 'poster' => ''), $emptyVisibility);
candyGirlsSeoTestAssert(strpos($scheduleHtml, 'id="girls-test-schedule"') !== false, 'all-off schedule section must render');
candyGirlsSeoTestAssertSame(7, substr_count($scheduleHtml, 'class="is-off"'), 'all seven off rows must render');
candyGirlsSeoTestAssertSame(7, substr_count($scheduleHtml, '次回出勤をご確認ください'), 'all seven off messages must render');
$fullHtml = candyGirlsPageRender($fullPageData, $scheduleRows, $movie, $visibility);
foreach (array('girls-test-schedule', 'girls-test-movie', 'girls-test-manager', 'girls-test-qa', 'girls-test-profile', 'girls-test-points') as $sectionId) {
	candyGirlsSeoTestAssert(strpos($fullHtml, 'id="' . $sectionId . '"') !== false, 'description-qualified section must render: ' . $sectionId);
}
candyGirlsSeoTestAssert(strpos($fullHtml, '非表示') === false, 'invalid QA content must not render');

$templatePath = dirname(dirname(__DIR__)) . '/HP/source/girls.html';
$template = file_get_contents($templatePath);
candyGirlsSeoTestAssert(strpos($template, '<title>rep09000004eot</title>') !== false, 'girls template must use the dynamic title token');
candyGirlsSeoTestAssert(strpos($template, 'rep09000007eot') !== false, 'girls template must use the dynamic JSON-LD block token');
candyGirlsSeoTestAssert(strpos($template, 'rep09000003eot') === false, 'obsolete JSON fragment token must be removed');
candyGirlsSeoTestAssert(strpos($template, 'キャンディ在籍女性') === false, 'generic Person name must be removed');
candyGirlsSeoTestAssert(strpos($template, 'rep01010007eot') === false || strpos($template, '<meta property="og:image" content="rep01010007eot">') === false, 'top media token must not be reused for og:image');

if (count($failures) > 0) {
	fwrite(STDERR, "CANDY_GIRLS_PROFILE_SEO_TEST=FAIL\n- " . implode("\n- ", $failures) . "\n");
	exit(1);
}

fwrite(STDOUT, "CANDY_GIRLS_PROFILE_SEO_TEST=PASS assertions=" . $assertionCount . "\n");
exit(0);
