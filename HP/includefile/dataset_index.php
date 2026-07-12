<?
/*********************************************************************
* データセット(統合版・レスポンシブ対応)
* 
* PC版とSP版を統合したデータセットファイル
*********************************************************************/

//-----  変数取得・設定  -----//

if(!function_exists('buildMovieUrl')){
	function buildMovieUrl($clubId, $filename){
		if($filename === ''){
			return '';
		}
		if(defined('USE_TEST_UPLOADS') && USE_TEST_UPLOADS && function_exists('mediaFileExistsLocal') && mediaFileExistsLocal($clubId, UP_DIR_MOVIE, $filename)){
			return TEST_UPLOAD_BASE_URL . $clubId . '/' . UP_DIR_MOVIE . $filename;
		}
		return IMG_HOME . $clubId . '/' . UP_DIR_MOVIE . $filename;
	}
}
//$img_path = IMG_HOME . CLUBID . '/';

//-----  処理  -----//
/*
* ソート設定情報取得
*/
$sortdata = array();
$QUERY  = "SELECT sort_id FROM girls_sort";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND no = '1'"; //
$QUERY .= " AND status = '1'";
$QUERY .= " ORDER BY id";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$sortdata["sort_id"][]  = $row["sort_id"];
	}
}
if($sortdata["sort_id"][0] == 1){ //ランダム
$sort = 11;
}elseif($sortdata["sort_id"][0] == 2){ //任意順
$sort = 12;
}elseif($sortdata["sort_id"][0] == 3){ //名前順
$sort = 13;
}

/*
* 女の子情報取得
* 取得段階で「登録日2週間以内」を優先し、足りない分を「今週出勤予定」で補い、最大25人のみ取得
*/
$girldata = array();
$girldata["id"] = array();
$TOP_PAGE_DISPLAY_MAX = 25;
$estimated_id_range_2weeks = 28; // 2週間相当のID範囲（1日2人×14日）

// 今週7日分の日付を取得（出勤予定の絞り込み用・NEWDAY_TIMEに合わせる）
if(date('G') < NEWDAY_TIME){
	$last_ymd = date("Y-n-j", getDay(-1));
	list($wy, $wm, $wd) = explode('-', $last_ymd);
}else{
	$wy = date('Y');
	$wm = date('n');
	$wd = date('j');
}
$week_yyy = array();
$week_mmm = array();
$week_ddd = array();
$wi = 0;
while($wi < 7){
	if(checkdate($wm, $wd, $wy)){
		$week_yyy[] = $wy;
		$week_mmm[] = $wm;
		$week_ddd[] = $wd;
		$wd++;
		$wi++;
	}else{
		$wd = 1;
		$wm++;
		if(checkdate($wm, $wd, $wy)){
			$week_yyy[] = $wy;
			$week_mmm[] = $wm;
			$week_ddd[] = $wd;
			$wd++;
			$wi++;
		}else{
			$wm = 1;
			$wy++;
			if(checkdate($wm, $wd, $wy)){
				$week_yyy[] = $wy;
				$week_mmm[] = $wm;
				$week_ddd[] = $wd;
				$wd++;
				$wi++;
			}
		}
	}
}

// (1) 最大IDを取得（※nyuuten基準に変更後も、他処理との互換性のため残置）
$max_id = 0;
$Q_MAX = "SELECT id FROM girls_data WHERE club_id = '" . CLUBID . "' AND status = 1 ORDER BY id DESC LIMIT 1";
$R_MAX = $Database->Query($Q_MAX);
if($Database->Num_Rows($R_MAX) != 0){
	$row_max = $Database->Fetch_Array($R_MAX);
	$max_id = intval($row_max["id"]);
}

// (2) 登録日2週間以内の女の子を最大25人取得（優先・nyuuten基準）
$recent_girls = array();
$Q_RECENT  = "SELECT id, club_id, no, name, age, height, bust, cup, waist, hip, name_kana, name_romaji";
$Q_RECENT .= ", caption, detail, image1, image2, type1, type2, playok, nyuuten, newface, options, next_photo_update, type_toku, last_update, last_uptype";
$Q_RECENT .= " FROM girls_data";
$Q_RECENT .= " WHERE club_id = '" . CLUBID . "' AND status = 1";
// nyuuten（入店日）を基準に直近2週間分を取得（NULLや0日は除外）
$Q_RECENT .= " AND nyuuten IS NOT NULL";
$Q_RECENT .= " AND nyuuten <> '0000-00-00 00:00:00'";
$Q_RECENT .= " AND nyuuten >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)";
$Q_RECENT .= " ORDER BY nyuuten DESC, id DESC LIMIT " . intval($TOP_PAGE_DISPLAY_MAX);
$R_RECENT = $Database->Query($Q_RECENT);
while($row = $Database->Fetch_Array($R_RECENT)){
	$recent_girls[] = $row;
}

$selected_girls = $recent_girls;
$selected_girl_ids = array();
foreach($selected_girls as $g){
	$selected_girl_ids[] = $g["id"];
}
$selected_count = count($selected_girls);

