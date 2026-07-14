<?

/*********************************************************************
 * データセット(BASE)
 * 
 * 2010-06-
 *********************************************************************/

//-----  SESSION設定  -----//
require_once("/home/firststar/public_html/group/control/includefile/setting_session_vv.php"); //SESSION設定ファイル
//ini_set( 'display_errors', 'on' );
//error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

//-----  設定読込  -----//
// テスト環境でも本番環境のデータベースを使用するため、本番環境の設定ファイルを読み込む
require_once("/home/firststar/public_html/group/control/includefile/incfiles_vv.php"); //設定ファイル（本番環境のデータベース接続を使用）
require_once("/home/firststar/public_html/group/candy/includefile/class.hpgcoder2.php"); //変換クラス2
require_once("/home/firststar/public_html/group/candy/includefile/funcs.php");           //関数ファイル


//-----  変数取得・設定  -----//
define("CLUBID", 2);
define("INCLUDE_DIR", '/home/firststar/public_html/group/candy/includefile/');
$trs     = 9;   //

//
$data1 = array();
$data  = array();

//カップ
//$cup_array = array(1=>'A', 2=>'B', 3=>'C', 4=>'D', 5=>'E', 6=>'F', 7=>'G～');
$cup_array = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J');

//待ちステータス
$machistatus = array(1 => '受付中', 2 => 'キャンセル待ち', 3 => '受付終了', 4 => 'ラスト1名');


//-----  処理  -----//

//Databaseクラス作成
$Database = new Database($DSN);

//Emojiクラス作成
//$Emoji = new Emoji($kara);

//設置ディレクトリ確認
$path = $_SERVER['SCRIPT_FILENAME'];

//テンプレートhtml取得
$path = str_replace('/group/candy/', '/group/candy/source/', $path);
$siteTransNum = 11;

//$path = str_replace('/site2/', '/source2/', $path);
// PC/SP版の分岐を無効化（20251201にはpc/sディレクトリがないため）
// if (strpos($path, 'index') === false && 
// strpos($path, 'test.php') === false && 
// strpos($path, 'system.php') === false && 
// strpos($path, 'schedule.php') === false && 
// strpos($path, 'movie.php') === false && 
// strpos($path, 'girls_list.php') === false &&
// strpos($path, 'girls.php') === false &&
// strpos($path, 'mypage.php') === false &&
// strpos($path, 'source/pc/') === false && 
// strpos($path, 'source/s/') === false
// ) {
// 	if (isset($_SERVER['HTTP_USER_AGENT'])) {
// 		$user_agent = $_SERVER['HTTP_USER_AGENT'];
// 		if (preg_match("/(iPhone|iPad|Android|DoCoMo|UP\.Browser|J-PHONE|Vodafone|SoftBank|J-EMULATOR)/i", $user_agent)) {
// 			$path = str_replace('/group/candy/source/', '/group/candy/source/s/', $path);
// 			$siteTransNum = 11;
// 		} else {
// 			$path = str_replace('/group/candy/source/', '/group/candy/source/pc/', $path);
// 			$siteTransNum = 12;
// 		}
// 	}
// }
$path = str_replace('.php', '.html', $path);
$source_file = $path;

//テンプレートファイル確認
if (!file_exists($source_file)) { //無ければエラー表示
	print "ERROR: Template file not found: " . $source_file;
	die;
}

//処理判定用ディレクトリ取得
$hdir = strstr($source_file, 'source/');
$hdir = str_replace('source/', '', $hdir);
//$hdir = strstr($source_file, 'source2/');
//$hdir = str_replace('source2/', '', $hdir);

//モバイル用ディレクトリ取得
$mmm = substr($hdir, 0, 2);



