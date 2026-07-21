<?
/*********************************************************************
* データセット(一般ページ用)
* 
* 2011-07-
*********************************************************************/

//-----  変数取得・設定  -----//
//$img_path = $club_urls_img[CLUBID] . 'img/';

// お気に入り数の設定
$favcast = array();
if(isset($_COOKIE["candyfav"])){
	$favcast = explode(',', urldecode($_COOKIE["candyfav"]));
}
$data1['00010601'] = count($favcast);

//-----  処理(SPECIAL-EVENT追加分)  -----//

//-----  処理  -----//
/*
* 女の子情報取得
*/
$girldata = array();
$girldata["id"] = array();
$QUERY  = "SELECT";
$QUERY .= " id, club_id, no, name, age, height, bust, cup, waist, hip, name_kana, name_romaji";
$QUERY .= ", caption, detail, image1, image2, type1, type2, playok, nyuuten, newface, options, next_photo_update, type_toku";
$QUERY .= " FROM girls_data";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = 1";
//$QUERY .= " AND guest_club_id = 0";
//$QUERY .= " ORDER BY nyuuten DESC, id DESC;";
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
		$girldata["caption"][$row["id"]]     = $row["caption"];
		$girldata["detail"][$row["id"]]      = $row["detail"];
		$girldata["image1"][$row["id"]]      = $row["image1"];
		$girldata["image2"][$row["id"]]      = $row["image2"];
		$girldata["type1"][$row["id"]]       = $row["type1"];
		$girldata["type2"][$row["id"]]       = $row["type2"];
		$girldata["playok"][$row["id"]]      = $row["playok"];
		$girldata["nyuuten"][$row["id"]]     = $row["nyuuten"];
		$girldata["newface"][$row["id"]]     = $row["newface"];
		$girldata["options"][$row["id"]]     = $row["options"];
		$girldata["next_photo_update"][$row["id"]] = $row["next_photo_update"];
		$girldata["type_toku"][$row["id"]]       = $row["type_toku"];
	}
}

/*
* 画像情報を取得 // horw/1:h,2:w
*/
$imagedata = array();
$QUERY  = "SELECT `type`, girls_id, filename FROM girls_images";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND (type = '1' || type = '2')"; //sq or w
$QUERY .= " AND status = 1";
$QUERY .= " ORDER BY sort, id DESC";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		if($row["type"] == 1){
			$imagedata["filename"][$row["girls_id"]][1][] = $row["filename"];
		}elseif($row["type"] == 2){
			$imagedata["filename"][$row["girls_id"]][2][] = $row["filename"];
		}
	}
}

//--  NOW  --//
/*
* 日付情報生成
*/
$weekarr1 = array(0=>'SUN',1=>'MON',2=>'TUE',3=>'WED',4=>'THU',5=>'FRI',6=>'SAT');
$weekarr = array(0=>'日',1=>'月',2=>'火',3=>'水',4=>'木',5=>'金',6=>'土');
//
//切替時間確認
if(date('G') < NEWDAY_TIME){
	$last_ymd = date("Y-n-j",getDay(-1));
	list($yy,$mm,$dd) = explode('-', $last_ymd);
	$ww = date("w",getDay(-1));
}else{
$yy = date('Y');
$mm = date('n');
$dd = date('j');
$ww = date('w');
}
$yyy = array();
$mmm = array();
$ddd = array();
$www = array();
$i = 0;
while($i < 8){
	if(checkdate($mm,$dd,$yy)){
		$yyy[] = $yy;
		$mmm[] = $mm;
		$ddd[] = $dd;
		$dd++;
		$i++;
	}else{
		$dd = 1;
		$mm++;
		if(checkdate($mm,$dd,$yy)){
			$yyy[] = $yy;
			$mmm[] = $mm;
			$ddd[] = $dd;
			$dd++;
			$i++;
		}else{
			$mm = 1;
			$yy++;
			if(checkdate($mm,$dd,$yy)){
				$yyy[] = $yy;
				$mmm[] = $mm;
				$ddd[] = $dd;
				$dd++;
				$i++;
			}
		}
	}
	$www[] = $ww;
	$ww++;
	if($ww > 6){
		$ww = 0;
	}
}

//検索用日付設定
if($work != ""){
	$y = $yyy[$work];
	$m = $mmm[$work];
	$d = $ddd[$work];
	$now = 0;
}else{
	//切替時間確認
	if(date('G') < NEWDAY_TIME){
		$last_ymd = date("Y-n-j",getDay(-1));
		list($y,$m,$d) = explode('-', $last_ymd);
		$now = intval(date('Gi')) + 2400;
	}else{
	$y = date('Y');
	$m = date('n');
	$d = date('j');
	$now = intval(date('Gi'));
	}
}

//表示用
$workday = array();
$workday[0] = $mmm[0] . '/' . $ddd[0];
$workday[1] = $mmm[1] . '/' . $ddd[1];
$workday[2] = $mmm[2] . '/' . $ddd[2];
$workday[3] = $mmm[3] . '/' . $ddd[3];
$workday[4] = $mmm[4] . '/' . $ddd[4];
$workday[5] = $mmm[5] . '/' . $ddd[5];
$workday[6] = $mmm[6] . '/' . $ddd[6];

// class="date"用：元の形式「2026.3.5 THU」
$data1['00040001'] = $yyy[0] . '.' . $mmm[0] . '.' . $ddd[0] . ' ' . $weekarr1[$www[0]];
// #scheduleTabs用：見やすく「3/5 THU」形式
$data1['00040008'] = $mmm[0] . '/' . $ddd[0] . ' ' . $weekarr1[$www[0]];
$data1['00040002'] = $mmm[1] . '/' . $ddd[1] . ' ' . $weekarr1[$www[1]];
$data1['00040003'] = $mmm[2] . '/' . $ddd[2] . ' ' . $weekarr1[$www[2]];
$data1['00040004'] = $mmm[3] . '/' . $ddd[3] . ' ' . $weekarr1[$www[3]];
$data1['00040005'] = $mmm[4] . '/' . $ddd[4] . ' ' . $weekarr1[$www[4]];
$data1['00040006'] = $mmm[5] . '/' . $ddd[5] . ' ' . $weekarr1[$www[5]];
$data1['00040007'] = $mmm[6] . '/' . $ddd[6] . ' ' . $weekarr1[$www[6]];

/*
* 出勤情報取得
*/
$scheduledata = array();
$schedate[1] = array();
$schedate[2] = array();
$schedate[3] = array();
$scheduledata2 = array();
$schedate2[0] = array();
$scheduledata["girls_id"] = array();
$scheduledata2["girls_id"] = array();
$karicast11 = array();
	$karicast11[0] = array();
	$karicast11[1] = array();
	$karicast11[2] = array();
	$karicast11[3] = array();
	$karicast11[4] = array();
	$karicast11[5] = array();
	$karicast11[6] = array();
$karicast12 = array();
	$karicast12[0] = array();
	$karicast12[1] = array();
	$karicast12[2] = array();
	$karicast12[3] = array();
	$karicast12[4] = array();
	$karicast12[5] = array();
	$karicast12[6] = array();
