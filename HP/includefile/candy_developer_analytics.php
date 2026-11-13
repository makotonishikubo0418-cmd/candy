<?php

/** Add the developer property without replacing the operator's Google tag. */
function candy_developer_analytics($html, $measurementId, $host, $isTest)
{
    $host = strtolower(preg_replace('/:\d+$/', '', $host));
    if ($isTest || !in_array($host, array('www.55810.com', '55810.com'), true)
        || !preg_match('/^G-[A-Z0-9]+$/D', $measurementId)
        || stripos($html, '</head>') === false
        || strpos($html, 'id="candy-developer-analytics"') !== false
        || strpos($html, $measurementId) !== false) {
        return $html;
    }

    $tag = '';
    if (!preg_match('~<script\b[^>]*\bsrc=["\'][^"\']*googletagmanager\.com/gtag/js\?~i', $html)) {
        $tag .= '<script async src="https://www.googletagmanager.com/gtag/js?id='
            . $measurementId . '"></script>' . "\n";
    }
    $tag .= '<script id="candy-developer-analytics">' . "\n"
        . '(function(w){' . "\n"
        . '  w.dataLayer = w.dataLayer || [];' . "\n"
        . '  if (typeof w.gtag !== "function") {' . "\n"
        . '    w.gtag = function(){w.dataLayer.push(arguments);};' . "\n"
        . '    w.gtag("js", new Date());' . "\n"
        . '  }' . "\n"
        . '  w.gtag("config", ' . json_encode($measurementId) . ');' . "\n"
        . '})(window);' . "\n"
        . '</script>' . "\n";

    $position = stripos($html, '</head>');
    return substr($html, 0, $position) . $tag . substr($html, $position);
}
