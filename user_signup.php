<?php

// 商品管理ページ

$msg = '';
$search_method ='';
$stock_list ='';
$err_msg = array();

$img_dir = './img/';
$created_date = date('Y-m-d H:i:s');
$updated_date = date('Y-m-d H:i:s');

require_once './include/conf/const.php';
require_once './include/model/function.php';

$title = 'なぁーんの森　ユーザー登録';

// if (isset($_SESSION['user_id']) === TRUE) {
//   $user_id = $_SESSION['user_id'];
//   print __LINE__;
// } else {
//   // 非ログインの場合、ログインページへリダイレクト

//   header('Location: http://' . SERVERNAME . '/myecsite/top.php');
//   exit;
// }

// DB接続
$link = get_db_connect();

$success_flug = FALSE;

if ($link) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['user_name']) === TRUE) {
            $user_name = $_POST['user_name'];
        }
        if (isset($_POST['password']) === TRUE) {
            $password = $_POST['password'];
        }
        if (empty($user_name) === TRUE) {
            $err_msg[] = 'ユーザー名を入力してください';       
        } else if (preg_match('/^([0-9a-zA-Z]{6,20})$/', $user_name) !== 1) {
            $err_msg[] = 'ユーザー名は半角英数字6文字以上20文字以内で入力してください';
        }
        if ($password === '') {
            $err_msg[] = 'パスワードを入力してください';       
        } else if (preg_match('/^([0-9a-zA-Z]{6,20})$/', $password) !== 1) {
            $err_msg[] = '正しい形式でパスワードを入力してください';
            $err_msg[] = 'パスワードは6文字以上20文字以内で入力してください';
        }
// count($result) > 0) オブジェクトを数える（必ずオブジェクトは1にしかならない データの塊）
// (mysqli_num_rows($result));　$result = ["num_rows"]というデータが入っている。mysqli_queryでとれた結果（レコード）の件数を数える　　
        if (count($err_msg) === 0) {
            $sql = "SELECT user_table.id FROM user_table WHERE user_name='" . $user_name . "'";
            // $sql = 'SELECT user_table.id FROM user_table WHERE user_name=\'' . $user_name . '\';';
            $result = mysqli_query($link, $sql);
// print 'a';
// var_dump($result);
// print 'b';
// var_dump(count($result));
// print 'c';
// var_dump(mysqli_num_rows($result));
            $row = mysqli_fetch_array($result);
            if (isset($row['id']) === TRUE) {
            // if (mysqli_num_rows($result) > 0) { レコードが何個かあったとき
                
                $err_msg[] = '既に存在するユーザー名です 他の名前に変更してください';
            }
        }
        if (count($err_msg) === 0) {
            $user_data = array(
                'user_name'     => "'" . $user_name . "'",
                'password'      => "'" . $password . "'",
                'status'        => 0,
                'created_date'  => '\'' . $created_date . '\'',
                
            );
            $sql = 'INSERT INTO user_table (user_name, password, status, created_date) VALUES(' . implode(',', $user_data) . ')'; 
        

            if (mysqli_query($link, $sql) !== TRUE) {
                $err_msg[] = 'ユーザー登録出来ませんでした';
            } else {
                $msg = 'ユーザー登録完了しました';
            }
        }
    }
}

mysqli_close($link);

// ログインページ遷移リンクもつける

include_once './include/view/user_signup.php';