$karicast21 = array();
	$karicast21[0] = array();
	$karicast21[1] = array();
	$karicast21[2] = array();
	$karicast21[3] = array();
	$karicast21[4] = array();
	$karicast21[5] = array();
	$karicast21[6] = array();
$karicast22 = array();
	$karicast22[0] = array();
	$karicast22[1] = array();
	$karicast22[2] = array();
	$karicast22[3] = array();
	$karicast22[4] = array();
	$karicast22[5] = array();
	$karicast22[6] = array();
$QUERY  = "SELECT";
$QUERY .= " girls_schedule.id, girls_schedule.club_id, girls_schedule.girls_id, girls_schedule.year, girls_schedule.month, girls_schedule.day, girls_schedule.type, girls_schedule.type2";
$QUERY .= ", girls_schedule.open_ji, girls_schedule.open_fun, girls_schedule.end_ji, girls_schedule.end_fun, girls_schedule.aki_ji, girls_schedule.aki_fun";
$QUERY .= ", girls_schedule.update_time, girls_schedule.plusview, girls_schedule.views, girls_schedule.status";
$QUERY .= " FROM girls_schedule INNER JOIN girls_data ON girls_schedule.girls_id = girls_data.id";
$QUERY .= " WHERE girls_schedule.club_id = '" . CLUBID . "'";
$QUERY .= " AND (girls_schedule.type = '1' || girls_schedule.type = '6' || girls_schedule.type = '0')";

//1week全選択（今週7日分）
$QUERY .= " AND ( ";
$QUERY .= "(girls_schedule.year = '" . $yyy[0] . "' AND girls_schedule.month = '" . $mmm[0] . "' AND girls_schedule.day = '" . $ddd[0] . "')";
$QUERY .= " || (girls_schedule.year = '" . $yyy[1] . "' AND girls_schedule.month = '" . $mmm[1] . "' AND girls_schedule.day = '" . $ddd[1] . "')";
$QUERY .= " || (girls_schedule.year = '" . $yyy[2] . "' AND girls_schedule.month = '" . $mmm[2] . "' AND girls_schedule.day = '" . $ddd[2] . "')";
$QUERY .= " || (girls_schedule.year = '" . $yyy[3] . "' AND girls_schedule.month = '" . $mmm[3] . "' AND girls_schedule.day = '" . $ddd[3] . "')";
$QUERY .= " || (girls_schedule.year = '" . $yyy[4] . "' AND girls_schedule.month = '" . $mmm[4] . "' AND girls_schedule.day = '" . $ddd[4] . "')";
$QUERY .= " || (girls_schedule.year = '" . $yyy[5] . "' AND girls_schedule.month = '" . $mmm[5] . "' AND girls_schedule.day = '" . $ddd[5] . "')";
$QUERY .= " || (girls_schedule.year = '" . $yyy[6] . "' AND girls_schedule.month = '" . $mmm[6] . "' AND girls_schedule.day = '" . $ddd[6] . "')";
$QUERY .= " ) ";

$QUERY .= " AND girls_schedule.views >= '0'";
$QUERY .= " AND girls_schedule.status = '1'";
$QUERY .= " AND girls_data.status = '1'";
$QUERY .= " ORDER BY girls_schedule.year, girls_schedule.month, girls_schedule.day";
$QUERY .= ", girls_schedule.open_ji, girls_schedule.open_fun";
$QUERY .= ", girls_data.age";
$QUERY .= ", girls_schedule.end_ji, girls_schedule.end_fun, girls_schedule.id DESC";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
	
	if($row["year"] == $yyy[0] && $row["month"] == $mmm[0] && $row["day"] == $ddd[0]){
		$weekid = 0;
	}elseif($row["year"] == $yyy[1] && $row["month"] == $mmm[1] && $row["day"] == $ddd[1]){
		$weekid = 1;
	}elseif($row["year"] == $yyy[2] && $row["month"] == $mmm[2] && $row["day"] == $ddd[2]){
		$weekid = 2;
	}elseif($row["year"] == $yyy[3] && $row["month"] == $mmm[3] && $row["day"] == $ddd[3]){
		$weekid = 3;
	}elseif($row["year"] == $yyy[4] && $row["month"] == $mmm[4] && $row["day"] == $ddd[4]){
		$weekid = 4;
	}elseif($row["year"] == $yyy[5] && $row["month"] == $mmm[5] && $row["day"] == $ddd[5]){
		$weekid = 5;
	}elseif($row["year"] == $yyy[6] && $row["month"] == $mmm[6] && $row["day"] == $ddd[6]){
		$weekid = 6;
	}else{
		$weekid = 9;
	}
		
		if($row["end_fun"] < 10){
		$endtime = intval($row["end_ji"]."0".$row["end_fun"]);
		}else{
		$endtime = intval($row["end_ji"].$row["end_fun"]);
		}
		
		if($row["type"] == 0 || $row["type"] == 1 || $row["type"] == 6){
		
		if($weekid >= 0){ //全て出勤予定//出勤ステータス無視
				
				if($row["open_ji"] == 100){ //日の出
					$karicast11[$weekid][] = $row["girls_id"];
				}else{
					$karicast12[$weekid][] = $row["girls_id"];
				}
				
				//
				$scheduledata[$weekid]["type2"][$row["girls_id"]]    = $row["type2"];
				$scheduledata[$weekid]["view"]["type2"][$row["girls_id"]] = $row["type2"];
		}else{
		
			if(($row["type"] == 6) || ($row["type2"] == 3) || ($endtime < $now)){
				
				if($row["open_ji"] == 100){ //日の出
					$karicast21[$weekid][] = $row["girls_id"];
				}else{
					$karicast22[$weekid][] = $row["girls_id"];
				}
				
			}elseif($row["type"] == 0 || $row["type"] == 1){
				
				if($row["open_ji"] == 100){ //日の出
					$karicast11[$weekid][] = $row["girls_id"];
				}else{
					$karicast12[$weekid][] = $row["girls_id"];
				}
				
			}
			
			//終了時刻経過
			if($endtime < $now){
				$scheduledata[$weekid]["type2"][$row["girls_id"]]    = 3;
				$scheduledata[$weekid]["view"]["type2"][$row["girls_id"]] = 3;
			}else{
				$scheduledata[$weekid]["type2"][$row["girls_id"]]    = $row["type2"];
				$scheduledata[$weekid]["view"]["type2"][$row["girls_id"]] = $row["type2"];
			}
		}
		
		}
		$scheduledata[$weekid]["type"][$row["girls_id"]]     = $row["type"];
		$scheduledata[$weekid]["open_ji"][$row["girls_id"]]  = $row["open_ji"];
		$scheduledata[$weekid]["open_fun"][$row["girls_id"]] = $row["open_fun"];
		$scheduledata[$weekid]["end_ji"][$row["girls_id"]]   = $row["end_ji"];
		$scheduledata[$weekid]["end_fun"][$row["girls_id"]]  = $row["end_fun"];
		$scheduledata[$weekid]["aki_ji"][$row["girls_id"]]   = $row["aki_ji"];
		$scheduledata[$weekid]["aki_fun"][$row["girls_id"]]  = $row["aki_fun"];
		$scheduledata[$weekid]["plusview"][$row["girls_id"]]  = $row["plusview"];
		
		//表示テキスト生成
		if($row["type"] == 0 || $row["type"] == 1 || $row["type"] == 6){
			
			//開始時間
			if($row["open_ji"] == 100){
				$vtime = '日の出';
			}else{
				if($row["open_fun"] == 0){
					$ofun = "00";
				}else{
					$ofun = $row["open_fun"];
				}
				$vtime = $row["open_ji"] . ":" . $ofun;
			}
			
			$vtime .= '<span class="small">～</span>';
			
			if($row["end_ji"] == 99){
				$vtime .= "";
			}else{
				if($row["end_fun"] == 0){
					$efun = "00";
				}else{
					$efun = $row["end_fun"];
				}
				$vtime .= $row["end_ji"] . ":" . $efun;
			}
			
			$scheduledata[$weekid]["view"]["time"][$row["girls_id"]] = $vtime;
			$scheduledata[$weekid]["view"]["type"][$row["girls_id"]] = $row["type"];
			
		}else{
			$scheduledata[$weekid]["view"]["type"][$row["girls_id"]] = $row["type"];
		}
	}
}

