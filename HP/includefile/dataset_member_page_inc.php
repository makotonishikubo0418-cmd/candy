<?

/**
 * 会員ページ（ログイン・登録）共通: テンプレート読込とヘッダーお気に入り数
 */
$source = file_get_contents($source_file);

$favcast = array();
if (isset($_COOKIE['candyfav'])) {
	$favcast = explode(',', urldecode($_COOKIE['candyfav']));
}
$data1['00010601'] = count($favcast);

if ($data1['00010601'] > 0) {
	$source = str_replace('class="num" style="display:none;"', 'class="num"', $source);
	$source = str_replace('class="headNavi"', 'class="headNavi headNavi2"', $source);
}