// (3) 25人に満たない場合、今週出勤予定がある女の子を取得して追加
$schedule_girl_rows = array();
if($selected_count < $TOP_PAGE_DISPLAY_MAX && count($week_yyy) >= 7){
	$need = $TOP_PAGE_DISPLAY_MAX - $selected_count;
	$not_in_sql = (count($selected_girl_ids) > 0)
		? " AND s.girls_id NOT IN (" . implode(",", array_map("intval", $selected_girl_ids)) . ")"
		: "";
	$Q_SCHED  = "SELECT DISTINCT s.girls_id";
	$Q_SCHED .= " FROM girls_schedule s INNER JOIN girls_data g ON g.id = s.girls_id AND g.club_id = s.club_id";
	$Q_SCHED .= " WHERE s.club_id = '" . CLUBID . "' AND g.status = 1 AND s.status = 1";
	$Q_SCHED .= " AND (s.type = 0 OR s.type = 1 OR s.type = 6)";
	$Q_SCHED .= " AND ( ";
	$Q_SCHED .= " (s.year = '" . intval($week_yyy[0]) . "' AND s.month = '" . intval($week_mmm[0]) . "' AND s.day = '" . intval($week_ddd[0]) . "')";
	for($si = 1; $si < 7; $si++){
		$Q_SCHED .= " OR (s.year = '" . intval($week_yyy[$si]) . "' AND s.month = '" . intval($week_mmm[$si]) . "' AND s.day = '" . intval($week_ddd[$si]) . "')";
	}
	$Q_SCHED .= " )" . $not_in_sql;
	$Q_SCHED .= " ORDER BY s.year, s.month, s.day, s.id DESC LIMIT " . intval($need);
	$R_SCHED = $Database->Query($Q_SCHED);
	$schedule_girl_ids = array();
	while($row = $Database->Fetch_Array($R_SCHED)){
		$schedule_girl_ids[] = intval($row["girls_id"]);
	}
	if(count($schedule_girl_ids) > 0){
		$Q_FULL  = "SELECT id, club_id, no, name, age, height, bust, cup, waist, hip, name_kana, name_romaji";
		$Q_FULL .= ", caption, detail, image1, image2, type1, type2, playok, nyuuten, newface, options, next_photo_update, type_toku, last_update, last_uptype";
		$Q_FULL .= " FROM girls_data";
		$Q_FULL .= " WHERE club_id = '" . CLUBID . "' AND status = 1 AND id IN (" . implode(",", $schedule_girl_ids) . ")";
		$R_FULL = $Database->Query($Q_FULL);
		while($row = $Database->Fetch_Array($R_FULL)){
			$schedule_girl_rows[] = $row;
		}
		$selected_girls = array_merge($selected_girls, $schedule_girl_rows);
		foreach($schedule_girl_rows as $g){
			$selected_girl_ids[] = $g["id"];
		}
		$selected_count = count($selected_girls);
	}
}

$all_girls = $selected_girls;
$recent_girl_ids = array();
// nyuuten（入店日）が直近2週間以内の女の子を recent_girl_ids として保持
foreach($all_girls as $g){
	if(!isset($g["nyuuten"]) || $g["nyuuten"] === "" || $g["nyuuten"] === "0000-00-00 00:00:00"){
		continue;
	}
	$nyu_ts = @strtotime($g["nyuuten"]);
	if($nyu_ts === false){
		continue;
	}
	// 14日以内を「2週間以内」とみなす
	if((time() - $nyu_ts) <= 14 * 24 * 60 * 60){
		$recent_girl_ids[] = $g["id"];
	}
}

$all_girl_ids = $selected_girl_ids;
$temp_girldata = array();
foreach($all_girls as $girl){
	$temp_girldata[$girl["id"]] = $girl;
}

$temp_girldata_for_schedule = array();
foreach($all_girls as $row){
	$temp_girldata_for_schedule["id"][]          = $row["id"];
	$temp_girldata_for_schedule["club_id"][$row["id"]]     = $row["club_id"];
	$temp_girldata_for_schedule["no"][$row["id"]]          = $row["no"];
	$temp_girldata_for_schedule["name"][$row["id"]]        = $row["name"];
	$temp_girldata_for_schedule["name_kana"][$row["id"]]   = $row["name_kana"];
	$temp_girldata_for_schedule["name_romaji"][$row["id"]] = $row["name_romaji"];
	$temp_girldata_for_schedule["age"][$row["id"]]         = $row["age"];
	$temp_girldata_for_schedule["height"][$row["id"]]      = $row["height"];
	$temp_girldata_for_schedule["bust"][$row["id"]]        = $row["bust"];
	$temp_girldata_for_schedule["cup"][$row["id"]]         = $row["cup"];
	$temp_girldata_for_schedule["waist"][$row["id"]]       = $row["waist"];
	$temp_girldata_for_schedule["hip"][$row["id"]]         = $row["hip"];
	$temp_girldata_for_schedule["caption"][$row["id"]]     = $row["caption"];
	$temp_girldata_for_schedule["detail"][$row["id"]]      = $row["detail"];
	$temp_girldata_for_schedule["image1"][$row["id"]]      = $row["image1"];
	$temp_girldata_for_schedule["image2"][$row["id"]]      = $row["image2"];
	$temp_girldata_for_schedule["type1"][$row["id"]]       = $row["type1"];
	$temp_girldata_for_schedule["type2"][$row["id"]]       = $row["type2"];
	$temp_girldata_for_schedule["playok"][$row["id"]]      = $row["playok"];
	$temp_girldata_for_schedule["nyuuten"][$row["id"]]     = $row["nyuuten"];
	$temp_girldata_for_schedule["newface"][$row["id"]]     = $row["newface"];
	$temp_girldata_for_schedule["options"][$row["id"]]     = $row["options"];
	$temp_girldata_for_schedule["last_update"][$row["id"]] = $row["last_update"];
	$temp_girldata_for_schedule["last_uptype"][$row["id"]] = $row["last_uptype"];
	$temp_girldata_for_schedule["next_photo_update"][$row["id"]] = $row["next_photo_update"];
	$temp_girldata_for_schedule["type_toku"][$row["id"]]       = $row["type_toku"];
}

foreach($selected_girls as $row){
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
	$girldata["last_update"][$row["id"]] = $row["last_update"];
	$girldata["last_uptype"][$row["id"]] = $row["last_uptype"];
	$girldata["next_photo_update"][$row["id"]] = $row["next_photo_update"];
	$girldata["type_toku"][$row["id"]]       = $row["type_toku"];
}

/*
* 画像情報を取得 // horw/1:h,2:w
*/
$imagedata = array();
$QUERY  = "SELECT `type`, girls_id, filename FROM girls_images";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND (type = '2' || type = '3')"; //31:pc,32:sp
$QUERY .= " AND status = 1";
$QUERY .= " ORDER BY sort, id DESC";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$imagedata["filename"][$row["girls_id"]][$row["type"]][] = $row["filename"];
	}
}

/*
* 動画情報を取得 // 
*/
$moviedata = array();
$QUERY  = "SELECT id,girls_id,filetype,filename,sort FROM girls_movie_file";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = '1'";
$QUERY .= " ORDER BY sort ASC, id DESC";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$moviedata["filename"][$row["girls_id"]][$row["filetype"]] = $row["filename"];
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

