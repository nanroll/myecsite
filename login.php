<?php
/*
*  ログイン処理

*/
require_once './include/conf/const.php';
require_once './include/model/function.php';

// リクエストメソッド確認
if (get_request_method() !== 'POST') {
   // POSTでなければログインページへリダイレクト
   header('Location: http://' . SERVERNAME . '/myecsite/top.php');
   exit;
}


// ユーザーネームとパスワードを取った後に、
// admin  admin ならばｍ、管理ページに飛ばす



// 飛んできたときにチェックがついてたら保存してください
if (isset($_POST['cookie_check']) === TRUE) {
   $cookie_check = $_POST['cookie_check'];
} else {
   $cookie_check = '';
}
if (isset($_POST['user_name']) === TRUE) {
   $cookie_value = $_POST['user_name'];
} else {
   $cookie_value = '';
}


if ($cookie_check === 'checked') {
   // Cookieへ保存
   setcookie('cookie_check', $cookie_check, time() + 60 * 60 * 24 * 365);
   setcookie('user_name'   , $cookie_value, time() + 60 * 60 * 24 * 365);
} else {
   // Cookieを削除
   setcookie('cookie_check', '', time() - 3600);
   setcookie('user_name'   , '', time() - 3600);
}


// セッション開始
session_start();
// POST値取得
$user_name  = get_post_data('user_name');  // メールアドレス
$password = get_post_data('password'); // パスワード
// ユーザーネームをCookieへ保存
// setcookie('user_name', $user_name, time() + 60 * 60 * 24 * 365);
// データベース接続
$link = get_db_connect();
// user_tableを参照し、ユーザーネームとパスワードからidを取得するSQL
$sql = 'SELECT id, status FROM user_table
       WHERE user_name =\'' . $user_name . '\' AND password =\'' . $password . '\'';
// SQL実行し登録データを配列で取得
$data = get_as_array($link, $sql);
// データベース切断
close_db_connect($link);
// 登録データを取得できたか確認
if (isset($data[0]['id'])) {
   // セッション変数にidを保存
   $_SESSION['user_id'] = $data[0]['id'];
   $_SESSION['status'] = $data[0]['status'];
// print_r($_SESSION['user_id']);
    if ($_SESSION['status'] === '0') {
        header('Location: http://' . SERVERNAME . '/myecsite/home.php');
    } else if ($_SESSION['status'] === '1') {
        header('Location: http://' . SERVERNAME . '/myecsite/tool.php');
    }
   // ログイン済みユーザのホームページへリダイレクト
   exit;
} else {
   // セッション変数にログインのエラーフラグを保存
   $_SESSION['login_err_flag'] = TRUE;
   // ログインページへリダイレクト
   header('Location: http://' . SERVERNAME . '/myecsite/top.php');
   exit;
}