$i = 0;
while($i < 7){
	$scheduledata2[$i]["girls_id"] = array_merge($karicast21[$i], $karicast22[$i]);
$i++;
}
$i = 0;
while($i < 7){
	$scheduledata[$i]["girls_id"] = array_merge($karicast11[$i], $karicast12[$i], $karicast21[$i], $karicast22[$i]);
$i++;
}

$week = array(0=>'SUN',1=>'MON',2=>'TUE',3=>'WED',4=>'THU',5=>'FRI',6=>'SAT');

$data1['00010611'] = count($scheduledata[0]["girls_id"]);

/*
* 独自タグから表示枠ソースを取得
*/
$source = file_get_contents($source_file);
$source = str_replace('<img src="./imgHtml/dot.gif" alt="rep01010071eot">', '<img src="./imgHtml/dot.gif" alt="rep01010071eot" width="66" height="66">', $source);
$source = str_replace('<img src="./imgHtml/dot.gif" alt="rep01010070eot">', '<img src="./imgHtml/dot.gif" alt="rep01010070eot" width="640" height="480">', $source);

//FAV-COOKIE取得
$favcast = array();
if(isset($_COOKIE["candyfav"])){
	$favcast = explode(',', urldecode($_COOKIE["candyfav"]));
}

$data1['00010601'] = count($favcast);

if($data1['00010601'] > 0){
	$source = str_replace('class="num" style="display:none;"', 'class="num"', $source);
	$source = str_replace('class="headNavi"', 'class="headNavi headNavi2"', $source);
}

//表示順生成
$order_1_1 = array();$order_1_2 = array();
$order_2_1 = array();$order_2_2 = array();

$i = 0;
while($i < count($scheduledata[0]["girls_id"])){

	$gid = $scheduledata[0]["girls_id"][$i];

	if($girldata["newface"][$gid] == 1){
		
		if($scheduledata[0]["plusview"][$gid] == 1){
			$order_1_1[] = $gid;
		}else{
			$order_1_2[] = $gid;
		}
		
	}else{
	
		if($scheduledata[0]["plusview"][$gid] == 1){
			$order_2_1[] = $gid;
		}else{
			$order_2_2[] = $gid;
		}
		
	}
	
$i++;
}
$cast_order = array();
$cast_order = array_merge(
$order_1_1,$order_1_2,
$order_2_1,$order_2_2
);

/*
* 枠1 (PC版とSP版共通)
*/
$result = preg_match_all('/(<!-- girlsbox_01 -->.*?<!-- \/girlsbox_01 -->)/s', $source, $get_code);
$waku0 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_01 -->', '', $waku0);
$waku_01 = str_replace('<!-- /girlsbox_01 -->', '', $waku);

$waku_html = "";

$j = 1;
$i = 0;
$h = 0;

while($i < count($cast_order)){
	
	$gid = $cast_order[$i];
	
	if(in_array($gid, $girldata["id"])){
	
	$waku1 = $waku_01;
	
	//img sq
		if($imagedata["filename"][$gid][1][0] != ""){
			$imgsize = 66;
			$imgfile = $imagedata["filename"][$gid][1][0];
			$imgtype = 'icon';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
			if(file_exists($img_path)){
			$imageres = $img_uri;
			}else{
			$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
			}
			$waku1 = str_replace('rep01010071eot', IMG_HOME . $imageres, $waku1);
		}else{
			$imgsize = 66;
			$imgfile = DAMMY_IMG_SQ;
			$imgtype = 'dmy';
			$imageres = './imgHtml/unnamed.jpg';
			$waku1 = str_replace('rep01010071eot', $imageres, $waku1);
		}
	
	//img w
		if($imagedata["filename"][$gid][2][0] != ""){
			$imgsize = 640;
			$imgfile = $imagedata["filename"][$gid][2][0];
			$imgtype = 'w';
			//
			$img_uri  = CLUBID . '/' . UP_DIR_W . $imgfile;
		}else{
			$imgsize = 400;
			$imgfile = DAMMY_IMG_SQ_w;
			$imgtype = 'dmy';
			//
			$img_uri  = CLUBID . '/' . 'dmy/' . $imgfile;
		}
		$imageres = $img_uri;
	//
	$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
	
	//
	$waku1 = str_replace('rep03010090eot', 'girls.php?no=' . $girldata["no"][$gid], $waku1);

		$waku1 = str_replace('rep00010320eot', $girldata["name"][$gid], $waku1);
		$waku1 = str_replace('rep00010321eot', $girldata["age"][$gid], $waku1);
		$waku1 = str_replace('rep00010322eot', $girldata["height"][$gid], $waku1);
		$waku1 = str_replace('rep00010323eot', $girldata["bust"][$gid], $waku1);
		$waku1 = str_replace('rep00010324eot', $cup_array[$girldata["cup"][$gid]], $waku1);
		$waku1 = str_replace('rep00010325eot', $girldata["waist"][$gid], $waku1);
		$waku1 = str_replace('rep00010326eot', $girldata["hip"][$gid], $waku1);
		$waku1 = str_replace('rep00010331eot', strtoupper($girldata["name_romaji"][$gid]), $waku1);
		
		$waku1 = str_replace('rep00010329eot', $girldata["caption"][$gid], $waku1);
		//
		$kari = $girldata["detail"][$gid];
		if(mb_strlen($kari, 'UTF-8') > 75){
		$kari2 = mb_substr($kari, 0, 74, 'UTF-8') . '…';
		}else{
		$kari2 = $kari;
		}
		$waku1 = str_replace('rep00010330eot', $kari2, $waku1);
		
		//新人判定
		if($girldata["newface"][$gid] == 1){
			$waku1 = str_replace('<!-- TRIAL', '<!-- TRIAL -->', $waku1);
			$waku1 = str_replace('/TRIAL -->', '<!-- /TRIAL -->', $waku1);
		}elseif($girldata["newface"][$gid] == 2){
			$waku1 = str_replace('<!-- NEWFACE', '<!-- NEWFACE -->', $waku1);
			$waku1 = str_replace('/NEWFACE -->', '<!-- /NEWFACE -->', $waku1);
		}
	
	//追加出勤
	if($scheduledata[0]["plusview"][$gid] == 1){
		$waku1 = str_replace('<div class="box">', '<div class="box add">', $waku1);
	}

	//schedule
	$sche_type = $scheduledata[0]["view"]["type"][$gid];
	//
	if($sche_type == ""){
	$sche_type = 9;
	}elseif($sche_type == 6){
	$sche_type = 8;
	}else{
	$sche_type = 1;
	}
	//
	if($scheduledata[0]["view"]["type2"][$gid] == 6){
	$sche_type = 6;
	}elseif($scheduledata[0]["view"]["type2"][$gid] == 3){
	$sche_type = 8;
	}
	
	if($sche_type == 1){
		$status = $scheduledata[0]["view"]["time"][$gid];
	}elseif($sche_type == 6){
		$status = '<span>TEL確認</span>';
	}elseif($sche_type == 8){
		$status = '<span>案内終了</span>';
	}elseif($sche_type == 9){
		$status = 'CLOSED TODAY';
	}
		$waku1 = str_replace('rep00010354eot', $status, $waku1);
	
	$waku_html .= $waku1;
	
	$h++;
	}
	$j++;
$i++;
}

