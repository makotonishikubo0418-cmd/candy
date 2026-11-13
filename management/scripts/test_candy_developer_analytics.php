<?php
// Offline checks only: no application bootstrap, database or Google requests.
require_once dirname(dirname(__DIR__)) . '/HP/includefile/candy_developer_analytics.php';

function analytics_assert($condition, $message)
{
    if (!$condition) {
        fwrite(STDERR, $message . "\n");
        exit(1);
    }
}

$id = 'G-5RN2Z12HB8';
$owner = 'G-0VBTBPHDD2';
$fixture = '<html><head><script async src="https://www.googletagmanager.com/gtag/js?id='
    . $owner . '"></script><script>window.dataLayer=[];function gtag(){dataLayer.push(arguments);}'
    . 'gtag("js",new Date());gtag("config","' . $owner . '");</script></head><body>Page</body></html>';
$result = candy_developer_analytics($fixture, $id, 'www.55810.com', false);
analytics_assert(substr_count($result, 'gtag/js?id=') === 1, 'Existing loader must not be duplicated');
analytics_assert(substr_count($result, 'gtag("config","' . $owner . '")') === 1, 'Operator configuration must stay unchanged');
analytics_assert(substr_count($result, 'w.gtag("config", "' . $id . '")') === 1, 'Developer configuration must occur once');
analytics_assert(candy_developer_analytics($result, $id, 'www.55810.com', false) === $result, 'Repeated injection must be idempotent');
analytics_assert(strpos($result, "</script>\n</head>") !== false, 'Tag must be inside head');
analytics_assert(substr($result, strpos($result, '</head>')) === substr($fixture, strpos($fixture, '</head>')), 'Page body must be unchanged');
foreach (array('localhost', 'firststar.kir.jp', 'www.55810.com.example.com', '') as $host) {
    analytics_assert(candy_developer_analytics($fixture, $id, $host, false) === $fixture, 'Excluded host changed: ' . $host);
}
analytics_assert(candy_developer_analytics($fixture, $id, 'www.55810.com', true) === $fixture, 'Test environment must be excluded');
analytics_assert(candy_developer_analytics('{"ok":true}', $id, 'www.55810.com', false) === '{"ok":true}', 'Non-HTML must be unchanged');
analytics_assert(candy_developer_analytics($fixture, 'invalid', 'www.55810.com', false) === $fixture, 'Invalid ID must be rejected');
$untagged = '<html><head></head><body>Page</body></html>';
$fallback = candy_developer_analytics($untagged, $id, '55810.com:443', false);
analytics_assert(substr_count($fallback, 'gtag/js?id=' . $id) === 1, 'Missing loader must be added once');

$tested = 0;
foreach (glob(dirname(dirname(__DIR__)) . '/HP/source/*.html') as $path) {
    $source = file_get_contents($path);
    if (stripos($source, '</head>') === false) {
        continue;
    }
    $output = candy_developer_analytics($source, $id, 'www.55810.com', false);
    analytics_assert(substr_count($output, 'id="candy-developer-analytics"') === 1, 'Missing or duplicate tag: ' . basename($path));
    analytics_assert(substr_count($source, $owner) === substr_count($output, $owner), 'Operator tag changed: ' . basename($path));
    analytics_assert(candy_developer_analytics($output, $id, 'www.55810.com', false) === $output, 'Duplicate on repeat: ' . basename($path));
    $tested++;
}
echo 'ANALYTICS_CHECK=PASS source_pages=' . $tested . "\n";
