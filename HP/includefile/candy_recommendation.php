<?php
if (!defined('CANDY_RECOMMENDATION_READER')) { http_response_code(403); exit; }
function cmr_escape($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function cmr_filename($value) { return is_string($value) && preg_match('/^[a-f0-9]{64}\.(jpg|png)$/D', $value); }
function cmr_load($dsn) {
    // Bound only the new feature's connection; never alter the shared legacy connection.
    $db = mysqli_init();
    if (!$db) { throw new RuntimeException('Recommendation connection initialization failed'); }
    try {
        if (!defined('MYSQLI_OPT_READ_TIMEOUT')
            || !mysqli_options($db, MYSQLI_OPT_CONNECT_TIMEOUT, 3)
            || !mysqli_options($db, MYSQLI_OPT_READ_TIMEOUT, 5)
            || !mysqli_real_connect($db, $dsn['host'], $dsn['user'], $dsn['password'], $dsn['dbname'])
            || !mysqli_set_charset($db, 'utf8mb4')) {
            throw new RuntimeException('Recommendation bounded connection failed');
        }
        return cmr_read($db);
    } finally { mysqli_close($db); }
}
function cmr_read($db) {
    $state = mysqli_query($db,'SELECT schema_version,migration_ready FROM candy_recommendation_settings WHERE club_id=2');
    if (!$state) { throw new RuntimeException('Recommendation settings unavailable'); }
    $settings=mysqli_fetch_assoc($state); mysqli_free_result($state);
    if (!$settings || (int)$settings['schema_version']!==1 || (int)$settings['migration_ready']!==1) { throw new RuntimeException('Recommendation migration incomplete'); }
    // One result set drives HTML and JSON-LD. No writes or connection-charset changes.
    $result=mysqli_query($db,"SELECT g.id,g.cast_id,g.club_id,g.no,g.name,g.status,g.height,g.bust,g.cup,g.waist,g.hip,
        c.id AS cast_exists,c.status AS cast_status,r.cast_id AS recommendation_cast_id,
        r.pc_image,r.sp_image,r.title,r.heading,r.body,
        CASE WHEN p.publish_status=1 THEN p.profile_hobby ELSE '' END AS hobby,
        n.no_count FROM candy_recommendations r
        JOIN girls_data g ON g.id=r.girls_id AND g.club_id=2
        JOIN cast_mast c ON c.id=g.cast_id
        JOIN (SELECT no,COUNT(*) AS no_count FROM girls_data WHERE club_id=2 GROUP BY no) n ON n.no=g.no
        LEFT JOIN girls_candy_page_content p ON p.girls_id=g.id AND p.club_id=2
        WHERE r.club_id=2 AND r.selected=1 AND g.status=1 AND c.status=1
        ORDER BY r.sort_order,r.girls_id");
    if (!$result) { throw new RuntimeException('Recommendation data unavailable'); }
    $rows=array(); while ($row=mysqli_fetch_assoc($result)) { $rows[]=$row; } mysqli_free_result($result);
    return $rows;
}
function cmr_eligible($row) {
    if ((int)$row['club_id']!==2 || (int)$row['status']!==1 || empty($row['cast_exists']) || (int)$row['cast_status']!==1
        || (int)$row['cast_id']!==(int)$row['recommendation_cast_id'] || (int)$row['no']<1 || (int)$row['no_count']!==1) { return false; }
    foreach (array('pc_image','sp_image','title','heading','body') as $key) { if (!isset($row[$key]) || trim($row[$key])==='') { return false; } }
    return cmr_filename($row['pc_image']) && cmr_filename($row['sp_image']);
}
function cmr_render($rows, $header, $config) {
    $cards=''; $items=array();
    foreach ($rows as $row) {
        if (!cmr_eligible($row)) { continue; }
        $link='./girls.php?no='.(int)$row['no']; $pc=$config['image_url'].$row['pc_image']; $sp=$config['image_url'].$row['sp_image'];
        $cup=(int)$row['cup']; $cup=$cup>=1 && $cup<=10 ? chr(64+$cup) : '';
        $style='T'.(int)$row['height'].'.B'.(int)$row['bust'].'（'.$cup.'）.W'.(int)$row['waist'].'.H'.(int)$row['hip'];
        $cards.='<li class="girls-info-item"><div class="girls-info bg_f lmt_20"><div class="girls-info-img-wrap"><a href="'.$link.'" class="girls-info-img-link"><picture>'
            .'<source media="(max-width: 768px)" srcset="'.cmr_escape($sp).'"><img src="'.cmr_escape($pc).'" alt="'.cmr_escape($row['title']).'" width="300" height="498" loading="lazy" class="nolazy"></picture></a></div>'
            .'<div class="girls-info-text lp_25"><h3 class="lpb_15 fs_xl fc_p">'.cmr_escape($row['title']).'</h3>'
            .'<div class="lpb_7 fs_md3"><strong>'.nl2br(cmr_escape($row['heading'])).'</strong></div>'
            .'<div class="lpb_30 fs_md3">'.nl2br(cmr_escape($row['body'])).'</div>'
            .'<table class="campaign-table fs_md3"><tr><td>スタイル</td><td>'.cmr_escape($style).'</td></tr>'
            .'<tr><td>趣味</td><td>'.cmr_escape($row['hobby']).'</td></tr></table>'
            .'<div class="lmt_20"><a href="'.$link.'" class="bt-pk-m">'.cmr_escape($row['name']).' 詳細</a></div></div></div></li>';
        $items[]=array('@type'=>'ListItem','position'=>count($items)+1,'item'=>array('@type'=>'Person','name'=>$row['name'],
            'url'=>'https://www.55810.com/girls.php?no='.(int)$row['no'],'image'=>$pc));
    }
    if (!$items) { return array('html'=>'','json'=>''); }
    $json=json_encode(array('@context'=>'https://schema.org','@type'=>'ItemList','name'=>'鹿児島デリヘルキャンディ店長おすすめの女の子一覧',
        'itemListOrder'=>'https://schema.org/ItemListOrderAscending','numberOfItems'=>count($items),'itemListElement'=>$items),
        JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE);
    if ($json===false) { throw new RuntimeException('Recommendation encoding failed'); }
    return array('html'=>$header.$cards.'</ul></div>', 'json'=>'<script type="application/ld+json">'.$json.'</script>');
}
function cmr_slots($source) {
    $patterns=array('html'=>'/<!-- 店長おすすめの女の子 START -->(.*?)<!-- 店長おすすめの女の子 END -->/s',
        'json'=>'/<!-- CANDY_RECOMMENDATION_JSON_START -->(.*?)<!-- CANDY_RECOMMENDATION_JSON_END -->/s');
    $header=''; $valid=true;
    foreach ($patterns as $key=>$pattern) {
        if (preg_match_all($pattern,$source,$matches)!==1) { $valid=false; }
        if ($key==='html' && isset($matches[1][0])) {
            $needle='<ul class="campaign-list" role="list">'; $end=strpos($matches[1][0],$needle);
            if ($end===false) { $valid=false; } else { $header=substr($matches[1][0],0,$end+strlen($needle)); }
        }
        $source=preg_replace($pattern,'<!-- CANDY_RECOMMENDATION_SLOT_'.strtoupper($key).' -->',$source);
    }
    if (!$valid) {
        // Known legacy boundaries are a fallback for a missing comment in a partial template deployment.
        // Keep the girl-list/schedule links outside the removed range.
        $source=preg_replace('/<div>\s*<picture>\s*<source[^>]*candy_manager_recommendation_sp\.jpg[^>]*>.*?(?=<div class="lpt_50 center"><a href="\.\/girls_list\.php")/s','',$source);
        $source=preg_replace('/<script type="application\/ld\+json">(?:(?!<\/script>).)*鹿児島デリヘルキャンディ店長おすすめの女の子一覧(?:(?!<\/script>).)*<\/script>/s','',$source);
    }
    return array('source'=>$source,'header'=>$header,'valid'=>$valid);
}
function cmr_insert($source, $parts) {
    $map=array('<!-- CANDY_RECOMMENDATION_SLOT_HTML -->'=>$parts['html'],'<!-- CANDY_RECOMMENDATION_SLOT_JSON -->'=>$parts['json']);
    foreach ($map as $slot=>$value) {
        if (substr_count($source,$slot)!==1) { error_log('CANDY_RECOMMENDATION: slot mismatch'); return strtr($source,array_fill_keys(array_keys($map),'')); }
    }
    // strtr does not reprocess inserted user text, including slot-like/rep codes.
    return strtr($source,$map);
}
