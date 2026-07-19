<?php
set_time_limit(0);

const CANONICAL_HOST = 'https://www.55810.com';

$excludedPages = array(
    'create.php',
    'main.php',
    'makeSitemap.php',
    'movie_iframe.php',
    'page.php',
    'test.php',
    // Legacy duplicate slugs. Keep the approved current slugs in the sitemap.
    'kagoshima-deliveryhealth-area-kenohikarigaoka.php',
    'kagoshima-deliveryhealth-area-kiirehitokuracho.php',
    'kagoshima-deliveryhealth-area-kiirenakamyoch.php',
);

$urls = array();
$sourceFiles = glob(__DIR__ . '/source/*.html');

foreach ($sourceFiles as $sourceFile) {
    $sourceName = basename($sourceFile, '.html');
    if (strpos($sourceName, 'template_') === 0) {
        continue;
    }

    $publicName = $sourceName . '.php';
    if (in_array($publicName, $excludedPages, true)) {
        continue;
    }

    if (!is_file(__DIR__ . '/' . $publicName)) {
        continue;
    }

    $html = file_get_contents($sourceFile);
    if ($html === false) {
        continue;
    }

    $robots = getMetaContent($html, 'robots');
    if (!isIndexableRobots($robots)) {
        continue;
    }

    $canonical = getApprovedCanonical(getCanonicalUrl($html), $publicName);
    if ($canonical === false) {
        continue;
    }

    $urls[$canonical] = true;
}

$urls = array_keys($urls);
sort($urls, SORT_STRING);

header('Content-Type: application/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($urls as $url) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($url, ENT_QUOTES | ENT_XML1, 'UTF-8') . "</loc>\n";
    echo "  </url>\n";
}
echo "</urlset>\n";

function getMetaContent($html, $name)
{
    if (!preg_match_all('/<meta\b[^>]*>/i', $html, $tags)) {
        return '';
    }

    foreach ($tags[0] as $tag) {
        if (strcasecmp(getHtmlAttribute($tag, 'name'), $name) === 0) {
            return getHtmlAttribute($tag, 'content');
        }
    }

    return '';
}

function getCanonicalUrl($html)
{
    if (!preg_match_all('/<link\b[^>]*>/i', $html, $tags)) {
        return '';
    }

    foreach ($tags[0] as $tag) {
        $rel = preg_split('/\s+/', strtolower(trim(getHtmlAttribute($tag, 'rel'))));
        if (in_array('canonical', $rel, true)) {
            return trim(getHtmlAttribute($tag, 'href'));
        }
    }

    return '';
}

function getHtmlAttribute($tag, $attribute)
{
    $pattern = '/\b' . preg_quote($attribute, '/') . '\s*=\s*(["\'])(.*?)\1/i';
    if (!preg_match($pattern, $tag, $match)) {
        return '';
    }

    return html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function isIndexableRobots($robots)
{
    $tokens = array_map('trim', explode(',', strtolower($robots)));
    return in_array('index', $tokens, true) && !in_array('noindex', $tokens, true);
}

function getApprovedCanonical($canonical, $publicName)
{
    if ($canonical === '') {
        return false;
    }

    $parts = parse_url($canonical);
    if ($parts === false || !isset($parts['scheme'], $parts['host'])) {
        return false;
    }

    if (strtolower($parts['scheme']) !== 'https' || strtolower($parts['host']) !== 'www.55810.com') {
        return false;
    }

    if (isset($parts['port']) || isset($parts['user']) || isset($parts['pass']) || isset($parts['query']) || isset($parts['fragment'])) {
        return false;
    }

    $path = isset($parts['path']) ? $parts['path'] : '/';
    if ($publicName === 'index.php') {
        return $path === '/' ? CANONICAL_HOST . '/' : false;
    }

    if ($path !== '/' . $publicName) {
        return false;
    }

    return CANONICAL_HOST . $path;
}
?>
