<?php
/*********************************************************************
* データセット(統合版 - PC/SP対応)
 * 
 * 2011-07-
 *********************************************************************/

//-----  変数取得・設定  -----//
$img_path = IMG_HOME . CLUBID . '/' . UP_DIR_NEWS;

// お気に入り数の設定
$favcast = array();
if(isset($_COOKIE["candyfav"])){
	$favcast = explode(',', urldecode($_COOKIE["candyfav"]));
}
$data1['00010601'] = count($favcast);

// デバイス判定
$is_sp = false;
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/(iPhone|iPod|Android|Windows Phone|Mobile|BlackBerry|IEMobile)/', $user_agent)) {
        $is_sp = true;
    }
}

//-----  処理  -----//
//movie情報を取得
$newsldata = array();
$newsldata["id"] = array();
$QUERY  = "SELECT id,no,date,caption,detail FROM shop_movie_archive_mast";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = 1";
$QUERY .= " ORDER BY date DESC, id DESC";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$newsldata["id"][]          = $row["id"];
		$newsldata["no"][$row["id"]]          = $row["no"];
		$newsldata["date"][$row["id"]]        = $row["date"];
		$newsldata["caption"][$row["id"]]     = $row["caption"];
		$newsldata["detail"][$row["id"]]      = $row["detail"];
	}
}

//動画情報取得（SP版とPC版で異なる処理）
		$filedata = array();
		$filedata["id"] = array();
if ($is_sp) {
    // SP版：全ファイルタイプを取得
		$QUERY  = "SELECT id,arch_id,filename,filetype FROM shop_movie_archive_file";
		$QUERY .= " WHERE club_id = '" . CLUBID . "'";
		$QUERY .= " AND status = 1";
		$QUERY .= " ORDER BY id DESC";
		$RESULT = $Database->Query($QUERY);
		$ROWS = $Database->Num_Rows($RESULT);
    if($ROWS != 0){
        while($row = $Database->Fetch_Array($RESULT)){
            if(in_array($row["arch_id"], $newsldata["id"])){
                $filedata[$row["arch_id"]][$row["filetype"]] = $row["filename"];
            }
        }
    }
			} else {
    // PC版：サムネイルのみ取得
		$QUERY  = "SELECT id,arch_id,filename FROM shop_movie_archive_file";
		$QUERY .= " WHERE club_id = '" . CLUBID . "'";
		$QUERY .= " AND status = 1";
		$QUERY .= " AND filetype = 4";
		$QUERY .= " ORDER BY id DESC";
		$RESULT = $Database->Query($QUERY);
		$ROWS = $Database->Num_Rows($RESULT);
    if($ROWS != 0){
        while($row = $Database->Fetch_Array($RESULT)){
            $filedata["id"][] = $row["arch_id"];
            $filedata["filename"][$row["arch_id"]] = $row["filename"];
        }
			}
		}

		$arch_dir = IMG_HOME . CLUBID . '/' . UP_DIR_ARCHIVE;

		/*
* 独自タグから表示枠ソースを取得
*/
		$source = file_get_contents($source_file);

		/*
* 枠1
*/
		$result = preg_match_all('/(<!-- girlsbox_01 -->.*?<!-- \/girlsbox_01 -->)/s', $source, $get_code);
		$waku0 = $get_code[0][0];
		$waku = str_replace('<!-- girlsbox_01 -->', '', $waku0);
		$waku_01 = str_replace('<!-- /girlsbox_01 -->', '', $waku);

		$waku_html = "";

		$j = 1;
		$i = 0;
		$h = 0;
while($i < count($newsldata["id"])){
			$nid = $newsldata["id"][$i];

			//枠の初期化
	$waku1 = $waku_01;

			//日付
	list($yy,$mm,$dd) = explode('-', $newsldata["date"][$nid]);
	$day = $yy .'.'. $mm;
			$waku1 = str_replace('rep00010400eot', $day, $waku1);

	$waku1 = str_replace('rep00010401eot', $newsldata["caption"][$nid], $waku1);

	// PC版のみdetailを設定
	if (!$is_sp) {
		$waku1 = str_replace('rep00010402eot', $newsldata["detail"][$nid], $waku1);
	}

			//サムネイル
	if ($is_sp) {
		// SP版の処理
		if($filedata[$nid][4] != ""){
			$img = $arch_dir . $filedata[$nid][4];
				$waku1 = str_replace('rep01010070eot', $img, $waku1);
		}else{
			$imgsize = 310;
			$imgfile = DAMMY_IMG_SQ_w;
			$imgtype = 'dmy';
			$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
			$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
		}
			} else {
		// PC版の処理
		if($filedata["filename"][$nid] != ""){
			$img = $arch_dir . $filedata["filename"][$nid];
			$waku1 = str_replace('rep01010070eot', $img, $waku1);
		}else{
				$imgsize = 310;
				$imgfile = DAMMY_IMG_SQ_w;
				$imgtype = 'dmy';
			$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
				$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
		}
			}

			//iframe link
			$waku1 = str_replace('rep03010090eot', 'movie_iframe.php?mids=' . $nid, $waku1);

			$waku_html .= $waku1;

			$h++;
			$j++;
			$i++;
			//MAX1
	if($i > 0){ break; }
		}

		//置換
		$source = str_replace($waku0, $waku_html, $source);

		//
		$data1['01010013'] = $newsldata["detail"][$nid];

		/*
* 枠2
*/
		$result = preg_match_all('/(<!-- girlsbox_02 -->.*?<!-- \/girlsbox_02 -->)/s', $source, $get_code);
		$waku0 = $get_code[0][0];
		$waku = str_replace('<!-- girlsbox_02 -->', '', $waku0);
		$waku_01 = str_replace('<!-- /girlsbox_02 -->', '', $waku);

		$waku_html = "";

		$j = 1;
		$i = 0;
		$h = 0;