$week = array(0=>'SUN',1=>'MON',2=>'TUE',3=>'WED',4=>'THU',5=>'FRI',6=>'SAT');
$data1['00040001'] = date("Y.m/d");

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

// 出勤情報を取得するために、一時的にすべての女の子を$girldata["id"]に設定
// （出勤情報取得後に25人に制限するため）
$temp_all_girl_ids = isset($all_girl_ids) ? $all_girl_ids : array();
if(count($temp_all_girl_ids) == 0 && isset($temp_girldata)){
	$temp_all_girl_ids = array_keys($temp_girldata);
}
$original_girldata_ids = $girldata["id"]; // バックアップ
$girldata["id"] = $temp_all_girl_ids; // 一時的にすべての女の子を設定

if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
	// 出勤情報はすべての女の子を対象に取得（後で25人に制限するため）
	if(in_array($row["girls_id"], $girldata["id"])){
	
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
		
			if(($row["type"] == 6) || ($row["type2"] == 3) || ($endtime < $now)){ //受付終了or受付終了or受付時間＜現在時刻or23:30以降
				
				if($row["open_ji"] == 100){ //日の出
					$karicast21[$weekid][] = $row["girls_id"];
				}else{
					$karicast22[$weekid][] = $row["girls_id"];
				}
				
			}elseif($row["type"] == 0 || $row["type"] == 1){ //出勤予定or出勤済
				
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
		$scheduledata[$weekid]["update_time"][$row["girls_id"]]  = $row["update_time"];
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
			
			$vtime .= "~";
			
			if($row["end_ji"] == 99){
				$vtime .= "LAST";
			}else{
				if($row["end_fun"] == 0){
					$efun = "00";
				}else{
					$efun = $row["end_fun"];
				}
				$vtime .= $row["end_ji"] . ":" . $efun;
			}
			
			$scheduledata[$weekid]["view"]["time"][$row["girls_id"]] = $vtime; //時間
			$scheduledata[$weekid]["view"]["type"][$row["girls_id"]] = $row["type"]; //
			
		}else{
			$scheduledata[$weekid]["view"]["type"][$row["girls_id"]] = $row["type"]; //出/休
		}
	}
	}
}

// 取得段階で最大25人にしているため、$girldata["id"]はそのまま（追加で上書きしない）
if(isset($original_girldata_ids)){
	$girldata["id"] = $original_girldata_ids;
}else{
	if(isset($selected_girl_ids)){
		$girldata["id"] = $selected_girl_ids;
	}
}

// 最終的に25人に制限（念のため）
if(count($girldata["id"]) > 25){
	// IDの降順でソート（登録日の新しい順）
	$sorted_ids = $girldata["id"];
	rsort($sorted_ids);
	$sorted_ids = array_slice($sorted_ids, 0, 25);
	
	// $girldataを再構築
	$new_girldata = array();
	$new_girldata["id"] = array();
	foreach($sorted_ids as $gid){
		if(isset($girldata["club_id"][$gid])){
			$new_girldata["id"][] = $gid;
			foreach(array("club_id", "no", "name", "name_kana", "name_romaji", "age", "height", "bust", "cup", "waist", "hip", "caption", "detail", "image1", "image2", "type1", "type2", "playok", "nyuuten", "newface", "options", "last_update", "last_uptype", "next_photo_update", "type_toku") as $key){
				if(isset($girldata[$key][$gid])){
					$new_girldata[$key][$gid] = $girldata[$key][$gid];
				}
			}
		}
	}
	$girldata = $new_girldata;
}

// $girldata["id"]を最終的な25人に制限（出勤情報は$girldata["id"]に含まれる女の子のみが処理されるため、自動的に25人に制限される）
$final_girl_ids = $girldata["id"];

// $karicast配列を25人に制限（$girldata["id"]に含まれる女の子のみ）
for($i = 0; $i < 7; $i++){
	if(isset($karicast11[$i]) && is_array($karicast11[$i])){
		$karicast11[$i] = array_intersect($karicast11[$i], $final_girl_ids);
		$karicast11[$i] = array_values($karicast11[$i]); // インデックスを再構築
	}
	if(isset($karicast12[$i]) && is_array($karicast12[$i])){
		$karicast12[$i] = array_intersect($karicast12[$i], $final_girl_ids);
		$karicast12[$i] = array_values($karicast12[$i]);
	}
	if(isset($karicast21[$i]) && is_array($karicast21[$i])){
		$karicast21[$i] = array_intersect($karicast21[$i], $final_girl_ids);
		$karicast21[$i] = array_values($karicast21[$i]);
	}
	if(isset($karicast22[$i]) && is_array($karicast22[$i])){
		$karicast22[$i] = array_intersect($karicast22[$i], $final_girl_ids);
		$karicast22[$i] = array_values($karicast22[$i]);
	}
}

// $scheduledataを再構築（25人に制限後）
$i = 0;
while($i < 7){
	$scheduledata[$i]["girls_id"] = array_merge($karicast11[$i], $karicast12[$i], $karicast21[$i], $karicast22[$i]); //全部
	// 重複を除去
	$scheduledata[$i]["girls_id"] = array_unique($scheduledata[$i]["girls_id"]);
	$scheduledata[$i]["girls_id"] = array_values($scheduledata[$i]["girls_id"]); // インデックスを再構築
$i++;
}

