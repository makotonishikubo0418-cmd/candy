<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

header('X-Robots-Tag: noindex, nofollow', true);
header('Cache-Control: no-store, max-age=0', true);

$originalScriptFilename = isset($_SERVER['SCRIPT_FILENAME'])
	? $_SERVER['SCRIPT_FILENAME']
	: null;
$_SERVER['SCRIPT_FILENAME'] = '/home/firststar/public_html/group/candy/index.php';

ob_start();
include('/home/firststar/public_html/group/candy/includefile/dataset_base.php');
$html = ob_get_clean();

if ($originalScriptFilename === null) {
	unset($_SERVER['SCRIPT_FILENAME']);
} else {
	$_SERVER['SCRIPT_FILENAME'] = $originalScriptFilename;
}

$tileScriptPath = '/home/firststar/public_html/group/candy/js/candyTile.js';
$tileScript = file_get_contents($tileScriptPath);
$tileGuard = 'if(!el || typeof gBox === "undefined"){ return false; }';
$tileGuardFixed = 'if(!el || typeof gBox === "undefined" || typeof tmplate === "undefined"){ return false; }';
$tileScheduleCall = 'scheduleTileInit();';

if (
	$tileScript === false
	|| substr_count($tileScript, $tileGuard) !== 1
	|| substr_count($tileScript, $tileScheduleCall) !== 1
	|| stripos($tileScript, '</script') !== false
) {
	header('HTTP/1.1 500 Internal Server Error');
	print 'Preview tile script preparation failed.';
	exit;
}

$tileScript = str_replace($tileGuard, $tileGuardFixed, $tileScript);
$tileScript = str_replace($tileScheduleCall, '', $tileScript);
$tileScript = rtrim($tileScript) . "\n\n" . $tileScheduleCall . "\n";

$tileScriptPattern = '#<script\s+defer\s+type="text/javascript"\s+src="\./js/candyTile\.js(?:\?v=[a-f0-9]+)?"></script>#';
if (preg_match_all($tileScriptPattern, $html) !== 1) {
	header('HTTP/1.1 500 Internal Server Error');
	print 'Preview tile script replacement failed.';
	exit;
}
$html = preg_replace_callback(
	$tileScriptPattern,
	function () use ($tileScript) {
		return "<script type=\"text/javascript\">\n" . $tileScript . "</script>";
	},
	$html
);

$tileTemplateReplacements = array(
	'<div class="photo"><img  src="imgHtml/dot.gif" alt="____photo____"></div>' =>
		"<!--photo-->\n\t<div class=\"photo\"><img  src=\"imgHtml/dot.gif\" alt=\"____photo____\"></div>\n\t<!--photoEnd-->",
	'<div class="movie"> <img  src="imgHtml/dot.gif"  alt="____photo____">' =>
		"<!--video-->\n\t<div class=\"movie\"> <img  src=\"imgHtml/dot.gif\"  alt=\"____photo____\">",
	'<div class="photo"><img src="imgHtml/dot.gif" alt="____photo____"></div>' =>
		"<!--photo-->\n\t<div class=\"photo\"><img src=\"imgHtml/dot.gif\" alt=\"____photo____\"></div>\n\t<!--photoEnd-->",
	'<div class="movie"> <img src="imgHtml/dot.gif" alt="____photo____">' =>
		"<!--video-->\n\t<div class=\"movie\"> <img src=\"imgHtml/dot.gif\" alt=\"____photo____\">"
);
foreach ($tileTemplateReplacements as $search => $replacement) {
	if (substr_count($html, $search) !== 1) {
		header('HTTP/1.1 500 Internal Server Error');
		print 'Preview tile template preparation failed.';
		exit;
	}
	$html = str_replace($search, $replacement, $html);
}

$tileVideoEnd = "\t</div>\n\t<dl class=\"data vCenter\">";
if (substr_count($html, $tileVideoEnd) !== 2) {
	header('HTTP/1.1 500 Internal Server Error');
	print 'Preview tile video marker preparation failed.';
	exit;
}
$html = str_replace(
	$tileVideoEnd,
	"\t</div>\n\t<!--videoEnd-->\n\t<dl class=\"data vCenter\">",
	$html
);

$robotsMeta = '<meta name="robots" content="index">';
$headTag = '<head>';
if (substr_count($html, $robotsMeta) !== 1 || substr_count($html, $headTag) !== 1) {
	header('HTTP/1.1 500 Internal Server Error');
	print 'Preview rendering failed.';
	exit;
}

$html = str_replace(
	$robotsMeta,
	'<meta name="robots" content="noindex,nofollow">',
	$html
);
$html = str_replace(
	$headTag,
	"<head>\n\t<base href=\"/\">",
	$html
);

print $html;
?>
