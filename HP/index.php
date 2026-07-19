<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
//データセット基本ファイル読込
include("/home/firststar/public_html/group/candy/includefile/dataset_base.php");


//転送設定
// $smart_add="./s/sp_index.php";
// $pc_add = "./pc/pc_index.php";
// if(isset($_SERVER['HTTP_USER_AGENT'])){
//     $user_agent = $_SERVER['HTTP_USER_AGENT'];
//     header( "HTTP/1.1 301 Moved Permanently" );
//     if(preg_match("/iPhone/i",$user_agent)){header("Location: $smart_add");}
//     elseif(preg_match("/iPad/i",$user_agent)){header("Location: $smart_add");}
//     elseif(preg_match("/Android/i",$user_agent)){header("Location: $smart_add");}
//     elseif(preg_match("/DoCoMo/i",$user_agent)){header("Location: $smart_add");}
//     elseif(preg_match("/UP\.Browser/i",$user_agent)){header("Location: $smart_add");}
//     elseif(preg_match("/J-PHONE/i",$user_agent)){header("Location: $smart_add");}
//     elseif(preg_match("/Vodafone/i",$user_agent)){header("Location: $smart_add");}
//     elseif(preg_match("/SoftBank/i",$user_agent)){header("Location: $smart_add");}
//     elseif(preg_match("/J-EMULATOR/i",$user_agent)){header("Location: $smart_add");}
//     else{header("Location: $pc_add");}
//     die;
// }else{
// print <<<END
// <html><body>
// HTTP_USER_AGENT Error<br /><br />
// ユーザーエージェントが読み込めませんでした。<br />
// </body></html>
// END;
// }
?>