while($i < count($newsldata["id"])){
	if($i > 0){
				$nid = $newsldata["id"][$i];

				//枠の初期化
		$waku1 = $waku_01;

				//日付
		list($yy,$mm,$dd) = explode('-', $newsldata["date"][$nid]);
		$day = $yy .'.'. $mm;
				$waku1 = str_replace('rep00010400eot', $day, $waku1);

		$waku1 = str_replace('rep00010401eot', $newsldata["caption"][$nid], $waku1);
		
		// PC版のみdetailを設定
		if (!$is_sp) {
			$waku1 = str_replace('rep00010402eot', $newsldata["detail"][$nid], $waku1);
		}

			//サムネイル
		if ($is_sp) {
			// SP版の処理
			if($filedata[$nid][4] != ""){
				$img = $arch_dir . $filedata[$nid][4];
				$waku1 = str_replace('rep01010070eot', $img, $waku1);
			}else{
					$imgsize = 310;
					$imgfile = DAMMY_IMG_SQ_w;
					$imgtype = 'dmy';
				$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
					$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
	}
} else {
			// PC版の処理
			if($filedata["filename"][$nid] != ""){
		$img = $arch_dir . $filedata["filename"][$nid];
		$waku1 = str_replace('rep01010070eot', $img, $waku1);
			}else{
		$imgsize = 310;
		$imgfile = DAMMY_IMG_SQ_w;
		$imgtype = 'dmy';
				$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
		$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
	}
		}

		//iframe link
		$waku1 = str_replace('rep03010090eot', 'movie_iframe.php?mids=' . $nid, $waku1);

		$waku_html .= $waku1;

		$h++;
	}
	$j++;
	$i++;
}

//置換
$source = str_replace($waku0, $waku_html, $source);

/*
* 女の子情報取得
*/
$girldata = array();
$girldata["id"] = array();
$QUERY  = "SELECT";
$QUERY .= " id, club_id, no, name, age, height, bust, cup, waist, hip, name_kana, name_romaji";
$QUERY .= " FROM girls_data";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = 1";
$QUERY .= " ORDER BY id DESC";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$girldata["id"][]          = $row["id"];
		$girldata["club_id"][$row["id"]]     = $row["club_id"];
		$girldata["no"][$row["id"]]          = $row["no"];
		$girldata["name"][$row["id"]]        = $row["name"];
		$girldata["name_kana"][$row["id"]]   = $row["name_kana"];
		$girldata["name_romaji"][$row["id"]] = $row["name_romaji"];
		$girldata["age"][$row["id"]]         = $row["age"];
		$girldata["height"][$row["id"]]      = $row["height"];
		$girldata["bust"][$row["id"]]        = $row["bust"];
		$girldata["cup"][$row["id"]]         = $row["cup"];
		$girldata["waist"][$row["id"]]       = $row["waist"];
		$girldata["hip"][$row["id"]]         = $row["hip"];
	}
}

/*
* 動画情報取得
*/
$moviedata = array();
$filedatab = array();
$QUERY  = "SELECT id,filetype,filename,girls_id,update_time FROM girls_movie_file";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = '1'";
$QUERY .= " ORDER BY id DESC";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		if(in_array($row["girls_id"], $girldata["id"])){
			if($moviedata[$row["girls_id"]] == ""){
				$ymdhis = str_replace(' ', '', $row["update_time"]);
				$ymdhis = str_replace('-', '', $ymdhis);
				$ymdhis = str_replace(':', '', $ymdhis);
				$moviedata[$row["girls_id"]] = intval($ymdhis);
			}

			$filedatab[$row["girls_id"]][$row["filetype"]] = $row["filename"];
		}
	}
}

$movie_dir = IMG_HOME . CLUBID . '/' . UP_DIR_MOVIE;