//置換
$source = str_replace($waku0, $waku_html, $source);

/*
* SP版専用枠1
*/
$result = preg_match_all('/(<!-- girlsbox_01 -->.*?<!-- \/girlsbox_01 -->)/s', $source, $get_code);
$waku0 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_01 -->', '', $waku0);
$waku_01 = str_replace('<!-- /girlsbox_01 -->', '', $waku);

$waku_html = "";

$j = 1;
$i = 0;
$h = 0;

while($i < count($cast_order)){
	
	$gid = $cast_order[$i];
	
	if(in_array($gid, $girldata["id"])){
	
	$waku1 = $waku_01;
	
	//img sq (SP版用50px)
		if($imagedata["filename"][$gid][1][0] != ""){
			$imgsize = 50;
			$imgfile = $imagedata["filename"][$gid][1][0];
			$imgtype = 'icon';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
			if(file_exists($img_path)){
			$imageres = $img_uri;
			}else{
			$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
			}
			$waku1 = str_replace('rep01010071eot', IMG_HOME . $imageres, $waku1);
		}else{
			$imgsize = 50;
			$imgfile = DAMMY_IMG_SQ;
			$imgtype = 'dmy';
			$imageres = './imgHtml/unnamed.jpg';
			$waku1 = str_replace('rep01010071eot', $imageres, $waku1);
		}
	
	//img w
		if($imagedata["filename"][$gid][2][0] != ""){
			$imgsize = 640;
			$imgfile = $imagedata["filename"][$gid][2][0];
			$imgtype = 'w';
			//
			$img_uri  = CLUBID . '/' . UP_DIR_W . $imgfile;
		}else{
			$imgsize = 400;
			$imgfile = DAMMY_IMG_SQ_w;
			$imgtype = 'dmy';
			//
			$img_uri  = CLUBID . '/' . 'dmy/' . $imgfile;
		}
		$imageres = $img_uri;
	//
	$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
	
	//
	$waku1 = str_replace('rep03010090eot', 'girls.php?no=' . $girldata["no"][$gid], $waku1);

		$waku1 = str_replace('rep00010320eot', $girldata["name"][$gid], $waku1);
		$waku1 = str_replace('rep00010321eot', $girldata["age"][$gid], $waku1);
		$waku1 = str_replace('rep00010322eot', $girldata["height"][$gid], $waku1);
		$waku1 = str_replace('rep00010323eot', $girldata["bust"][$gid], $waku1);
		$waku1 = str_replace('rep00010324eot', $cup_array[$girldata["cup"][$gid]], $waku1);
		$waku1 = str_replace('rep00010325eot', $girldata["waist"][$gid], $waku1);
		$waku1 = str_replace('rep00010326eot', $girldata["hip"][$gid], $waku1);
		$waku1 = str_replace('rep00010331eot', strtoupper($girldata["name_romaji"][$gid]), $waku1);
		
		//
		$kari = $girldata["caption"][$gid];
		if(mb_strlen($kari, 'UTF-8') > 30){
		$kari2 = mb_substr($kari, 0, 29, 'UTF-8') . '…';
		}else{
		$kari2 = $kari;
		}
		$waku1 = str_replace('rep00010329eot', $kari2, $waku1);
		//
		$kari = $girldata["detail"][$gid];
		if(mb_strlen($kari, 'UTF-8') > 40){
		$kari2 = mb_substr($kari, 0, 39, 'UTF-8') . '…';
		}else{
		$kari2 = $kari;
		}
		$waku1 = str_replace('rep00010330eot', $kari2, $waku1);
		
		//新人判定
		if($girldata["newface"][$gid] == 1){
			$waku1 = str_replace('<!-- TRIAL', '<!-- TRIAL -->', $waku1);
			$waku1 = str_replace('/TRIAL -->', '<!-- /TRIAL -->', $waku1);
		}elseif($girldata["newface"][$gid] == 2){
			$waku1 = str_replace('<!-- NEWFACE', '<!-- NEWFACE -->', $waku1);
			$waku1 = str_replace('/NEWFACE -->', '<!-- /NEWFACE -->', $waku1);
		}

	//schedule
	$sche_type = $scheduledata[0]["view"]["type"][$gid];
	//
	if($sche_type == ""){
	$sche_type = 9;
	}elseif($sche_type == 6){
	$sche_type = 8;
	}else{
	$sche_type = 1;
	}
	//
	if($scheduledata[0]["view"]["type2"][$gid] == 6){
	$sche_type = 6;
	}elseif($scheduledata[0]["view"]["type2"][$gid] == 3){
	$sche_type = 8;
	}
	
	if($sche_type == 1){
		$status = $scheduledata[0]["view"]["time"][$gid];
		$waku1 = str_replace('rep00010354eot', $status, $waku1);
		//
		if($scheduledata[0]["plusview"][$gid] == 1){
		$waku1 = str_replace('<!-- STATUS2', '<!-- STATUS2 -->', $waku1);
		$waku1 = str_replace('/STATUS2 -->', '<!-- /STATUS2 -->', $waku1);
		}else{
		$waku1 = str_replace('<!-- STATUS1', '<!-- STATUS1 -->', $waku1);
		$waku1 = str_replace('/STATUS1 -->', '<!-- /STATUS1 -->', $waku1);
		}

	}elseif($sche_type == 6){
		//
		if($scheduledata[0]["plusview"][$gid] == 1){
		$waku1 = str_replace('<!-- STATUS4', '<!-- STATUS4-->', $waku1);
		$waku1 = str_replace('/STATUS4 -->', '<!-- /STATUS4 -->', $waku1);
		}else{
		$waku1 = str_replace('<!-- STATUS3', '<!-- STATUS3 -->', $waku1);
		$waku1 = str_replace('/STATUS3 -->', '<!-- /STATUS3 -->', $waku1);
		}
		
	}elseif($sche_type == 8){
		$waku1 = str_replace('<!-- STATUS5', '<!-- STATUS5-->', $waku1);
		$waku1 = str_replace('/STATUS5 -->', '<!-- /STATUS5 -->', $waku1);
	
	}elseif($sche_type == 9){
		$waku1 = str_replace('<!-- STATUS6', '<!-- STATUS6-->', $waku1);
		$waku1 = str_replace('/STATUS6 -->', '<!-- /STATUS6 -->', $waku1);
	
	}
	
	$waku_html .= $waku1;
	
	$h++;
	}
	$j++;
$i++;
}

