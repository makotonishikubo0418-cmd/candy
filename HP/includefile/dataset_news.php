<?
/*********************************************************************
* データセット(一般ページ用)
* 
* 2011-07-
*********************************************************************/

//-----  変数取得・設定  -----//
$img_path = IMG_HOME . CLUBID . '/' . UP_DIR_NEWS;




//-----  処理  -----//
//HOTEL情報を取得
$newsldata = array();
$newsldata["id"] = array();
$QUERY  = "SELECT * FROM newstopics";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = 1";
$QUERY .= " AND ((date < '" . date("Y-m-d") . "') || (date = '" . date("Y-m-d") . "' AND time <= '" . date("H:i:s") . "'))"; //現在日付・時刻以前
$QUERY .= " ORDER BY date DESC, time DESC";
$QUERY .= " LIMIT 3";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$newsldata["id"][]          = $row["id"];
		$newsldata["club_id"][$row["id"]]     = $row["club_id"];
		$newsldata["no"][$row["id"]]          = $row["no"];
		$newsldata["date"][$row["id"]]        = $row["date"];
		$newsldata["time"][$row["id"]]        = $row["time"];
		$newsldata["caption"][$row["id"]]     = $row["caption"];
		$newsldata["detail"][$row["id"]]      = $row["detail"];
		$newsldata["image"][$row["id"]]       = $row["image"];
		$newsldata["status"][$row["id"]]      = $row["status"];
	}
}



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
//$source = str_replace($waku0, "", $source);

$waku_html = "";

$j = 1;
$i = 0;
$h = 0;
//$k = ($page-1)*$views;//表示開始用
while($i < count($newsldata["id"])){
	//if(($i >= $k) && ($h < $views)){//表示範囲指定
	
	$nid = $newsldata["id"][$i];
	
	//枠の初期化
	$waku1 = $waku_01; //
	
	//img
	if($newsldata["image"][$nid] != ""){
		
		$img = 'resizeimg.php?club='. CLUBID .'&j=' . $newsldata["image"][$nid] . '&size=560&type=news';
		$waku1 = str_replace('rep01010294eot', IMG_HOME . $img, $waku1);
		
	}else{
	
		$waku1 = str_replace('<div class="photo"><img src="rep01010294eot" class="nolazy" alt="キャンディからのお知らせ画像" width="560" height="560"></div>', '', $waku1);
	
	}
	
	//日付
	list($yy,$mm,$dd) = explode('-', $newsldata["date"][$nid]);
	//list($hh,$ii,$ss) = explode(':', $newsldata["time"][$nid]);
	$day = $month_name[intval($mm)] . ' ' . $dd . ', ' . $yy;
	$waku1 = str_replace('rep00010400eot', $day, $waku1);
	
	$waku1 = str_replace('rep00010401eot', $newsldata["caption"][$nid], $waku1);//
	$waku1 = str_replace('rep00010402eot', nl2br($newsldata["detail"][$nid]), $waku1);//
	
		
	$waku_html .= $waku1;
	
	
	$h++;
	//}
	$j++;
$i++;
}

//置換
if($waku_html == ""){
	// ニュースがない場合、テンプレート部分を削除して、noNewsメッセージを表示
	$source = str_replace($waku0, '', $source);
	$source = str_replace('<div class="noNews" style="display:none;">', '<div class="noNews">', $source);
} else {
	// ニュースがある場合、テンプレート部分を置換し、noNewsメッセージを非表示のままにする
	$source = str_replace($waku0, $waku_html, $source);
}

?>
