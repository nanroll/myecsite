<?php

// カートページ。同一ユーザーの「カートテーブル」の情報を一覧で表示させる場所

// このユーザーは既に使われていますとかはAjaxでデータベースを見ている


// 11月3日
// JS　
// フォームの入力途中で注意する（Jqueryでできる？）
// sampleフォルダーに考え方のサンプルあり（発火タイミング）
// JSにも正規表現がある　Ajaxは不要


// クリアボタン　は　画面のリロードさせる
// window onroad(最優先で読み込むもの)

// PHP
// フォーム入力内容を確認画面で指摘し、戻った時にすでに入力した内容は残す＝SESSIONを使う



$err_msg = array();
$msg = '';
$search_method = '';
$created_date = date('Y-m-d H:i:s');
$updated_date = date('Y-m-d H:i:s');

require_once './include/conf/const.php';
require_once './include/model/function.php';

$title = 'なぁーんのレジ';

// セッション開始
session_start();

login_check();


// データベース接続
$link = get_db_connect();

// カートページは。、　同一ユーザーの「カートテーブル」の情報を一覧で表示させる場所

if ($link) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['search_method']) === TRUE) {
            $search_method = $_POST['search_method'];
        }
        if ($search_method === 'amount_update') {
            if (isset($_POST['amount']) === TRUE) {
                $amount = $_POST['amount'];
            }
            if (isset($_POST['item_id']) === TRUE) {
                $item_id = $_POST['item_id'];
            }
            if ($amount === '') {
                $err_msg[] = '購入数量を入れてください';       
            } else if (preg_match('/^([1-9]{1}[0-9]*)$/', $amount) !== 1) {
                $err_msg[] = '購入数量は正しい数字を入れてください';
            }
            if (preg_match('/^[1-9]{1}[0-9]*$/', $item_id) !== 1) {
                $err_msg[] = '不正な処理です';    
            }
            // if (isset($_SESSION['user_id']) === TRUE) {
            //     $user_id = $_SESSION['user_id'];
            // }
            
            
            $stock_alart[$item_id] = single_stock_check($link, $user_id, $item_id, $amount);
// print_r($stock_alart);

            
            if (count($err_msg) === 0) {
                $sql = 'UPDATE cart_table 
                        SET amount =' . $amount . ', updated_date =' . "'" . $updated_date . "'" . ' 
                        WHERE item_id =' . $item_id . ' AND user_id =' . $_SESSION['user_id'];
                                                                        //   $user_idでよい
                if (mysqli_query($link, $sql) === TRUE) {
                    $msg = '購入数量の変更をしました';
                } else {
                    $err_msg[] = 'SQL失敗:' . $sql;
                }
            }
        // 削除ボタンを押された時
        } else if ($search_method === 'delete') {
            if (isset($_POST['item_id']) === TRUE) {
                $item_id = $_POST['item_id'];
            }
            if (preg_match('/^[1-9]{1}[0-9]*$/', $item_id) !== 1) {
                $err_msg[] = '不正な処理です';    
            }
            
            if (count($err_msg) === 0) {
                $sql = 'DELETE FROM cart_table 
                        WHERE item_id =' . $item_id . ' 
                        AND user_id =' . $_SESSION['user_id'];
                if (mysqli_query($link, $sql) === TRUE) {
                    $msg = 'カート内の商品を削除しました';
                } else {
                    $err_msg[] = 'SQL失敗:' . $sql;
                }
            }
        } else if ($search_method === 'buying') {
            // 注文ボタンを押された時点で
            // 在庫があるか？ステータスが非公開になっていないか？
            // カートテーブルのamountとstock_item_tableの在庫を比較する
            check_cart_item($link, $_SESSION['user_id']);
            
            header('Location: http://' . SERVERNAME . '/myecsite/form.php');
            exit;
            
        }
    }
}

// カート内の商品情報を取得(税込金額出すならTRUE)
$cart_list = select_cart_table($link, $_SESSION['user_id'], TRUE);

    if ($cart_list === FALSE) {
        $err_msg[] = 'データ取得失敗';
    }
    // カートテーブルの合計金額を価格×数量を表示する
    $sum = 0;
    foreach ($cart_list as $value) {
        $sum += ($value['price'] * $value['amount']);
    } 
    // 注文ボタン押されたら
    if ($search_method !== 'buying') {
        check_cart_item($link, $_SESSION['user_id']);
    }

close_db_connect($link);

 
// ログイン済みユーザのホームページ表示
include_once './include/view/cart.php';