//置換
$source = str_replace($waku0, $waku_html, $source);

//表示順生成
$order_1_1_1 = array();$order_1_1_6 = array();$order_1_1_8 = array();$order_1_1_9 = array();
$order_1_2_1 = array();$order_1_2_6 = array();$order_1_2_8 = array();$order_1_2_9 = array();
$order_4_1_1 = array();$order_4_1_6 = array();$order_4_1_8 = array();$order_4_1_9 = array();
$order_4_2_1 = array();$order_4_2_6 = array();$order_4_2_8 = array();$order_4_2_9 = array();

$i = 0;
while($i < count($scheduledata[1]["girls_id"])){

	$gid = $scheduledata[1]["girls_id"][$i];
	
		$sche_type = $scheduledata[1]["view"]["type"][$gid];
		if($sche_type == ""){
		$sche_type = 9;
		}elseif($sche_type == 6){
		$sche_type = 8;
		}else{
		$sche_type = 1;
		}
		
		if($scheduledata[0]["view"]["type2"][$gid] == 6){
		$sche_type = 6;
		}elseif($scheduledata[0]["view"]["type2"][$gid] == 3){
		$sche_type = 8;
		}
	
	if($girldata["newface"][$gid] == 1){
	
		if($scheduledata[0]["plusview"][$gid] == 1){
		
			if($sche_type == 1){
				$order_1_1_1[] = $gid;
			}elseif($sche_type == 6){
				$order_1_1_6[] = $gid;
			}elseif($sche_type == 8){
				$order_1_1_8[] = $gid;
			}else{
				$order_1_1_9[] = $gid;
			}
		
		}else{
		
			if($sche_type == 1){
				$order_1_2_1[] = $gid;
			}elseif($sche_type == 6){
				$order_1_2_6[] = $gid;
			}elseif($sche_type == 8){
				$order_1_2_8[] = $gid;
			}else{
				$order_1_2_9[] = $gid;
			}
		
		}
	
	}else{
	
		if($scheduledata[0]["plusview"][$gid] == 1){
		
			if($sche_type == 1){
				$order_4_1_1[] = $gid;
			}elseif($sche_type == 6){
				$order_4_1_6[] = $gid;
			}elseif($sche_type == 8){
				$order_4_1_8[] = $gid;
			}else{
				$order_4_1_9[] = $gid;
			}
		
		}else{
		
			if($sche_type == 1){
				$order_4_2_1[] = $gid;
			}elseif($sche_type == 6){
				$order_4_2_6[] = $gid;
			}elseif($sche_type == 8){
				$order_4_2_8[] = $gid;
			}else{
				$order_4_2_9[] = $gid;
			}
		
		}
	
	}
$i++;
}

$cast_order = array();
$cast_order = array_merge(
$order_1_1_1,$order_1_1_6,$order_1_1_8,$order_1_1_9,
$order_1_2_1,$order_1_2_6,$order_1_2_8,$order_1_2_9,
$order_4_1_1,$order_4_1_6,$order_4_1_8,$order_4_1_9,
$order_4_2_1,$order_4_2_6,$order_4_2_8,$order_4_2_9
);

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

while($i < count($cast_order)){
	
	$gid = $cast_order[$i];
	
	if(in_array($gid, $girldata["id"])){
	
	$waku1 = $waku_01;
	
	//img sq
		if($imagedata["filename"][$gid][1][0] != ""){
			$imgsize = 66;
			$imgfile = $imagedata["filename"][$gid][1][0];
			$imgtype = 'icon';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
			if(file_exists($img_path)){
			$imageres = $img_uri;
			}else{
			$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
			}
			$waku1 = str_replace('rep01010071eot', IMG_HOME . $imageres, $waku1);
		}else{
			$imgsize = 66;
			$imgfile = DAMMY_IMG_SQ;
			$imgtype = 'dmy';
			$imageres = './imgHtml/unnamed.jpg';
			$waku1 = str_replace('rep01010071eot', $imageres, $waku1);
		}
	
	//img w
		if($imagedata["filename"][$gid][2][0] != ""){
			$imgsize = 640;
			$imgfile = $imagedata["filename"][$gid][2][0];
			$imgtype = 'w';
			//
			$img_uri  = CLUBID . '/' . UP_DIR_W . $imgfile;
		}else{
			$imgsize = 400;
			$imgfile = DAMMY_IMG_SQ_w;
			$imgtype = 'dmy';
			//
			$img_uri  = CLUBID . '/' . 'dmy/' . $imgfile;
		}
		$imageres = $img_uri;
	//
	$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
	
	//
	$waku1 = str_replace('rep03010090eot', 'girls.php?no=' . $girldata["no"][$gid], $waku1);

		$waku1 = str_replace('rep00010320eot', $girldata["name"][$gid], $waku1);
		$waku1 = str_replace('rep00010321eot', $girldata["age"][$gid], $waku1);
		$waku1 = str_replace('rep00010322eot', $girldata["height"][$gid], $waku1);
		$waku1 = str_replace('rep00010323eot', $girldata["bust"][$gid], $waku1);
		$waku1 = str_replace('rep00010324eot', $cup_array[$girldata["cup"][$gid]], $waku1);
		$waku1 = str_replace('rep00010325eot', $girldata["waist"][$gid], $waku1);
		$waku1 = str_replace('rep00010326eot', $girldata["hip"][$gid], $waku1);
		$waku1 = str_replace('rep00010331eot', strtoupper($girldata["name_romaji"][$gid]), $waku1);
		
		$waku1 = str_replace('rep00010329eot', $girldata["caption"][$gid], $waku1);
		//
		$kari = $girldata["detail"][$gid];
		if(mb_strlen($kari, 'UTF-8') > 75){
		$kari2 = mb_substr($kari, 0, 74, 'UTF-8') . '…';
		}else{
		$kari2 = $kari;
		}
		$waku1 = str_replace('rep00010330eot', $kari2, $waku1);
		
		//新人判定
		if($girldata["newface"][$gid] == 1){
			$waku1 = str_replace('<!-- TRIAL', '<!-- TRIAL -->', $waku1);
			$waku1 = str_replace('/TRIAL -->', '<!-- /TRIAL -->', $waku1);
		}elseif($girldata["newface"][$gid] == 2){
			$waku1 = str_replace('<!-- NEWFACE', '<!-- NEWFACE -->', $waku1);
			$waku1 = str_replace('/NEWFACE -->', '<!-- /NEWFACE -->', $waku1);
		}
	
	//追加出勤
	if($scheduledata[1]["plusview"][$gid] == 1){
		$waku1 = str_replace('<div class="box">', '<div class="box add">', $waku1);
	}

	//schedule
	$sche_type = $scheduledata[1]["view"]["type"][$gid];
	//
	if($sche_type == ""){
	$sche_type = 9;
	}elseif($sche_type == 6){
	$sche_type = 8;
	}else{
	$sche_type = 1;
	}
	//
	if($scheduledata[1]["view"]["type2"][$gid] == 6){
	$sche_type = 6;
	}elseif($scheduledata[1]["view"]["type2"][$gid] == 3){
	$sche_type = 8;
	}
	
	if($sche_type == 1){
		$status = $scheduledata[1]["view"]["time"][$gid];
	}elseif($sche_type == 6){
		$status = 'TEL確認';
	}elseif($sche_type == 8){
		$status = '案内終了';
	}elseif($sche_type == 9){
		$status = 'CLOSED TODAY';
	}
		$waku1 = str_replace('rep00010354eot', $status, $waku1);
	
	$waku_html .= $waku1;
	
	$h++;
	}
	$j++;
$i++;
}