// $scheduledataのその他の配列も25人に制限（$girldata["id"]に含まれる女の子のみ）
foreach($scheduledata as $weekid => $schedule){
	if(isset($schedule["girls_id"]) && is_array($schedule["girls_id"])){
		$scheduledata[$weekid]["girls_id"] = array_intersect($schedule["girls_id"], $final_girl_ids);
		// その他の配列も制限
		if(isset($schedule["type"]) && is_array($schedule["type"])){
			$scheduledata[$weekid]["type"] = array_intersect_key($schedule["type"], array_flip($final_girl_ids));
		}
		if(isset($schedule["open_ji"]) && is_array($schedule["open_ji"])){
			$scheduledata[$weekid]["open_ji"] = array_intersect_key($schedule["open_ji"], array_flip($final_girl_ids));
		}
		if(isset($schedule["open_fun"]) && is_array($schedule["open_fun"])){
			$scheduledata[$weekid]["open_fun"] = array_intersect_key($schedule["open_fun"], array_flip($final_girl_ids));
		}
		if(isset($schedule["end_ji"]) && is_array($schedule["end_ji"])){
			$scheduledata[$weekid]["end_ji"] = array_intersect_key($schedule["end_ji"], array_flip($final_girl_ids));
		}
		if(isset($schedule["end_fun"]) && is_array($schedule["end_fun"])){
			$scheduledata[$weekid]["end_fun"] = array_intersect_key($schedule["end_fun"], array_flip($final_girl_ids));
		}
		if(isset($schedule["type2"]) && is_array($schedule["type2"])){
			$scheduledata[$weekid]["type2"] = array_intersect_key($schedule["type2"], array_flip($final_girl_ids));
		}
		if(isset($schedule["aki_ji"]) && is_array($schedule["aki_ji"])){
			$scheduledata[$weekid]["aki_ji"] = array_intersect_key($schedule["aki_ji"], array_flip($final_girl_ids));
		}
		if(isset($schedule["aki_fun"]) && is_array($schedule["aki_fun"])){
			$scheduledata[$weekid]["aki_fun"] = array_intersect_key($schedule["aki_fun"], array_flip($final_girl_ids));
		}
		if(isset($schedule["plusview"]) && is_array($schedule["plusview"])){
			$scheduledata[$weekid]["plusview"] = array_intersect_key($schedule["plusview"], array_flip($final_girl_ids));
		}
		if(isset($schedule["view"]) && is_array($schedule["view"])){
			if(isset($schedule["view"]["time"]) && is_array($schedule["view"]["time"])){
				$scheduledata[$weekid]["view"]["time"] = array_intersect_key($schedule["view"]["time"], array_flip($final_girl_ids));
			}
			if(isset($schedule["view"]["type"]) && is_array($schedule["view"]["type"])){
				$scheduledata[$weekid]["view"]["type"] = array_intersect_key($schedule["view"]["type"], array_flip($final_girl_ids));
			}
			if(isset($schedule["view"]["type2"]) && is_array($schedule["view"]["type2"])){
				$scheduledata[$weekid]["view"]["type2"] = array_intersect_key($schedule["view"]["type2"], array_flip($final_girl_ids));
			}
			if(isset($schedule["view"]["time_view"]) && is_array($schedule["view"]["time_view"])){
				$scheduledata[$weekid]["view"]["time_view"] = array_intersect_key($schedule["view"]["time_view"], array_flip($final_girl_ids));
			}
			if(isset($schedule["view"]["time2"]) && is_array($schedule["view"]["time2"])){
				$scheduledata[$weekid]["view"]["time2"] = array_intersect_key($schedule["view"]["time2"], array_flip($final_girl_ids));
			}
		}
	}
}

$data1['00010611'] = count($scheduledata[0]["girls_id"]);

//未出勤者取得
$schedule_off_cast = array();
foreach($girldata["id"] as $gid){
	if(!in_array($gid, $scheduledata[0]["girls_id"])){
		$schedule_off_cast[] = $gid;
	}
}

//並び替え
// 2週間以内の女の子とそれ以外を分ける
$order_1_1_recent = array(); // 2週間以内の体験+追加出勤
$order_1_2_recent = array(); // 2週間以内の体験+通常
$order_2_1_recent = array(); // 2週間以内の新人・一般+追加出勤
$order_2_2_recent = array(); // 2週間以内の新人・一般+通常
$order_1_1_other = array(); // 2週間以内以外の体験+追加出勤
$order_1_2_other = array(); // 2週間以内以外の体験+通常
$order_2_1_other = array(); // 2週間以内以外の新人・一般+追加出勤
$order_2_2_other = array(); // 2週間以内以外の新人・一般+通常

$i = 0;
while($i < count($scheduledata[0]["girls_id"])){

	$gid = $scheduledata[0]["girls_id"][$i];
	
	// 2週間以内の女の子かどうかを判定
	$is_recent = in_array($gid, $recent_girl_ids);

	if($girldata["newface"][$gid] == 1){ //体験order_1_
		
		if($scheduledata[0]["plusview"][$gid] == 1){ //追加出勤order_1_1
			if($is_recent){
				$order_1_1_recent[] = $gid;
			}else{
				$order_1_1_other[] = $gid;
			}
		}else{ //通常order_1_2
			if($is_recent){
				$order_1_2_recent[] = $gid;
			}else{
				$order_1_2_other[] = $gid;
			}
		}
		
	}else{ //新人・一般order_2_
	
		if($scheduledata[0]["plusview"][$gid] == 1){ //追加出勤order_2_1
			if($is_recent){
				$order_2_1_recent[] = $gid;
			}else{
				$order_2_1_other[] = $gid;
			}
		}else{ //通常order_2_2
			if($is_recent){
				$order_2_2_recent[] = $gid;
			}else{
				$order_2_2_other[] = $gid;
			}
		}
		
	}
	
$i++;
}

// 出勤時間が若い順でソートする比較関数（PC/SP共通表示順）
$sort_by_start_time = function($a, $b) use (&$scheduledata) {
	$ji_a = isset($scheduledata[0]["open_ji"][$a]) ? intval($scheduledata[0]["open_ji"][$a]) : 99;
	$fun_a = isset($scheduledata[0]["open_fun"][$a]) ? intval($scheduledata[0]["open_fun"][$a]) : 0;
	$ji_b = isset($scheduledata[0]["open_ji"][$b]) ? intval($scheduledata[0]["open_ji"][$b]) : 99;
	$fun_b = isset($scheduledata[0]["open_fun"][$b]) ? intval($scheduledata[0]["open_fun"][$b]) : 0;
	$min_a = $ji_a * 60 + $fun_a;
	$min_b = $ji_b * 60 + $fun_b;
	return ($min_a === $min_b) ? 0 : (($min_a < $min_b) ? -1 : 1);
};
usort($order_1_1_recent, $sort_by_start_time);
usort($order_1_2_recent, $sort_by_start_time);
usort($order_2_1_recent, $sort_by_start_time);
usort($order_2_2_recent, $sort_by_start_time);

