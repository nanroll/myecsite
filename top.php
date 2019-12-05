<?php
//ログインページ(Topページ)

$now = time();
// $user_name = '';

require_once './include/conf/const.php';
require_once './include/model/function.php';

$title = 'ようこそなぁーんの森へ！！';

// 開いたときにチェックを付けるかどうか（クッキー情報を確認）
if (isset($_COOKIE['cookie_check']) === TRUE) {
   $cookie_check = 'checked';
} else {
   $cookie_check = '';
}
if (isset($_COOKIE['user_name']) === TRUE) {
   $user_name = $_COOKIE['user_name'];
} else {
   $user_name = '';
}
// 関数を使っていない（使っても使わなくても一緒　何でもかんでも関数するのｈはかえって非効率的）
$cookie_check = htmlspecialchars($cookie_check, ENT_QUOTES, 'UTF-8');
$user_name = htmlspecialchars($user_name  , ENT_QUOTES, 'UTF-8');

// セッション開始
session_start();
// セッション変数からログイン済みか確認
if (isset($_SESSION['user_id']) === TRUE) {
   // ログイン済みの場合、ホームページへリダイレクト
   header('Location: http://' . SERVERNAME . '/myecsite/home.php');
   exit;
}
// セッション変数からログインエラーフラグを確認
if (isset($_SESSION['login_err_flag']) === TRUE) {
   // ログインエラーフラグ取得
   $login_err_flag = $_SESSION['login_err_flag'];
   // エラー表示は1度だけのため、フラグをFALSEへ変更
   $_SESSION['login_err_flag'] = FALSE;
} else {
   // セッション変数が存在しなければエラーフラグはFALSE
   $login_err_flag = FALSE;
}
// echo "<pre>";
// print_r ($_COOKIE);
// echo "</pre>";
include_once './include/view/top.php';