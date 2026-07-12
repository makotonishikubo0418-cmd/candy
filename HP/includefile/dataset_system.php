<?
/*********************************************************************
* データセット(一般ページ用)
* 
* 2011-07-
*********************************************************************/

//-----  変数取得・設定  -----//
$img_path = IMG_HOME . CLUBID . '/' . UP_DIR_HOTEL;




//-----  処理  -----//
//HOTEL情報を取得
$hoteldata = array();
$hoteldata["id"] = array();
$QUERY  = "SELECT * FROM hotel_coupon";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = 1";
$QUERY .= " ORDER BY id";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$hoteldata["id"][]          = $row["id"];
		$hoteldata["club_id"][$row["id"]]     = $row["club_id"];
		$hoteldata["no"][$row["id"]]          = $row["no"];
		$hoteldata["caption"][$row["id"]]     = $row["caption"];
		$hoteldata["filename"][$row["id"]]    = $row["filename"];
		$hoteldata["detail"][$row["id"]]      = $row["detail"];
		$hoteldata["telno"][$row["id"]]       = $row["telno"];
		$hoteldata["status"][$row["id"]]      = $row["status"];
	}
}



/*
* 独自タグから表示枠ソースを取得
*/
$source = file_get_contents($source_file);

if (isset($_SERVER['HTTP_USER_AGENT'])) {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	if (!preg_match("/(iPhone|iPad|Android|DoCoMo|UP\.Browser|J-PHONE|Vodafone|SoftBank|J-EMULATOR)/i", $user_agent)) {

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
	}
}


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
while($i < count($hoteldata["id"])){
	//if(($i >= $k) && ($h < $views)){//表示範囲指定
	
	$hid = $hoteldata["id"][$i];
	
	//枠の初期化
	$waku1 = $waku_01; //
	
	//img
	if($hoteldata["filename"][$hid] != ""){
		
		$waku1 = str_replace('rep01010070eot', $img_path . $hoteldata["filename"][$hid], $waku1);
		
	}else{
	
		$waku1 = str_replace('rep01010070eot', IMG_HOME . 'null.png', $waku1);
	
	}
	
		$waku1 = str_replace('rep00010320eot', $hoteldata["caption"][$hid], $waku1);//
		$waku1 = str_replace('rep00010321eot', $hoteldata["detail"][$hid], $waku1);//
		$waku1 = str_replace('rep00010322eot', $hoteldata["telno"][$hid], $waku1);//
	
		
	$waku_html .= $waku1;
	
	
	$h++;
	//}
	$j++;
$i++;
}

//置換
$source = str_replace($waku0, $waku_html, $source);

?>