// 明日以降は一番うしろ（other_scheduledはシャッフルせずそのまま末尾に）
$other_scheduled = array_merge(
	$order_1_1_other, $order_1_2_other,
	$order_2_1_other, $order_2_2_other
);

// 表示順を構築: 2週間以内（出勤時間若い順）→ 明日以降 → 未出勤者
$cast_order = array();
$cast_order = array_merge(
	// 2週間以内の女の子（出勤時間が若い順）
	$order_1_1_recent, $order_1_2_recent,
	$order_2_1_recent, $order_2_2_recent,
	// 明日以降の出勤予定
	$other_scheduled,
	// 未出勤者
	$schedule_off_cast
);

/*
* お気に入り取得
*/
$favgirls = array();

/*
* 独自タグから表示枠ソースを取得
*/
$source = file_get_contents($source_file);

/*
* 動画情報取得
*/
$filedata = array();
$filedata["filename"] = array();
$filedata["id"] = array();
$QUERY  = "SELECT id,girls_id,filetype,filename,sort FROM girls_movie_file";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = '1'";
$QUERY .= " ORDER BY sort ASC, id DESC";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$filedata["filename"][$row["girls_id"]][$row["filetype"]]       = $row["filename"];
		$filedata["id"][$row["girls_id"]][$row["filetype"]]             = $row["id"];
	}
}

$movie_dir = IMG_HOME . CLUBID . '/' . UP_DIR_MOVIE;

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

// PC版用データ生成
$que = array();
$waku_html = "";

$j = 0;
$i = 0;
$h = 0;
while($i < count($cast_order)){
	
	$gid = $cast_order[$i];
	
	$kari = array();
	
	// profile //
		$kari[] = strtoupper($girldata["name_romaji"][$gid]);
		$kari[] = $girldata["age"][$gid];
		$kari[] = $girldata["height"][$gid];
		$kari[] = $girldata["bust"][$gid];
		$kari[] = $cup_array[$girldata["cup"][$gid]];
		$kari[] = $girldata["waist"][$gid];
		$kari[] = $girldata["hip"][$gid];
	
	// images //
	//横小
		if($imagedata["filename"][$gid][2][0] != ""){
			$imgsize = 640;
			$imgfile = $imagedata["filename"][$gid][2][0];
			$imgtype = 'w';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . UP_DIR_W . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . UP_DIR_W . 'thumb/' . $fname;
		}else{
			$imgsize = 400;
			$imgfile = DAMMY_IMG_SQ_w;
			$imgtype = 'dmy';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
		}
			if(file_exists($img_path)){
			$img_ws = IMG_HOME . $img_uri;
			}else{
			$img_ws = IMG_HOME . 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
			}
	//横大
		if($imagedata["filename"][$gid][2][0] != ""){
			$imgfile = $imagedata["filename"][$gid][2][0];
			$img_wb = IMG_HOME . CLUBID . '/' . UP_DIR_W . $imgfile;
		}else{
			$imgsize = 1200;
			$imgfile = DAMMY_IMG_SQ_w;
			$imgtype = 'dmy';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
			
			if(file_exists($img_path)){
			$img_wb = IMG_HOME . $img_uri;
			}else{
			$img_wb = IMG_HOME . 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
			}
		}
	//縦
		if($imagedata["filename"][$gid][3][0] != ""){
			$imgsize = 640;
			$imgfile = $imagedata["filename"][$gid][3][0];
			$imgtype = 'h';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . UP_DIR_H . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . UP_DIR_H . 'thumb/' . $fname;
		}else{
			$imgsize = 400;
			$imgfile = DAMMY_IMG_SQ_h;
			$imgtype = 'dmy';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
		}
			if(file_exists($img_path)){
			$img_hs = IMG_HOME . $img_uri;
			}else{
			$img_hs = IMG_HOME . 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
			}
	
//MOVIE
$mv_webm = "";
$mv_mp4  = "";
$mv_ogv  = "";
$horizontal_movie = "";
$horizontal_movie_id = 0;
if(isset($filedata["filename"][$gid])){
	foreach($filedata["filename"][$gid] as $filetype => $filename){
		if(strpos($filetype, 'v') === 0){
			$slot_num = intval(substr($filetype, 1));
			if($slot_num >= 1 && $slot_num <= 4 && $filename != ""){
				$current_id = isset($filedata["id"][$gid][$filetype]) ? intval($filedata["id"][$gid][$filetype]) : 0;
				if($current_id > $horizontal_movie_id){
					$horizontal_movie = $filename;
					$horizontal_movie_id = $current_id;
				}
			}
		}
	}
}
if($horizontal_movie != ""){
	$movie_url = buildMovieUrl(CLUBID, $horizontal_movie);
	$mv_webm = $movie_url;
	$mv_mp4  = $movie_url;
	$mv_ogv  = $movie_url;
}
	
	
	//link
	$link = 'girls.php?no=' . $girldata["no"][$gid];
		
		
	// status //
		
		//新人判定
		if($girldata["newface"][$gid] == 1){ //体験
			$nftype = "test";
		}elseif($girldata["newface"][$gid] == 2){ //新人
			$nftype = "new";
		}else{
			$nftype = "";
		}
		
		//お気に入り判定
		if(in_array($gid, $favcast)){
			$favck = 'true';
		}else{
			$favck = 'false';
		}
		
		//新着photo判定
		if($girldata["last_update"][$gid] != ""){
			$ymd1 = date("Y/m/d",getDay(0));
			$ymd2 = str_replace('-', '/', $girldata["last_update"][$gid]);
			$daydiff1 = (strtotime($ymd1)-strtotime($ymd2))/(3600*24);
			
			if($daydiff1 < 30){
				$photock = 'true';
			}else{
				$photock = 'false';
			}
		}else{
			$photock = 'false';
		}
		
		//日記判定
		$diaryck = 'false';
		

	//type = 1:出勤,6:受付終了(案内終了),0:未出勤(CLOSEDTODAY)
	//type2= 1:受付中,2:キャンセル待ち,3:受付終了,4:ラスト1名,5:接客中(受付中),6:TEL確認
	//schedule
	$sche_type = $scheduledata[0]["view"]["type"][$gid];
	//
	if($sche_type == ""){//予定なし
	$sche_type = 9;//休み
	}elseif($sche_type == 6){
	$sche_type = 8;//終了
	}else{
	$sche_type = 1;//出勤(受付中)
	}
	//
	if($scheduledata[0]["view"]["type2"][$gid] == 6){ //TEL確認
	$sche_type = 6;
	}elseif($scheduledata[0]["view"]["type2"][$gid] == 3){ //受付終了
	$sche_type = 8;
	}
	
	if($sche_type == 1){//出勤(受付中)
		
		//時間帯
		$status = $scheduledata[0]["view"]["time"][$gid];

	}elseif($sche_type == 6){//TEL確認
		
		//
		$status = 'TEL確認';
		
	}elseif($sche_type == 8){//受付終了
		
		//
		$status = '案内終了';
	
	}elseif($sche_type == 9){//休み
		
		//
		$status = 'CLOSED';
	
	}
	//追加出勤
	if($scheduledata[0]["plusview"][$gid] == 1){
		$status .= '+';
	}

	//status set
	$kari[] = $status;
	$kari[] = $favck;
	$kari[] = $photock;
	$kari[] = $diaryck;
	$kari[] = $nftype;
	
	//images set
	$kari[] = $img_ws;
	$kari[] = $img_hs;
	$kari[] = $img_wb;
	$kari[] = $mv_webm;
	$kari[] = $mv_mp4;
	$kari[] = $mv_ogv;
	$kari[] = $link;
	
	
	$que[] = implode('/__/', $kari);
	
	$h++;
$i++;
}

