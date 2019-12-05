<?php
/*
*  ログアウト処理
*
*/

require_once './include/conf/const.php';
// require_once '/htdocs/PHP/25kadai_ec/tool.php';


// セッション開始
session_start();
// セッション名取得 ※デフォルトはPHPSESSID
$session_name = session_name();
// セッション変数を全て削除
$_SESSION = array();
// セッションのuser_idも消える
 // ユーザのCookieに保存されているセッションIDを削除
if (isset($_COOKIE[$session_name])) {
  // sessionに関連する設定を取得
  $params = session_get_cookie_params();
//   この関数は、クッキーの内容（セッションに関する）をすべて取得
 
  // sessionに利用しているクッキーの有効期限を過去に設定することで無効化　有効期限の後は、どの範囲までかを指定する
  setcookie($session_name, '', time() - 42000,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]
  );
//   setcookie('user_name'   , '', $now - 3600);
//   21行目で取ってきた内容をすべて消去（ブラウザ上の）
}
 
// セッションIDを無効化　サーバーとの切断
session_destroy();
// ログアウトの処理が完了したらログインページへリダイレクト
header('Location: http://' . SERVERNAME . '/myecsite/top.php');
exit;