$moviedatb = array();
//動画ファイル確認
foreach($moviedata as $gid=>$ymd){
	if($filedatab[$gid][1] != "" || $filedatab[$gid][2] != "" || $filedatab[$gid][3] != ""){
		$moviedatb[$gid] = $ymd;
	}
}

//日付逆順
arsort($moviedatb);

/*
* 枠3
*/
$result = preg_match_all('/(<!-- girlsbox_03 -->.*?<!-- \/girlsbox_03 -->)/s', $source, $get_code);
$waku0 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_03 -->', '', $waku0);
$waku_01 = str_replace('<!-- /girlsbox_03 -->', '', $waku);

$waku_html = "";

$j = 1;
$i = 0;
$h = 0;
foreach($moviedatb as $gid=>$ymdhi){
	//枠の初期化
	$waku1 = $waku_01;

	//日付
	$day = substr($ymdhi, 0, 4) . '.' . substr($ymdhi, 4, 2);
	$waku1 = str_replace('rep00010400eot', $day, $waku1);

	$waku1 = str_replace('rep00010401eot', $girldata["name_romaji"][$gid], $waku1);
	
	//movie（PC版のみ動画ファイルの直接リンクを設定）
	if (!$is_sp) {
		if($filedatab[$gid][1] != ""){//mp4
		$mov = $movie_dir . $filedatab[$gid][1];
		$waku1 = str_replace('rep00010402eot', $mov, $waku1);
		}elseif($filedatab[$gid][2] != ""){//ogv
		$mov = $movie_dir . $filedatab[$gid][2];
		$waku1 = str_replace('rep00010402eot', $mov, $waku1);
		}elseif($filedatab[$gid][3] != ""){//webm
		$mov = $movie_dir . $filedatab[$gid][3];
		$waku1 = str_replace('rep00010402eot', $mov, $waku1);
		}
	}

	//サムネイル
	if($filedatab[$gid][4] != ""){
		$img = $movie_dir . $filedatab[$gid][4];
		$waku1 = str_replace('rep01010070eot', $img, $waku1);
	}else{
		if ($is_sp) {
			$imgsize = 310;
	} else {
		$imgsize = 946;
		}
		$imgfile = DAMMY_IMG_SQ_w;
		$imgtype = 'dmy';
		$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
		$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
	}

	//iframe link（SP版のみ）
	if ($is_sp) {
		$waku1 = str_replace('rep03010090eot', 'movie_iframe.php?midg=' . $gid, $waku1);
	}

	$waku_html .= $waku1;

	$h++;
	$j++;
	$i++;
	//MAX1
	if($i > 0){ break; }
}

//置換
$source = str_replace($waku0, $waku_html, $source);

/*
* 枠4
*/
$result = preg_match_all('/(<!-- girlsbox_04 -->.*?<!-- \/girlsbox_04 -->)/s', $source, $get_code);
$waku0 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_04 -->', '', $waku0);
$waku_01 = str_replace('<!-- /girlsbox_04 -->', '', $waku);

$waku_html = "";

$j = 1;
$i = 0;
$h = 0;
foreach($moviedatb as $gid=>$ymdhi){
	if($i > 0){
		//枠の初期化
		$waku1 = $waku_01;

		//日付
		$day = substr($ymdhi, 0, 4) . '.' . substr($ymdhi, 4, 2);
		$waku1 = str_replace('rep00010400eot', $day, $waku1);

		$waku1 = str_replace('rep00010401eot', $girldata["name_romaji"][$gid], $waku1);
		
		//movie（PC版のみ動画ファイルの直接リンクを設定）
		if (!$is_sp) {
			if($filedatab[$gid][1] != ""){//mp4
			$mov = $movie_dir . $filedatab[$gid][1];
			$waku1 = str_replace('rep00010402eot', $mov, $waku1);
			}elseif($filedatab[$gid][2] != ""){//ogv
			$mov = $movie_dir . $filedatab[$gid][2];
			$waku1 = str_replace('rep00010402eot', $mov, $waku1);
			}elseif($filedatab[$gid][3] != ""){//webm
			$mov = $movie_dir . $filedatab[$gid][3];
			$waku1 = str_replace('rep00010402eot', $mov, $waku1);
			}
		}

		//サムネイル
		if($filedatab[$gid][4] != ""){
			$img = $movie_dir . $filedatab[$gid][4];
			$waku1 = str_replace('rep01010070eot', $img, $waku1);
		}else{
			$imgsize = 310;
			$imgfile = DAMMY_IMG_SQ_w;
			$imgtype = 'dmy';
			$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
			$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
		}

		//iframe link（SP版のみ）
		if ($is_sp) {
			$waku1 = str_replace('rep03010090eot', 'movie_iframe.php?midg=' . $gid, $waku1);
		}

		$waku_html .= $waku1;

		$h++;
	}
	$j++;
	$i++;
}

//置換
$source = str_replace($waku0, $waku_html, $source);

?>