//置換
$source = str_replace($waku0, $waku_html, $source);

// SP版用データ生成
/*
* 枠1 w-s
*/
$result = preg_match_all('/(<!-- girlsbox_01 -->.*?<!-- \/girlsbox_01 -->)/s', $source, $get_code);
$waku0 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_01 -->', '', $waku0);
$waku_01 = str_replace('<!-- /girlsbox_01 -->', '', $waku);

/*
* 枠2 w-b
*/
$result = preg_match_all('/(<!-- girlsbox_02 -->.*?<!-- \/girlsbox_02 -->)/s', $source, $get_code);
$waku2 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_02 -->', '', $waku2);
$waku_02 = str_replace('<!-- /girlsbox_02 -->', '', $waku);
$source = str_replace($waku2, "", $source);

/*
* 枠3 h-s
*/
$result = preg_match_all('/(<!-- girlsbox_03 -->.*?<!-- \/girlsbox_03 -->)/s', $source, $get_code);
$waku3 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_03 -->', '', $waku3);
$waku_03 = str_replace('<!-- /girlsbox_03 -->', '', $waku);
$source = str_replace($waku3, "", $source);

$waku_html = "";

$j = 1;
$i = 0;
$h = 0;
while($i < count($cast_order)){
	
	$gid = $cast_order[$i];
	
	//枠の初期化
	if($j == 5){ //5:w-b（横長1カラム）
		$waku1 = $waku_02; //
	}else{ //それ以外はすべてw-s（2カラムの横長）を使用
		$waku1 = $waku_01; //
	}
	
	
	//img
	if($j == 5){ //5:w-b（横長大）
		if($imagedata["filename"][$gid][2][0] != ""){
			$imgsize = 640;
			$imgfile = $imagedata["filename"][$gid][2][0];
			$imgtype = 'w';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . UP_DIR_W . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . UP_DIR_W . 'thumb/' . $fname;
		}else{
			$imgsize = 640;
			$imgfile = DAMMY_IMG_SQ_w;
			$imgtype = 'dmy';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
		}
		if(file_exists($img_path)){
		$imageres = $img_uri;
		}else{
		$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
		}
	}else{ //1~4,6,7... すべてw-s（横長小）
		if($imagedata["filename"][$gid][2][0] != ""){
			$imgsize = 300;
			$imgfile = $imagedata["filename"][$gid][2][0];
			$imgtype = 'w';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . UP_DIR_W . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . UP_DIR_W . 'thumb/' . $fname;
		}else{
			$imgsize = 300;
			$imgfile = DAMMY_IMG_SQ_w;
			$imgtype = 'dmy';
			//
			$plus = '_'.$imgsize.'_0_0_0';
			$fname = FilenamePlus($imgfile, $plus);
			$img_uri  = CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
			$img_path = UP_DIR . CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
		}
		if(file_exists($img_path)){
		$imageres = $img_uri;
		}else{
		$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
		}
	}
	//
	$waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
	//
	$waku1 = str_replace('rep03010090eot', 'girls.php?no=' . $girldata["no"][$gid], $waku1);//URI

	$waku1 = str_replace('rep00010320eot', $girldata["name"][$gid], $waku1);//NAME
	$waku1 = str_replace('rep00010321eot', $girldata["age"][$gid], $waku1);//AGE
	$waku1 = str_replace('rep00010322eot', $girldata["height"][$gid], $waku1);//height
	$waku1 = str_replace('rep00010323eot', $girldata["bust"][$gid], $waku1);//B
	$waku1 = str_replace('rep00010324eot', $cup_array[$girldata["cup"][$gid]], $waku1);//cup
	$waku1 = str_replace('rep00010325eot', $girldata["waist"][$gid], $waku1);//W
	$waku1 = str_replace('rep00010326eot', $girldata["hip"][$gid], $waku1);//H
	$waku1 = str_replace('rep00010331eot', strtoupper($girldata["name_romaji"][$gid]), $waku1);//romaji
	
	//新人判定
	if($girldata["newface"][$gid] == 1){ //体験
		
		//コメントイン
		$waku1 = str_replace('<!-- TRIAL', '<!-- TRIAL -->', $waku1);//
		$waku1 = str_replace('/TRIAL -->', '<!-- /TRIAL -->', $waku1);//
		
	}elseif($girldata["newface"][$gid] == 2){ //新人
	
		//コメントイン
		$waku1 = str_replace('<!-- NEWFACE', '<!-- NEWFACE -->', $waku1);//
		$waku1 = str_replace('/NEWFACE -->', '<!-- /NEWFACE -->', $waku1);//
		
	}
	
	//お気に入り判定
	if(in_array($gid, $favcast)){
		$waku1 = str_replace('<!-- ICONFAV', '<!-- ICONFAV -->', $waku1);//
		$waku1 = str_replace('/ICONFAV -->', '<!-- /ICONFAV -->', $waku1);//
	}
	
	//新着photo判定
	if($girldata["last_update"][$gid] != ""){
		$ymd1 = date("Y/m/d",getDay(0));
		$ymd2 = str_replace('-', '/', $girldata["last_update"][$gid]);
		$daydiff1 = (strtotime($ymd1)-strtotime($ymd2))/(3600*24);
		
		if($daydiff1 < 30){
		$waku1 = str_replace('<!-- ICONPHOTO', '<!-- ICONPHOTO -->', $waku1);//
		$waku1 = str_replace('/ICONPHOTO -->', '<!-- /ICONPHOTO -->', $waku1);//
		}
	}
	
	//日記判定
	$diaryck = 'false';
	if($diaryck == 'true'){
		$waku1 = str_replace('<!-- ICONDIARY', '<!-- ICONDIARY -->', $waku1);//
		$waku1 = str_replace('/ICONDIARY -->', '<!-- /ICONDIARY -->', $waku1);//
	}
	

//type = 1:出勤,6:受付終了(案内終了),0:未出勤(CLOSEDTODAY)
//type2= 1:受付中,2:キャンセル待ち,3:受付終了,4:ラスト1名,5:接客中(受付中),6:TEL確認
//schedule
$sche_type = $scheduledata[0]["view"]["type"][$gid];
//
if($sche_type == ""){//予定なし
$sche_type = 9;//休み
}elseif($sche_type == 6){
$sche_type = 8;//終了
}else{
$sche_type = 1;//出勤(受付中)
}
//
if($scheduledata[0]["view"]["type2"][$gid] == 6){ //TEL確認
$sche_type = 6;
}elseif($scheduledata[0]["view"]["type2"][$gid] == 3){ //受付終了
$sche_type = 8;
}

if($sche_type == 1){//出勤(受付中)
	
	//時間帯
	$status = $scheduledata[0]["view"]["time"][$gid];
	$waku1 = str_replace('rep00010354eot', $status, $waku1);
	//
	if($scheduledata[0]["plusview"][$gid] == 1){ //追加出勤
	$waku1 = str_replace('<!-- STATUS2', '<!-- STATUS2 -->', $waku1);//
	$waku1 = str_replace('/STATUS2 -->', '<!-- /STATUS2 -->', $waku1);//
	}else{
	$waku1 = str_replace('<!-- STATUS1', '<!-- STATUS1 -->', $waku1);//
	$waku1 = str_replace('/STATUS1 -->', '<!-- /STATUS1 -->', $waku1);//
	}
	
	if(in_array($gid, $favcast)){
		$waku1 = str_replace('cssSprite topIconFav', 'cssSprite topIconFav tIFv', $waku1);//
	}

}elseif($sche_type == 6){//TEL確認
	
	//
	if($scheduledata[0]["plusview"][$gid] == 1){ //追加出勤
	$waku1 = str_replace('<!-- STATUS4', '<!-- STATUS4-->', $waku1);//
	$waku1 = str_replace('/STATUS4 -->', '<!-- /STATUS4 -->', $waku1);//
	}else{
	$waku1 = str_replace('<!-- STATUS3', '<!-- STATUS3 -->', $waku1);//
	$waku1 = str_replace('/STATUS3 -->', '<!-- /STATUS3 -->', $waku1);//
	}
	
}elseif($sche_type == 8){//受付終了

	$waku1 = str_replace('<!-- STATUS5', '<!-- STATUS5-->', $waku1);//
	$waku1 = str_replace('/STATUS5 -->', '<!-- /STATUS5 -->', $waku1);//

}elseif($sche_type == 9){//休み

	$waku1 = str_replace('<!-- STATUS6', '<!-- STATUS6-->', $waku1);//
	$waku1 = str_replace('/STATUS6 -->', '<!-- /STATUS6 -->', $waku1);//

}


$waku_html .= $waku1;


$h++;
if($j == 7){ $j = 0; } //reset
$j++;
$i++;
}

