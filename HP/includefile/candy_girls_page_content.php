<?php
if (!defined('CANDY_GIRLS_PAGE_CONTENT_FRONT')) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

function candyGirlsPageEscape($value)
{
	return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function candyGirlsPageText($value)
{
	return nl2br(candyGirlsPageEscape($value), false);
}

function candyGirlsPageHasText($value)
{
	return trim((string)$value) !== '';
}

function candyGirlsPageNormalizeHttpsUrl($url)
{
	$url = trim((string)$url);
	if ($url === '') {
		return '';
	}
	if (strpos($url, '//') === 0) {
		$url = 'https:' . $url;
	} elseif (stripos($url, 'http://') === 0) {
		$url = 'https://' . substr($url, 7);
	}
	$parts = @parse_url($url);
	if (!is_array($parts) || !isset($parts['scheme']) || strtolower($parts['scheme']) !== 'https' || empty($parts['host'])) {
		return '';
	}
	return $url;
}

function candyGirlsPageNormalizePublicImageFilename($filename)
{
	return preg_replace_callback(
		'/\.(?:jpe?g|png|gif|webp|avif)$/i',
		function($matches) {
			return strtolower($matches[0]);
		},
		(string)$filename
	);
}

function candyGirlsPageSelectProfileImage($candidates)
{
	if (!is_array($candidates)) {
		return '';
	}
	foreach ($candidates as $candidate) {
		if (!is_array($candidate)) {
			continue;
		}
		$filename = isset($candidate['filename']) ? trim((string)$candidate['filename']) : '';
		$url = isset($candidate['url']) ? candyGirlsPageNormalizeHttpsUrl($candidate['url']) : '';
		if ($filename === '' || $url === '') {
			continue;
		}
		$basename = strtolower(basename(str_replace('\\', '/', $filename)));
		if (!preg_match('/\.(?:jpe?g|png|gif|webp|avif)$/i', $basename)) {
			continue;
		}
		if (preg_match('/^(?:dmy(?:_[hw])?|dummy(?:_[hw])?|sample|no[-_]?image|placeholder)\.(?:jpe?g|png|gif|webp|avif)$/i', $basename)) {
			continue;
		}
		return $url;
	}
	return '';
}

function candyGirlsPageOrderProfileImageCandidates($horizontalCandidates, $verticalCandidates, $mainUsesHorizontalImage)
{
	$ordered = array();
	if ($mainUsesHorizontalImage && is_array($horizontalCandidates)) {
		$ordered = array_merge($ordered, $horizontalCandidates);
	}
	if (is_array($verticalCandidates)) {
		$ordered = array_merge($ordered, $verticalCandidates);
	}
	return $ordered;
}

function candyGirlsPageBuildVisibility($pageData, $scheduleRows, $movie)
{
	$content = isset($pageData['content']) && is_array($pageData['content']) ? $pageData['content'] : array();
	$qaSource = isset($pageData['qa']) && is_array($pageData['qa']) ? $pageData['qa'] : array();
	$movieSource = isset($movie['sources']) && is_array($movie['sources']) ? $movie['sources'] : array();
	$profileFields = array(
		'profile_hobby' => '趣味',
		'profile_favorite_food' => '好きな食べ物',
		'profile_favorite_color' => '好きな色',
		'profile_strength' => '長所',
		'profile_specialty' => '得意なこと',
		'profile_day_off' => '休日の過ごし方'
	);
	$qaRows = array();
	foreach ($qaSource as $row) {
		if (is_array($row) && isset($row['question']) && isset($row['answer']) && candyGirlsPageHasText($row['question']) && candyGirlsPageHasText($row['answer'])) {
			$qaRows[] = array('question' => $row['question'], 'answer' => $row['answer']);
		}
	}
	$profileRows = array();
	foreach ($profileFields as $key => $label) {
		if (isset($content[$key]) && candyGirlsPageHasText($content[$key])) {
			$profileRows[] = array('label' => $label, 'value' => $content[$key]);
		}
	}
	$movieSources = array();
	foreach ($movieSource as $source) {
		if (is_array($source) && isset($source['src']) && isset($source['type']) && candyGirlsPageHasText($source['src']) && candyGirlsPageHasText($source['type'])) {
			$movieSources[] = array('src' => $source['src'], 'type' => $source['type']);
		}
	}
	$managerVisible = (isset($content['manager_comment_title']) && candyGirlsPageHasText($content['manager_comment_title'])) || (isset($content['manager_comment_body']) && candyGirlsPageHasText($content['manager_comment_body']));
	$salesVisible = (isset($content['sales_point_title']) && candyGirlsPageHasText($content['sales_point_title'])) || (isset($content['sales_point_body']) && candyGirlsPageHasText($content['sales_point_body']));

	return array(
		'schedule' => is_array($scheduleRows) && count($scheduleRows) === 7,
		'movie' => count($movieSources) > 0,
		'manager' => $managerVisible,
		'qa' => count($qaRows) > 0,
		'profile' => count($profileRows) > 0,
		'sales' => $salesVisible,
		'qa_rows' => $qaRows,
		'profile_rows' => $profileRows,
		'movie_sources' => $movieSources
	);
}

function candyGirlsPageBuildDescription($name, $visibility)
{
	$name = trim((string)$name);
	$labels = array();
	$optionalGroups = array(
		'movie' => 'プロフィール動画',
		'manager' => '店長紹介コメント',
		'qa' => '本人Q&A',
		'profile' => '詳細プロフィール',
		'sales' => 'セールスポイント'
	);
	foreach ($optionalGroups as $key => $label) {
		if (!empty($visibility[$key])) {
			$labels[] = $label;
		}
	}
	$description = $name . 'のプロフィール・週間出勤情報を掲載。';
	if (count($labels) > 0) {
		$description .= implode('、', $labels) . 'も確認できます。';
	}
	return $description . '鹿児島デリヘル「キャンディ」公式。';
}

function candyGirlsPageBuildSeoData($name, $canonical, $visibility, $profileImage, $fallbackImage)
{
	$name = trim((string)$name);
	$canonical = candyGirlsPageNormalizeHttpsUrl($canonical);
	$profileImage = candyGirlsPageNormalizeHttpsUrl($profileImage);
	$fallbackImage = candyGirlsPageNormalizeHttpsUrl($fallbackImage);
	$heading = $name . 'のプロフィール・出勤情報';
	$description = candyGirlsPageBuildDescription($name, $visibility);
	return array(
		'name' => $name,
		'h1' => $heading,
		'title' => $heading . '｜鹿児島デリヘル キャンディ',
		'og_title' => $heading . '｜鹿児島デリヘル キャンディ',
		'description' => $description,
		'og_description' => $description,
		'canonical' => $canonical,
		'og_image' => $profileImage !== '' ? $profileImage : $fallbackImage,
		'person_image' => $profileImage
	);
}

function candyGirlsPageBuildStructuredData($seo)
{
	$person = array(
		'@type' => 'Person',
		'name' => isset($seo['name']) ? $seo['name'] : '',
		'url' => isset($seo['canonical']) ? $seo['canonical'] : ''
	);
	if (!empty($seo['person_image'])) {
		$person['image'] = $seo['person_image'];
	}
	return array(
		array(
			'@context' => 'https://schema.org',
			'@type' => 'ProfilePage',
			'name' => isset($seo['h1']) ? $seo['h1'] : '',
			'url' => isset($seo['canonical']) ? $seo['canonical'] : '',
			'description' => isset($seo['description']) ? $seo['description'] : '',
			'mainEntity' => $person
		),
		array(
			'@context' => 'https://schema.org',
			'@type' => 'BreadcrumbList',
			'itemListElement' => array(
				array('@type' => 'ListItem', 'position' => 1, 'name' => 'TOP', 'item' => 'https://www.55810.com/'),
				array('@type' => 'ListItem', 'position' => 2, 'name' => '女の子一覧', 'item' => 'https://www.55810.com/girls_list.php'),
				array('@type' => 'ListItem', 'position' => 3, 'name' => isset($seo['h1']) ? $seo['h1'] : '', 'item' => isset($seo['canonical']) ? $seo['canonical'] : '')
			)
		)
	);
}

function candyGirlsPageJsonLdScript($structuredData)
{
	$json = json_encode(
		$structuredData,
		JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);
	if ($json === false) {
		$error = function_exists('json_last_error_msg') ? json_last_error_msg() : (string)json_last_error();
		error_log('[candy][girls-profile-seo] JSON-LD encoding failed: ' . $error);
		return '';
	}
	return '<script type="application/ld+json">' . $json . '</script>';
}

function candyGirlsPageApplySeo($source, $seo)
{
	$jsonLd = candyGirlsPageJsonLdScript(candyGirlsPageBuildStructuredData($seo));
	return str_replace(
		array('rep09000004eot', 'rep09000005eot', 'rep09000006eot', 'rep09000007eot'),
		array(
			candyGirlsPageEscape(isset($seo['title']) ? $seo['title'] : ''),
			candyGirlsPageEscape(isset($seo['description']) ? $seo['description'] : ''),
			candyGirlsPageEscape(isset($seo['og_image']) ? $seo['og_image'] : ''),
			$jsonLd
		),
		$source
	);
}

function candyGirlsPageHashEquals($known, $actual)
{
	if (function_exists('hash_equals')) {
		return hash_equals($known, $actual);
	}
	if (!is_string($known) || !is_string($actual) || strlen($known) !== strlen($actual)) {
		return false;
	}
	$result = 0;
	for ($i = 0, $length = strlen($known); $i < $length; $i++) {
		$result |= ord($known[$i]) ^ ord($actual[$i]);
	}
	return $result === 0;
}

function candyGirlsPageScheduleTimeHtml($value)
{
	$value = (string)$value;
	if (preg_match('/^(\d{1,2}:\d{2})–(\d{1,2}:\d{2})$/', $value, $matches)) {
		return candyGirlsPageTimeHtml($matches[1]) . '–' . candyGirlsPageTimeHtml($matches[2]);
	}
	if (preg_match('/^(\d{1,2}:\d{2})–LAST$/', $value, $matches)) {
		return candyGirlsPageTimeHtml($matches[1]) . '–LAST';
	}
	return candyGirlsPageEscape($value);
}

function candyGirlsPageTimeHtml($value)
{
	if (!preg_match('/^(\d{1,2}):(\d{2})$/', (string)$value, $matches)) {
		return candyGirlsPageEscape($value);
	}
	$hour = (int)$matches[1];
	$minute = (int)$matches[2];
	if ($minute > 59) {
		return candyGirlsPageEscape($value);
	}
	$datetime = $hour < 24
		? sprintf('%02d:%02d', $hour, $minute)
		: 'PT' . $hour . 'H' . ($minute > 0 ? $minute . 'M' : '');
	return '<time datetime="' . candyGirlsPageEscape($datetime) . '">' . candyGirlsPageEscape($value) . '</time>';
}

function candyGirlsPageLoadGirlIdByNo($conn, $girlNo)
{
	$stmt = @mysqli_prepare($conn, 'SELECT id FROM girls_data WHERE club_id=2 AND no=? AND status=1 LIMIT 1');
	if (!$stmt) {
		return 0;
	}
	$girlNo = (int)$girlNo;
	mysqli_stmt_bind_param($stmt, 'i', $girlNo);
	if (!mysqli_stmt_execute($stmt)) {
		mysqli_stmt_close($stmt);
		return 0;
	}
	mysqli_stmt_bind_result($stmt, $girlsId);
	$found = mysqli_stmt_fetch($stmt);
	mysqli_stmt_close($stmt);
	return $found ? (int)$girlsId : 0;
}

function candyGirlsPageLoadContent($conn, $girlsId, $publishStatus)
{
	$empty = array('content' => array(), 'qa' => array());
	if (!$conn || $girlsId <= 0 || ($publishStatus !== 0 && $publishStatus !== 1)) {
		return $empty;
	}
	$sql = 'SELECT manager_comment_title,manager_comment_body,profile_hobby,profile_favorite_food,profile_favorite_color,profile_strength,profile_specialty,profile_day_off,sales_point_title,sales_point_body FROM girls_candy_page_content WHERE club_id=2 AND girls_id=? AND publish_status=? LIMIT 1';
	$stmt = @mysqli_prepare($conn, $sql);
	if (!$stmt) {
		return $empty;
	}
	mysqli_stmt_bind_param($stmt, 'ii', $girlsId, $publishStatus);
	if (!mysqli_stmt_execute($stmt)) {
		mysqli_stmt_close($stmt);
		return $empty;
	}
	mysqli_stmt_bind_result($stmt, $managerTitle, $managerBody, $hobby, $food, $color, $strength, $specialty, $dayOff, $salesTitle, $salesBody);
	if (!mysqli_stmt_fetch($stmt)) {
		mysqli_stmt_close($stmt);
		return $empty;
	}
	$content = array(
		'manager_comment_title' => $managerTitle,
		'manager_comment_body' => $managerBody,
		'profile_hobby' => $hobby,
		'profile_favorite_food' => $food,
		'profile_favorite_color' => $color,
		'profile_strength' => $strength,
		'profile_specialty' => $specialty,
		'profile_day_off' => $dayOff,
		'sales_point_title' => $salesTitle,
		'sales_point_body' => $salesBody
	);
	mysqli_stmt_close($stmt);

	$qa = array();
	$stmt = @mysqli_prepare($conn, 'SELECT question,answer FROM girls_candy_page_qa WHERE club_id=2 AND girls_id=? AND status=1 AND question IS NOT NULL AND answer IS NOT NULL ORDER BY sort_order,id');
	if (!$stmt) {
		return array('content' => $content, 'qa' => $qa);
	}
	mysqli_stmt_bind_param($stmt, 'i', $girlsId);
	if (mysqli_stmt_execute($stmt)) {
		mysqli_stmt_bind_result($stmt, $question, $answer);
		while (mysqli_stmt_fetch($stmt)) {
			if (trim((string)$question) !== '' && trim((string)$answer) !== '') {
				$qa[] = array('question' => $question, 'answer' => $answer);
			}
		}
	}
	mysqli_stmt_close($stmt);
	return array('content' => $content, 'qa' => $qa);
}

function candyGirlsPageLoadScheduleRows($conn, $girlsId, $newDayHour)
{
	$newDayHour = (int)$newDayHour;
	$baseTimestamp = time();
	if ((int)date('G', $baseTimestamp) < $newDayHour) {
		$baseTimestamp = strtotime('-1 day', $baseTimestamp);
	}
	$years = array();
	$months = array();
	$days = array();
	$weekdays = array();
	$scheduleData = array();
	for ($i = 0; $i < 7; $i++) {
		$timestamp = strtotime('+' . $i . ' day', $baseTimestamp);
		$years[$i] = (int)date('Y', $timestamp);
		$months[$i] = (int)date('n', $timestamp);
		$days[$i] = (int)date('j', $timestamp);
		$weekdays[$i] = (int)date('w', $timestamp);
	}
	if (!$conn || $girlsId <= 0) {
		return candyGirlsPageBuildScheduleRows($years, $months, $days, $weekdays, $scheduleData, $girlsId);
	}
	$startNumber = $years[0] * 10000 + $months[0] * 100 + $days[0];
	$endNumber = $years[6] * 10000 + $months[6] * 100 + $days[6];
	$sql = 'SELECT year,month,day,type,type2,open_ji,open_fun,end_ji,end_fun FROM girls_schedule WHERE club_id=2 AND girls_id=? AND status=1 AND views>=0 AND type IN (0,1,6) AND (year*10000+month*100+day) BETWEEN ? AND ? ORDER BY year,month,day,open_ji,open_fun,end_ji,end_fun,id DESC';
	$stmt = @mysqli_prepare($conn, $sql);
	if (!$stmt) {
		return candyGirlsPageBuildScheduleRows($years, $months, $days, $weekdays, $scheduleData, $girlsId);
	}
	mysqli_stmt_bind_param($stmt, 'iii', $girlsId, $startNumber, $endNumber);
	if (mysqli_stmt_execute($stmt)) {
		mysqli_stmt_bind_result($stmt, $year, $month, $day, $type, $type2, $openHour, $openMinute, $endHour, $endMinute);
		while (mysqli_stmt_fetch($stmt)) {
			for ($i = 0; $i < 7; $i++) {
				if ((int)$year === $years[$i] && (int)$month === $months[$i] && (int)$day === $days[$i] && !isset($scheduleData[$i]['type'][$girlsId])) {
					$scheduleData[$i]['type'][$girlsId] = (int)$type;
					$scheduleData[$i]['type2'][$girlsId] = (int)$type2;
					$scheduleData[$i]['open_ji'][$girlsId] = (int)$openHour;
					$scheduleData[$i]['open_fun'][$girlsId] = (int)$openMinute;
					$scheduleData[$i]['end_ji'][$girlsId] = (int)$endHour;
					$scheduleData[$i]['end_fun'][$girlsId] = (int)$endMinute;
					break;
				}
			}
		}
	}
	mysqli_stmt_close($stmt);
	return candyGirlsPageBuildScheduleRows($years, $months, $days, $weekdays, $scheduleData, $girlsId);
}

function candyGirlsPageBuildScheduleRows($years, $months, $days, $weekdays, $scheduleData, $girlsId)
{
	$labels = array('SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT');
	$rows = array();
	for ($i = 0; $i < 7; $i++) {
		$year = isset($years[$i]) ? (int)$years[$i] : 0;
		$month = isset($months[$i]) ? (int)$months[$i] : 0;
		$day = isset($days[$i]) ? (int)$days[$i] : 0;
		$weekday = isset($weekdays[$i]) ? (int)$weekdays[$i] : 0;
		$type = isset($scheduleData[$i]['type'][$girlsId]) ? (int)$scheduleData[$i]['type'][$girlsId] : -1;
		$type2 = isset($scheduleData[$i]['type2'][$girlsId]) ? (int)$scheduleData[$i]['type2'][$girlsId] : -1;
		$row = array(
			'label' => isset($labels[$weekday]) ? $labels[$weekday] : '',
			'datetime' => sprintf('%04d-%02d-%02d', $year, $month, $day),
			'date' => $month . '/' . $day,
			'time' => 'お休み',
			'note' => '次回出勤をご確認ください',
			'off' => true,
			'weekday' => $weekday
		);
		if ($type === 0 || $type === 1) {
			$openHour = isset($scheduleData[$i]['open_ji'][$girlsId]) ? (int)$scheduleData[$i]['open_ji'][$girlsId] : 0;
			$openMinute = isset($scheduleData[$i]['open_fun'][$girlsId]) ? (int)$scheduleData[$i]['open_fun'][$girlsId] : 0;
			$endHour = isset($scheduleData[$i]['end_ji'][$girlsId]) ? (int)$scheduleData[$i]['end_ji'][$girlsId] : 0;
			$endMinute = isset($scheduleData[$i]['end_fun'][$girlsId]) ? (int)$scheduleData[$i]['end_fun'][$girlsId] : 0;
			$open = $openHour === 100 ? '日の出' : sprintf('%d:%02d', $openHour, $openMinute);
			$end = $endHour === 99 ? 'LAST' : sprintf('%d:%02d', $endHour, $endMinute);
			$row['time'] = $open . '–' . $end;
			$row['note'] = 'ご予約受付中';
			$row['off'] = false;
		}
		if ($type2 === 6) {
			$row['time'] = '電話確認';
			$row['note'] = 'お電話でご確認ください';
			$row['off'] = false;
		} elseif ($type === 6 || $type2 === 3) {
			$row['time'] = '受付終了';
			$row['note'] = '本日の受付は終了しました';
			$row['off'] = true;
		}
		$rows[] = $row;
	}
	return $rows;
}

function candyGirlsPageRender($pageData, $scheduleRows, $movie, $visibility = null)
{
	$content = isset($pageData['content']) && is_array($pageData['content']) ? $pageData['content'] : array();
	if (!is_array($visibility)) {
		$visibility = candyGirlsPageBuildVisibility($pageData, $scheduleRows, $movie);
	}
	$qa = isset($visibility['qa_rows']) && is_array($visibility['qa_rows']) ? $visibility['qa_rows'] : array();
	$profileRows = isset($visibility['profile_rows']) && is_array($visibility['profile_rows']) ? $visibility['profile_rows'] : array();
	$movieSources = isset($visibility['movie_sources']) && is_array($visibility['movie_sources']) ? $visibility['movie_sources'] : array();
	$managerVisible = !empty($visibility['manager']);
	$salesVisible = !empty($visibility['sales']);
	$scheduleVisible = !empty($visibility['schedule']);
	$hasVisibleContent = $scheduleVisible || !empty($visibility['movie']) || $managerVisible || !empty($visibility['qa']) || !empty($visibility['profile']) || $salesVisible;
	if (!$hasVisibleContent) {
		return '';
	}
	$sectionToneIndex = 0;

	ob_start();
?>
<div class="girls-test-content girls-page-content" data-page-purpose="girls-profile-detail-content">
	<?php if ($scheduleVisible) { ?>
	<section class="girls-test-section<?php echo ($sectionToneIndex++ % 2 === 0) ? ' is-tone-white' : ' is-tone-gray'; ?>" id="girls-test-schedule" aria-labelledby="girls-test-schedule-title">
		<div class="girls-test-heading">
			<p class="girls-test-heading-en">WEEKLY SCHEDULE</p>
			<h2 id="girls-test-schedule-title">週間出勤スケジュール</h2>
			<p>今週の出勤予定をご確認いただけます。ご予約状況はお電話にてお問い合わせください。</p>
		</div>
		<ol class="girls-test-schedule" aria-label="一週間の出勤予定">
<?php foreach ($scheduleRows as $row) { ?>
			<li<?php echo !empty($row['off']) ? ' class="is-off"' : ''; ?>><span class="girls-test-day<?php echo (int)$row['weekday'] === 6 ? ' is-saturday' : ((int)$row['weekday'] === 0 ? ' is-sunday' : ''); ?>"><?php echo candyGirlsPageEscape($row['label']); ?><time class="girls-test-date" datetime="<?php echo candyGirlsPageEscape($row['datetime']); ?>"><?php echo candyGirlsPageEscape($row['date']); ?></time></span><strong><?php echo candyGirlsPageScheduleTimeHtml($row['time']); ?></strong><small><?php echo candyGirlsPageEscape($row['note']); ?></small></li>
<?php } ?>
		</ol>
	</section>
	<?php } ?>
<?php if (count($movieSources) > 0) { ?>
	<section class="girls-test-section girls-test-movie-section<?php echo ($sectionToneIndex++ % 2 === 0) ? ' is-tone-white' : ' is-tone-gray'; ?>" id="girls-test-movie" aria-labelledby="girls-test-movie-title">
		<div class="girls-test-heading">
			<p class="girls-test-heading-en">PROFILE MOVIE</p>
			<h2 id="girls-test-movie-title">プロフィール動画</h2>
			<p>写真だけでは伝わらない、表情や雰囲気をご覧ください。</p>
		</div>
		<div class="girls-test-movie-frame">
			<video controls playsinline preload="metadata"<?php echo !empty($movie['poster']) ? ' poster="' . candyGirlsPageEscape($movie['poster']) . '"' : ''; ?> aria-label="プロフィール動画">
<?php foreach ($movieSources as $source) { ?>
				<source src="<?php echo candyGirlsPageEscape($source['src']); ?>" type="<?php echo candyGirlsPageEscape($source['type']); ?>">
<?php } ?>
				お使いのブラウザは動画再生に対応していません。
			</video>
		</div>
	</section>
<?php } ?>
<?php if ($managerVisible) { ?>
	<section class="girls-test-section<?php echo ($sectionToneIndex++ % 2 === 0) ? ' is-tone-white' : ' is-tone-gray'; ?>" id="girls-test-manager" aria-labelledby="girls-test-manager-title">
		<div class="girls-test-heading"><p class="girls-test-heading-en">MANAGER'S VOICE</p><h2 id="girls-test-manager-title">店長紹介コメント</h2></div>
		<div class="girls-test-text-card">
<?php if (!empty($content['manager_comment_title'])) { ?><p class="girls-test-text-catch"><?php echo candyGirlsPageEscape($content['manager_comment_title']); ?></p><?php } ?>
<?php if (!empty($content['manager_comment_body'])) { ?><p><?php echo candyGirlsPageText($content['manager_comment_body']); ?></p><?php } ?>
		</div>
	</section>
<?php } ?>
<?php if (count($qa) > 0) { ?>
	<section class="girls-test-section<?php echo ($sectionToneIndex++ % 2 === 0) ? ' is-tone-white' : ' is-tone-gray'; ?>" id="girls-test-qa" aria-labelledby="girls-test-qa-title">
		<div class="girls-test-heading"><p class="girls-test-heading-en">PERSONAL Q&amp;A</p><h2 id="girls-test-qa-title">本人Q&amp;A</h2><p>彼女の人柄がもう少し分かる、本人へのショートインタビューです。</p></div>
		<div class="girls-test-qa-list">
<?php foreach ($qa as $index => $row) { ?>
			<details<?php echo $index === 0 ? ' open' : ''; ?>><summary><span>Q.</span><?php echo candyGirlsPageEscape($row['question']); ?></summary><div><span>A.</span><p><?php echo candyGirlsPageText($row['answer']); ?></p></div></details>
<?php } ?>
		</div>
	</section>
<?php } ?>
<?php if (count($profileRows) > 0) { ?>
	<section class="girls-test-section<?php echo ($sectionToneIndex++ % 2 === 0) ? ' is-tone-white' : ' is-tone-gray'; ?>" id="girls-test-profile" aria-labelledby="girls-test-profile-title">
		<div class="girls-test-heading"><p class="girls-test-heading-en">MORE PROFILE</p><h2 id="girls-test-profile-title">プロフィール詳細</h2><p>趣味や好みなど、基本プロフィール以外の情報をご紹介します。</p></div>
		<dl class="girls-test-profile-list">
<?php foreach ($profileRows as $row) { ?><div><dt><?php echo candyGirlsPageEscape($row['label']); ?></dt><dd><?php echo candyGirlsPageEscape($row['value']); ?></dd></div><?php } ?>
		</dl>
	</section>
<?php } ?>
<?php if ($salesVisible) { ?>
	<section class="girls-test-section<?php echo ($sectionToneIndex++ % 2 === 0) ? ' is-tone-white' : ' is-tone-gray'; ?>" id="girls-test-points" aria-labelledby="girls-test-points-title">
		<div class="girls-test-heading"><p class="girls-test-heading-en">CHARM POINTS</p><h2 id="girls-test-points-title">セールスポイント</h2></div>
		<div class="girls-test-text-card">
<?php if (!empty($content['sales_point_title'])) { ?><p class="girls-test-text-catch"><?php echo candyGirlsPageEscape($content['sales_point_title']); ?></p><?php } ?>
<?php if (!empty($content['sales_point_body'])) { ?><p><?php echo candyGirlsPageText($content['sales_point_body']); ?></p><?php } ?>
		</div>
	</section>
<?php } ?>
</div>
<?php
	return ob_get_clean();
}
