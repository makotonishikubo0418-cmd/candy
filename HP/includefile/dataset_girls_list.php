<?php

/*********************************************************************
* データセット(一般ページ用)
* 
* 2011-07-
*********************************************************************/

//-----  変数取得・設定  -----//
//$img_path = IMG_HOME . CLUBID . '/' . UP_DIR_NEWS;

// お気に入り数の設定
$favcast = array();
if(isset($_COOKIE["candyfav"])){
	$favcast = explode(',', urldecode($_COOKIE["candyfav"]));
}
$data1['00010601'] = count($favcast);


//-----  処理(SPECIAL-EVENT追加分)  -----//


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
		//$sortdata["id"][]       = $row["id"];
		//$sortdata["club_id"][]  = $row["club_id"];
		//$sortdata["no"][]       = $row["no"];
		$sortdata["sort_id"][]  = $row["sort_id"];
		//$sortdata["status"][]   = $row["status"];
	}
}
if($sortdata["sort_id"][0] == 1){ //ランダム
$sort = 11;
}elseif($sortdata["sort_id"][0] == 2){ //任意順
$sort = 12;
}elseif($sortdata["sort_id"][0] == 3){ //名前順
$sort = 13;
}
if (isset($_SERVER['HTTP_USER_AGENT'])) {
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
  if (preg_match("/(iPhone|iPad|Android|DoCoMo|UP\.Browser|J-PHONE|Vodafone|SoftBank|J-EMULATOR)/i", $user_agent)) {

    /*
* 女の子情報取得
*/
    $girldata = array();
    $girldata["id"] = array();
    $QUERY  = "SELECT";
    $QUERY .= " id, club_id, no, name, age, height, bust, cup, waist, hip, name_kana, name_romaji";
    $QUERY .= ", caption, detail, image1, image2, type1, type2, playok, nyuuten, newface, options, next_photo_update, type_toku, last_update, last_uptype";
    $QUERY .= " FROM girls_data";
    //$QUERY .= " WHERE (club_id = '2' || club_id = '3' || club_id = '4')";
    $QUERY .= " WHERE club_id = '" . CLUBID . "'";
    $QUERY .= " AND status = 1";
    //$QUERY .= " ORDER BY nyuuten DESC, id DESC;";
    if ($sort == 12) { //任意順
      $QUERY .= " ORDER BY sort, id DESC";
    } elseif ($sort == 13) { //名前順
      $QUERY .= " ORDER BY name_kana, id DESC";
    } else { //
      $QUERY .= " ORDER BY id DESC";
    }
    $RESULT = $Database->Query($QUERY);
    $ROWS = $Database->Num_Rows($RESULT);
    if ($ROWS != 0) {
      while ($row = $Database->Fetch_Array($RESULT)) {
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
        /*$girldata["newface"][$row["id"]]     = $row["newface"];
		$girldata["gravure"][$row["id"]]     = $row["gravure"];
		$girldata["in_blog"][$row["id"]]     = $row["in_blog"];
		$girldata["out_blog"][$row["id"]]    = $row["out_blog"];
		$girldata["teiban"][$row["id"]]      = $row["teiban"];
		$girldata["status"][$row["id"]]      = $row["status"];
		$girldata["grade"][$row["id"]]       = $row["grade"];*/
        $girldata["last_update"][$row["id"]] = $row["last_update"];
        $girldata["last_uptype"][$row["id"]] = $row["last_uptype"]; //1:img,2:syame,3:news,4:realtime:
        $girldata["next_photo_update"][$row["id"]] = $row["next_photo_update"];
        $girldata["type_toku"][$row["id"]]       = $row["type_toku"];
      }
    }
    if ($sort == 11) {
      shuffle($girldata["id"]);
    }



    /*
* 画像情報を取得 // horw/1:h,2:w
*/
    $imagedata = array();
    $QUERY  = "SELECT `type`, girls_id, filename FROM girls_images";
    $QUERY .= " WHERE club_id = '" . CLUBID . "'";
    $QUERY .= " AND (type = '1' || type = '2')"; //sq or w
    //$QUERY .= " AND type = '2'"; //w
    $QUERY .= " AND status = 1";
    $QUERY .= " ORDER BY sort, id DESC";
    $RESULT = $Database->Query($QUERY);
    $ROWS = $Database->Num_Rows($RESULT);
    if ($ROWS != 0) {
      while ($row = $Database->Fetch_Array($RESULT)) {
        //$imagedata["id"][]       = $row["id"];
        //$imagedata["club_id"][]  = $row["club_id"];
        //$imagedata["girls_id"][] = $row["girls_id"];
        //$imagedata["no"][$row["type"]][$row["girls_id"]][]       = $row["no"];
        //$imagedata["type"][]     = $row["type"];
        //$imagedata["name"][$row["type"]][$row["girls_id"]][]     = $row["name"];
        //$imagedata["filename"][$row["type"]][$row["girls_id"]][] = $row["filename"];
        //$imagedata["status"][$row["type"]][$row["girls_id"]][]   = $row["status"];
        //$imagedata["filename"][$row["girls_id"]][] = $row["filename"];
        if ($row["type"] == 1) {
          $imagedata["filename"][$row["girls_id"]][1][] = $row["filename"];
        } elseif ($row["type"] == 2) {
          $imagedata["filename"][$row["girls_id"]][2][] = $row["filename"];
        }
      }
    }






    /*
* 出勤数
*/
    /*$y = date('Y');
$m = date('n');
$d = date('j');
$schedule_count = array();
$schedule_count_all = 0;
$QUERY  = "SELECT club_id, count(*) AS cnt FROM girls_schedule";
//$QUERY .= " WHERE (club_id = '2' || club_id = '3' || club_id = '4')";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND (type = '1' || type = '6' || type = '0')";
$QUERY .= " AND year = '" . $y . "'";
$QUERY .= " AND month = '" . $m . "'";
$QUERY .= " AND day = '" . $d . "'";
//$QUERY .= " AND type2 >= '0'";
$QUERY .= " AND views >= '0'";
$QUERY .= " AND status = '1'";
//$QUERY .= " ORDER BY aki_ji, aki_fun, open_ji, open_fun, id DESC";//待ち時間順にソート
//$QUERY .= " ORDER BY open_ji, open_fun, end_ji, end_fun, id DESC";//出勤時間順にソート
$QUERY .= " GROUP BY club_id";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$schedule_count[$row["club_id"]] = $row["cnt"];
		$schedule_count_all += $row["cnt"];
	}
}*/


    //--  NOW  --//
    /*
* 日付情報生成
*/
    $weekarr1 = array(0 => 'SUN', 1 => 'MON', 2 => 'TUE', 3 => 'WED', 4 => 'THU', 5 => 'FRI', 6 => 'SAT');
    $weekarr = array(0 => '日', 1 => '月', 2 => '火', 3 => '水', 4 => '木', 5 => '金', 6 => '土');
    //
    //切替時間確認
    if (date('G') < NEWDAY_TIME) {
      $last_ymd = date("Y-n-j", getDay(-1));
      list($yy, $mm, $dd) = explode('-', $last_ymd);
      $ww = date("w", getDay(-1));
    } else {
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
    while ($i < 8) {
      if (checkdate($mm, $dd, $yy)) {
        $yyy[] = $yy;
        $mmm[] = $mm;
        $ddd[] = $dd;
        $dd++;
        $i++;
      } else {
        $dd = 1;
        $mm++;
        if (checkdate($mm, $dd, $yy)) {
          $yyy[] = $yy;
          $mmm[] = $mm;
          $ddd[] = $dd;
          $dd++;
          $i++;
        } else {
          $mm = 1;
          $yy++;
          if (checkdate($mm, $dd, $yy)) {
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
      if ($ww > 6) {
        $ww = 0;
      }
    }

    //if($work == ""){$work = 1;} //空白時強制

    //検索用日付設定
    if ($work != "") {
      $y = $yyy[$work];
      $m = $mmm[$work];
      $d = $ddd[$work];
      $now = 0;
    } else {
      //切替時間確認
      if (date('G') < NEWDAY_TIME) {
        $last_ymd = date("Y-n-j", getDay(-1));
        list($y, $m, $d) = explode('-', $last_ymd);
        $now = intval(date('Gi')) + 2400;
      } else {
        $y = date('Y');
        $m = date('n');
        $d = date('j');
        $now = intval(date('Gi'));
      }
    }

    //表示用
    $workday = array();
    $workday[0] = $mmm[0] . '/' . $ddd[0]; // . '(' . $weekarr1[$www[0]] . ')';
    $workday[1] = $mmm[1] . '/' . $ddd[1]; // . '(' . $weekarr1[$www[1]] . ')';
    $workday[2] = $mmm[2] . '/' . $ddd[2]; // . '(' . $weekarr1[$www[2]] . ')';
    $workday[3] = $mmm[3] . '/' . $ddd[3]; // . '(' . $weekarr1[$www[3]] . ')';
    $workday[4] = $mmm[4] . '/' . $ddd[4]; // . '(' . $weekarr1[$www[4]] . ')';
    $workday[5] = $mmm[5] . '/' . $ddd[5]; // . '(' . $weekarr1[$www[5]] . ')';
    $workday[6] = $mmm[6] . '/' . $ddd[6]; // . '(' . $weekarr1[$www[6]] . ')';


    /*
* 出勤情報取得
*/
    //$y = date('Y');
    //$m = date('n');
    //$d = date('j');
    //$now = intval(date('Gi'));
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
    $QUERY  = "SELECT * FROM girls_schedule";
    $QUERY .= " WHERE club_id = '" . CLUBID . "'";
    $QUERY .= " AND (type = '1' || type = '6' || type = '0')";
    //$QUERY .= " AND year = '" . $y . "'";
    //$QUERY .= " AND month = '" . $m . "'";
    //$QUERY .= " AND day = '" . $d . "'";

    //1week全選択
    $QUERY .= " AND ( ";
    $QUERY .= "(year = '" . $yyy[0] . "' AND month = '" . $mmm[0] . "' AND day = '" . $ddd[0] . "')";
    $QUERY .= " || (year = '" . $yyy[1] . "' AND month = '" . $mmm[1] . "' AND day = '" . $ddd[1] . "')";
    //$QUERY .= " || (year = '" . $yyy[2] . "' AND month = '" . $mmm[2] . "' AND day = '" . $ddd[2] . "')";
    //$QUERY .= " || (year = '" . $yyy[3] . "' AND month = '" . $mmm[3] . "' AND day = '" . $ddd[3] . "')";
    //$QUERY .= " || (year = '" . $yyy[4] . "' AND month = '" . $mmm[4] . "' AND day = '" . $ddd[4] . "')";
    //$QUERY .= " || (year = '" . $yyy[5] . "' AND month = '" . $mmm[5] . "' AND day = '" . $ddd[5] . "')";
    //$QUERY .= " || (year = '" . $yyy[6] . "' AND month = '" . $mmm[6] . "' AND day = '" . $ddd[6] . "')";
    $QUERY .= " ) ";

    //$QUERY .= " AND type2 >= '0'";
    $QUERY .= " AND views >= '0'";
    $QUERY .= " AND status = '1'";
    //$QUERY .= " ORDER BY aki_ji, aki_fun, open_ji, open_fun, id DESC";//待ち時間順にソート
    //$QUERY .= " ORDER BY aki_ji, aki_fun, open_ji, open_fun, end_ji, end_fun, id DESC";//待ち時間順にソート
    //$QUERY .= " ORDER BY open_ji, open_fun, end_ji, end_fun, id DESC";//時間順にソート
    $QUERY .= " ORDER BY year, month, day, open_ji, open_fun, end_ji, end_fun, id DESC"; //日付、時間順にソート
    $RESULT = $Database->Query($QUERY);
    $ROWS = $Database->Num_Rows($RESULT);
    if ($ROWS != 0) {
      while ($row = $Database->Fetch_Array($RESULT)) {

        if ($row["year"] == $yyy[0] && $row["month"] == $mmm[0] && $row["day"] == $ddd[0]) {
          $weekid = 0;
        } elseif ($row["year"] == $yyy[1] && $row["month"] == $mmm[1] && $row["day"] == $ddd[1]) {
          $weekid = 1;
        } elseif ($row["year"] == $yyy[2] && $row["month"] == $mmm[2] && $row["day"] == $ddd[2]) {
          $weekid = 2;
        } elseif ($row["year"] == $yyy[3] && $row["month"] == $mmm[3] && $row["day"] == $ddd[3]) {
          $weekid = 3;
        } elseif ($row["year"] == $yyy[4] && $row["month"] == $mmm[4] && $row["day"] == $ddd[4]) {
          $weekid = 4;
        } elseif ($row["year"] == $yyy[5] && $row["month"] == $mmm[5] && $row["day"] == $ddd[5]) {
          $weekid = 5;
        } elseif ($row["year"] == $yyy[6] && $row["month"] == $mmm[6] && $row["day"] == $ddd[6]) {
          $weekid = 6;
        } else {
          $weekid = 9;
        }

        if ($row["end_fun"] < 10) {
          $endtime = intval($row["end_ji"] . "0" . $row["end_fun"]);
        } else {
          $endtime = intval($row["end_ji"] . $row["end_fun"]);
        }

        if ($row["type"] == 0 || $row["type"] == 1 || $row["type"] == 6) {

          //if($work > 0){} //翌日以降は全て出勤予定に
          if ($weekid > 0) { //翌日以降は全て出勤予定に

            if ($row["open_ji"] == 100) { //日の出
              $karicast11[$weekid][] = $row["girls_id"];
            } else {
              $karicast12[$weekid][] = $row["girls_id"];
            }

            //
            $scheduledata[$weekid]["type2"][$row["girls_id"]]    = $row["type2"];
            $scheduledata[$weekid]["view"]["type2"][$row["girls_id"]] = $row["type2"];
          } else {

            //if(($row["type"] == 6) || ($row["type2"] == 3) || ($endtime < $now) || (2330 <= $now)){} //受付終了or受付終了or受付時間＜現在時刻or23:30以降
            if (($row["type"] == 6) || ($row["type2"] == 3) || ($endtime < $now)) { //受付終了or受付終了or受付時間＜現在時刻or23:30以降

              if ($row["open_ji"] == 100) { //日の出
                $karicast21[$weekid][] = $row["girls_id"];
              } else {
                $karicast22[$weekid][] = $row["girls_id"];
              }
            } elseif ($row["type"] == 0 || $row["type"] == 1) { //出勤予定or出勤済

              if ($row["open_ji"] == 100) { //日の出
                $karicast11[$weekid][] = $row["girls_id"];
              } else {
                $karicast12[$weekid][] = $row["girls_id"];
              }
            }

            //終了時刻経過
            if ($endtime < $now) {
              $scheduledata[$weekid]["type2"][$row["girls_id"]]    = 3;
              $scheduledata[$weekid]["view"]["type2"][$row["girls_id"]] = 3;
            } else {
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
        //$scheduledata[$weekid]["type2"][$row["girls_id"]]    = $row["type2"];
        $scheduledata[$weekid]["aki_ji"][$row["girls_id"]]   = $row["aki_ji"];
        $scheduledata[$weekid]["aki_fun"][$row["girls_id"]]  = $row["aki_fun"];
        $scheduledata[$weekid]["plusview"][$row["girls_id"]]  = $row["plusview"];

        //表示テキスト生成
        if ($row["type"] == 0 || $row["type"] == 1 || $row["type"] == 6) {

          //開始時間
          if ($row["open_ji"] == 100) {
            $vtime = '日の出';
          } else {
            if ($row["open_fun"] == 0) {
              $ofun = "00";
            } else {
              $ofun = $row["open_fun"];
            }
            $vtime = $row["open_ji"] . ":" . $ofun;
          }

          //$vtime = $row["open_ji"] . ":" . $ofun . " - ";
          $vtime .= "<span>▼</span>"; //"~";//

          if ($row["end_ji"] == 99) {
            $vtime .= ""; //"LAST";
          } else {
            if ($row["end_fun"] == 0) {
              $efun = "00";
            } else {
              $efun = $row["end_fun"];
            }
            $vtime .= $row["end_ji"] . ":" . $efun;
          }

          $scheduledata[$weekid]["view"]["time"][$row["girls_id"]] = $vtime; //時間
          $scheduledata[$weekid]["view"]["type"][$row["girls_id"]] = $row["type"]; //
          //$scheduledata[$weekid]["view"]["type2"][$row["girls_id"]] = $row["type2"]; //


          //出勤２
          /*if($row["kn2_open_ji"] != ""){
			
			//開始時間
			if($row["kn2_open_ji"] == 100){
				$vtime2 = '日の出';
			}else{
				if($row["kn2_open_fun"] == 0){
					$ofun = "00";
				}else{
					$ofun = $row["kn2_open_fun"];
				}
				$vtime2 = $row["kn2_open_ji"] . ":" . $ofun;
			}
			
			//$vtime = $row["open_ji"] . ":" . $ofun . " - ";
			$vtime2 .= "<span>▼</span>";//"~";//" - ";
			
			if($row["kn2_end_ji"] == 99){
				$vtime2 .= "LAST";
			}else{
				if($row["kn2_end_fun"] == 0){
					$efun = "00";
				}else{
					$efun = $row["kn2_end_fun"];
				}
				$vtime2 .= $row["kn2_end_ji"] . ":" . $efun;
			}
			
			$scheduledata[$weekid]["view"]["time2"][$row["girls_id"]] = $vtime2; //時間
			
			}else{
			$scheduledata[$weekid]["view"]["time2"][$row["girls_id"]] = ""; //時間
			}*/
        } else {
          $scheduledata[$weekid]["view"]["type"][$row["girls_id"]] = $row["type"]; //出/休
        }
      }
    }/*else{
$scheduledata["girls_id"] = array();
}*/
    //$scheduledata["girls_id"] = array_merge($karicast11, $karicast12); //本日出勤
    //$scheduledata2["girls_id"] = array_merge($karicast21, $karicast22);//受付終了
    //$scheduledata["girls_id"] = array_merge($karicast11, $karicast12, $karicast21, $karicast22); //全部
    /*$i = 0;
while($i < 7){
	$scheduledata2[$i]["girls_id"] = array_merge($karicast21[$i], $karicast22[$i]);//受付終了
$i++;
}
$i = 0;
while($i < 7){
	$scheduledata[$i]["girls_id"] = array_merge($karicast11[$i], $karicast12[$i], $karicast21[$i], $karicast22[$i]); //全部
$i++;
}*/
    //$scheduledata2["girls_id"] = array_merge($karicast21, $karicast22);//受付終了
    //$scheduledata["girls_id"] = array_merge($karicast11, $karicast12, $karicast21, $karicast22); //全部

    $week = array(0 => 'SUN', 1 => 'MON', 2 => 'TUE', 3 => 'WED', 4 => 'THU', 5 => 'FRI', 6 => 'SAT');
    //$today = date("Y.m/d") . '(' .$week[date('w')]. ')';

    //
    $data1['00010611'] = count($girldata["id"]);


    /*
* 独自タグから表示枠ソースを取得
*/
    $source = file_get_contents($source_file);


    //FAV-COOKIE取得
    $favcast = array();
    if (isset($_COOKIE["candyfav"])) {
      $favcast = explode(',', urldecode($_COOKIE["candyfav"]));
    }
    
    // お気に入り数の表示処理
    $data1['00010601'] = count($favcast);


    /*
* 枠1
*/
    $result = preg_match_all('/(<!-- girlsbox_01 -->.*?<!-- \/girlsbox_01 -->)/s', $source, $get_code);
    $waku0 = $get_code[0][0];
    $waku = str_replace('<!-- girlsbox_01 -->', '', $waku0);
    $waku_01 = str_replace('<!-- /girlsbox_01 -->', '', $waku);
    //$source = str_replace($waku0, "", $source);

    /*
* 枠2
*/
    /*$result = preg_match_all('/(<!-- girlsbox_02 -->.*?<!-- \/girlsbox_02 -->)/s', $source, $get_code);
$waku2 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_02 -->', '', $waku2);
$waku_02 = str_replace('<!-- /girlsbox_02 -->', '', $waku);
$source = str_replace($waku2, "", $source);*/

    /*
* 枠3
*/
    /*$result = preg_match_all('/(<!-- girlsbox_03 -->.*?<!-- \/girlsbox_03 -->)/s', $source, $get_code);
$waku3 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_03 -->', '', $waku3);
$waku_03 = str_replace('<!-- /girlsbox_03 -->', '', $waku);
$source = str_replace($waku3, "", $source);*/

    $waku_html = "";

    $j = 1;
    $i = 0;
    $h = 0;
    //$k = ($page-1)*$views;//表示開始用
    while ($i < count($girldata["id"])) {
      //if(($i >= $k) && ($h < $views)){//表示範囲指定

      $gid = $girldata["id"][$i];

      //枠の初期化
      $waku1 = $waku_01; //


      //img sq
      
      
      if (isset($imagedata["filename"][$gid][1][0]) && $imagedata["filename"][$gid][1][0] != "") {
        $imgsize = 50;
        $imgfile = $imagedata["filename"][$gid][1][0];
        $imgtype = 'icon';
        //
        $plus = '_' . $imgsize . '_0_0_0';
        $fname = FilenamePlus($imgfile, $plus);
        $img_uri  = CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
        $img_path = UP_DIR . CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
        if (file_exists($img_path)) {
          $imageres = $img_uri;
        } else {
          $imageres = 'resizeimg.php?club=' . CLUBID . '&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
        }
        $waku1 = str_replace('rep01010071eot', IMG_HOME . $imageres, $waku1);
        
      } else {
        $imgsize = 50;
        $imgfile = DAMMY_IMG_SQ;
        $imgtype = 'dmy';
        $imageres = './imgHtml/unnamed.jpg';
        $waku1 = str_replace('rep01010071eot', $imageres, $waku1);
        
      }
      //$imageres = 'resizeimg.php?club='. CLUBID .'&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
      //

      //img w
      if ($imagedata["filename"][$gid][2][0] != "") {
        $imgsize = 640;
        $imgfile = $imagedata["filename"][$gid][2][0];
        $imgtype = 'w';
        //
        $plus = '_' . $imgsize . '_0_0_0';
        $fname = FilenamePlus($imgfile, $plus);
        $img_uri  = CLUBID . '/' . UP_DIR_W . 'thumb/' . $fname;
        $img_path = UP_DIR . CLUBID . '/' . UP_DIR_W . 'thumb/' . $fname;
      } else {
        $imgsize = 640;
        $imgfile = DAMMY_IMG_SQ_w;
        $imgtype = 'dmy';
        //
        $plus = '_' . $imgsize . '_0_0_0';
        $fname = FilenamePlus($imgfile, $plus);
        $img_uri  = CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
        $img_path = UP_DIR . CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
      }
      if (file_exists($img_path)) {
        $imageres = $img_uri;
      } else {
        $imageres = 'resizeimg.php?club=' . CLUBID . '&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
      }
      //
      $waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
      //
      $waku1 = str_replace('rep03010090eot', 'girls.php?no=' . $girldata["no"][$gid], $waku1); //URI

      $waku1 = str_replace('rep00010320eot', $girldata["name"][$gid], $waku1); //NAME
      $waku1 = str_replace('rep00010321eot', $girldata["age"][$gid], $waku1); //AGE
      $waku1 = str_replace('rep00010322eot', $girldata["height"][$gid], $waku1); //height
      $waku1 = str_replace('rep00010323eot', $girldata["bust"][$gid], $waku1); //B
      $waku1 = str_replace('rep00010324eot', $cup_array[$girldata["cup"][$gid]], $waku1); //cup
      $waku1 = str_replace('rep00010325eot', $girldata["waist"][$gid], $waku1); //W
      $waku1 = str_replace('rep00010326eot', $girldata["hip"][$gid], $waku1); //H
      //$waku1 = str_replace('rep00010331eot', ucwords(strtolower($girldata["name_romaji"][$gid])), $waku1);//romaji
      $waku1 = str_replace('rep00010331eot', strtoupper($girldata["name_romaji"][$gid]), $waku1); //romaji

      //
      /*if($grade_name[$girldata["grade"][$gid]] != ""){
		$waku1 = str_replace('rep00010332eot', $grade_name[$girldata["grade"][$gid]], $waku1);//grade
		}else{
		$waku1 = str_replace('<div class="rank">rep00010332eot</div>', '', $waku1);//grade
		}*/
      /*
		if($scheduledata[$wk]["caption"][$gid] != ""){
		$waku1 = str_replace('rep00010390eot', $scheduledata[$wk]["caption"][$gid], $waku1);//フリーテキスト
		}else{
		$waku1 = str_replace('rep00010390eot', '', $waku1);//フリーテキスト
		}*/
      //$waku1 = str_replace('rep00010329eot', EncodeEucToUtf8($girldata["caption"][$gid]), $waku1);//リード
      //
      /*$kari = EncodeEucToUtf8($girldata["caption"][$gid]);
		if(mb_strlen($kari, 'UTF-8') > 25){ //長ければ省略
		$kari2 = mb_substr($kari, 0, 24, 'UTF-8') . '…';
		}else{
		$kari2 = $kari;
		}
		$waku1 = str_replace('rep00010329eot', $kari2, $waku1);//*/

      /*
		//$waku1 = str_replace('rep00010340eot', $scheduledata[$wk]["view"]["time"][$gid], $waku1);//勤務時間
		//
		if($scheduledata[$wk]["view"]["time2"][$gid] != ""){ //出勤２があれば連結表示
		$work_time = $scheduledata[$wk]["view"]["time"][$gid] . ', ' . $scheduledata[$wk]["view"]["time2"][$gid];
		}else{
		$work_time = $scheduledata[$wk]["view"]["time"][$gid];
		}
		$waku1 = str_replace('rep00010340eot', $work_time, $waku1);//勤務時間

		$waku1 = str_replace('rep01010293eot', $grade_arr2[$girldata["grade"][$gid]], $waku1);//GRADE
		*/
      /*if($funiki2 != ""){
		$waku1 = str_replace('rep00010327eot', $funiki_arr[$funiki2], $waku1);//雰囲気
		}else{
			if($girldata["image1"][$schedate[$j][$i]] != "" && $girldata["image2"][$gid] != ""){ //２つ設定されていればランダムに表示
				$rnd = mt_rand(0, 1);
				if($rnd == 0){
				$waku1 = str_replace('rep00010327eot', $funiki_arr[$girldata["image1"][$gid]], $waku1);//雰囲気
				}else{
				$waku1 = str_replace('rep00010327eot', $funiki_arr[$girldata["image2"][$gid]], $waku1);//雰囲気
				}
			}else{
				$waku1 = str_replace('rep00010327eot', $funiki_arr[$girldata["image1"][$gid]], $waku1);//雰囲気
			}
		}*/
      /*if($type2 != ""){
		$waku1 = str_replace('rep00010328eot', $type_arr[$type2], $waku1);//タイプ
		}else{
			if($girldata["type1"][$gid] != "" && $girldata["type2"][$gid] != ""){
				$rnd = mt_rand(0, 1);
				if($rnd == 0){
				$waku1 = str_replace('rep00010328eot', $type_arr[$girldata["type1"][$gid]], $waku1);//タイプ
				}else{
				$waku1 = str_replace('rep00010328eot', $type_arr[$girldata["type2"][$gid]], $waku1);//タイプ
				}
			}else{
				$waku1 = str_replace('rep00010328eot', $type_arr[$girldata["type1"][$gid]], $waku1);//タイプ
			}
		}*/
      /*if($play != ""){
			$waku1 = str_replace('rep00010327eot', $play_arr[$play], $waku1);//可能プレイ
		}else{
			if($girldata["playok"][$gid] != ""){
				$plays = array();
				$playsb = ltrim($girldata["playok"][$gid] , ',');//左端','削除
				$playsb = rtrim($playsb , ',');//右端','削除
				$plays  = explode(',' , $playsb);
				if(count($plays) > 1){
				$playsmax = count($plays) - 1;
				$rnd = mt_rand(0, count($playsmax));
				}else{
				$rnd = 0;
				}
				$waku1 = str_replace('rep00010327eot', $play_arr[$plays[$rnd]], $waku1);//可能プレイ
			}else{
				$waku1 = str_replace('rep00010327eot', '', $waku1);
			}
		}*/

      //新人判定
      if ($girldata["newface"][$gid] == 1) { //体験

        //コメントイン
        $waku1 = str_replace('<!-- TRIAL', '<!-- TRIAL -->', $waku1); //
        $waku1 = str_replace('/TRIAL -->', '<!-- /TRIAL -->', $waku1); //

      } elseif ($girldata["newface"][$gid] == 2) { //新人

        //コメントイン
        $waku1 = str_replace('<!-- NEWFACE', '<!-- NEWFACE -->', $waku1); //
        $waku1 = str_replace('/NEWFACE -->', '<!-- /NEWFACE -->', $waku1); //

      }

      //お気に入り判定
      if (in_array($gid, $favcast)) {
        $waku1 = str_replace('<!-- ICONFAV', '<!-- ICONFAV -->', $waku1); //
        $waku1 = str_replace('/ICONFAV -->', '<!-- /ICONFAV -->', $waku1); //
      }

      //新着photo判定
      if ($girldata["last_update"][$gid] != "") {
        $ymd1 = date("Y/m/d", getDay(0));
        $ymd2 = str_replace('-', '/', $girldata["last_update"][$gid]);
        $daydiff1 = (strtotime($ymd1) - strtotime($ymd2)) / (3600 * 24);

        if ($daydiff1 < 30) {
          $waku1 = str_replace('<!-- ICONPHOTO', '<!-- ICONPHOTO -->', $waku1); //
          $waku1 = str_replace('/ICONPHOTO -->', '<!-- /ICONPHOTO -->', $waku1); //
        }
      }

      //日記判定
      //$diaryck = 'true';
      //$diaryck = 'false';
      if ($diaryck == 'true') {
        $waku1 = str_replace('<!-- ICONDIARY', '<!-- ICONDIARY -->', $waku1); //
        $waku1 = str_replace('/ICONDIARY -->', '<!-- /ICONDIARY -->', $waku1); //
      }


      //type = 1:出勤,6:受付終了(案内終了),0:未出勤(CLOSEDTODAY)
      //type2= 1:受付中,2:キャンセル待ち,3:受付終了,4:ラスト1名,5:接客中(受付中),6:TEL確認
      //schedule
      $sche_type = $scheduledata[0]["view"]["type"][$gid];
      //
      if ($sche_type == "") { //予定なし
        $sche_type = 9; //休み
      } elseif ($sche_type == 6) {
        $sche_type = 8; //終了
      } else {
        $sche_type = 1; //出勤(受付中)
      }
      //
      if ($scheduledata[0]["view"]["type2"][$gid] == 6) { //TEL確認
        $sche_type = 6;
      } elseif ($scheduledata[0]["view"]["type2"][$gid] == 3) { //受付終了
        $sche_type = 8;
      }

      if ($sche_type == 1) { //出勤(受付中)

        //時間帯
        $status = $scheduledata[0]["view"]["time"][$gid];
        $waku1 = str_replace('rep00010354eot', $status, $waku1);
        //
        if ($scheduledata[0]["plusview"][$gid] == 1) { //追加出勤
          $waku1 = str_replace('<!-- STATUS2', '<!-- STATUS2 -->', $waku1); //
          $waku1 = str_replace('/STATUS2 -->', '<!-- /STATUS2 -->', $waku1); //
        } else {
          $waku1 = str_replace('<!-- STATUS1', '<!-- STATUS1 -->', $waku1); //
          $waku1 = str_replace('/STATUS1 -->', '<!-- /STATUS1 -->', $waku1); //
        }
      } elseif ($sche_type == 6) { //TEL確認

        //
        if ($scheduledata[0]["plusview"][$gid] == 1) { //追加出勤
          $waku1 = str_replace('<!-- STATUS4', '<!-- STATUS4-->', $waku1); //
          $waku1 = str_replace('/STATUS4 -->', '<!-- /STATUS4 -->', $waku1); //
        } else {
          $waku1 = str_replace('<!-- STATUS3', '<!-- STATUS3 -->', $waku1); //
          $waku1 = str_replace('/STATUS3 -->', '<!-- /STATUS3 -->', $waku1); //
        }
      } elseif ($sche_type == 8) { //受付終了

        $waku1 = str_replace('<!-- STATUS5', '<!-- STATUS5-->', $waku1); //
        $waku1 = str_replace('/STATUS5 -->', '<!-- /STATUS5 -->', $waku1); //

      } elseif ($sche_type == 9) { //休み

        $waku1 = str_replace('<!-- STATUS6', '<!-- STATUS6-->', $waku1); //
        $waku1 = str_replace('/STATUS6 -->', '<!-- /STATUS6 -->', $waku1); //

      }


      $waku_html .= $waku1;


      $h++;
      //}
      $j++;
      $i++;
    }

    //置換
    $source = str_replace($waku0, $waku_html, $source);
  } else {
    /*
* 女の子情報取得
*/
    $girldata = array();
    $girldata["id"] = array();
    $QUERY  = "SELECT";
    $QUERY .= " id, club_id, no, name, age, height, bust, cup, waist, hip, name_kana, name_romaji";
    $QUERY .= ", caption, detail, image1, image2, type1, type2, playok, nyuuten, newface, options, next_photo_update, type_toku";
    $QUERY .= " FROM girls_data";
    //$QUERY .= " WHERE (club_id = '2' || club_id = '3' || club_id = '4')";
    $QUERY .= " WHERE club_id = '" . CLUBID . "'";
    $QUERY .= " AND status = 1";
    //$QUERY .= " ORDER BY nyuuten DESC, id DESC;";
    if ($sort == 12) { //任意順
      $QUERY .= " ORDER BY sort, id DESC";
    } elseif ($sort == 13) { //名前順
      $QUERY .= " ORDER BY name_kana, id DESC";
    } else { //
      $QUERY .= " ORDER BY id DESC";
    }
    $RESULT = $Database->Query($QUERY);
    $ROWS = $Database->Num_Rows($RESULT);
    if ($ROWS != 0) {
      while ($row = $Database->Fetch_Array($RESULT)) {
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
        /*$girldata["newface"][$row["id"]]     = $row["newface"];
		$girldata["gravure"][$row["id"]]     = $row["gravure"];
		$girldata["in_blog"][$row["id"]]     = $row["in_blog"];
		$girldata["out_blog"][$row["id"]]    = $row["out_blog"];
		$girldata["teiban"][$row["id"]]      = $row["teiban"];
		$girldata["status"][$row["id"]]      = $row["status"];
		$girldata["grade"][$row["id"]]       = $row["grade"];
		$girldata["last_update"][$row["id"]] = $row["last_update"];
		$girldata["last_uptype"][$row["id"]] = $row["last_uptype"]; //1:img,2:syame,3:news,4:realtime:*/
        $girldata["next_photo_update"][$row["id"]] = $row["next_photo_update"];
        $girldata["type_toku"][$row["id"]]       = $row["type_toku"];
      }
    }
    if ($sort == 11) {
      shuffle($girldata["id"]);
    }



    /*
* 画像情報を取得 // horw/1:h,2:w
*/
    $imagedata = array();
    $QUERY  = "SELECT `type`, girls_id, filename FROM girls_images";
    $QUERY .= " WHERE club_id = '" . CLUBID . "'";
    $QUERY .= " AND (type = '1' || type = '2')"; // アイコン画像と横長画像の両方を取得
    $QUERY .= " AND status = 1";
    $QUERY .= " ORDER BY sort, id DESC";
    $RESULT = $Database->Query($QUERY);
    $ROWS = $Database->Num_Rows($RESULT);
    if ($ROWS != 0) {
      while ($row = $Database->Fetch_Array($RESULT)) {
        $imagedata["filename"][$row["girls_id"]][$row["type"]][] = $row["filename"];
        
      }
    } else {
      
    }






    /*
* 出勤数
*/
    /*$y = date('Y');
$m = date('n');
$d = date('j');
$schedule_count = array();
$schedule_count_all = 0;
$QUERY  = "SELECT club_id, count(*) AS cnt FROM girls_schedule";
//$QUERY .= " WHERE (club_id = '2' || club_id = '3' || club_id = '4')";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND (type = '1' || type = '6' || type = '0')";
$QUERY .= " AND year = '" . $y . "'";
$QUERY .= " AND month = '" . $m . "'";
$QUERY .= " AND day = '" . $d . "'";
//$QUERY .= " AND type2 >= '0'";
$QUERY .= " AND views >= '0'";
$QUERY .= " AND status = '1'";
//$QUERY .= " ORDER BY aki_ji, aki_fun, open_ji, open_fun, id DESC";//待ち時間順にソート
//$QUERY .= " ORDER BY open_ji, open_fun, end_ji, end_fun, id DESC";//出勤時間順にソート
$QUERY .= " GROUP BY club_id";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$schedule_count[$row["club_id"]] = $row["cnt"];
		$schedule_count_all += $row["cnt"];
	}
}*/


    //--  NOW  --//
    /*
* 日付情報生成
*/
    $weekarr1 = array(0 => 'SUN', 1 => 'MON', 2 => 'TUE', 3 => 'WED', 4 => 'THU', 5 => 'FRI', 6 => 'SAT');
    $weekarr = array(0 => '日', 1 => '月', 2 => '火', 3 => '水', 4 => '木', 5 => '金', 6 => '土');
    //
    if (date('G') < NEWDAY_TIME) {
      $last_ymd = date("Y-n-j", getDay(-1));
      list($yy, $mm, $dd) = explode('-', $last_ymd);
      $ww = date("w", getDay(-1));
    } else {
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
    while ($i < 8) {
      if (checkdate($mm, $dd, $yy)) {
        $yyy[] = $yy;
        $mmm[] = $mm;
        $ddd[] = $dd;
        $dd++;
        $i++;
      } else {
        $dd = 1;
        $mm++;
        if (checkdate($mm, $dd, $yy)) {
          $yyy[] = $yy;
          $mmm[] = $mm;
          $ddd[] = $dd;
          $dd++;
          $i++;
        } else {
          $mm = 1;
          $yy++;
          if (checkdate($mm, $dd, $yy)) {
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
      if ($ww > 6) {
        $ww = 0;
      }
    }

    //if($work == ""){$work = 1;} //空白時強制

    //検索用日付設定
    if ($work != "") {
      $y = $yyy[$work];
      $m = $mmm[$work];
      $d = $ddd[$work];
      $now = 0;
    } else {
      //切替時間確認
      if (date('G') < NEWDAY_TIME) {
        $last_ymd = date("Y-n-j", getDay(-1));
        list($y, $m, $d) = explode('-', $last_ymd);
        $now = intval(date('Gi')) + 2400;
      } else {
        $y = date('Y');
        $m = date('n');
        $d = date('j');
        $now = intval(date('Gi'));
      }
    }

    //表示用
    $workday = array();
    $workday[0] = $mmm[0] . '/' . $ddd[0]; // . '(' . $weekarr1[$www[0]] . ')';
    $workday[1] = $mmm[1] . '/' . $ddd[1]; // . '(' . $weekarr1[$www[1]] . ')';
    $workday[2] = $mmm[2] . '/' . $ddd[2]; // . '(' . $weekarr1[$www[2]] . ')';
    $workday[3] = $mmm[3] . '/' . $ddd[3]; // . '(' . $weekarr1[$www[3]] . ')';
    $workday[4] = $mmm[4] . '/' . $ddd[4]; // . '(' . $weekarr1[$www[4]] . ')';
    $workday[5] = $mmm[5] . '/' . $ddd[5]; // . '(' . $weekarr1[$www[5]] . ')';
    $workday[6] = $mmm[6] . '/' . $ddd[6]; // . '(' . $weekarr1[$www[6]] . ')';


    /*
* 出勤情報取得
*/
    //$y = date('Y');
    //$m = date('n');
    //$d = date('j');
    //$now = intval(date('Gi'));
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
    $QUERY  = "SELECT * FROM girls_schedule";
    $QUERY .= " WHERE club_id = '" . CLUBID . "'";
    $QUERY .= " AND (type = '1' || type = '6' || type = '0')";
    //$QUERY .= " AND year = '" . $y . "'";
    //$QUERY .= " AND month = '" . $m . "'";
    //$QUERY .= " AND day = '" . $d . "'";

    //1week全選択
    $QUERY .= " AND ( ";
    $QUERY .= "(year = '" . $yyy[0] . "' AND month = '" . $mmm[0] . "' AND day = '" . $ddd[0] . "')";
    $QUERY .= " || (year = '" . $yyy[1] . "' AND month = '" . $mmm[1] . "' AND day = '" . $ddd[1] . "')";
    //$QUERY .= " || (year = '" . $yyy[2] . "' AND month = '" . $mmm[2] . "' AND day = '" . $ddd[2] . "')";
    //$QUERY .= " || (year = '" . $yyy[3] . "' AND month = '" . $mmm[3] . "' AND day = '" . $ddd[3] . "')";
    //$QUERY .= " || (year = '" . $yyy[4] . "' AND month = '" . $mmm[4] . "' AND day = '" . $ddd[4] . "')";
    //$QUERY .= " || (year = '" . $yyy[5] . "' AND month = '" . $mmm[5] . "' AND day = '" . $ddd[5] . "')";
    //$QUERY .= " || (year = '" . $yyy[6] . "' AND month = '" . $mmm[6] . "' AND day = '" . $ddd[6] . "')";
    $QUERY .= " ) ";

    //$QUERY .= " AND type2 >= '0'";
    $QUERY .= " AND views >= '0'";
    $QUERY .= " AND status = '1'";
    //$QUERY .= " ORDER BY aki_ji, aki_fun, open_ji, open_fun, id DESC";//待ち時間順にソート
    //$QUERY .= " ORDER BY aki_ji, aki_fun, open_ji, open_fun, end_ji, end_fun, id DESC";//待ち時間順にソート
    //$QUERY .= " ORDER BY open_ji, open_fun, end_ji, end_fun, id DESC";//時間順にソート
    $QUERY .= " ORDER BY year, month, day, open_ji, open_fun, end_ji, end_fun, id DESC"; //日付、時間順にソート
    $RESULT = $Database->Query($QUERY);
    $ROWS = $Database->Num_Rows($RESULT);
    if ($ROWS != 0) {
      while ($row = $Database->Fetch_Array($RESULT)) {

        if ($row["year"] == $yyy[0] && $row["month"] == $mmm[0] && $row["day"] == $ddd[0]) {
          $weekid = 0;
        } elseif ($row["year"] == $yyy[1] && $row["month"] == $mmm[1] && $row["day"] == $ddd[1]) {
          $weekid = 1;
        } elseif ($row["year"] == $yyy[2] && $row["month"] == $mmm[2] && $row["day"] == $ddd[2]) {
          $weekid = 2;
        } elseif ($row["year"] == $yyy[3] && $row["month"] == $mmm[3] && $row["day"] == $ddd[3]) {
          $weekid = 3;
        } elseif ($row["year"] == $yyy[4] && $row["month"] == $mmm[4] && $row["day"] == $ddd[4]) {
          $weekid = 4;
        } elseif ($row["year"] == $yyy[5] && $row["month"] == $mmm[5] && $row["day"] == $ddd[5]) {
          $weekid = 5;
        } elseif ($row["year"] == $yyy[6] && $row["month"] == $mmm[6] && $row["day"] == $ddd[6]) {
          $weekid = 6;
        } else {
          $weekid = 9;
        }

        if ($row["end_fun"] < 10) {
          $endtime = intval($row["end_ji"] . "0" . $row["end_fun"]);
        } else {
          $endtime = intval($row["end_ji"] . $row["end_fun"]);
        }

        if ($row["type"] == 0 || $row["type"] == 1 || $row["type"] == 6) {

          //if($work > 0){} //翌日以降は全て出勤予定に
          if ($weekid > 0) { //翌日以降は全て出勤予定に

            if ($row["open_ji"] == 100) { //日の出
              $karicast11[$weekid][] = $row["girls_id"];
            } else {
              $karicast12[$weekid][] = $row["girls_id"];
            }

            //
            $scheduledata[$weekid]["type2"][$row["girls_id"]]    = $row["type2"];
            $scheduledata[$weekid]["view"]["type2"][$row["girls_id"]] = $row["type2"];
          } else {

            //if(($row["type"] == 6) || ($row["type2"] == 3) || ($endtime < $now) || (2330 <= $now)){} //受付終了or受付終了or受付時間＜現在時刻or23:30以降
            if (($row["type"] == 6) || ($row["type2"] == 3) || ($endtime < $now)) { //受付終了or受付終了or受付時間＜現在時刻or23:30以降

              if ($row["open_ji"] == 100) { //日の出
                $karicast21[$weekid][] = $row["girls_id"];
              } else {
                $karicast22[$weekid][] = $row["girls_id"];
              }
            } elseif ($row["type"] == 0 || $row["type"] == 1) { //出勤予定or出勤済

              if ($row["open_ji"] == 100) { //日の出
                $karicast11[$weekid][] = $row["girls_id"];
              } else {
                $karicast12[$weekid][] = $row["girls_id"];
              }
            }

            //終了時刻経過
            if ($endtime < $now) {
              $scheduledata[$weekid]["type2"][$row["girls_id"]]    = 3;
              $scheduledata[$weekid]["view"]["type2"][$row["girls_id"]] = 3;
            } else {
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
        //$scheduledata[$weekid]["type2"][$row["girls_id"]]    = $row["type2"];
        $scheduledata[$weekid]["aki_ji"][$row["girls_id"]]   = $row["aki_ji"];
        $scheduledata[$weekid]["aki_fun"][$row["girls_id"]]  = $row["aki_fun"];
        $scheduledata[$weekid]["plusview"][$row["girls_id"]]  = $row["plusview"];

        //表示テキスト生成
        if ($row["type"] == 0 || $row["type"] == 1 || $row["type"] == 6) {

          //開始時間
          if ($row["open_ji"] == 100) {
            $vtime = '日の出';
          } else {
            if ($row["open_fun"] == 0) {
              $ofun = "00";
            } else {
              $ofun = $row["open_fun"];
            }
            $vtime = $row["open_ji"] . ":" . $ofun;
          }

          //$vtime = $row["open_ji"] . ":" . $ofun . " - ";
          $vtime .= "~"; //" - ";

          if ($row["end_ji"] == 99) {
            $vtime .= ""; //"LAST";
          } else {
            if ($row["end_fun"] == 0) {
              $efun = "00";
            } else {
              $efun = $row["end_fun"];
            }
            $vtime .= $row["end_ji"] . ":" . $efun;
          }

          $scheduledata[$weekid]["view"]["time"][$row["girls_id"]] = $vtime; //時間
          $scheduledata[$weekid]["view"]["type"][$row["girls_id"]] = $row["type"]; //
          //$scheduledata[$weekid]["view"]["type2"][$row["girls_id"]] = $row["type2"]; //


          //出勤２
          /*if($row["kn2_open_ji"] != ""){
			
			//開始時間
			if($row["kn2_open_ji"] == 100){
				$vtime2 = '日の出';
			}else{
				if($row["kn2_open_fun"] == 0){
					$ofun = "00";
				}else{
					$ofun = $row["kn2_open_fun"];
				}
				$vtime2 = $row["kn2_open_ji"] . ":" . $ofun;
			}
			
			//$vtime = $row["open_ji"] . ":" . $ofun . " - ";
			$vtime2 .= "~";//" - ";
			
			if($row["kn2_end_ji"] == 99){
				$vtime2 .= "LAST";
			}else{
				if($row["kn2_end_fun"] == 0){
					$efun = "00";
				}else{
					$efun = $row["kn2_end_fun"];
				}
				$vtime2 .= $row["kn2_end_ji"] . ":" . $efun;
			}
			
			$scheduledata[$weekid]["view"]["time2"][$row["girls_id"]] = $vtime2; //時間
			
			}else{
			$scheduledata[$weekid]["view"]["time2"][$row["girls_id"]] = ""; //時間
			}*/
        } else {
          $scheduledata[$weekid]["view"]["type"][$row["girls_id"]] = $row["type"]; //出/休
        }
      }
    }/*else{
$scheduledata["girls_id"] = array();
}*/
    //$scheduledata["girls_id"] = array_merge($karicast11, $karicast12); //本日出勤
    //$scheduledata2["girls_id"] = array_merge($karicast21, $karicast22);//受付終了
    //$scheduledata["girls_id"] = array_merge($karicast11, $karicast12, $karicast21, $karicast22); //全部
    /*$i = 0;
while($i < 7){
	$scheduledata2[$i]["girls_id"] = array_merge($karicast21[$i], $karicast22[$i]);//受付終了
$i++;
}
$i = 0;
while($i < 7){
	$scheduledata[$i]["girls_id"] = array_merge($karicast11[$i], $karicast12[$i], $karicast21[$i], $karicast22[$i]); //全部
$i++;
}*/
    //$scheduledata2["girls_id"] = array_merge($karicast21, $karicast22);//受付終了
    //$scheduledata["girls_id"] = array_merge($karicast11, $karicast12, $karicast21, $karicast22); //全部

    $week = array(0 => 'SUN', 1 => 'MON', 2 => 'TUE', 3 => 'WED', 4 => 'THU', 5 => 'FRI', 6 => 'SAT');
    //$today = date("Y.m/d") . '(' .$week[date('w')]. ')';

    //
    $data1['00010611'] = count($girldata["id"]);


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

    /*
* 枠2
*/
    /*$result = preg_match_all('/(<!-- girlsbox_02 -->.*?<!-- \/girlsbox_02 -->)/s', $source, $get_code);
$waku2 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_02 -->', '', $waku2);
$waku_02 = str_replace('<!-- /girlsbox_02 -->', '', $waku);
$source = str_replace($waku2, "", $source);*/

    /*
* 枠3
*/
    /*$result = preg_match_all('/(<!-- girlsbox_03 -->.*?<!-- \/girlsbox_03 -->)/s', $source, $get_code);
$waku3 = $get_code[0][0];
$waku = str_replace('<!-- girlsbox_03 -->', '', $waku3);
$waku_03 = str_replace('<!-- /girlsbox_03 -->', '', $waku);
$source = str_replace($waku3, "", $source);*/

    $waku_html = "";

    $j = 1;
    $i = 0;
    $h = 0;
    //$k = ($page-1)*$views;//表示開始用
    while ($i < count($girldata["id"])) {
      //if(($i >= $k) && ($h < $views)){//表示範囲指定

      $gid = $girldata["id"][$i];

      //枠の初期化
      $waku1 = $waku_01; //


      //img sq (rep01010071eot用)
      
      
      if (isset($imagedata["filename"][$gid][1][0]) && $imagedata["filename"][$gid][1][0] != "") {
        $imgsize = 50;
        $imgfile = $imagedata["filename"][$gid][1][0];
        $imgtype = 'icon';
        //
        $plus = '_' . $imgsize . '_0_0_0';
        $fname = FilenamePlus($imgfile, $plus);
        $img_uri  = CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
        $img_path = UP_DIR . CLUBID . '/' . UP_DIR_ICON . 'thumb/' . $fname;
        if (file_exists($img_path)) {
          $imageres = $img_uri;
        } else {
          $imageres = 'resizeimg.php?club=' . CLUBID . '&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
        }
        $waku1 = str_replace('rep01010071eot', IMG_HOME . $imageres, $waku1);
      } else {
        $imgsize = 50;
        $imgfile = DAMMY_IMG_SQ;
        $imgtype = 'dmy';
        $imageres = './imgHtml/unnamed.jpg';
        $waku1 = str_replace('rep01010071eot', $imageres, $waku1);
      }

      //img w (rep01010070eot用)
      if (isset($imagedata["filename"][$gid][2][0]) && $imagedata["filename"][$gid][2][0] != "") {
        $imgsize = 640;
        $imgfile = $imagedata["filename"][$gid][2][0];
        $imgtype = 'w';
        //
        $plus = '_' . $imgsize . '_0_0_0';
        $fname = FilenamePlus($imgfile, $plus);
        $img_uri  = CLUBID . '/' . UP_DIR_W . 'thumb/' . $fname;
        $img_path = UP_DIR . CLUBID . '/' . UP_DIR_W . 'thumb/' . $fname;
      } else {
        $imgsize = 640;
        $imgfile = DAMMY_IMG_SQ_w;
        $imgtype = 'dmy';
        //
        $plus = '_' . $imgsize . '_0_0_0';
        $fname = FilenamePlus($imgfile, $plus);
        $img_uri  = CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
        $img_path = UP_DIR . CLUBID . '/' . 'dmy/' . 'thumb/' . $fname;
      }
      if (file_exists($img_path)) {
        $imageres = $img_uri;
      } else {
        $imageres = 'resizeimg.php?club=' . CLUBID . '&j=' . $imgfile . '&size=' . $imgsize . '&type=' . $imgtype;
      }
      //
      $waku1 = str_replace('rep01010070eot', IMG_HOME . $imageres, $waku1);
      //
      $waku1 = str_replace('rep03010090eot', 'girls.php?no=' . $girldata["no"][$gid], $waku1); //URI

      $waku1 = str_replace('rep00010320eot', $girldata["name"][$gid], $waku1); //NAME
      $waku1 = str_replace('rep00010321eot', $girldata["age"][$gid], $waku1); //AGE
      $waku1 = str_replace('rep00010322eot', $girldata["height"][$gid], $waku1); //height
      $waku1 = str_replace('rep00010323eot', $girldata["bust"][$gid], $waku1); //B
      $waku1 = str_replace('rep00010324eot', $cup_array[$girldata["cup"][$gid]], $waku1); //cup
      $waku1 = str_replace('rep00010325eot', $girldata["waist"][$gid], $waku1); //W
      $waku1 = str_replace('rep00010326eot', $girldata["hip"][$gid], $waku1); //H
      //$waku1 = str_replace('rep00010331eot', ucwords(strtolower($girldata["name_romaji"][$gid])), $waku1);//romaji
      $waku1 = str_replace('rep00010331eot', strtoupper($girldata["name_romaji"][$gid]), $waku1); //romaji

      //
      /*if($grade_name[$girldata["grade"][$gid]] != ""){
		$waku1 = str_replace('rep00010332eot', $grade_name[$girldata["grade"][$gid]], $waku1);//grade
		}else{
		$waku1 = str_replace('<div class="rank">rep00010332eot</div>', '', $waku1);//grade
		}*/
      /*
		if($scheduledata[$wk]["caption"][$gid] != ""){
		$waku1 = str_replace('rep00010390eot', $scheduledata[$wk]["caption"][$gid], $waku1);//フリーテキスト
		}else{
		$waku1 = str_replace('rep00010390eot', '', $waku1);//フリーテキスト
		}*/
      //$waku1 = str_replace('rep00010329eot', EncodeEucToUtf8($girldata["caption"][$gid]), $waku1);//リード
      //
      /*$kari = EncodeEucToUtf8($girldata["caption"][$gid]);
		if(mb_strlen($kari, 'UTF-8') > 25){ //長ければ省略
		$kari2 = mb_substr($kari, 0, 24, 'UTF-8') . '…';
		}else{
		$kari2 = $kari;
		}
		$waku1 = str_replace('rep00010329eot', $kari2, $waku1);//*/

      /*
		//$waku1 = str_replace('rep00010340eot', $scheduledata[$wk]["view"]["time"][$gid], $waku1);//勤務時間
		//
		if($scheduledata[$wk]["view"]["time2"][$gid] != ""){ //出勤２があれば連結表示
		$work_time = $scheduledata[$wk]["view"]["time"][$gid] . ', ' . $scheduledata[$wk]["view"]["time2"][$gid];
		}else{
		$work_time = $scheduledata[$wk]["view"]["time"][$gid];
		}
		$waku1 = str_replace('rep00010340eot', $work_time, $waku1);//勤務時間

		$waku1 = str_replace('rep01010293eot', $grade_arr2[$girldata["grade"][$gid]], $waku1);//GRADE
		*/
      /*if($funiki2 != ""){
		$waku1 = str_replace('rep00010327eot', $funiki_arr[$funiki2], $waku1);//雰囲気
		}else{
			if($girldata["image1"][$schedate[$j][$i]] != "" && $girldata["image2"][$gid] != ""){ //２つ設定されていればランダムに表示
				$rnd = mt_rand(0, 1);
				if($rnd == 0){
				$waku1 = str_replace('rep00010327eot', $funiki_arr[$girldata["image1"][$gid]], $waku1);//雰囲気
				}else{
				$waku1 = str_replace('rep00010327eot', $funiki_arr[$girldata["image2"][$gid]], $waku1);//雰囲気
				}
			}else{
				$waku1 = str_replace('rep00010327eot', $funiki_arr[$girldata["image1"][$gid]], $waku1);//雰囲気
			}
		}*/
      /*if($type2 != ""){
		$waku1 = str_replace('rep00010328eot', $type_arr[$type2], $waku1);//タイプ
		}else{
			if($girldata["type1"][$gid] != "" && $girldata["type2"][$gid] != ""){
				$rnd = mt_rand(0, 1);
				if($rnd == 0){
				$waku1 = str_replace('rep00010328eot', $type_arr[$girldata["type1"][$gid]], $waku1);//タイプ
				}else{
				$waku1 = str_replace('rep00010328eot', $type_arr[$girldata["type2"][$gid]], $waku1);//タイプ
				}
			}else{
				$waku1 = str_replace('rep00010328eot', $type_arr[$girldata["type1"][$gid]], $waku1);//タイプ
			}
		}*/
      /*if($play != ""){
			$waku1 = str_replace('rep00010327eot', $play_arr[$play], $waku1);//可能プレイ
		}else{
			if($girldata["playok"][$gid] != ""){
				$plays = array();
				$playsb = ltrim($girldata["playok"][$gid] , ',');//左端','削除
				$playsb = rtrim($playsb , ',');//右端','削除
				$plays  = explode(',' , $playsb);
				if(count($plays) > 1){
				$playsmax = count($plays) - 1;
				$rnd = mt_rand(0, count($playsmax));
				}else{
				$rnd = 0;
				}
				$waku1 = str_replace('rep00010327eot', $play_arr[$plays[$rnd]], $waku1);//可能プレイ
			}else{
				$waku1 = str_replace('rep00010327eot', '', $waku1);
			}
		}*/

      //新人判定
      if ($girldata["newface"][$gid] == 1) { //体験

        //コメントイン
        $waku1 = str_replace('<!-- TRIAL', '<!-- TRIAL -->', $waku1); //
        $waku1 = str_replace('/TRIAL -->', '<!-- /TRIAL -->', $waku1); //

      } elseif ($girldata["newface"][$gid] == 2) { //新人

        //コメントイン
        $waku1 = str_replace('<!-- NEWFACE', '<!-- NEWFACE -->', $waku1); //
        $waku1 = str_replace('/NEWFACE -->', '<!-- /NEWFACE -->', $waku1); //

      }


      //type = 1:出勤,6:受付終了(案内終了),0:未出勤(CLOSEDTODAY)
      //type2= 1:受付中,2:キャンセル待ち,3:受付終了,4:ラスト1名,5:接客中(受付中),6:TEL確認
      //schedule
      $sche_type = $scheduledata[0]["view"]["type"][$gid];
      //
      if ($sche_type == "") { //予定なし
        $sche_type = 9; //休み
      } elseif ($sche_type == 6) {
        $sche_type = 8; //終了
      } else {
        $sche_type = 1; //出勤(受付中)
      }
      //
      if ($scheduledata[0]["view"]["type2"][$gid] == 6) { //TEL確認
        $sche_type = 6;
      } elseif ($scheduledata[0]["view"]["type2"][$gid] == 3) { //受付終了
        $sche_type = 8;
      }

      if ($sche_type == 1) { //出勤(受付中)

        //時間帯
        $status = $scheduledata[0]["view"]["time"][$gid];
        $waku1 = str_replace('rep00010354eot', $status, $waku1);
        //
        if ($scheduledata[0]["plusview"][$gid] == 1) { //追加出勤
          $waku1 = str_replace('<!-- STATUS2', '<!-- STATUS2 -->', $waku1); //
          $waku1 = str_replace('/STATUS2 -->', '<!-- /STATUS2 -->', $waku1); //
        } else {
          $waku1 = str_replace('<!-- STATUS1', '<!-- STATUS1 -->', $waku1); //
          $waku1 = str_replace('/STATUS1 -->', '<!-- /STATUS1 -->', $waku1); //
        }
      } elseif ($sche_type == 6) { //TEL確認

        //
        if ($scheduledata[0]["plusview"][$gid] == 1) { //追加出勤
          $waku1 = str_replace('<!-- STATUS4', '<!-- STATUS4-->', $waku1); //
          $waku1 = str_replace('/STATUS4 -->', '<!-- /STATUS4 -->', $waku1); //
        } else {
          $waku1 = str_replace('<!-- STATUS3', '<!-- STATUS3 -->', $waku1); //
          $waku1 = str_replace('/STATUS3 -->', '<!-- /STATUS3 -->', $waku1); //
        }
      } elseif ($sche_type == 8) { //受付終了

        $waku1 = str_replace('<!-- STATUS5', '<!-- STATUS5-->', $waku1); //
        $waku1 = str_replace('/STATUS5 -->', '<!-- /STATUS5 -->', $waku1); //

      } elseif ($sche_type == 9) { //休み

        $waku1 = str_replace('<!-- STATUS6', '<!-- STATUS6-->', $waku1); //
        $waku1 = str_replace('/STATUS6 -->', '<!-- /STATUS6 -->', $waku1); //

      }


      $waku_html .= $waku1;


      $h++;
      //}
      $j++;
      $i++;
    }

    //置換
    $source = str_replace($waku0, $waku_html, $source);
  }
} else {
  print <<<END
<html><body>
HTTP_USER_AGENT Error<br /><br />
ユーザーエージェントが読み込めませんでした。<br />
</body></html>
END;
}