//置換
$source = str_replace($waku0, $waku_html, $source);

//JavaScript生成（PC幅時はgBoxをJSで描画。json_encodeで確実にエスケープし全件がブラウザでparseされるようにする）
$que_count = count($que);
$cast_count = count($cast_order);
$jsc  = "window.__gBoxCountFromServer=" . $que_count . ";window.__castOrderCount=" . $cast_count . ";\n";
foreach($que as $val){
	$jsc .= "gBox.push(" . json_encode($val, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) . ");\n";
}
$jsc .= "if(typeof console!=='undefined')console.log('[candyTile] PHP: count(que)='+" . $que_count . "+', count(cast_order)='+" . $cast_count . "+', gBox.length='+(typeof gBox!=='undefined'?gBox.length:'n/a')+((typeof gBox!=='undefined'&&gBox.length!==" . $que_count . ")?' ★ MISMATCH':''));\n";
$data1['00010010'] = $jsc;

// エントランス画像設定
$img_path = IMG_HOME . CLUBID . '/';

$entrdata = array();
$QUERY  = "SELECT filename_pc,filename_sp FROM entrance_setting";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = 1";
$QUERY .= " ORDER BY id DESC LIMIT 1";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$entrdata["filename_pc"][]    = $row["filename_pc"];
		$entrdata["filename_sp"][]    = $row["filename_sp"];
	}
}
//img
if($entrdata["filename_pc"][0] != ""){
	$data1['01010012'] = $img_path . UP_DIR_ENTR . $entrdata["filename_pc"][0];
}else{
	$data1['01010012'] = '../imgHtml/entranceBg.jpg';
}