//置換
$source = str_replace($waku0, $waku_html, $source);

/*
* SP版専用枠2（明日）
*/
$result = preg_match_all('/(<!-- girlsbox_02 -->.*?<!-- \/girlsbox_02 -->)/s', $source, $get_code);
$waku0 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_02 -->', '', $waku0);
$waku_01 = str_replace('<!-- /girlsbox_02 -->', '', $waku);

$waku_html = "";

$j = 1;
$i = 0;
$h = 0;

while($i < count($cast_order)){
	
	$gid = $cast_order[$i];
	
	if(in_array($gid, $girldata["id"])){
	
	$waku1 = $waku_01;
	
	//img sq (SP版用50px)
		if($imagedata["filename"][$gid][1][0] != ""){
			$imgsize = 50;
			$imgfile = $imagedata["filename"][$gid][1][0];
			$imgtype = 'icon';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
			if(file_exists($img_path)){
			$imageres = $img_uri;
			}else{
			$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
			}
			$waku1 = str_replace('rep01010071eot', IMG_HOME . $imageres, $waku1);
		}else{
			$imgsize = 50;
			$imgfile = DAMMY_IMG_SQ;
			$imgtype = 'dmy';
			$imageres = './imgHtml/unnamed.jpg';
			$waku1 = str_replace('rep01010071eot', $imageres, $waku1);
		}
	
	//img w
		if($imagedata["filename"][$gid][2][0] != ""){
			$imgsize = 640;
			$imgfile = $imagedata["filename"][$gid][2][0];
			$imgtype = 'w';
			//
			$img_uri  = CLUBID . '/' . UP_DIR_W . $imgfile;
		}else{
			$imgsize = 400;
			$imgfile = DAMMY_IMG_SQ_w;
			$imgtype = 'dmy';
			//
			$img_uri  = CLUBID . '/' . 'dmy/' . $imgfile;
		}
		$imageres = $img_uri;
	//
	$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
	
	//
	$waku1 = str_replace('rep03010090eot', 'girls.php?no=' . $girldata["no"][$gid], $waku1);

		$waku1 = str_replace('rep00010320eot', $girldata["name"][$gid], $waku1);
		$waku1 = str_replace('rep00010321eot', $girldata["age"][$gid], $waku1);
		$waku1 = str_replace('rep00010322eot', $girldata["height"][$gid], $waku1);
		$waku1 = str_replace('rep00010323eot', $girldata["bust"][$gid], $waku1);
		$waku1 = str_replace('rep00010324eot', $cup_array[$girldata["cup"][$gid]], $waku1);
		$waku1 = str_replace('rep00010325eot', $girldata["waist"][$gid], $waku1);
		$waku1 = str_replace('rep00010326eot', $girldata["hip"][$gid], $waku1);
		$waku1 = str_replace('rep00010331eot', strtoupper($girldata["name_romaji"][$gid]), $waku1);
		
		//
		$kari = $girldata["caption"][$gid];
		if(mb_strlen($kari, 'UTF-8') > 30){
		$kari2 = mb_substr($kari, 0, 29, 'UTF-8') . '…';
		}else{
		$kari2 = $kari;
		}
		$waku1 = str_replace('rep00010329eot', $kari2, $waku1);
		//
		$kari = $girldata["detail"][$gid];
		if(mb_strlen($kari, 'UTF-8') > 40){
		$kari2 = mb_substr($kari, 0, 39, 'UTF-8') . '…';
		}else{
		$kari2 = $kari;
		}
		$waku1 = str_replace('rep00010330eot', $kari2, $waku1);
		
		//新人判定
		if($girldata["newface"][$gid] == 1){
			$waku1 = str_replace('<!-- TRIAL', '<!-- TRIAL -->', $waku1);
			$waku1 = str_replace('/TRIAL -->', '<!-- /TRIAL -->', $waku1);
		}elseif($girldata["newface"][$gid] == 2){
			$waku1 = str_replace('<!-- NEWFACE', '<!-- NEWFACE -->', $waku1);
			$waku1 = str_replace('/NEWFACE -->', '<!-- /NEWFACE -->', $waku1);
		}

	//schedule
	$sche_type = $scheduledata[1]["view"]["type"][$gid];
	//
	if($sche_type == ""){
	$sche_type = 9;
	}elseif($sche_type == 6){
	$sche_type = 8;
	}else{
	$sche_type = 1;
	}
	//
	if($scheduledata[1]["view"]["type2"][$gid] == 6){
	$sche_type = 6;
	}elseif($scheduledata[1]["view"]["type2"][$gid] == 3){
	$sche_type = 8;
	}
	
	if($sche_type == 1){
		$status = $scheduledata[1]["view"]["time"][$gid];
		$waku1 = str_replace('rep00010354eot', $status, $waku1);
		//
		if($scheduledata[1]["plusview"][$gid] == 1){
		$waku1 = str_replace('<!-- STATUS2', '<!-- STATUS2 -->', $waku1);
		$waku1 = str_replace('/STATUS2 -->', '<!-- /STATUS2 -->', $waku1);
		}else{
		$waku1 = str_replace('<!-- STATUS1', '<!-- STATUS1 -->', $waku1);
		$waku1 = str_replace('/STATUS1 -->', '<!-- /STATUS1 -->', $waku1);
		}

	}elseif($sche_type == 6){
		//
		if($scheduledata[1]["plusview"][$gid] == 1){
		$waku1 = str_replace('<!-- STATUS4', '<!-- STATUS4-->', $waku1);
		$waku1 = str_replace('/STATUS4 -->', '<!-- /STATUS4 -->', $waku1);
		}else{
		$waku1 = str_replace('<!-- STATUS3', '<!-- STATUS3 -->', $waku1);
		$waku1 = str_replace('/STATUS3 -->', '<!-- /STATUS3 -->', $waku1);
		}
		
	}elseif($sche_type == 8){
		$waku1 = str_replace('<!-- STATUS5', '<!-- STATUS5-->', $waku1);
		$waku1 = str_replace('/STATUS5 -->', '<!-- /STATUS5 -->', $waku1);
	
	}elseif($sche_type == 9){
		$waku1 = str_replace('<!-- STATUS6', '<!-- STATUS6-->', $waku1);
		$waku1 = str_replace('/STATUS6 -->', '<!-- /STATUS6 -->', $waku1);
	
	}
	
	$waku_html .= $waku1;
	
	$h++;
	}
	$j++;
$i++;
}

