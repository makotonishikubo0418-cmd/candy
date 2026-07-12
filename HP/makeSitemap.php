<?php
set_time_limit(0);
$page=array();
$seed=array();
$domain=(empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"];//末尾の/は無し
array_push($seed,$domain."/",$domain."/pc/pc_index.php",$domain."/s/sp_index.php");

while(count($seed)>0){
	$headerParams = @get_headers($seed[0]);
	if($headerParams[0] === 'HTTP/1.1 404 Not Found') {
		array_shift($seed);
	} else {
		$href=getHref($seed[0]);

		$href=array_values(array_diff($href,$page));

	
		$href=array_values(array_diff($href,$seed));

		$seed=array_merge($seed,$href);
		$tmp=array_shift($seed);
		if(strpos($tmp,'index') === false && strpos($tmp,'___') === false){
			$page[]=$tmp;
		}
	}
}
sort($page);
if($_GET['mode']=='test'){
	echo $_SERVER['HTTP_HOST'];
	test($page);
}else{
	header('Content-Type: application/xhtml+xml; charset=utf-8');
	echo "<?xml version='1.0' encoding='UTF-8'?>\n";
	echo "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";
	for($i=0;$i<count($page);$i++){
			echo "<url>\n";
			echo "<loc>".htmlspecialchars($page[$i], ENT_QUOTES)."</loc>\n";
			echo "</url>\n";
	}
	echo "</urlset>";
}
function test($t){
	echo "<pre>";
	var_dump($t);
	echo "</pre>";
	ob_flush();
	flush();
}


if(!function_exists('my_file_get_contents')){
    /**
     * Read the contents of a file into a string.
     *
     * @param string $filename The path to the file to read.
     * @return string|false Returns the file contents as a string on success, or false on failure.
     */
    function my_file_get_contents($filename)
    {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

        return file_get_contents($filename, false, $context);
    }
}
function getHref($path){
		
	global $domain;
	$ptn='/<a[^>]href\s?=\s?[\"\']([^\"\']+)[\"\'][^>]*>/i';
    preg_match_all($ptn, preg_replace('/<!--[\s\S]*?-->/s', '', my_file_get_contents($path)), $m);
	$m=$m[1];
	//リンクで無いhrefを削除
	if(count($m)>0){
		$iMax=count($m);
		for($i=0;$i<$iMax;$i++){
			if(strncasecmp($m[$i],"tel:",4)==0) unset($m[$i]);
			if(strncasecmp($m[$i],"mailto:",7)==0) unset($m[$i]);
			if(strncasecmp($m[$i],"javascript:",11)==0) unset($m[$i]);
			if(strncasecmp($m[$i],"#",1)==0) unset($m[$i]);
		}
	}
	
	$m=array_values($m);
	//相対パスを絶対パスに変換
	if(count($m)>0){
		$iMax=count($m);
		for($i=0;$i<$iMax;$i++){
			if(strpos($m[$i],'://') === false){
				$m[$i]=pathToUrl($m[$i],$path);
			}
			if(strpos($m[$i],'#') >0){
				$m[$i]=explode("#",$m[$i]);
				$m[$i]=$m[$i][0];
			}
			//外部サイトを削除
			if(strncasecmp($m[$i],$domain,strlen($domain))!=0) unset($m[$i]);
		}
	}
	
	$m=array_unique($m);
	
	$m=array_values($m);
    return $m;
}
function pathToUrl($pPath, $pUrl)
{
    $path = trim($pPath);    // 変換対象パス
    $url = trim($pUrl);      // 変換元URL

    //-- 変換不要
    if (stripos($path, 'http') === 0 ||
        stripos($path, 'mailto:') === 0 ||
        stripos($path, 'tel:') === 0) { return $path; }

    //-- #anchor
    if (strpos($path, '#') === 0) { return $url . $path; }

    //-- 変換元URLのホームURL(scheme://host)
    $tmpUrlAry = explode('/', $url);
    if (empty($tmpUrlAry[2])) { return $url; }
    $urlHome = $tmpUrlAry[0] . '//' . $tmpUrlAry[2];

    //-- 変換元URLの path
    if (!$tmpUrlAry = parse_url($url)) { return $url; }
    $pathUrl = (isset($tmpUrlAry['path'])) ? $tmpUrlAry['path'] : '/';

    //-- ?query
    if (strpos($path, '?') === 0) { return $urlHome . $pathUrl . $path; }

    //-- /path
    if (strpos($path, '/') === 0) { return $urlHome . $path; }

    //-- ./path or ../path
    $pathUrlAry = array_filter(explode('/', $pathUrl), 'strlen');
    if (strpos(end($pathUrlAry), '.') !== FALSE) { array_pop($pathUrlAry); }

    foreach (explode('/', $path) as $pathElem) {
        if ($pathElem === '.') { continue; }
        if ($pathElem === '..') { array_pop($pathUrlAry); continue; }
        if ($pathElem !== '') { $pathUrlAry[] = $pathElem; }
    }

    $urlBuild = $urlHome . '/' . implode('/', $pathUrlAry);
    if (substr($path, -1) === '/') { $urlBuild .= '/'; }

    return $urlBuild;
}
?>