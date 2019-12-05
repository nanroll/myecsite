<?php

//お客さま情報確認ページ

require_once './include/conf/const.php';
require_once './include/model/function.php';

$title = 'な～んのかくにん';


// （カートボタン等がない）ヘッダーを表示するためのフラグ
$header_flg = 1;
// $send = array('normal' => '通常配送'); 
// SESSION情報として$_SESSION['customer_info']['send']の中にはnoomalが入っていると
// $send[$_SESSION['customer_info']['send']]とすることで、通常配送という文字列が取得できる



// セッション開始
session_start();

login_check();

// データベース接続
$link = get_db_connect();

$cart_list = select_cart_table($link, $_SESSION['user_id'], TRUE);

if ($link) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['search_method']) === TRUE) {
            $search_method = $_POST['search_method'];
        }
        if ($search_method === 'buying') {
            header('Location: http://' . SERVERNAME . '/myecsite/buying.php');
            exit;
        }
    }        
}


close_db_connect($link);
 
// ログイン済みの場合ホームページ表示
include_once './include/view/form_check.php';