//置換
$source = str_replace($waku0, $waku_html, $source);

// 2日目～6日目の処理（各日の処理を追加）
for($day_index = 2; $day_index <= 6; $day_index++){
	// 表示順生成
	$order_1_1_1 = array();$order_1_1_6 = array();$order_1_1_8 = array();$order_1_1_9 = array();
	$order_1_2_1 = array();$order_1_2_6 = array();$order_1_2_8 = array();$order_1_2_9 = array();
	$order_4_1_1 = array();$order_4_1_6 = array();$order_4_1_8 = array();$order_4_1_9 = array();
	$order_4_2_1 = array();$order_4_2_6 = array();$order_4_2_8 = array();$order_4_2_9 = array();

	$i = 0;
	if(isset($scheduledata[$day_index]["girls_id"]) && is_array($scheduledata[$day_index]["girls_id"])){
		while($i < count($scheduledata[$day_index]["girls_id"])){
			$gid = $scheduledata[$day_index]["girls_id"][$i];
			
			$sche_type = isset($scheduledata[$day_index]["view"]["type"][$gid]) ? $scheduledata[$day_index]["view"]["type"][$gid] : "";
			if($sche_type == ""){
				$sche_type = 9;
			}elseif($sche_type == 6){
				$sche_type = 8;
			}else{
				$sche_type = 1;
			}
			
			if(isset($scheduledata[$day_index]["view"]["type2"][$gid]) && $scheduledata[$day_index]["view"]["type2"][$gid] == 6){
				$sche_type = 6;
			}elseif(isset($scheduledata[$day_index]["view"]["type2"][$gid]) && $scheduledata[$day_index]["view"]["type2"][$gid] == 3){
				$sche_type = 8;
			}
		
			if(isset($girldata["newface"][$gid]) && $girldata["newface"][$gid] == 1){
				if(isset($scheduledata[$day_index]["plusview"][$gid]) && $scheduledata[$day_index]["plusview"][$gid] == 1){
					if($sche_type == 1){
						$order_1_1_1[] = $gid;
					}elseif($sche_type == 6){
						$order_1_1_6[] = $gid;
					}elseif($sche_type == 8){
						$order_1_1_8[] = $gid;
					}else{
						$order_1_1_9[] = $gid;
					}
				}else{
					if($sche_type == 1){
						$order_1_2_1[] = $gid;
					}elseif($sche_type == 6){
						$order_1_2_6[] = $gid;
					}elseif($sche_type == 8){
						$order_1_2_8[] = $gid;
					}else{
						$order_1_2_9[] = $gid;
					}
				}
			}else{
				if(isset($scheduledata[$day_index]["plusview"][$gid]) && $scheduledata[$day_index]["plusview"][$gid] == 1){
					if($sche_type == 1){
						$order_4_1_1[] = $gid;
					}elseif($sche_type == 6){
						$order_4_1_6[] = $gid;
					}elseif($sche_type == 8){
						$order_4_1_8[] = $gid;
					}else{
						$order_4_1_9[] = $gid;
					}
				}else{
					if($sche_type == 1){
						$order_4_2_1[] = $gid;
					}elseif($sche_type == 6){
						$order_4_2_6[] = $gid;
					}elseif($sche_type == 8){
						$order_4_2_8[] = $gid;
					}else{
						$order_4_2_9[] = $gid;
					}
				}
			}
			$i++;
		}
	}

	$cast_order = array();
	$cast_order = array_merge(
		$order_1_1_1,$order_1_1_6,$order_1_1_8,$order_1_1_9,
		$order_1_2_1,$order_1_2_6,$order_1_2_8,$order_1_2_9,
		$order_4_1_1,$order_4_1_6,$order_4_1_8,$order_4_1_9,
		$order_4_2_1,$order_4_2_6,$order_4_2_8,$order_4_2_9
	);

	// PC版とSP版を別々に処理（girlsbox_XXを取得して置換）
	$box_num = $day_index + 1; // girlsbox_03, girlsbox_04, ..., girlsbox_07
	$box_pattern = '/(<!-- girlsbox_0' . $box_num . ' -->.*?<!-- \/girlsbox_0' . $box_num . ' -->)/s';
	
	// PC版とSP版を2回処理（0日目と1日目と同じパターン）
	for($version = 0; $version < 2; $version++){
		$result = preg_match_all($box_pattern, $source, $get_code);
		if(count($get_code[0]) > 0){
			$waku0 = $get_code[0][0];
			$waku = str_replace('<!-- girlsbox_0' . $box_num . ' -->', '', $waku0);
			$waku_01 = str_replace('<!-- /girlsbox_0' . $box_num . ' -->', '', $waku);

			$waku_html = "";
			$j = 1;
			$i = 0;
			$h = 0;

			while($i < count($cast_order)){
				$gid = $cast_order[$i];
				
				if(in_array($gid, $girldata["id"])){
					$waku1 = $waku_01;
					
					// PC版とSP版の判定（waku_01の内容で判定）
					$is_pc = (strpos($waku_01, 'class="detail"') !== false);
					$img_size = $is_pc ? 66 : 50;
				
				//img sq
				if(isset($imagedata["filename"][$gid][1][0]) && $imagedata["filename"][$gid][1][0] != ""){
					$imgsize = $img_size;
					$imgfile = $imagedata["filename"][$gid][1][0];
					$imgtype = 'icon';
					$plus = '_'.$imgsize.'_0_0_0';
					$fname = FilenamePlus($imgfile, $plus);
					$img_uri  = CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
					$img_path = UP_DIR . CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
					if(file_exists($img_path)){
						$imageres = $img_uri;
					}else{
						$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
					}
					$waku1 = str_replace('rep01010071eot', IMG_HOME . $imageres, $waku1);
				}else{
					$imgsize = $img_size;
					$imgfile = DAMMY_IMG_SQ;
					$imgtype = 'dmy';
					$imageres = './imgHtml/unnamed.jpg';
					$waku1 = str_replace('rep01010071eot', $imageres, $waku1);
				}
			
				//img w
				if(isset($imagedata["filename"][$gid][2][0]) && $imagedata["filename"][$gid][2][0] != ""){
					$imgsize = 640;
					$imgfile = $imagedata["filename"][$gid][2][0];
					$imgtype = 'w';
					$img_uri  = CLUBID . '/' . UP_DIR_W . $imgfile;
				}else{
					$imgsize = 400;
					$imgfile = DAMMY_IMG_SQ_w;
					$imgtype = 'dmy';
					$img_uri  = CLUBID . '/' . 'dmy/' . $imgfile;
				}
				$imageres = $img_uri;
				$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
			
				$waku1 = str_replace('rep03010090eot', 'girls.php?no=' . $girldata["no"][$gid], $waku1);
				$waku1 = str_replace('rep00010320eot', $girldata["name"][$gid], $waku1);
				$waku1 = str_replace('rep00010321eot', $girldata["age"][$gid], $waku1);
				$waku1 = str_replace('rep00010322eot', $girldata["height"][$gid], $waku1);
				$waku1 = str_replace('rep00010323eot', $girldata["bust"][$gid], $waku1);
				$waku1 = str_replace('rep00010324eot', $cup_array[$girldata["cup"][$gid]], $waku1);
				$waku1 = str_replace('rep00010325eot', $girldata["waist"][$gid], $waku1);
				$waku1 = str_replace('rep00010326eot', $girldata["hip"][$gid], $waku1);
				$waku1 = str_replace('rep00010331eot', strtoupper($girldata["name_romaji"][$gid]), $waku1);
				
				$kari = $girldata["caption"][$gid];
				if($is_pc){
					if(mb_strlen($kari, 'UTF-8') > 75){
						$kari2 = mb_substr($kari, 0, 74, 'UTF-8') . '…';
					}else{
						$kari2 = $kari;
					}
				}else{
					if(mb_strlen($kari, 'UTF-8') > 30){
						$kari2 = mb_substr($kari, 0, 29, 'UTF-8') . '…';
					}else{
						$kari2 = $kari;
					}
				}
				$waku1 = str_replace('rep00010329eot', $kari2, $waku1);
				
				$kari = $girldata["detail"][$gid];
				if($is_pc){
					if(mb_strlen($kari, 'UTF-8') > 75){
						$kari2 = mb_substr($kari, 0, 74, 'UTF-8') . '…';
					}else{
						$kari2 = $kari;
					}
				}else{
					if(mb_strlen($kari, 'UTF-8') > 40){
						$kari2 = mb_substr($kari, 0, 39, 'UTF-8') . '…';
					}else{
						$kari2 = $kari;
					}
				}
				$waku1 = str_replace('rep00010330eot', $kari2, $waku1);
				
				//新人判定
				if(isset($girldata["newface"][$gid]) && $girldata["newface"][$gid] == 1){
					$waku1 = str_replace('<!-- TRIAL', '<!-- TRIAL -->', $waku1);
					$waku1 = str_replace('/TRIAL -->', '<!-- /TRIAL -->', $waku1);
				}elseif(isset($girldata["newface"][$gid]) && $girldata["newface"][$gid] == 2){
					$waku1 = str_replace('<!-- NEWFACE', '<!-- NEWFACE -->', $waku1);
					$waku1 = str_replace('/NEWFACE -->', '<!-- /NEWFACE -->', $waku1);
				}
			
				//追加出勤
				if(isset($scheduledata[$day_index]["plusview"][$gid]) && $scheduledata[$day_index]["plusview"][$gid] == 1){
					$waku1 = str_replace('<div class="box">', '<div class="box add">', $waku1);
				}

				//schedule
				$sche_type = isset($scheduledata[$day_index]["view"]["type"][$gid]) ? $scheduledata[$day_index]["view"]["type"][$gid] : "";
				if($sche_type == ""){
					$sche_type = 9;
				}elseif($sche_type == 6){
					$sche_type = 8;
				}else{
					$sche_type = 1;
				}
				
				if(isset($scheduledata[$day_index]["view"]["type2"][$gid]) && $scheduledata[$day_index]["view"]["type2"][$gid] == 6){
					$sche_type = 6;
				}elseif(isset($scheduledata[$day_index]["view"]["type2"][$gid]) && $scheduledata[$day_index]["view"]["type2"][$gid] == 3){
					$sche_type = 8;
				}
				
				if($sche_type == 1){
					$status = isset($scheduledata[$day_index]["view"]["time"][$gid]) ? $scheduledata[$day_index]["view"]["time"][$gid] : '';
					$waku1 = str_replace('rep00010354eot', $status, $waku1);
					if(!$is_pc){
						if(isset($scheduledata[$day_index]["plusview"][$gid]) && $scheduledata[$day_index]["plusview"][$gid] == 1){
							$waku1 = str_replace('<!-- STATUS2', '<!-- STATUS2 -->', $waku1);
							$waku1 = str_replace('/STATUS2 -->', '<!-- /STATUS2 -->', $waku1);
						}else{
							$waku1 = str_replace('<!-- STATUS1', '<!-- STATUS1 -->', $waku1);
							$waku1 = str_replace('/STATUS1 -->', '<!-- /STATUS1 -->', $waku1);
						}
					}
				}elseif($sche_type == 6){
					$status = 'TEL確認';
					if(!$is_pc){
						if(isset($scheduledata[$day_index]["plusview"][$gid]) && $scheduledata[$day_index]["plusview"][$gid] == 1){
							$waku1 = str_replace('<!-- STATUS4', '<!-- STATUS4-->', $waku1);
							$waku1 = str_replace('/STATUS4 -->', '<!-- /STATUS4 -->', $waku1);
						}else{
							$waku1 = str_replace('<!-- STATUS3', '<!-- STATUS3 -->', $waku1);
							$waku1 = str_replace('/STATUS3 -->', '<!-- /STATUS3 -->', $waku1);
						}
					}else{
						$waku1 = str_replace('rep00010354eot', $status, $waku1);
					}
				}elseif($sche_type == 8){
					$status = '案内終了';
					if(!$is_pc){
						$waku1 = str_replace('<!-- STATUS5', '<!-- STATUS5-->', $waku1);
						$waku1 = str_replace('/STATUS5 -->', '<!-- /STATUS5 -->', $waku1);
					}else{
						$waku1 = str_replace('rep00010354eot', $status, $waku1);
					}
				}else					if($sche_type == 9){
						$status = 'CLOSED TODAY';
						if(!$is_pc){
							$waku1 = str_replace('<!-- STATUS6', '<!-- STATUS6-->', $waku1);
							$waku1 = str_replace('/STATUS6 -->', '<!-- /STATUS6 -->', $waku1);
						}else{
							$waku1 = str_replace('rep00010354eot', $status, $waku1);
						}
					}
				
					$waku_html .= $waku1;
					$h++;
				}
				$j++;
				$i++;
			}

			//置換
			$source = str_replace($waku0, $waku_html, $source);
		}
	}
}

?>
