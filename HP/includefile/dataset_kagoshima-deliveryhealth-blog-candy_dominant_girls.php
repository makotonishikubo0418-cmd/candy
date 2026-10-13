<?php
// このページ専用の関連PHP。本文はsourceの同名HTMLで編集。
$source = file_get_contents($source_file);
$source = str_replace($waku0, $waku_html, $source);
// HTMLを直接開く場合の相対パス補助。PHP表示では既存ブログと同じ./を使用。
$source = str_replace('<base href="../">', '', $source);
?>