//TOPimg
$topimgdata = array();
$QUERY  = "SELECT filename_pc,filename_sp FROM topimg_setting";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = 1";
$QUERY .= " ORDER BY id DESC LIMIT 1";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$topimgdata["filename_pc"][]    = $row["filename_pc"];
		$topimgdata["filename_sp"][]    = $row["filename_sp"];
	}
}
//img
if($topimgdata["filename_pc"][0] != ""){
	$data1['01010011'] = $img_path . UP_DIR_TOPBK . $topimgdata["filename_pc"][0];
}else{
	$data1['01010011'] = IMG_HOME . "null.png";
}

//TOP動画
/*$topmvdata = array();
$QUERY  = "SELECT no,detail FROM shop_movie_tag";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND no = '1'";
$QUERY .= " AND status = 1";
$QUERY .= " ORDER BY no";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		//$shopdata["id"][]          = $row["id"];
		//$shopdata["club_id"][]     = $row["club_id"];
		//$shopdata["type"][]        = $row["type"];
		$topmvdata["no"][]          = $row["no"];
		$topmvdata["detail"]    = $row["detail"];
		//$shopdata["sort"][$row["no"]]        = $row["sort"];
	}
}
//
if($topmvdata["detail"] != ""){
	$data1['01010013'] = $topmvdata["detail"];
}else{
	$data1['01010013'] = "";
}

//TOPmovie&img
if($data1['01010013'] == ""){
	//img in
	$source = str_replace('<!-- TOPBACKIMG ', '<!-- TOPBACKIMG -->', $source);
	$source = str_replace(' /TOPBACKIMG -->', '<!-- /TOPBACKIMG -->', $source);
	//movie out
	$source = str_replace('<!-- TOPBACKMOVIE -->', '<!-- TOPBACKMOVIE', $source);
	$source = str_replace('<!-- /TOPBACKMOVIE -->', '/TOPBACKMOVIE -->', $source);
}*/

/*
* TOPバナー
*/
$bnrsetdata = array();
$bnrsetdata["id"] = array();
$QUERY  = "SELECT";
$QUERY .= " banner_top2.id, banner_top2.filename_pc, banner_top2.filename_sp, banner_top2.link_pc, banner_top2.link_sp";
$QUERY .= " FROM banner_top2_setting INNER JOIN banner_top2 ON banner_top2_setting.banner_id = banner_top2.id";
$QUERY .= " WHERE banner_top2_setting.club_id = '" . CLUBID . "'";
$QUERY .= " AND banner_top2_setting.banner_id IS NOT NULL";
$QUERY .= " AND banner_top2_setting.no < 101";
$QUERY .= " AND (banner_top2.filename_pc IS NOT NULL OR banner_top2.filename_sp IS NOT NULL)";
$QUERY .= " AND banner_top2.status = '0'";
$QUERY .= " ORDER BY banner_top2_setting.id";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$bnrsetdata["id"][] = $row["id"];
		$bnrsetdata["filename_pc"][$row["id"]] = $row["filename_pc"];
		$bnrsetdata["filename_sp"][$row["id"]] = $row["filename_sp"];
		$bnrsetdata["link_pc"][$row["id"]] = $row["link_pc"];
		$bnrsetdata["link_sp"][$row["id"]] = $row["link_sp"];
	}
}

//
$result = preg_match_all('/(<!-- bnrbox_01 -->.*?<!-- \/bnrbox_01 -->)/s', $source, $get_code);
if(isset($get_code[0][0])){
	$waku0 = $get_code[0][0];
	$waku = str_replace('<!-- bnrbox_01 -->', '', $waku0);
	$waku_01 = str_replace('<!-- /bnrbox_01 -->', '', $waku);
	$waku_html = "";
	$i = 0;$j = 0;
	
	if(count($bnrsetdata["id"]) > 0){
		while($i < count($bnrsetdata["id"])){
			//
			$bid = $bnrsetdata["id"][$i];
			
			//枠の初期化
			$waku1 = $waku_01; //
			
			//IMG
			$waku1 = str_replace('rep01010014eot', $img_path . UP_DIR_TOPBNR . $bnrsetdata["filename_pc"][$bid], $waku1);//
			
			//URL
			if($bnrsetdata["link_pc"][$bid] != ""){
			$waku1 = str_replace('rep03010014eot', $bnrsetdata["link_pc"][$bid], $waku1);//
			}else{
			$waku1 = str_replace('<a href="rep03010014eot"', '<a', $waku1);//
			}
			
			$waku_html .= $waku1;
			
			$j++;
			
		$i++;
		}
	}
	
	//置換
	$source = str_replace($waku0, $waku_html, $source);
}

// SP版用の動画URL設定（元のSP版ではコメントアウトされているため、ここでもコメントアウト）
//if($data1['01010013'] != ""){
//	$source = str_replace('rep01010013eot', $data1['01010013'], $source);
//}

// SP版用のバナー処理（topbannerクラス用）
$result = preg_match_all('/(<div class="topbanner spOnly">.*?<\/div>)/s', $source, $get_code);
if(isset($get_code[0][0]) && count($bnrsetdata["id"]) > 0){
	$waku0 = $get_code[0][0];
	$waku_html = "";
	$i = 0;
	while($i < count($bnrsetdata["id"])){
		$bid = $bnrsetdata["id"][$i];
		
		// SP版用のファイル名とリンクを選択
		$filename = !empty($bnrsetdata["filename_sp"][$bid]) ? $bnrsetdata["filename_sp"][$bid] : $bnrsetdata["filename_pc"][$bid];
		$link = !empty($bnrsetdata["link_sp"][$bid]) ? $bnrsetdata["link_sp"][$bid] : $bnrsetdata["link_pc"][$bid];
		
		//SP版用のバナーHTML生成
		$waku1 = '<div class="topbanner spOnly">';
		$waku1 .= '<a href="' . $link . '" target="_blank">';
		$waku1 .= '<img src="' . $img_path . UP_DIR_TOPBNR . $filename . '">';
		$waku1 .= '</a>';
		$waku1 .= '</div>';
		
		$waku_html .= $waku1;
		$i++;
	}
	
	//置換
	$source = str_replace($waku0, $waku_html, $source);
}

?>
