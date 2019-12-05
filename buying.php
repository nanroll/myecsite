<?php

// カートページは。、　同一ユーザーの「カートテーブル」の情報を一覧で表示させる場所

$err_msg = array();
$msg = '';
$created_date = date('Y-m-d H:i:s');
$updated_date = date('Y-m-d H:i:s');

require_once './include/conf/const.php';
require_once './include/model/function.php';

$title = '購入完了';

// セッション開始
session_start();

login_check();


// データベース接続
$link = get_db_connect();

$cart_list = select_cart_table($link, $_SESSION['user_id'], TRUE);
    if ($cart_list === FALSE) {
        $err_msg[] = 'データ取得失敗';
    }
// カートテーブルの合計金額を価格×数量を表示する
$sum = 0;
foreach ($cart_list as $value) {
    $sum += ($value['price'] * $value['amount']);
} 



// $user_info = is_user_info($link, $_SESSION['user_id']);

$customer_info = $_SESSION ['customer_info'];
$family_name = $customer_info['family_name'];
$first_name = $customer_info['first_name'];
$post_code = $customer_info['post_code'];
$address = $customer_info['address'];
$phone = $customer_info['phone'];
$mail = $customer_info['mail'];
$user_id = $_SESSION['user_id'];
$update_user_info = is_update_user_info($link, $user_id, $family_name, $first_name, $post_code, $address, $phone, $mail);


if (count($err_msg) === 0) {
    mysqli_autocommit($link, false);// トランザクションスタート
    $sql = 'DELETE FROM cart_table WHERE user_id =' . $_SESSION['user_id'];
    if (mysqli_query($link, $sql) === TRUE) {
        foreach($cart_list as $value) {
        $sql = 'UPDATE stock_item_table 
                SET stock_count = stock_count -' . $value['amount'] . 
                ' WHERE item_id =' . $value['id'];
            if (mysqli_query($link, $sql) !== TRUE) {
                $err_msg[] = '購入手続きにエラーが発生しました。カートに戻り、再度お手続きください'; 
            }    
        }       
    } else {
        $err_msg[] = '購入手続きにエラーが発生しました。カートに戻り、再度お手続きください'; 
    }
    
    
    
    if ($update_user_info === true) {
        $sql = "UPDATE user_table SET 
                family_name = '$family_name', 
                first_name = '$first_name', 
                post_code = $post_code, 
                address = '$address', 
                phone = $phone, 
                mail = '$mail' WHERE id = $user_id"; 
        if (mysqli_query($link, $sql) !== TRUE) {
            $err_msg[] = '購入手続きにエラーが発生しました。カートに戻り、再度お手続きください'; 
        }   
    }
    
    if (count($err_msg) === 0) {
       // 処理確定
       mysqli_commit($link);
       $msg = 'ご注文ありがとうございました';
    } else {
       // 処理取消
       mysqli_rollback($link);
    }
}



// if ($link) {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         if (isset($_POST['search_method']) === TRUE) {
//             $search_method = $_POST['search_method'];
//         }
//         if ($search_method === 'amount_update') {
//             if (isset($_POST['amount']) === TRUE) {
//                 $amount = $_POST['amount'];
//             }
//             if (isset($_POST['item_id']) === TRUE) {
//                 $item_id = $_POST['item_id'];
//             }
//             if ($amount === '') {
//                 $err_msg[] = '購入数量を入れてください';       
//             } else if (preg_match('/^([1-9]{1}[0-9]*)$/', $amount) !== 1) {
//                 $err_msg[] = '購入数量は正しい数字を入れてください';
//             }
//             if (preg_match('/^[1-9]{1}[0-9]*$/', $item_id) !== 1) {
//                 $err_msg[] = '不正な処理です';    
//             }
//             if (isset($_SESSION['user_id']) === TRUE) {
//                 $user_id = $_SESSION['user_id'];
//             }
//             if (count($err_msg) === 0) {
//                 $sql = 'UPDATE cart_table 
//                         SET amount =' . $amount . ', updated_date =' . "'" . $updated_date . "'" . ' 
//                         WHERE item_id =' . $item_id . ' AND user_id =' . $_SESSION['user_id'];
//                 if (mysqli_query($link, $sql) === TRUE) {
//                     $msg = '購入数量の変更をしました';
//                 } else {
//                     $err_msg[] = 'SQL失敗:' . $sql;
//                 }
//             }
//         } else if ($search_method === 'delete') {
//             if (isset($_POST['item_id']) === TRUE) {
//                 $item_id = $_POST['item_id'];
//             }
//             if (preg_match('/^[1-9]{1}[0-9]*$/', $item_id) !== 1) {
//                 $err_msg[] = '不正な処理です';    
//             }
            
            // if (count($err_msg) === 0) {
            //     mysqli_autocommit($link, false);// トランザクションスタート
            //     $sql = 'DELETE FROM cart_table WHERE item_id =' . $item_id . ' AND user_id =' . $_SESSION['user_id'];
            //     if (mysqli_query($link, $sql) !== TRUE) {
            //         $err_msg[] = '購入手続きにエラーが発生しました。カートに戻り、再度お手続きください' 
            //     }
            // }
        
            
            

            
            
            
            
            // 注文の確定処理
                // １．（テーブル更新のエラーがなければ）購入完了ページに飛ばす（カートテーブルのitem_idを消す前に）
                    // （２．のエラーが起きたら、カートページに戻す）
            
                // ２．テーブルの更新
                    // トランザクションスタート
                    // stock_item_tableのstock_countを（cart_tableのamount）の数だけマイナスする
                    // cart_tableの商品一覧を全て削除（ログイン中のuser_idの部分だけ）
                
                
            
//         }
//     }
// }










close_db_connect($link);

 

include_once './include/view/buying.php';