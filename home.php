<?php

//ログイン済みユーザの商品一覧ページ

$err_msg = array();
$msg = '';
$created_date = date('Y-m-d H:i:s');
$updated_date = date('Y-m-d H:i:s');

require_once './include/conf/const.php';
require_once './include/model/function.php';

$title = 'ようこそなぁーんの森へ！！';



// セッション開始
session_start();

login_check();


// データベース接続
$link = get_db_connect();

// カートページは。、　同一ユーザーの「カートテーブル」の情報を一覧で表示させる場所


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 商品はidだけで管理する(主キーを送る)
    // データが飛んで来たら、（item_id）受け取って、チェック（数字かどうか）
    if (isset($_POST['item_id']) === TRUE) {
        $item_id = $_POST['item_id'];
    }
// print_r($_POST['item_id']);
    if (preg_match('/^([1-9]{1}[0-9]*)$/', $item_id) !== 1) {
       $err_msg[] = '不正な値です';
    }
    // エラーが無かったら、item_idの商品情報（在庫とかステータス）を取って来る(送るためじゃない)
    // （在庫の有無と、公開か非公開をチェック）
    // 在庫とステータスをチェックする関数に飛ばす
    if (count($err_msg) === 0) {
        $result = check_status_stock($link, $item_id);
    
        if ($result !== '') {
            $err_msg[] = $result;
        } 
    }
    
    // 同一ユーザーの同一商品が「カートデータベース」にあるかないか
// ない　＝　新規のインサート（数量は必ず１）
// ある　＝　注文数量の追加（数量にプラス１）
    
    // if (isset($_SESSION['user_id']) === TRUE) { 上でログインチェックするから不要
    
    $sql = 'SELECT id FROM cart_table WHERE user_id =' . $_SESSION['user_id'] . ' AND item_id =' . $item_id;
        //   下の行程(update)でamountに+1しているだけになるので、amount情報は不要！
    $cart_data = get_as_array($link, $sql, 1);
    //新機能：配列データ取り出しモード1　配列箱に入ってこず、1行だけ$cart_data変数に入るモード
    
    if ($cart_data === FALSE) {
        $err_msg[] = 'sql失敗' . $sql;
    // } else if (isset($cart_data[0]['id']) === TRUE) {
        // 配列の塊をレコード単位と捉えている
    } else {
        if (isset($cart_data['id']) === TRUE) {
            $sql = 'UPDATE cart_table SET amount = amount +1 WHERE id=' . $cart_data['id'] ;
        } else {
            $sql = "INSERT INTO cart_table(user_id, item_id, amount, created_date, updated_date) 
                    VALUES('" . $_SESSION['user_id'] ."','" . $item_id ."','1','" . $created_date ."','" .$updated_date ."')";
        }
        $result = insert_db($link, $sql);
        if ($result === FALSE) {
            $err_msg[] = 'sql失敗' . $sql;
        } else {
            $msg = '商品をカートに追加しました';
        }
    }
   
} 

// 商品並べ替え実行ボタンで飛んでくるデータを受け取る
$order = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['order']) === TRUE) {// nameのjobがあるか
        $order = $_GET['order'];
    }// この時点で$orderの中は、空文字かGETのvalueが入った状態
}



// （並べ替え用の）引数で$orderを渡す
// item_tableから商品情報を取得（statusの状態, 税計算をやるかやらないか を選択可）
// select_item_table($link, $statusの引数 1=公開を取得 0=非公開のみ取得 未入力=どちらも取得, taxflgの引数 TRUE=税込 FALSE=税別);
$item_list = select_item_table($link, TRUE, 1, $order);
    if ($item_list === FALSE) {
        $err_msg[] = 'データ取得失敗';
    }



close_db_connect($link);

 
// ログイン済みの場合ホームページ表示
include_once './include/view/home.php';