switch ($hdir) {
	case 'create.html':
		include(INCLUDE_DIR . 'dataset_create.php');
		break;

	case 'test.html':
		include(INCLUDE_DIR . 'dataset_test.php');
		break;
	case 'kagoshima-deliveryhealth-area-hirakawacho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-hirakawacho.php');
		break;

	case 'kagoshima-deliveryhealth-area-kawadacho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kawadacho.php');
		break;

	case 'kagoshima-deliveryhealth-area-kawakamicho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kawakamicho.php');
		break;

	case 'kagoshima-deliveryhealth-area-shimizucho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-shimizucho.php');
		break;

	case 'kagoshima-deliveryhealth-area-kamitatsuocho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kamitatsuocho.php');
		break;

	case 'kagoshima-deliveryhealth-area-kamihonmachi.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kamihonmachi.php');
		break;

	case 'kagoshima-deliveryhealth-area-kamifukumotocho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kamifukumotocho.php');
		break;

	case 'kagoshima-deliveryhealth-area-kamitaniguchicho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kamitaniguchicho.php');
		break;

	case 'kagoshima-deliveryhealth-area-komatsubara.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-komatsubara.php');
		break;

	case 'kagoshima-deliveryhealth-area-koyamadacho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-koyamadacho.php');
		break;

	case 'kagoshima-deliveryhealth-area-kasugacho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kasugacho.php');
		break;

	case 'kagoshima-deliveryhealth-area-sanwacho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-sanwacho.php');
		break;

	case 'kagoshima-deliveryhealth-area-sakuragaoka.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-sakuragaoka.php');
		break;

	case 'kagoshima-deliveryhealth-area-sakanoue.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-sakanoue.php');
		break;

	case 'kagoshima-deliveryhealth-area-sakamotocho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-sakamotocho.php');
		break;

	case 'kagoshima-deliveryhealth-area-koraicho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-koraicho.php');
		break;

	case 'kagoshima-deliveryhealth-area-koutokujidai.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-koutokujidai.php');
		break;

	case 'kagoshima-deliveryhealth-area-kotsukicho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kotsukicho.php');
		break;

	case 'kagoshima-deliveryhealth-area-koyo.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-koyo.php');
		break;

	case 'kagoshima-deliveryhealth-area-gofukucho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-gofukucho.php');
		break;

	case 'kagoshima-deliveryhealth-area-gokabeppucho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-gokabeppucho.php');
		break;

	case 'kagoshima-deliveryhealth-area-koriyamacho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-koriyamacho.php');
		break;

	case 'kagoshima-deliveryhealth-area-koriyamadakecho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-koriyamadakecho.php');
		break;

	case 'kagoshima-deliveryhealth-area-korimotocho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-korimotocho.php');
		break;

	case 'kagoshima-deliveryhealth-area-korimoto.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-korimoto.php');
		break;

	case 'kagoshima-deliveryhealth-area-kinseicho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kinseicho.php');
		break;

	case 'kagoshima-deliveryhealth-area-kinkodai.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kinkodai.php');
		break;

	case 'kagoshima-deliveryhealth-area-gionnosucho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-gionnosucho.php');
		break;

	case 'kagoshima-deliveryhealth-area-kibougaokacho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kibougaokacho.php');
		break;

	case 'kagoshima-deliveryhealth-area-kiirecho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kiirecho.php');
		break;

	case 'kagoshima-deliveryhealth-area-shimotatsuocho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-shimotatsuocho.php');
		break;

	case 'kagoshima-deliveryhealth-area-shimofukumotocho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-shimofukumotocho.php');
		break;

	case 'kagoshima-deliveryhealth-area-shimotacho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-shimotacho.php');
		break;

	case 'kagoshima-deliveryhealth-area-shimoarata.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-shimoarata.php');
		break;

	case 'kagoshima-deliveryhealth-area-shimoishikicho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-shimoishikicho.php');
		break;

	case 'kagoshima-deliveryhealth-area-shimoishiki.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-shimoishiki.php');
		break;

	case 'kagoshima-deliveryhealth-shiroutogirl.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-shiroutogirl.php');
		break;

	case 'kagoshima-deliveryhealth-tallbeautygirl.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-tallbeautygirl.php');
		break;

	case 'kagoshima-deliveryhealth-poccharigirl.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-poccharigirl.php');
		break;

	case 'kagoshima-deliveryhealth-glamourgirl.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-glamourgirl.php');
		break;

	case 'kagoshima-deliveryhealth-area-arata.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-arata.php');
		break;

	case 'kagoshima-deliveryhealth-area-nagayoshi.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-nagayoshi.php');
		break;

	case 'kagoshima-deliveryhealth-area-hanaomachi.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-hanaomachi.php');
		break;

	case 'kagoshima-deliveryhealth-area-minayoshicho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-minayoshicho.php');
		break;

	case 'kagoshima-deliveryhealth-area-yoshino.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-yoshino.php');
		break;

	case 'kagoshima-deliveryhealth-area-yoshinocho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-yoshinocho.php');
		break;

	case 'kagoshima-deliveryhealth-area-tamazatodanchi.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-tamazatodanchi.php');
		break;

	case 'kagoshima-deliveryhealth-area-tamazatocho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-tamazatocho.php');
		break;

	case 'kagoshima-deliveryhealth-area-harara.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-harara.php');
		break;

	case 'kagoshima-deliveryhealth-area-hikariyama.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-hikariyama.php');
		break;

	case 'kagoshima-deliveryhealth-area-hiroki.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-hiroki.php');
		break;

	case 'contact.html':
		include(INCLUDE_DIR . 'dataset_contact.php');
		break;

	case 'area.html':
		include(INCLUDE_DIR . 'dataset_area.php');
		break;

	case 'hotel.html':
		include(INCLUDE_DIR . 'dataset_hotel.php');
		break;

	case 'blog.html':
		include(INCLUDE_DIR . 'dataset_blog.php');
		break;

	case 'kagoshima-deliveryhealth-area-kinkocho.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-area-kinkocho.php');
		break;

	case 'kagoshima-deliveryhealth-hotel-villacosta500.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-hotel-villacosta500.php');
		break;

	case 'kagoshima-deliveryhealth-petitegirl.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-petitegirl.php');
		break;

	case 'kagoshima-deliveryhealth-slendergirl.html':
		include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-slendergirl.php');
		break;

	case 'kagodeli_girl_slender.html':
		include(INCLUDE_DIR . 'dataset_kagodeli_girl_slender.php');
		break;

	case 'sample_123.html':
		include(INCLUDE_DIR . 'dataset_sample_123.php');
		break;

	case 'page.html':
		include(INCLUDE_DIR . 'dataset_page.php');
		break;

	case 'testda.html':
		include(INCLUDE_DIR . 'dataset_testda.php');
		break;

	//TOPページ
	case 'index.html':
		SiteTrans($siteTransNum, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_index.php');
		break;

	//メインページ（年齢認証後に表示されるページ）
	case 'main.html':
		SiteTrans($siteTransNum, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_index.php');
		break;

	//キャスト一覧
	case 'girls_list.html':
		SiteTrans($siteTransNum, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_girls_list.php');
		break;

		case 'girls.html':
			// SiteTrans($siteTransNum, CLUBID, $trs, $Database); // 一時的に無効化
			include(INCLUDE_DIR . 'dataset_girls.php');
			break;

	//NEWS
	case 'news.html':
		include(INCLUDE_DIR . 'dataset_news.php');
		break;

	//スケジュール
	case 'schedule.html':
		include(INCLUDE_DIR . 'dataset_schedule.php');
		break;

	//システム
	case 'system.html':
		SiteTrans($siteTransNum, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_system.php');
		break;

	//MOVIE
	case 'movie.html':
		SiteTrans($siteTransNum, CLUBID, $trs, $Database);
		// 統合テンプレート movie2.html を使用
		// $source_file = str_replace('movie.html', 'movie2.html', $source_file);
		include(INCLUDE_DIR . 'dataset_movie.php');
		break;

	//マイページ
	case 'mypage.html':
		SiteTrans($siteTransNum, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_mypage.php');
		break;

	//MOVIE IFRAME TEST
	case 'movie_iframe.html':
		include(INCLUDE_DIR . 'dataset_movie_iframe.php');
		break;

	// PCページ //
	case 'pc/pc_index.html':
		SiteTrans(11, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_pc_index.php');
		break;

	//TOPページTEST
	case 'pc/pc_indexTest.html':
		include(INCLUDE_DIR . 'dataset_pc_index2.php');
		break;

	//キャスト一覧
	case 'pc/girls_list.html':
		SiteTrans(11, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_pc_girls_list.php');
		break;

	//キャスト詳細
	case 'pc/girls.html':
		SiteTrans(11, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_pc_girls.php');
		break;

	//キャスト詳細
	case 'pc/girlsTest.html':
		include(INCLUDE_DIR . 'dataset_pc_girls2.php');
		break;

	//MOVIE
	case 'pc/movie.html':
		include(INCLUDE_DIR . 'dataset_pc_movie.php');
		break;

	//MOVIE TEST
	case 'pc/movieTest.html':
		include(INCLUDE_DIR . 'dataset_pc_movie2.php');
		break;

	//MOVIE IFRAME TEST
	case 'pc/movie_iframe.html':
		include(INCLUDE_DIR . 'dataset_movie_iframe.php');
		break;

	//マイページ
	case 'pc/mypage.html':
		SiteTrans(11, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_pc_mypage.php');
		break;

	//NEWS
	case 'pc/news.html':
		include(INCLUDE_DIR . 'dataset_pc_news.php');
		break;

	//スケジュール
	case 'pc/schedule.html':
		SiteTrans(11, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_pc_schedule.php');
		break;

	//スケジュール
	case 'pc/scheduleTest.html':
		include(INCLUDE_DIR . 'dataset_pc_schedule2.php');
		break;

	//システム
	case 'pc/system.html':
		SiteTrans(11, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_pc_system.php');
		break;


	// スマホページ //
	//TOPページ
	case 's/sp_index.html':
		SiteTrans(12, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_sp_index.php');
		break;

	//TOPページTEST
	case 's/sp_indexTest.html':
		include(INCLUDE_DIR . 'dataset_sp_index2.php');
		break;

	//キャスト一覧
	case 's/girls_list.html':
		SiteTrans(12, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_sp_girls_list.php');
		break;

	//キャスト詳細
	case 's/girls.html':
		SiteTrans(12, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_sp_girls.php');
		break;

	//キャスト詳細
	case 's/girlsTest.html':
		include(INCLUDE_DIR . 'dataset_sp_girls2.php');
		break;

	//MOVIE
	case 's/movie.html':
		include(INCLUDE_DIR . 'dataset_sp_movie.php');
		break;

	//MOVIE
	case 's/movie_iframe.html':
		include(INCLUDE_DIR . 'dataset_movie_iframe.php');
		break;

	//マイページ
	case 's/mypage.html':
		SiteTrans(12, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_sp_mypage.php');
		break;

	//NEWS
	case 's/news.html':
		include(INCLUDE_DIR . 'dataset_sp_news.php');
		break;

	//スケジュール
	case 's/schedule.html':
		SiteTrans(12, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_sp_schedule.php');
		break;

	//スケジュール
	case 's/scheduleTest.html':
		include(INCLUDE_DIR . 'dataset_sp_schedule2.php');
		break;

	//システム
	case 's/system.html':
		SiteTrans(12, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_sp_system.php');
		break;

	//その他一般ページ
	default:
		SiteTrans(12, CLUBID, $trs, $Database);
		include(INCLUDE_DIR . 'dataset_default.php');
		break;
}




/*
* データ代入
*/
$data['code']['00010001'] = $data1['00010001']; //[ 本店] テロップテキスト
$data['code']['00010007'] = $data1['00010007']; //[ 本店] バナー01(兼全ページ反映バナー)のキャプション太字
$data['code']['00010008'] = $data1['00010008']; //[ 本店] バナー02のキャプション太字
$data['code']['00010009'] = $data1['00010009']; //[ 本店] バナー03のキャプション太字
$data['code']['00010010'] = $data1['00010010']; //[ 本店] バナー04のキャプション太字
$data['code']['00010011'] = $data1['00010011']; //[ 本店] バナー05のキャプション太字
$data['code']['00010012'] = $data1['00010012']; //[ 本店] バナー06のキャプション太字
$data['code']['00010013'] = $data1['00010013']; //[ 本店] バナー07のキャプション太字
$data['code']['00010014'] = $data1['00010014']; //[ 本店] バナー08のキャプション太字
$data['code']['00010015'] = $data1['00010015']; //[ 本店] バナー09のキャプション太字
$data['code']['00010016'] = $data1['00010016']; //[ 本店] バナー10のキャプション太字
$data['code']['00010017'] = $data1['00010017']; //[ 本店] バナー11のキャプション太字
$data['code']['00010018'] = $data1['00010018']; //[ 本店] バナー12のキャプション太字
$data['code']['00010019'] = $data1['00010019']; //[ 本店] バナー13のキャプション太字
$data['code']['00010020'] = $data1['00010020']; //[ 本店] バナー14のキャプション太字
$data['code']['00010021'] = $data1['00010021']; //[ 本店] バナー15のキャプション太字
$data['code']['00010022'] = $data1['00010022']; //[ 本店] バナー16のキャプション太字
$data['code']['00010023'] = $data1['00010023']; //[ 本店] バナー17のキャプション太字
$data['code']['00010024'] = $data1['00010024']; //[ 本店] バナー18のキャプション太字
$data['code']['00010025'] = $data1['00010025']; //[ 本店] バナー19のキャプション太字
$data['code']['00010026'] = $data1['00010026']; //[ 本店] バナー20のキャプション太字
$data['code']['00010027'] = $data1['00010027']; //[ 本店] バナー21のキャプション太字
$data['code']['00010107'] = $data1['00010107']; //[ 本店] バナー01(兼全ページ反映バナー)のキャプション
$data['code']['00010108'] = $data1['00010108']; //[ 本店] バナー02のキャプション
$data['code']['00010109'] = $data1['00010109']; //[ 本店] バナー03のキャプション
$data['code']['00010110'] = $data1['00010110']; //[ 本店] バナー04のキャプション
$data['code']['00010111'] = $data1['00010111']; //[ 本店] バナー05のキャプション
$data['code']['00010112'] = $data1['00010112']; //[ 本店] バナー06のキャプション
$data['code']['00010113'] = $data1['00010113']; //[ 本店] バナー07のキャプション
$data['code']['00010114'] = $data1['00010114']; //[ 本店] バナー08のキャプション
$data['code']['00010115'] = $data1['00010115']; //[ 本店] バナー09のキャプション
$data['code']['00010116'] = $data1['00010116']; //[ 本店] バナー10のキャプション
$data['code']['00010117'] = $data1['00010117']; //[ 本店] バナー11のキャプション
$data['code']['00010118'] = $data1['00010118']; //[ 本店] バナー12のキャプション
$data['code']['00010119'] = $data1['00010119']; //[ 本店] バナー13のキャプション
$data['code']['00010120'] = $data1['00010120']; //[ 本店] バナー14のキャプション
$data['code']['00010121'] = $data1['00010121']; //[ 本店] バナー15のキャプション
$data['code']['00010122'] = $data1['00010122']; //[ 本店] バナー16のキャプション
$data['code']['00010123'] = $data1['00010123']; //[ 本店] バナー17のキャプション
$data['code']['00010124'] = $data1['00010124']; //[ 本店] バナー18のキャプション
$data['code']['00010125'] = $data1['00010125']; //[ 本店] バナー19のキャプション
$data['code']['00010126'] = $data1['00010126']; //[ 本店] バナー20のキャプション
$data['code']['00010127'] = $data1['00010127']; //[ 本店] バナー21のキャプション
$data['code']['00010250'] = $data1['00010250']; //[ 本店] バナー区切り1
$data['code']['00010251'] = $data1['00010251']; //[ 本店] バナー区切り2
$data['code']['00010252'] = $data1['00010252']; //[ 本店] バナー区切り3
$data['code']['00010253'] = $data1['00010253']; //[ 本店] バナー区切り4
$data['code']['00010254'] = $data1['00010254']; //[ 本店] バナー区切り5
$data['code']['00010255'] = $data1['00010255']; //[ 本店] バナー区切り6
$data['code']['00010260'] = $data1['00010260']; //[ 本店] リアルタイム予約状況アイコン名前
$data['code']['00010261'] = $data1['00010261']; //[ 本店] リアルタイム予約状況アイコン3サイズ
$data['code']['00010265'] = $data1['00010265']; //[ 本店] ニュース/新着 1行目ヘッドライン
$data['code']['00010266'] = $data1['00010266']; //[ 本店] ニュース/新着2行目ヘッドライン
$data['code']['00010267'] = $data1['00010267']; //[ 本店] ニュース/新着3行目ヘッドライン
$data['code']['00010268'] = $data1['00010268']; //[ 本店] ニュース/新着4行目ヘッドライン
$data['code']['00010269'] = $data1['00010269']; //[ 本店] ニュース/新着テキスト
$data['code']['00010270'] = $data1['00010270']; //[ 本店] イベント情報タイトル1
$data['code']['00010271'] = $data1['00010271']; //[ 本店] イベント情報タイトル2
$data['code']['00010272'] = $data1['00010272']; //[ 本店] イベント情報タイトル3
$data['code']['00010273'] = $data1['00010273']; //[ 本店] イベント情報タイトル4
$data['code']['00010280'] = $data1['00010280']; //[ 本店] 個人イベント情報氏名1
$data['code']['00010281'] = $data1['00010281']; //[ 本店] 個人イベント情報氏名2
$data['code']['00010282'] = $data1['00010282']; //[ 本店] 個人イベント情報氏名3
$data['code']['00010283'] = $data1['00010283']; //[ 本店] 個人イベント情報氏名4
$data['code']['00010285'] = $data1['00010285']; //[ 本店] 新人入店速報日時名前1
$data['code']['00010286'] = $data1['00010286']; //[ 本店] 新人入店速報日時名前2
$data['code']['00010287'] = $data1['00010287']; //[ 本店] 新人入店速報日時名前3
$data['code']['00010288'] = $data1['00010288']; //[ 本店] 新人入店速報日時名前4
$data['code']['00010289'] = $data1['00010289']; //[ 本店] 新人入店速報テキスト
$data['code']['00010290'] = $data1['00010290']; //[ 本店] 写メ日記最新書き込み名前+本文数文字
$data['code']['00010295'] = $data1['00010295']; //[ 本店] 新着フォトグラフィー名前
$data['code']['00010296'] = $data1['00010296']; //[ 本店] 新着フォトグラフィー名前2
$data['code']['00010297'] = $data1['00010297']; //[ 本店] 新着フォトグラフィー名前3
$data['code']['00010300'] = $data1['00010300']; //[ 本店] 新着グラビア名前
$data['code']['00010305'] = $data1['00010305']; //[ 本店] スペシャルムービー更新日+名前1
$data['code']['00010306'] = $data1['00010306']; //[ 本店] スペシャルムービー更新日+名前2
$data['code']['00010307'] = $data1['00010307']; //[ 本店] スペシャルムービー更新日+名前3
$data['code']['00010308'] = $data1['00010308']; //[ 本店] ランキングの集計期間(ex2010年6月27日～2010年7月3日)
$data['code']['00010309'] = $data1['00010309']; //[ 本店] ランキングのタイトル 
$data['code']['00010310'] = $data1['00010310']; //[ 本店] ランキング1位名前
$data['code']['00010311'] = $data1['00010311']; //[ 本店] ランキング2位名前
$data['code']['00010312'] = $data1['00010312']; //[ 本店] ランキング3位名前
$data['code']['00010313'] = $data1['00010313']; //[ 本店] ランキング4位名前
$data['code']['00010314'] = $data1['00010314']; //[ 本店] ランキング5位名前
$data['code']['00010315'] = $data1['00010315']; //[ 本店] ランキング6位名前
$data['code']['00010316'] = $data1['00010316']; //[ 本店] ランキング7位名前
$data['code']['00010317'] = $data1['00010317']; //[ 本店] ランキング8位名前
$data['code']['00010318'] = $data1['00010318']; //[ 本店] ランキング9位名前
$data['code']['00010319'] = $data1['00010319']; //[ 本店] ランキング10位名前
$data['code']['00010320'] = $data1['00010320']; //[ 本店] 女の子の名前(日本語)
$data['code']['00010321'] = $data1['00010321']; //[ 本店] 女の子の年齢
$data['code']['00010322'] = $data1['00010322']; //[ 本店] 身長
$data['code']['00010323'] = $data1['00010323']; //[ 本店] バスト
$data['code']['00010324'] = $data1['00010324']; //[ 本店] カップ
$data['code']['00010325'] = $data1['00010325']; //[ 本店] ウエスト
$data['code']['00010326'] = $data1['00010326']; //[ 本店] ヒップ
$data['code']['00010327'] = $data1['00010327']; //[ 本店] 雰囲気
$data['code']['00010328'] = $data1['00010328']; //[ 本店] タイプ
$data['code']['00010329'] = $data1['00010329']; //[ 本店] 女の子のリード
$data['code']['00010330'] = $data1['00010330']; //[ 本店] 女の子のボディコピー
$data['code']['00010331'] = $data1['00010331']; //[ 本店] 名字ローマ字大文字
$data['code']['00010332'] = $data1['00010332']; //[ 本店] 名前ローマ字大文字
$data['code']['00010333'] = $data1['00010333']; //[ 本店] 名字ローマ字先頭だけ大文字
$data['code']['00010334'] = $data1['00010334']; //[ 本店] 名前ローマ字先頭だけ大文字
$data['code']['00010340'] = $data1['00010340']; //[ 本店] 女の子の出勤時間帯(ex. 17:00～LAST)
$data['code']['00010341'] = $data1['00010341']; //[ 本店] 女の子の週間出勤予定(1日目・当日)
$data['code']['00010342'] = $data1['00010342']; //[ 本店] 女の子の週間出勤予定(2日目)
$data['code']['00010343'] = $data1['00010343']; //[ 本店] 女の子の週間出勤予定(3日目)
$data['code']['00010344'] = $data1['00010344']; //[ 本店] 女の子の週間出勤予定(4日目)
$data['code']['00010345'] = $data1['00010345']; //[ 本店] 女の子の週間出勤予定(5日目)
$data['code']['00010346'] = $data1['00010346']; //[ 本店] 女の子の週間出勤予定(6日目)
$data['code']['00010347'] = $data1['00010347']; //[ 本店] 女の子の週間出勤予定(7日目)
$data['code']['00010348'] = $data1['00010348']; //[ 本店] この子のリアルタイム予約状況
$data['code']['00010349'] = $data1['00010349']; //[ 本店] この子の指名料
$data['code']['00010350'] = $data1['00010350']; //[ 本店] Q&Aテキスト
$data['code']['00010351'] = $data1['00010351']; //[ 本店] 入店日(9999年99月99日)
$data['code']['00010352'] = $data1['00010352']; //[ 本店] 写真更新日(9999年99月99日)
$data['code']['00010353'] = $data1['00010353']; //[ 本店] この子が3P可能かどうかを表すテキスト「対応可」「未対応」
$data['code']['00010354'] = $data1['00010354']; //[ 本店] 待ち時間(ex30分待ち・ご案内可能)
$data['code']['00010355'] = $data1['00010355']; //[ 本店] 女の子の可能プレイ
$data['code']['00010360'] = $data1['00010360']; //[ 本店] 割引情報ページタイトル
$data['code']['00010361'] = $data1['00010361']; //[ 本店] 割引情報ページ本文
$data['code']['00010380'] = $data1['00010380']; //[ 本店] トップバナーのイベントタイトルリスト
$data['code']['00010381'] = $data1['00010381']; //[ 本店] トップバナーのイベントタイトルリスト2
$data['code']['00010382'] = $data1['00010382']; //[ 本店] トップバナーのイベントタイトルリスト3
$data['code']['00010383'] = $data1['00010383']; //[ 本店] トップバナーのイベントタイトルリスト4
$data['code']['00010384'] = $data1['00010384']; //[ 本店] トップバナーのイベントタイトルリスト5
$data['code']['00010385'] = $data1['00010385']; //[ 本店] イベントバナーアーカイブスイベント名リスト
$data['code']['00010386'] = $data1['00010386']; //[ 本店] イベントバナーアーカイブス制作データリスト
$data['code']['00010387'] = $data1['00010387']; //[ 本店] イベントバナーを持ち、表示可能な女の子の名前リスト
$data['code']['00010390'] = $data1['00010390']; //[ 本店] リアルタイム予約用コメント
$data['code']['00010391'] = $data1['00010391']; //[ 本店] ポップアップ画像表示画面テキストリード
$data['code']['00010392'] = $data1['00010392']; //[ 本店] ポップアップ画像表示画面テキストボディ
$data['code']['00010400'] = $data1['00010400']; //[ 本店] ニュース新着 更新日時(ex 2010年4月22日　20:55))
$data['code']['00010401'] = $data1['00010401']; //[ 本店] ニュース新着 タイトル
$data['code']['00010402'] = $data1['00010402']; //[ 本店] ニュース新着 本文
$data['code']['00010500'] = $data1['00010500']; //[ 本店] トップページナビゲーション内のバナーコメント1
$data['code']['00010501'] = $data1['00010501']; //[ 本店] トップページナビゲーション内のバナーコメント2
$data['code']['00010502'] = $data1['00010502']; //[ 本店] プレイリストによるコメントリスト
$data['code']['00010503'] = $data1['00010503']; //[ 本店] キャストイベントPRのコメントリスト
$data['code']['00010504'] = $data1['00010504']; //[ 本店] ショップイベントPRのコメントリスト
$data['code']['00010505'] = $data1['00010505']; //[ 本店] プレイリストによるタイトルリスト
$data['code']['00010506'] = $data1['00010506']; //[ 本店] キャストイベントPRのタイトルリスト
$data['code']['00010507'] = $data1['00010507']; //[ 本店] ショップイベントPRのタイトルリスト
$data['code']['00010510'] = $data1['00010510']; //[ 本店] 新着1行目ヘッドライン日時(ex 10/06/06
$data['code']['00010511'] = $data1['00010511']; //[ 本店] 新着2行目ヘッドライン日時(ex 10/06/06
$data['code']['00010512'] = $data1['00010512']; //[ 本店] 新着3行目ヘッドライン日時(ex 10/06/06
$data['code']['00010513'] = $data1['00010513']; //[ 本店] 新着4行目ヘッドライン日時(ex 10/06/06
$data['code']['00010520'] = $data1['00010520']; //[ 本店] キャンペーン情報ヘッドライン1
$data['code']['00010521'] = $data1['00010521']; //[ 本店] キャンペーン情報ヘッドライン2
$data['code']['00010522'] = $data1['00010522']; //[ 本店] キャンペーン情報ヘッドライン3
$data['code']['00010523'] = $data1['00010523']; //[ 本店] キャンペーン情報ヘッドライン4
$data['code']['00010530'] = $data1['00010530']; //[ 本店] ランキング1位名前(ローマ字)
$data['code']['00010531'] = $data1['00010531']; //[ 本店] ランキング2位名前(ローマ字)
$data['code']['00010532'] = $data1['00010532']; //[ 本店] ランキング3位名前(ローマ字)
$data['code']['00010533'] = $data1['00010533']; //[ 本店] ランキング4位名前(ローマ字)
$data['code']['00010534'] = $data1['00010534']; //[ 本店] ランキング5位名前(ローマ字)
$data['code']['00010535'] = $data1['00010535']; //[ 本店] ランキング6位名前(ローマ字)
$data['code']['00010536'] = $data1['00010536']; //[ 本店] ランキング7位名前(ローマ字)
$data['code']['00010537'] = $data1['00010537']; //[ 本店] ランキング8位名前(ローマ字)
$data['code']['00010538'] = $data1['00010538']; //[ 本店] ランキング9位名前(ローマ字)
$data['code']['00010539'] = $data1['00010539']; //[ 本店] ランキング10 位名前(ローマ字)
$data['code']['00010540'] = $data1['00010540']; //[ 本店] ランキング1位年齢
$data['code']['00010541'] = $data1['00010541']; //[ 本店] ランキング2位年齢
$data['code']['00010542'] = $data1['00010542']; //[ 本店] ランキング3位年齢
$data['code']['00010543'] = $data1['00010543']; //[ 本店] ランキング4位年齢
$data['code']['00010544'] = $data1['00010544']; //[ 本店] ランキング5位年齢
$data['code']['00010545'] = $data1['00010545']; //[ 本店] ランキング6位年齢
$data['code']['00010546'] = $data1['00010546']; //[ 本店] ランキング7位年齢
$data['code']['00010547'] = $data1['00010547']; //[ 本店] ランキング8位年齢
$data['code']['00010548'] = $data1['00010548']; //[ 本店] ランキング9位年齢
$data['code']['00010549'] = $data1['00010549']; //[ 本店] ランキング10位年齢
$data['code']['00010550'] = $data1['00010550']; //[ 本店] ランキング1位カップ
$data['code']['00010551'] = $data1['00010551']; //[ 本店] ランキング2位カップ
$data['code']['00010552'] = $data1['00010552']; //[ 本店] ランキング3位カップ
$data['code']['00010553'] = $data1['00010553']; //[ 本店] ランキング4位カップ
$data['code']['00010554'] = $data1['00010554']; //[ 本店] ランキング5位カップ
$data['code']['00010555'] = $data1['00010555']; //[ 本店] ランキング6位カップ
$data['code']['00010556'] = $data1['00010556']; //[ 本店] ランキング7位カップ
$data['code']['00010557'] = $data1['00010557']; //[ 本店] ランキング8位カップ
$data['code']['00010558'] = $data1['00010558']; //[ 本店] ランキング9位カップ
$data['code']['00010559'] = $data1['00010559']; //[ 本店] ランキング10位カップ
$data['code']['00010560'] = $data1['00010560']; //[ 本店] ランキング1位名字(ローマ字)
$data['code']['00010561'] = $data1['00010561']; //[ 本店] ランキング2位名字(ローマ字)
$data['code']['00010562'] = $data1['00010562']; //[ 本店] ランキング3位名字(ローマ字)
$data['code']['00010563'] = $data1['00010563']; //[ 本店] ランキング4位名字(ローマ字)
$data['code']['00010564'] = $data1['00010564']; //[ 本店] ランキング5位名字(ローマ字)
$data['code']['00010565'] = $data1['00010565']; //[ 本店] ランキング6位名字(ローマ字)
$data['code']['00010566'] = $data1['00010566']; //[ 本店] ランキング7位名字(ローマ字)
$data['code']['00010567'] = $data1['00010567']; //[ 本店] ランキング8位名字(ローマ字)
$data['code']['00010568'] = $data1['00010568']; //[ 本店] ランキング9位名字(ローマ字)
$data['code']['00010569'] = $data1['00010569']; //[ 本店] ランキング10位名字(ローマ字)
$data['code']['00010570'] = $data1['00010570']; //[ 本店] ランキング1位ウエスト
$data['code']['00010571'] = $data1['00010571']; //[ 本店] ランキング2位ウエスト
$data['code']['00010572'] = $data1['00010572']; //[ 本店] ランキング3位ウエスト
$data['code']['00010573'] = $data1['00010573']; //[ 本店] ランキング4位ウエスト
$data['code']['00010574'] = $data1['00010574']; //[ 本店] ランキング5位ウエスト
$data['code']['00010575'] = $data1['00010575']; //[ 本店] ランキング6位ウエスト
$data['code']['00010576'] = $data1['00010576']; //[ 本店] ランキング7位ウエスト
$data['code']['00010577'] = $data1['00010577']; //[ 本店] ランキング8位ウエスト
$data['code']['00010578'] = $data1['00010578']; //[ 本店] ランキング9位ウエスト
$data['code']['00010579'] = $data1['00010579']; //[ 本店] ランキング10位ウエスト
$data['code']['00010580'] = $data1['00010580']; //[ 本店] ランキング1位ヒップ
$data['code']['00010581'] = $data1['00010581']; //[ 本店] ランキング2位ヒップ
$data['code']['00010582'] = $data1['00010582']; //[ 本店] ランキング3位ヒップ
$data['code']['00010583'] = $data1['00010583']; //[ 本店] ランキング4位ヒップ
$data['code']['00010584'] = $data1['00010584']; //[ 本店] ランキング5位ヒップ
$data['code']['00010585'] = $data1['00010585']; //[ 本店] ランキング6位ヒップ
$data['code']['00010586'] = $data1['00010586']; //[ 本店] ランキング7位ヒップ
$data['code']['00010587'] = $data1['00010587']; //[ 本店] ランキング8位ヒップ
$data['code']['00010588'] = $data1['00010588']; //[ 本店] ランキング9位ヒップ
$data['code']['00010589'] = $data1['00010589']; //[ 本店] ランキング10位ヒップ
$data['code']['00010590'] = $data1['00010590']; //[ 本店] ランキング1位バスト
$data['code']['00010591'] = $data1['00010591']; //[ 本店] ランキング2位バスト
$data['code']['00010592'] = $data1['00010592']; //[ 本店] ランキング3位バスト
$data['code']['00010593'] = $data1['00010593']; //[ 本店] ランキング4位バスト
$data['code']['00010594'] = $data1['00010594']; //[ 本店] ランキング5位バスト
$data['code']['00010595'] = $data1['00010595']; //[ 本店] ランキング6位バスト
$data['code']['00010596'] = $data1['00010596']; //[ 本店] ランキング7位バスト
$data['code']['00010597'] = $data1['00010597']; //[ 本店] ランキング8位バスト
$data['code']['00010598'] = $data1['00010598']; //[ 本店] ランキング9位バスト
$data['code']['00010599'] = $data1['00010599']; //[ 本店] ランキング10位バスト
$data['code']['00010600'] = $data1['00010600']; //[ 本店] ランキング1位身長
$data['code']['00010601'] = $data1['00010601']; //[ 本店] ランキング2位身長
$data['code']['00010602'] = $data1['00010602']; //[ 本店] ランキング3位身長
$data['code']['00010603'] = $data1['00010603']; //[ 本店] ランキング4位身長
$data['code']['00010604'] = $data1['00010604']; //[ 本店] ランキング5位身長
$data['code']['00010605'] = $data1['00010605']; //[ 本店] ランキング6位身長
$data['code']['00010606'] = $data1['00010606']; //[ 本店] ランキング7位身長
$data['code']['00010607'] = $data1['00010607']; //[ 本店] ランキング8位身長
$data['code']['00010608'] = $data1['00010608']; //[ 本店] ランキング9位身長
$data['code']['00010609'] = $data1['00010609']; //[ 本店] ランキング10位身長
$data['code']['00010610'] = $data1['00010610']; //[ 本店] ランキング件数
$data['code']['00010611'] = $data1['00010611']; //[ 本店] 出勤予定件数
$data['code']['00010620'] = $data1['00010620']; //[ 本店] ランダムイベントタイトル(優先順位・女の子イベント>ショップイベント>女の子PR)
$data['code']['00010621'] = $data1['00010621']; //[ 本店] 本日売り出し中の女の子名前(日本語)
$data['code']['00010622'] = $data1['00010622']; //[ 本店] 本日売り出し中の女の子の待ち時間
$data['code']['00010623'] = $data1['00010623']; //[ 本店] ランダム新人名前
$data['code']['00010624'] = $data1['00010624']; //[ 本店] ランダム新人リード
$data['code']['00010625'] = $data1['00010625']; //[ 本店] ランダム新着フォト名前
$data['code']['00010626'] = $data1['00010626']; //[ 本店] ランダム新着フォトリード
$data['code']['00010627'] = $data1['00010627']; //[ 本店] 本日売り出し中の女の子のリード
$data['code']['00010630'] = $data1['00010630']; //[ 本店] site/m/enquete/enquete_m.htmlをそのままインクルード
$data['code']['00010640'] = $data1['00010640']; //[ 本店] ブログタイトル
$data['code']['00010641'] = $data1['00010641']; //[ 本店] 日記タイトル
$data['code']['00010642'] = $data1['00010642']; //[ 本店] 日記本文
$data['code']['00010643'] = $data1['00010643']; //[ 本店] 過去記事タイトル
$data['code']['00010651'] = $data1['00010651']; //[ 本店] オフィシャルブログ タイトル
$data['code']['00010652'] = $data1['00010652']; //[ 本店] オフィシャルブログ 本文
$data['code']['00010653'] = $data1['00010653']; //[ 本店] オフィシャルブログ 過去記事タイトル
$data['code']['00010654'] = $data1['00010654']; //[ 本店] オフィシャルブログ 日時テキスト
$data['code']['00010660'] = $data1['00010660']; //[ 本店] ShopPRイベント名
$data['code']['00010661'] = $data1['00010661']; //[ 本店] ShopPRイベントPR文
$data['code']['00010670'] = $data1['00010670']; //[ 本店ル] 写メ日記最新順1のコの名前
$data['code']['00010671'] = $data1['00010671']; //[ 本店] 写メ日記最新順2のコの名前
$data['code']['00010672'] = $data1['00010672']; //[ 本店] 写メ日記最新順3のコの名前
$data['code']['00010680'] = $data1['00010680']; //[ 本店] 写メ日記最新順1の本文10文字程度
$data['code']['00010681'] = $data1['00010681']; //[ 本店] 写メ日記最新順2の本文10文字程度
$data['code']['00010682'] = $data1['00010682']; //[ 本店] 写メ日記最新順3の本文10文字程度
$data['code']['00010690'] = $data1['00010690']; //[ 本店] 一覧用女の子の名前(日本語)
$data['code']['00010691'] = $data1['00010691']; //[ 本店] 一覧用女の子の年齢
$data['code']['00010692'] = $data1['00010692']; //[ 本店] 一覧用女の子の身長
$data['code']['00010693'] = $data1['00010693']; //[ 本店] 一覧用女の子のバスト
$data['code']['00010694'] = $data1['00010694']; //[ 本店] 一覧用女の子のカップ
$data['code']['00010695'] = $data1['00010695']; //[ 本店] 一覧用女の子のウエスト
$data['code']['00010696'] = $data1['00010696']; //[ 本店] 一覧用女の子のヒップ
$data['code']['00010697'] = $data1['00010697']; //[ 本店] 一覧用女の子の雰囲気
$data['code']['00010698'] = $data1['00010698']; //[ 本店] 一覧用女の子のタイプ
$data['code']['00010699'] = $data1['00010699']; //[ 本店] 一覧用女の子の指名料 ex 1,000
$data['code']['00010700'] = $data1['00010700']; //[ 本店] 新着ムービー＆グラビア日時1
$data['code']['00010701'] = $data1['00010701']; //[ 本店] 新着ムービー＆グラビア日時2
$data['code']['00010702'] = $data1['00010702']; //[ 本店] 新着ムービー＆グラビア日時3
$data['code']['00010705'] = $data1['00010705']; //[ 本店] 新着ムービー＆グラビア名前1
$data['code']['00010706'] = $data1['00010706']; //[ 本店] 新着ムービー＆グラビア名前2
$data['code']['00010707'] = $data1['00010707']; //[ 本店] 新着ムービー＆グラビア名前3
$data['code']['00010710'] = $data1['00010710']; //[ 本店] 新着フォトグラフィー日時1
$data['code']['00010711'] = $data1['00010711']; //[ 本店] 新着フォトグラフィー日時2
$data['code']['00010712'] = $data1['00010712']; //[ 本店] 新着フォトグラフィー日時3
$data['code']['00010713'] = $data1['00010713']; //[ 本店] 新着フォトグラフィー日時3
$data['code']['00010720'] = $data1['00010720']; //[ 本店] 新人入店速報バスト1
$data['code']['00010721'] = $data1['00010721']; //[ 本店] 新人入店速報バスト2
$data['code']['00010722'] = $data1['00010722']; //[ 本店] 新人入店速報バスト3
$data['code']['00010725'] = $data1['00010725']; //[ 本店] 新人入店速報カップ1
$data['code']['00010726'] = $data1['00010726']; //[ 本店] 新人入店速報カップ2
$data['code']['00010727'] = $data1['00010727']; //[ 本店] 新人入店速報カップ3
$data['code']['00010730'] = $data1['00010730']; //[ 本店] 新人入店速報バスト1
$data['code']['00010731'] = $data1['00010731']; //[ 本店] 新人入店速報バスト2
$data['code']['00010732'] = $data1['00010732']; //[ 本店] 新人入店速報バスト3
$data['code']['00010735'] = $data1['00010735']; //[ 本店] 新人入店速報カップ1
$data['code']['00010736'] = $data1['00010736']; //[ 本店] 新人入店速報カップ2
$data['code']['00010737'] = $data1['00010737']; //[ 本店] 新人入店速報カップ3
$data['code']['00010740'] = $data1['00010740']; //[ 本店] サブテロップ
$data['code']['00010741'] = $data1['00010741']; //[ 本店] PICKUP PRテキスト
$data['code']['00010750'] = $data1['00010750']; //[ 本店] 定番の子1名前
$data['code']['00010751'] = $data1['00010751']; //[ 本店] 定番の子2名前
$data['code']['00010752'] = $data1['00010752']; //[ 本店] 定番の子3名前
$data['code']['00010760'] = $data1['00010760']; //[ 本店] 定番の子1年齢
$data['code']['00010761'] = $data1['00010761']; //[ 本店] 定番の子2年齢
$data['code']['00010762'] = $data1['00010762']; //[ 本店] 定番の子3年齢
$data['code']['00010770'] = $data1['00010770']; //[ 本店] 新着情報本文1
$data['code']['00010771'] = $data1['00010771']; //[ 本店] 新着情報本文1
$data['code']['00010772'] = $data1['00010772']; //[ 本店] 新着情報本文1
$data['code']['01010001'] = $data1['01010001']; //[ 本店] トップバナーサムネールのファイルリスト
$data['code']['01010002'] = $data1['01010002']; //[ 本店] トップバナーサムネールのファイルリスト2
$data['code']['01010003'] = $data1['01010003']; //[ 本店] トップバナーサムネールのファイルリスト3
$data['code']['01010004'] = $data1['01010004']; //[ 本店] トップバナーサムネールのファイルリスト4
$data['code']['01010005'] = $data1['01010005']; //[ 本店] トップバナーサムネールのファイルリスト5
$data['code']['01010006'] = $data1['01010006']; //[ 本店] トップバナーサムネールのファイルリスト6
$data['code']['01010007'] = $data1['01010007']; //[ 本店] バナー01(兼全ページ反映バナー)の拡張子を含まないファイルネーム
$data['code']['01010008'] = $data1['01010008']; //[ 本店] バナー02の拡張子を含まないファイルネーム
$data['code']['01010009'] = $data1['01010009']; //[ 本店] バナー03の拡張子を含まないファイルネーム
$data['code']['01010010'] = $data1['01010010']; //[ 本店] バナー04の拡張子を含まないファイルネーム
$data['code']['01010011'] = $data1['01010011']; //[ 本店] バナー05の拡張子を含まないファイルネーム
$data['code']['01010012'] = $data1['01010012']; //[ 本店] バナー06の拡張子を含まないファイルネーム
$data['code']['01010013'] = $data1['01010013']; //[ 本店] バナー07の拡張子を含まないファイルネーム
$data['code']['01010014'] = $data1['01010014']; //[ 本店] バナー08の拡張子を含まないファイルネーム
$data['code']['01010015'] = $data1['01010015']; //[ 本店] バナー09の拡張子を含まないファイルネーム
$data['code']['01010016'] = $data1['01010016']; //[ 本店] バナー10の拡張子を含まないファイルネーム
$data['code']['01010017'] = $data1['01010017']; //[ 本店] バナー11の拡張子を含まないファイルネーム
$data['code']['01010018'] = $data1['01010018']; //[ 本店] バナー12の拡張子を含まないファイルネーム
$data['code']['01010019'] = $data1['01010019']; //[ 本店] バナー13の拡張子を含まないファイルネーム
$data['code']['01010020'] = $data1['01010020']; //[ 本店] バナー14の拡張子を含まないファイルネーム
$data['code']['01010021'] = $data1['01010021']; //[ 本店] バナー15の拡張子を含まないファイルネーム
$data['code']['01010022'] = $data1['01010022']; //[ 本店] バナー16の拡張子を含まないファイルネーム
$data['code']['01010023'] = $data1['01010023']; //[ 本店] バナー17の拡張子を含まないファイルネーム
$data['code']['01010024'] = $data1['01010024']; //[ 本店] バナー18の拡張子を含まないファイルネーム
$data['code']['01010025'] = $data1['01010025']; //[ 本店] バナー19の拡張子を含まないファイルネーム
$data['code']['01010026'] = $data1['01010026']; //[ 本店] バナー20の拡張子を含まないファイルネーム
$data['code']['01010027'] = $data1['01010027']; //[ 本店] バナー21の拡張子を含まないファイルネーム
$data['code']['01010030'] = $data1['01010030']; //[ 本店] リアルタイム予約状況アイコン画像拡張子を含まないファイルネーム
$data['code']['01010035'] = $data1['01010035']; //[ 本店] ニュース/新着1行目アイコン画像拡張子を含まないファイルネーム
$data['code']['01010036'] = $data1['01010036']; //[ 本店] ニュース/新着2 行目アイコン画像拡張子を含まないファイルネーム
$data['code']['01010037'] = $data1['01010037']; //[ 本店] ニュース/新着3行目アイコン画像拡張子を含まないファイルネーム
$data['code']['01010040'] = $data1['01010040']; //[ 本店] 写メ日記最新書き込み写メアイコン拡張子を含まないファイルネーム
$data['code']['01010045'] = $data1['01010045']; //[ 本店] 新着フォトグラフィーのアイコン画像拡張子を含まないファイルネーム
$data['code']['01010050'] = $data1['01010050']; //[ 本店] 新着グラビアのアイコン画像拡張子を含まないファイルネーム
$data['code']['01010055'] = $data1['01010055']; //[ 本店] ニュース/新着1行目ヘッドラインのアイコンcss名(soucho:hiru:yoru:sougou)
$data['code']['01010056'] = $data1['01010056']; //[ 本店] ニュース/新着 2行目ヘッドラインのアイコンcss名(soucho:hiru:yoru:sougou)
$data['code']['01010057'] = $data1['01010057']; //[ 本店] ニュース/新着3 行目ヘッドラインのアイコンcss名(soucho:hiru:yoru:sougou)
$data['code']['01010060'] = $data1['01010060']; //[ 本店] 定番の子1バナーの拡張子を含まないファイルネーム
$data['code']['01010061'] = $data1['01010061']; //[ 本店] 定番の子2バナーの拡張子を含まないファイルネーム
$data['code']['01010062'] = $data1['01010062']; //[ 本店] 定番の子3バナーの拡張子を含まないファイルネーム
$data['code']['01010063'] = $data1['01010063']; //[ 本店] 定番の子4バナーの拡張子を含まないファイルネーム
$data['code']['01010064'] = $data1['01010064']; //[ 本店] 定番の子5バナーの拡張子を含まないファイルネーム
$data['code']['01010065'] = $data1['01010065']; //[ 本店] 定番の子6バナーの拡張子を含まないファイルネーム
$data['code']['01010066'] = $data1['01010066']; //[ 本店] 定番の子7バナーの拡張子を含まないファイルネーム
$data['code']['01010067'] = $data1['01010067']; //[ 本店] 定番の子8バナーの拡張子を含まないファイルネーム
$data['code']['01010068'] = $data1['01010068']; //[ 本店] 定番の子9バナーの拡張子を含まないファイルネーム
$data['code']['01010069'] = $data1['01010069']; //[ 本店] 定番の子10バナーの拡張子を含まないファイルネーム
$data['code']['01010070'] = $data1['01010070']; //[ 本店] リアルタイム一覧の写真。拡張子を含まないファイルネーム
$data['code']['01010075'] = $data1['01010075']; //[ 本店] リアルタイム一覧の新人アイコン位置の画像。(該当なしの場合には白)
$data['code']['01010076'] = $data1['01010076']; //[ 本店] リアルタイム一覧・個人ページの個人イベントバナー。(該当なしの場合には白)
$data['code']['01010080'] = $data1['01010080']; //[ 本店] ローマ字表記画像。アルファベット画像を元に合成画像を出力
$data['code']['01010081'] = $data1['01010081']; //[ 本店] 女の子ページの写真1拡張子を含まないファイルネーム(javascriptで拡大するので実寸大)
$data['code']['01010082'] = $data1['01010082']; //[ 本店] 女の子ページの写真2拡張子を含まないファイルネーム(javascriptで拡大するので実寸大)
$data['code']['01010083'] = $data1['01010083']; //[ 本店] 女の子ページの写真3拡張子を含まないファイルネーム (javascriptで拡大するので実寸大)
$data['code']['01010084'] = $data1['01010084']; //[ 本店] 女の子ページの写真4拡張子を含まないファイルネーム(javascriptで拡大するので実寸大)
$data['code']['01010085'] = $data1['01010085']; //[ 本店] 女の子ページの写真5拡張子を含まないファイルネーム(javascriptで拡大するので実寸大)
$data['code']['01010091'] = $data1['01010091']; //[ 本店] 女の子ページの写真1の移り込み画像。拡張子を含まないファイルネーム
$data['code']['01010092'] = $data1['01010092']; //[ 本店] 女の子ページの写真2の移り込み画像。拡張子を含まないファイルネーム
$data['code']['01010093'] = $data1['01010093']; //[ 本店] 女の子ページの写真3の移り込み画像。拡張子を含まないファイルネーム
$data['code']['01010094'] = $data1['01010094']; //[ 本店] 女の子ページの写真4の移り込み画像。拡張子を含まないファイルネーム
$data['code']['01010095'] = $data1['01010095']; //[ 本店] 女の子ページの写真5の移り込み画像。拡張子を含まないファイルネーム
$data['code']['01010100'] = $data1['01010100']; //[ 本店] イベントバナーアーカイブスファイルリスト(正方形バナー)
$data['code']['01010105'] = $data1['01010105']; //[ 本店] 待ち時間を表す画像
$data['code']['01010101'] = $data1['01010101']; //[ 本店] イベントバナーを持ち、表示可能な女の子の正方形写真ファイルリスト
$data['code']['01010110'] = $data1['01010110']; //[ 本店] 正方形カラー写真
$data['code']['01010111'] = $data1['01010111']; //[ 本店] 正方形モノクロ写真
$data['code']['01010112'] = $data1['01010112']; //[ 本店] ビッグサイズバナー
$data['code']['01010113'] = $data1['01010113']; //[ 本店] ビッグサイズバナー映り込み画像
$data['code']['01010114'] = $data1['01010114']; //[ 本店] 正方形バナー
$data['code']['01010115'] = $data1['01010115']; //[ 本店] リアルタイム予約状況・表示上トップの女の子写真
$data['code']['01010120'] = $data1['01010120']; //[ 本店] ニュース新着 ニュースカテゴリアイコン画像のパス名
$data['code']['01010200'] = $data1['01010200']; //[ 本店] トップページナビゲーション内のバナー画像ファイルリスト
$data['code']['01010201'] = $data1['01010201']; //[ 本店] プレイリストによる正方形バナーのファイルリスト
$data['code']['01010202'] = $data1['01010202']; //[ 本店] キャストイベントPRの正方形バナーのファイルリスト
$data['code']['01010203'] = $data1['01010203']; //[ 本店] ショップイベントPRの正方形バナーのファイルリスト
$data['code']['01010221'] = $data1['01010221']; //[ 本店] ランキング画像1
$data['code']['01010222'] = $data1['01010222']; //[ 本店] ランキング画像2
$data['code']['01010223'] = $data1['01010223']; //[ 本店] ランキング画像3
$data['code']['01010224'] = $data1['01010224']; //[ 本店] ランキング画像4
$data['code']['01010225'] = $data1['01010225']; //[ 本店] ランキング画像5
$data['code']['01010226'] = $data1['01010226']; //[ 本店] ランキング画像6
$data['code']['01010227'] = $data1['01010227']; //[ 本店] ランキング画像7
$data['code']['01010228'] = $data1['01010228']; //[ 本店] ランキング画像8
$data['code']['01010229'] = $data1['01010229']; //[ 本店] ランキング画像9
$data['code']['01010230'] = $data1['01010230']; //[ 本店] ランキング画像10
$data['code']['01010240'] = $data1['01010240']; //[ 本店] ニュース新着1の画像
$data['code']['01010250'] = $data1['01010250']; //[ 本店] 個人ページオプションのON/OFF画像1
$data['code']['01010251'] = $data1['01010251']; //[ 本店] 個人ページオプションのON/OFF画像2
$data['code']['01010252'] = $data1['01010252']; //[ 本店] 個人ページオプションのON/OFF画像3
$data['code']['01010253'] = $data1['01010253']; //[ 本店] 個人ページオプションのON/OFF画像4
$data['code']['01010254'] = $data1['01010254']; //[ 本店] 個人ページオプションのON/OFF画像5
$data['code']['01010255'] = $data1['01010255']; //[ 本店] 個人ページオプションのON/OFF画像6
$data['code']['01010256'] = $data1['01010256']; //[ 本店] 個人ページオプションのON/OFF画像7
$data['code']['01010257'] = $data1['01010257']; //[ 本店] 個人ページオプションのON/OFF画像8
$data['code']['01010258'] = $data1['01010258']; //[ 本店] 個人ページオプションのON/OFF画像9
$data['code']['01010259'] = $data1['01010259']; //[ 本店] 個人ページオプションのON/OFF画像10
$data['code']['01010260'] = $data1['01010260']; //[ 本店] 個人ページオプションのON/OFF画像11
$data['code']['01010261'] = $data1['01010261']; //[ 本店] 個人ページオプションのON/OFF画像12
$data['code']['01010270'] = $data1['01010270']; //[ 本店] ランダムイベント画像(優先順位・女の子イベント>ショップイベント>女の子PR)
$data['code']['01010271'] = $data1['01010271']; //[ 本店] 本日売り出し中の女の子画像
$data['code']['01010273'] = $data1['01010273']; //[ 本店] ランダム新人画像
$data['code']['01010274'] = $data1['01010274']; //[ 本店] ランダム新着フォト写真
$data['code']['01010275'] = $data1['01010275']; //[ 本店] イベントかPRかを表すアイコン
$data['code']['01010280'] = $data1['01010280']; //[ 本店] 写メ日記にエントリしてる子の写真
$data['code']['01010290'] = $data1['01010290']; //[ 本店] 写メ日記最新順1のコの正方形画像
$data['code']['01010291'] = $data1['01010291']; //[ 本店] 写メ日記最新順2のコの正方形画像
$data['code']['01010292'] = $data1['01010292']; //[ 本店] 写メ日記最新順3のコの正方形画像
$data['code']['01010293'] = $data1['01010293']; //[ 本店] グレードのアイコン画像
$data['code']['01010294'] = $data1['01010294']; //[ 本店] ニュース新着用画像
$data['code']['01010295'] = $data1['01010295']; //[ 本店] この子のビッグサイズバナー画像
$data['code']['01010296'] = $data1['01010296']; //[ 本店] この子の過去ポップアップ
$data['code']['01010297'] = $data1['01010297']; //[ 本店] この子の過去ポップアップ URl
$data['code']['01010298'] = $data1['01010298']; //[ 本店] 空き予定時刻
$data['code']['01010300'] = $data1['01010300']; //[ 本店] 新人入店速報画像1
$data['code']['01010301'] = $data1['01010301']; //[ 本店] 新人入店速報画像2
$data['code']['01010302'] = $data1['01010302']; //[ 本店] 新人入店速報画像3
$data['code']['01010305'] = $data1['01010305']; //[ 本店] 新着フォトグラフィー画像1
$data['code']['01010306'] = $data1['01010306']; //[ 本店] 新着フォトグラフィー画像2
$data['code']['01010307'] = $data1['01010307']; //[ 本店] 新着フォトグラフィー画像3
$data['code']['01010310'] = $data1['01010310']; //[ 本店] 新着ムービー＆グラビア画像1
$data['code']['01010311'] = $data1['01010311']; //[ 本店] 新着ムービー＆グラビア画像2
$data['code']['01010312'] = $data1['01010312']; //[ 本店] 新着ムービー＆グラビア画像3
$data['code']['01010321'] = $data1['01010321']; //[ 本店] PICKUP PR 画像
$data['code']['01010327'] = $data1['01010327']; //[ 本店] イベントバナー帯アイコン画像1
$data['code']['01010328'] = $data1['01010328']; //[ 本店] イベントバナー帯アイコン画像2
$data['code']['01010329'] = $data1['01010329']; //[ 本店] イベントバナー帯アイコン画像3
$data['code']['01010330'] = $data1['01010330']; //[ 本店] イベントバナー帯アイコン画像4
$data['code']['01010331'] = $data1['01010331']; //[ 本店] イベントバナー帯アイコン画像5
$data['code']['01010332'] = $data1['01010332']; //[ 本店] イベントバナー帯アイコン画像6
$data['code']['01010333'] = $data1['01010333']; //[ 本店] イベントバナー帯アイコン画像7
$data['code']['01010334'] = $data1['01010334']; //[ 本店] イベントバナー帯アイコン画像8
$data['code']['03010007'] = $data1['03010007']; //[ 本店] バナー 01(兼全ページ反映バナー)のリンク先URI
$data['code']['03010008'] = $data1['03010008']; //[ 本店] バナー02のリンク先URI
$data['code']['03010009'] = $data1['03010009']; //[ 本店] バナー03のリンク先URI
$data['code']['03010010'] = $data1['03010010']; //[ 本店] バナー04のリンク先URI
$data['code']['03010011'] = $data1['03010011']; //[ 本店] バナー05のリンク先URI
$data['code']['03010012'] = $data1['03010012']; //[ 本店] バナー06のリンク先URI
$data['code']['03010013'] = $data1['03010013']; //[ 本店] バナー07のリンク先URI
$data['code']['03010014'] = $data1['03010014']; //[ 本店] バナー08のリンク先URI
$data['code']['03010015'] = $data1['03010015']; //[ 本店] バナー09のリンク先URI
$data['code']['03010016'] = $data1['03010016']; //[ 本店] バナー10のリンク先URI
$data['code']['03010017'] = $data1['03010017']; //[ 本店] バナー11のリンク先URI
$data['code']['03010018'] = $data1['03010018']; //[ 本店] バナー12のリンク先URI
$data['code']['03010019'] = $data1['03010019']; //[ 本店] バナー13のリンク先URI
$data['code']['03010020'] = $data1['03010020']; //[ 本店] バナー14のリンク先URI
$data['code']['03010021'] = $data1['03010021']; //[ 本店] バナー15のリンク先URI
$data['code']['03010022'] = $data1['03010022']; //[ 本店] バナー16のリンク先URI
$data['code']['03010023'] = $data1['03010023']; //[ 本店] バナー17のリンク先URI
$data['code']['03010024'] = $data1['03010024']; //[ 本店] バナー18のリンク先URI
$data['code']['03010025'] = $data1['03010025']; //[ 本店] バナー19のリンク先URI
$data['code']['03010026'] = $data1['03010026']; //[ 本店] バナー20のリンク先URI
$data['code']['03010027'] = $data1['03010027']; //[ 本店] バナー21のリンク先URI
$data['code']['03010030'] = $data1['03010030']; //[ 本店] ニュース/新着 1行目全文リンク先URI
$data['code']['03010031'] = $data1['03010031']; //[ 本店] ニュース/新着2行目全文リンク先URI
$data['code']['03010032'] = $data1['03010032']; //[ 本店] ニュース/新着3行目全文リンク先URI
$data['code']['03010035'] = $data1['03010035']; //[ 本店] イベント情報1 リンク先URI
$data['code']['03010036'] = $data1['03010036']; //[ 本店] イベント情報2リンク先URI
$data['code']['03010037'] = $data1['03010037']; //[ 本店] イベント情報3リンク先URI
$data['code']['03010038'] = $data1['03010038']; //[ 本店] イベント情報4 リンク先URI
$data['code']['03010040'] = $data1['03010040']; //[ 本店] 新人入店速報1個人ページへのURI(TOPページ用)
$data['code']['03010041'] = $data1['03010041']; //[ 本店] 新人入店速報2個人ページへのURI(TOPページ用)
$data['code']['03010042'] = $data1['03010042']; //[ 本店] 新人入店速報3個人ページへのURI(TOPページ用)
$data['code']['03010043'] = $data1['03010043']; //[ 本店] 新人入店速報4個人ページへのURI(TOPページ用)
$data['code']['03010050'] = $data1['03010050']; //[ 本店] 個人イベント情報1リンク先URI
$data['code']['03010051'] = $data1['03010051']; //[ 本店] 個人イベント情報2リンク先URI
$data['code']['03010052'] = $data1['03010052']; //[ 本店] 個人イベント情報3リンク先URI
$data['code']['03010053'] = $data1['03010053']; //[ 本店] 個人イベント情報4リンク先URI
$data['code']['03010055'] = $data1['03010055']; //[ 本店] 写メ日記最新書き込みへのリンクURI
$data['code']['03010060'] = $data1['03010060']; //[ 本店] 新着フォトグラフィーの個人ページへのURI
$data['code']['03010061'] = $data1['03010061']; //[ 本店] 新着フォトグラフィーの個人ページへのURI2
$data['code']['03010062'] = $data1['03010062']; //[ 本店] 新着フォトグラフィーの個人ページへのURI3
$data['code']['03010065'] = $data1['03010065']; //[ 本店] 新着グラビアの個人ページへのURI
$data['code']['03010068'] = $data1['03010068']; //[ 本店] 各ランキングページへリンクつきリスト(html)
$data['code']['03010069'] = $data1['03010069']; //[ 本店] このランキングページへのURI
$data['code']['03010070'] = $data1['03010070']; //[ 本店] ランキング1位の個人ページへのURI
$data['code']['03010071'] = $data1['03010071']; //[ 本店] ランキング2位の個人ページへのURI
$data['code']['03010072'] = $data1['03010072']; //[ 本店] ランキング3位の個人ページへのURI
$data['code']['03010073'] = $data1['03010073']; //[ 本店] ランキング4位の個人ページへのURI
$data['code']['03010074'] = $data1['03010074']; //[ 本店] ランキング4位の個人ページへのURI
$data['code']['03010075'] = $data1['03010075']; //[ 本店] スペシャルムービー1へのURI
$data['code']['03010076'] = $data1['03010076']; //[ 本店] スペシャルムービー2へのURI
$data['code']['03010077'] = $data1['03010077']; //[ 本店] スペシャルムービー3へのURI
$data['code']['03010080'] = $data1['03010080']; //[ 本店] 定番の子1個人ページへのURI
$data['code']['03010081'] = $data1['03010081']; //[ 本店] 定番の子2個人ページへのURI
$data['code']['03010082'] = $data1['03010082']; //[ 本店] 定番の子3個人ページへのURI
$data['code']['03010083'] = $data1['03010083']; //[ 本店] 定番の子4個人ページへのURI
$data['code']['03010084'] = $data1['03010084']; //[ 本店] 定番の子5個人ページへのURI
$data['code']['03010085'] = $data1['03010085']; //[ 本店] 定番の子6個人ページへのURI
$data['code']['03010086'] = $data1['03010086']; //[ 本店] 定番の子7個人ページへのURI
$data['code']['03010087'] = $data1['03010087']; //[ 本店] 定番の子8個人ページへのURI
$data['code']['03010088'] = $data1['03010088']; //[ 本店] 定番の子9個人ページへのURI
$data['code']['03010089'] = $data1['03010089']; //[ 本店] 定番の子10個人ページへのURI
$data['code']['03010090'] = $data1['03010090']; //[ 本店] 個人ページへのリンク先URI
$data['code']['03010091'] = $data1['03010091']; //[ 本店] 個人イベントページへのリンク先URI
$data['code']['03010092'] = isset($data1['03010092']) ? $data1['03010092'] : ''; // このページの正規URL（絶対URL・canonical/og:url用）
$data['code']['03010093'] = isset($data1['03010093']) ? $data1['03010093'] : ''; // CityHeaven個人専用口コミページURL
$data['code']['03010100'] = $data1['03010100']; //[ 本店] トップバナー1クリック時のリンク先URI //トップバナークリック時のURI
$data['code']['03010101'] = $data1['03010101']; //[ 本店] トップバナー2クリック時のリンク先URI
$data['code']['03010102'] = $data1['03010102']; //[ 本店] トップバナー3 クリック時のリンク先URI
$data['code']['03010103'] = $data1['03010103']; //[ 本店] トップバナー4クリック時のリンク先URI
$data['code']['03010104'] = $data1['03010104']; //[ 本店] トップバナー5クリック時のリンク先URI
$data['code']['03010105'] = $data1['03010105']; //[ 本店] トップバナーonclick=後に記述するjavascriptリスト
$data['code']['03010106'] = $data1['03010106']; //[ 本店] トップバナーonclick=後に記述するjavascriptリスト2
$data['code']['03010107'] = $data1['03010107']; //[ 本店] トップバナーonclick=後に記述するjavascriptリスト3
$data['code']['03010108'] = $data1['03010108']; //[ 本店] トップバナーonclick=後に記述するjavascriptリスト4
$data['code']['03010109'] = $data1['03010109']; //[ 本店] トップバナーonclick=後に記述するjavascriptリスト5
$data['code']['03010110'] = $data1['03010110']; //[ 本店] この子の縦ページ
$data['code']['03010111'] = $data1['03010111']; //[ 本店] この子の横ページ
$data['code']['03010115'] = $data1['03010115']; //[ 本店] この子のページ写真サムネールの[BACK]
$data['code']['03010116'] = $data1['03010116']; //[ 本店] この子のページ写真サムネールの[NEXT]
$data['code']['03010117'] = $data1['03010117']; //[ 本店] この子のスペシャルムービー一覧
$data['code']['03010118'] = $data1['03010118']; //[ 本店] この子のスペシャルグラビア一覧
$data['code']['03010119'] = $data1['03010119']; //[ 本店] この子の写メ日記
$data['code']['03010120'] = $data1['03010120']; //[ 本店] この子の個人ブログ
$data['code']['03010121'] = $data1['03010121']; //[ 本店] この子の個人イベントバナーアーカイブス
$data['code']['03010122'] = $data1['03010122']; //[ 本店] 前の子の個人ページへ[BACK]
$data['code']['03010123'] = $data1['03010123']; //[ 本店] 次の子の個人ページへ[NEXT]
$data['code']['03010130'] = $data1['03010130']; //[ 本店] ポップアップする画像のURI
$data['code']['03010131'] = $data1['03010131']; //[ 本店] ポップアップする画像とテキストのURI
$data['code']['03010140'] = $data1['03010140']; //[ 本店] アーカイブスの全一覧から、個人アーカイブスへのリンク先リスト
$data['code']['03010150'] = $data1['03010150']; //[ 本店] ニュース新着 全文リンク
$data['code']['03010151'] = $data1['03010151']; //[ 本店] ニュース新着　前の記事のURI
$data['code']['03010152'] = $data1['03010152']; //[ 本店] ニュース新着　次の記事のURI
$data['code']['03010200'] = $data1['03010200']; //[ 本店] トップページナビゲーション内のバナーonclick=後に記述するjavascriptリスト
$data['code']['03010210'] = $data1['03010210']; //[ 本店] ランダムイベント詳細ベージへのURI(優先順位・女の子イベント>ショップイベント>女の子
$data['code']['03010211'] = $data1['03010211']; //[ 本店] ランダムイベント一覧ベージへのURI(女の子イベントPR一覧かショップイベント一覧)
$data['code']['03010212'] = $data1['03010212']; //[ 本店] 本日売り出し中の女の子の個人ページへのURI
$data['code']['03010213'] = $data1['03010213']; //[ 本店] ランダム新人・個人ページへのURI
$data['code']['03010214'] = $data1['03010214']; //[ 本店] ランダム新着フォト個人ページへのURI
$data['code']['03010215'] = $data1['03010215']; //[ 本店] ショップイベントPR詳細画面へのURI
$data['code']['03010216'] = $data1['03010216']; //[ 本店] ShopPRイベント 縦横ページへのURI
$data['code']['03010223'] = $data1['03010223']; //[ 本店] ランキング4位の個人ページへのURI
$data['code']['03010224'] = $data1['03010224']; //[ 本店] ランキング5位の個人ページへのURI
$data['code']['03010225'] = $data1['03010225']; //[ 本店] ランキング6位の個人ページへのURI
$data['code']['03010226'] = $data1['03010226']; //[ 本店] ランキング7位の個人ページへのURI
$data['code']['03010227'] = $data1['03010227']; //[ 本店] ランキング8位の個人ページへのURI
$data['code']['03010228'] = $data1['03010228']; //[ 本店] ランキング9位の個人ページへのURI
$data['code']['03010229'] = $data1['03010229']; //[ 本店] ランキング10位の個人ページへのURI
$data['code']['03010240'] = $data1['03010240']; //[ 本店] 過去記事リンクアドレス
$data['code']['03010241'] = $data1['03010241']; //[ 本店] この子の最新写メ日記へのURI
$data['code']['03010250'] = $data1['03010250']; //[ 本店] オフィシャルブログ 過去記事リンクアドレス
$data['code']['03010251'] = $data1['03010251']; //[ 本店] リアルタイム予約用個人ページリンクURI
$data['code']['03010268'] = $data1['03010268']; //[ 本店] 写メ日記最新順1の記事へのURI
$data['code']['03010269'] = $data1['03010269']; //[ 本店] 写メ日記最新順2の記事へのURI
$data['code']['03010270'] = $data1['03010270']; //[ 本店] 写メ日記最新順3の記事へのURI
$data['code']['03010290'] = $data1['03010290']; //[ 本店] キャンペーン記事NEXTのURI
$data['code']['03010291'] = $data1['03010291']; //[ 本店] キャンペーン記事BACKのURI
$data['code']['03010292'] = $data1['03010292']; //[ 本店] ニュース新着記事NEXTのURI
$data['code']['03010293'] = $data1['03010293']; //[ 本店] ニュース新着記事BACKのURI
$data['code']['03010300'] = $data1['03010300']; //[ 本店] プレイリストによるリンクリスト
$data['code']['03010301'] = $data1['03010301']; //[ 本店] キャストイベントPRのリンクリスト
$data['code']['03010302'] = $data1['03010302']; //[ 本店] ショップイベントPRのリンクリスト
$data['code']['03010311'] = $data1['03010311']; //[ 本店] PICKUP PR URI
$data['code']['00040001'] = $data1['00040001']; //[ 本店] 本日の日付(ex. 2026.3.5 THU) class="date"用
$data['code']['00040008'] = $data1['00040008']; //[ 本店] 本日の日付タブ用(ex. 3/5 THU) #scheduleTabs用
$data['code']['00040002'] = $data1['00040002']; //[ 本店] 2日目の日付
$data['code']['00040003'] = $data1['00040003']; //[ 本店] 3日目の日付
$data['code']['00040004'] = $data1['00040004']; //[ 本店] 4日目の日付
$data['code']['00040005'] = $data1['00040005']; //[ 本店] 5日目の日付
$data['code']['00040006'] = $data1['00040006']; //[ 本店] 6日目の日付
$data['code']['00040007'] = $data1['00040007']; //[ 本店] 7日目の日付


//ファイル取得
//$source_file = "index.html";

// デバッグ情報（一時的に追加）
error_log("Source file: " . $source_file);
error_log("Source file exists: " . (file_exists($source_file) ? 'YES' : 'NO'));

// テンプレートファイルの読み込み

//index置換
$source = str_replace('kagoshima-deliveryhealth-area-hirakawacho.html', 'kagoshima-deliveryhealth-area-hirakawacho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kawadacho.html', 'kagoshima-deliveryhealth-area-kawadacho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kawakamicho.html', 'kagoshima-deliveryhealth-area-kawakamicho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-shimizucho.html', 'kagoshima-deliveryhealth-area-shimizucho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kamitatsuocho.html', 'kagoshima-deliveryhealth-area-kamitatsuocho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kamihonmachi.html', 'kagoshima-deliveryhealth-area-kamihonmachi.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kamifukumotocho.html', 'kagoshima-deliveryhealth-area-kamifukumotocho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kamitaniguchicho.html', 'kagoshima-deliveryhealth-area-kamitaniguchicho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-komatsubara.html', 'kagoshima-deliveryhealth-area-komatsubara.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-koyamadacho.html', 'kagoshima-deliveryhealth-area-koyamadacho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kasugacho.html', 'kagoshima-deliveryhealth-area-kasugacho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-sanwacho.html', 'kagoshima-deliveryhealth-area-sanwacho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-sakuragaoka.html', 'kagoshima-deliveryhealth-area-sakuragaoka.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-sakanoue.html', 'kagoshima-deliveryhealth-area-sakanoue.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-sakamotocho.html', 'kagoshima-deliveryhealth-area-sakamotocho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-koraicho.html', 'kagoshima-deliveryhealth-area-koraicho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-koutokujidai.html', 'kagoshima-deliveryhealth-area-koutokujidai.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kotsukicho.html', 'kagoshima-deliveryhealth-area-kotsukicho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-koyo.html', 'kagoshima-deliveryhealth-area-koyo.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-gofukucho.html', 'kagoshima-deliveryhealth-area-gofukucho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-gokabeppucho.html', 'kagoshima-deliveryhealth-area-gokabeppucho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-koriyamacho.html', 'kagoshima-deliveryhealth-area-koriyamacho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-koriyamadakecho.html', 'kagoshima-deliveryhealth-area-koriyamadakecho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-korimotocho.html', 'kagoshima-deliveryhealth-area-korimotocho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-korimoto.html', 'kagoshima-deliveryhealth-area-korimoto.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kinseicho.html', 'kagoshima-deliveryhealth-area-kinseicho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kinkodai.html', 'kagoshima-deliveryhealth-area-kinkodai.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-gionnosucho.html', 'kagoshima-deliveryhealth-area-gionnosucho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kibougaokacho.html', 'kagoshima-deliveryhealth-area-kibougaokacho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kiirecho.html', 'kagoshima-deliveryhealth-area-kiirecho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-shimotatsuocho.html', 'kagoshima-deliveryhealth-area-shimotatsuocho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-shimofukumotocho.html', 'kagoshima-deliveryhealth-area-shimofukumotocho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-shimotacho.html', 'kagoshima-deliveryhealth-area-shimotacho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-shimoarata.html', 'kagoshima-deliveryhealth-area-shimoarata.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-shimoishikicho.html', 'kagoshima-deliveryhealth-area-shimoishikicho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-shimoishiki.html', 'kagoshima-deliveryhealth-area-shimoishiki.php', $source);
$source = str_replace('kagoshima-deliveryhealth-shiroutogirl.html', 'kagoshima-deliveryhealth-shiroutogirl.php', $source);
$source = str_replace('kagoshima-deliveryhealth-tallbeautygirl.html', 'kagoshima-deliveryhealth-tallbeautygirl.php', $source);
$source = str_replace('kagoshima-deliveryhealth-poccharigirl.html', 'kagoshima-deliveryhealth-poccharigirl.php', $source);
$source = str_replace('kagoshima-deliveryhealth-glamourgirl.html', 'kagoshima-deliveryhealth-glamourgirl.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-arata.html', 'kagoshima-deliveryhealth-area-arata.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-nagayoshi.html', 'kagoshima-deliveryhealth-area-nagayoshi.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-hanaomachi.html', 'kagoshima-deliveryhealth-area-hanaomachi.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-minayoshicho.html', 'kagoshima-deliveryhealth-area-minayoshicho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-yoshino.html', 'kagoshima-deliveryhealth-area-yoshino.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-yoshinocho.html', 'kagoshima-deliveryhealth-area-yoshinocho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-tamazatodanchi.html', 'kagoshima-deliveryhealth-area-tamazatodanchi.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-tamazatocho.html', 'kagoshima-deliveryhealth-area-tamazatocho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-harara.html', 'kagoshima-deliveryhealth-area-harara.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-hikariyama.html', 'kagoshima-deliveryhealth-area-hikariyama.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-hiroki.html', 'kagoshima-deliveryhealth-area-hiroki.php', $source);
$source = str_replace('contact.html', 'contact.php', $source);
$source = str_replace('area.html', 'area.php', $source);
$source = str_replace('hotel.html', 'hotel.php', $source);
$source = str_replace('blog.html', 'blog.php', $source);
$source = str_replace('kagoshima-deliveryhealth-area-kinkocho.html', 'kagoshima-deliveryhealth-area-kinkocho.php', $source);
$source = str_replace('kagoshima-deliveryhealth-hotel-villacosta500.html', 'kagoshima-deliveryhealth-hotel-villacosta500.php', $source);
$source = str_replace('kagoshima-deliveryhealth-petitegirl.html', 'kagoshima-deliveryhealth-petitegirl.php', $source);
$source = str_replace('kagoshima-deliveryhealth-slendergirl.html', 'kagoshima-deliveryhealth-slendergirl.php', $source);
$source = str_replace('kagodeli_girl_slender.html', 'kagodeli_girl_slender.php', $source);
$source = str_replace('sample_123.html', 'sample_123.php', $source);
$source = str_replace('page.html', 'page.php', $source);
$source = str_replace('testda.html', 'testda.php', $source);
$source = str_replace('create.html', 'create.php', $source);
$source = str_replace('test.html', 'test.php', $source);
$source = str_replace('index.html', 'index.php', $source);
$source = str_replace('main.html', 'main.php', $source);
$source = str_replace('movie.html', 'movie.php', $source);
$source = str_replace('pc_index.html', 'pc_index.php', $source);
$source = str_replace('sp_index.html', 'sp_index.php', $source);
$source = str_replace('news.html', 'news.php', $source);
$source = str_replace('girls.html', 'girls.php', $source);
$source = str_replace('girls_list.html', 'girls_list.php', $source);
$source = str_replace('schedule.html', 'schedule.php', $source);
$source = str_replace('system.html', 'system.php', $source);
$source = str_replace('mypage.html', 'mypage.php', $source);
$source = str_replace('job.html', 'job.php', $source);
$source = str_replace('diary.html', 'diary.php', $source);
//$source = str_replace('="./"', '="./pc_index.php"', $source);

// データ配列に追加
$data['code']['00010601'] = $data1['00010601'];

//クラス生成
$HpgCoder = new HpgCoder($source, $data);

// お気に入り数の表示処理（共通処理）
if($data1['00010601'] > 0){
	$HpgCoder->Converted = str_replace('class="num" style="display:none;"', 'class="num"', $HpgCoder->Converted);
	$HpgCoder->Converted = str_replace('class="num" style="display: none;"', 'class="num"', $HpgCoder->Converted);
	$HpgCoder->Converted = preg_replace('/class="num" style="display:\s*none;"/', 'class="num"', $HpgCoder->Converted);
	$HpgCoder->Converted = str_replace('class="headNavi"', 'class="headNavi headNavi2"', $HpgCoder->Converted);
}

// お気に入り数の置換処理
$favcast = array();
if(isset($_COOKIE["candyfav"])){
	$favcast = explode(',', urldecode($_COOKIE["candyfav"]));
}
$HpgCoder->Converted = str_replace('____fCount____', count($favcast), $HpgCoder->Converted);

// favInfoの初期表示制御（お気に入り数が0より大きく、実際に出勤している女の子がいる場合のみ）
if(count($favcast) > 0){
	// お気に入り登録の女の子の出勤状況をチェック
	$workingGirlsCount = 0;
	
	// 現在の日時を取得
	$current_date = date('Y-m-d');
	$current_time = date('H:i');
	
	// お気に入り登録の女の子の出勤状況をチェック
	foreach($favcast as $girl_id) {
		$QUERY = "SELECT COUNT(*) as count FROM girls_schedule";
		$QUERY .= " WHERE club_id = '" . CLUBID . "'";
		$QUERY .= " AND girls_id = '" . $girl_id . "'";
		$QUERY .= " AND year = '" . date('Y') . "'";
		$QUERY .= " AND month = '" . date('n') . "'";
		$QUERY .= " AND day = '" . date('j') . "'";
		$QUERY .= " AND (type = '0' || type = '1')"; // 出勤予定または出勤済
		$QUERY .= " AND status = '1'";
		
		$RESULT = $Database->Query($QUERY);
		if($RESULT !== false) {
			$row = $Database->Fetch_Array($RESULT);
			if($row['count'] > 0) {
				$workingGirlsCount++;
			}
		}
	}
	
	// 実際に出勤している女の子がいる場合のみ表示
	if($workingGirlsCount > 0){
		// お気に入り数を実際の出勤数に更新
		$HpgCoder->Converted = str_replace('____fCount____', $workingGirlsCount, $HpgCoder->Converted);
		
		// PC版用のfavInfo表示制御
		$HpgCoder->Converted = str_replace('id="favInfo" class="toast pcOnly" style="display:none;"', 'id="favInfo" class="toast pcOnly"', $HpgCoder->Converted);
		$HpgCoder->Converted = str_replace('id="favInfo" class="toast pcOnly" style="display: none;"', 'id="favInfo" class="toast pcOnly"', $HpgCoder->Converted);
		$HpgCoder->Converted = preg_replace('/id="favInfo" class="toast pcOnly" style="display:\s*none;"/', 'id="favInfo" class="toast pcOnly"', $HpgCoder->Converted);
		
		// PC版用のfavInfo表示制御（pcOnlyクラスがない場合）
		$HpgCoder->Converted = str_replace('id="favInfo" class="toast" style="display:none;"', 'id="favInfo" class="toast"', $HpgCoder->Converted);
		$HpgCoder->Converted = str_replace('id="favInfo" class="toast" style="display: none;"', 'id="favInfo" class="toast"', $HpgCoder->Converted);
		$HpgCoder->Converted = preg_replace('/id="favInfo" class="toast" style="display:\s*none;"/', 'id="favInfo" class="toast"', $HpgCoder->Converted);
	}
}



//変換&表示
if(isset($_GET['no']) && strval($_GET['no']) === '1241'){
	$hasToken = (strpos($HpgCoder->Converted, 'rep03010093eot') !== false);
	$data03010093 = isset($data['code']['03010093']) ? $data['code']['03010093'] : '';
	$dbgExtractHas = isset($HpgCoder->dbg_extractedHas03010093) ? $HpgCoder->dbg_extractedHas03010093 : '';
	$dbgExtractCount = isset($HpgCoder->dbg_extractedTokenCount) ? $HpgCoder->dbg_extractedTokenCount : '';
	$dbgOutHas = isset($HpgCoder->dbg_outHas03010093) ? $HpgCoder->dbg_outHas03010093 : '';
	$dbgOutValue = isset($HpgCoder->dbg_outValue03010093) ? $HpgCoder->dbg_outValue03010093 : '';
	$HpgCoder->Converted .= "\n<!-- DEBUG rep03010093eot: tokenStillPresent=" . ($hasToken ? '1' : '0') . " extractedHas=" . htmlspecialchars($dbgExtractHas, ENT_QUOTES, 'UTF-8') . " extractedCount=" . htmlspecialchars($dbgExtractCount, ENT_QUOTES, 'UTF-8') . " outHas=" . htmlspecialchars($dbgOutHas, ENT_QUOTES, 'UTF-8') . " outValue=" . htmlspecialchars($dbgOutValue, ENT_QUOTES, 'UTF-8') . " data03010093=" . htmlspecialchars($data03010093, ENT_QUOTES, 'UTF-8') . " -->\n";
}
print($HpgCoder->Converted);

//ＤＢ切断//
$Database->Disconnect();
