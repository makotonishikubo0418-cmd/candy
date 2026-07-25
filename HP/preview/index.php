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
