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

// セッション開始
session_start();

// ログインチェック関数
login_check('1');


// DB接続
$link = get_db_connect();

// 入力文字にtrimをかける trim = 文字の左側、右側の半角スペース、TAB、改行マークをカットする
// 全角スペースをカットするには、('/　/', '', $item_name);　間にある全角空白も取り除いてしまう
// ('/^　$/', '', $item_name); ^が最初$が最後なので、全角空白1文字だけカット
// .全ての文字　?最短一致　出来るだけ短く取ろうとする preg=最長一致が基本
// .* 全角空白も含んでしまう　=>　?= 最短一致(出来るだけ短くしてね)にする　
if ($link) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['search_method']) === TRUE) {
            $search_method = $_POST['search_method'];
        }
        if ($search_method === 'insert') {
            if (isset($_POST['name']) === TRUE) {
                $item_name = trim($_POST['name']);
                $item_name = preg_replace('/^(　)*/', '', $item_name);// 左側の全角スペースのカット
                $item_name = preg_replace('/(　)*$/', '', $item_name);// 右側の全角スペースのカット
                // $item_name = preg_replace('/^(　)*|(　)*$/', '', $item_name);// 40と41をまとめたもの

                
            }
            if (isset($_POST['price']) === TRUE) {
                $item_price = trim($_POST['price']);
            }
            if (isset($_POST['stock_count']) === TRUE) {
                $stock_count = $_POST['stock_count'];
            }
            if (isset($_FILES['new_img'])) {
                $new_img = $_FILES['new_img'];
            }
            if (isset($_POST['status']) === TRUE) {
                $status = $_POST['status'];
            }
            // 数字の0もemptyになってしまう（emptyだと0が登録されない）　上で取ってきたときに空白をチェック
            if ($item_name === '') {
                $err_msg[] = '名前を入力してください';       
            }
            if ($item_price === '') {
                $err_msg[] = '金額を入力してください';       
            } else if (preg_match('/^(0|[1-9]{1}[0-9]*)$/', $item_price) !== 1) {
                $err_msg[] = '正しい形式で金額を入力してください';
            }
            if ($stock_count === '') {
                $err_msg[] = '在庫数を入力してください';
            } else if (preg_match('/^(0|[1-9]{1}[0-9]*)$/', $stock_count) !== 1) {
                $err_msg[] = '正しい形式で個数を入力してください';
            }
            if (preg_match('/^(0|1)$/', $status) !== 1) {
                $err_msg[] = '不正なエラーです。存在しないステータスです';
            }
            if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
                $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
                if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png') {
                    $new_img_filename = sha1(uniqid(mt_rand(), true)). '.' . $extension;
                    if (is_file($img_dir . $new_img_filename) !== TRUE) {
                        if (move_uploaded_file($_FILES['new_img']['tmp_name'], $img_dir . $new_img_filename) !== TRUE) {
                            $err_msg[] = 'ファイルアップロードに失敗しました';
                        }
                    } else {
                        $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
                    }
                } else {
                    $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGまたｈまたはpng形式のみ利用可能です。';
                }
            } else {
                $err_msg[] = 'ファイルを選択してください';
            }
            if (count($err_msg) === 0) {
                $item_data = array(
                    'name'    => "'" . $item_name . "'",
                    'price'         => $item_price,
                    'status'        => $status,
                    'img'     => "'" . $img_dir . $new_img_filename . "'",
                    'created_date'   => "'" . $created_date . "'",
                    'updated_date'   => "'" . $updated_date . "'",
                );
                mysqli_autocommit($link, false);// トランザクションスタート
                $sql = 'INSERT INTO item_table (name, price, status, img, created_date, updated_date) VALUES(' . implode(',', $item_data) . ')'; 
                $result = mysqli_query($link, $sql);
                if ($result === TRUE) {
                    $item_id = mysqli_insert_id($link);
                    $stock_data = array(
                    'item_id'      => $item_id,
                    'stock_count'   => $stock_count,
                    'created_date'   => "'" . $created_date . "'",
                    'updated_date'   => "'" . $updated_date . "'",
                    );
                    $sql = 'INSERT INTO stock_item_table (item_id, stock_count, created_date, updated_date) VALUES(' . implode(',', $stock_data) . ')';
                    $result = mysqli_query($link, $sql);
                    if ($result === FALSE) {
                        $err_msg[] = '在庫情報追加失敗';
                    }
                    
                } else {
                    $err_msg[] = '商品情報追加失敗';
                }
               
                if (count($err_msg) === 0) {
                   // 処理確定
                   mysqli_commit($link);
                   $msg = '商品を追加しました';
                } else {
                   // 処理取消
                   mysqli_rollback($link);
                }
            }   
        } else if ($search_method === 'stock_update') {
            if (isset($_POST['stock_count']) === TRUE) {
                $stock_count = $_POST['stock_count'];
            }
            if (isset($_POST['item_id']) === TRUE) {
                $item_id = $_POST['item_id'];
            }
            if ($stock_count === '') {
                $err_msg[] = '在庫数を入れてください';       
            } else if (preg_match('/^(0|[1-9]{1}[0-9]*)$/', $stock_count) !== 1) {
                $err_msg[] = '在庫数は正しい数字を入れてください';
            }
            if (preg_match('/^[1-9]{1}[0-9]*$/', $item_id) !== 1) {
                $err_msg[] = '不正な処理です';    
            } 
            if (count($err_msg) === 0) {
                $sql = 'UPDATE stock_item_table 
                        SET stock_count =' . $stock_count . ', updated_date =' . "'" . $updated_date . "'" . ' WHERE item_id =' . $item_id;
                if (mysqli_query($link, $sql) === TRUE) {
                    $msg = '在庫の更新をしました';
                } else {
                    $err_msg[] = 'SQL失敗:' . $sql;
                }
            }
        } else if ($search_method === 'status_update') {
            if (isset($_POST['status']) === TRUE) {
                $status = $_POST['status'];
            }
            if (isset($_POST['item_id']) === TRUE) {
                $item_id = $_POST['item_id'];
            }
            if (preg_match('/^(0|1)$/', $status) !== 1) {
                $err_msg[] = '存在しないステータスです';
            }
            if (preg_match('/^[1-9]{1}[0-9]*$/', $item_id) !== 1) {
                $err_msg[] = '不正な処理です';    
            }
            if ($status === '1') {
                $status = 0;
            } else if ($status === '0') {
                $status = 1;
            }
            if (count($err_msg) === 0) {
                $sql = 'UPDATE item_table 
                        SET status =' . $status . ', updated_date =' . "'" . $updated_date . "'" . ' WHERE id =' . $item_id;
            }
            if (mysqli_query($link, $sql) === TRUE) {
                $msg = 'ステータスの更新をしました';
            } else {
                $err_msg[] = 'SQL失敗:' . $sql;
            }
            
        } else if ($search_method === 'delete') {
            if (isset($_POST['item_id']) === TRUE) {
                $item_id = $_POST['item_id'];
            }
            if (preg_match('/^[1-9]{1}[0-9]*$/', $item_id) !== 1) {
                $err_msg[] = '不正な処理です';    
            }
            
            // 先にstock_item_tableから消す
            // item_tableは消せない stock_item_table=item_tableを元として（リレーションビューで）外部キーを設定した
            
            if (count($err_msg) === 0) {
                // $sql = 'DELETE FROM stock_item_table WHERE item_id =' . $item_id ';';
                // $sql .= 'DELETE FROM item_table WHERE id =' . $item_id . ';';
                mysqli_autocommit($link, false);// トランザクションスタート
                $sql = 'DELETE FROM stock_item_table WHERE item_id =' . $item_id;
                $result = mysqli_query($link, $sql);
                if ($result === TRUE) {
                    // $item_id = mysqli_insert_id($link);// 直近のクエリで使用した自動生成の ID を返す
                    $sql = 'DELETE FROM item_table WHERE id =' . $item_id;
                    $result = mysqli_query($link, $sql);
                    if ($result === FALSE) {
                        $err_msg[] = '商品情報削除失敗';
                    }
                    
                } else {
                    $err_msg[] = '在庫情報削除失敗';
                }
               
                if (count($err_msg) === 0) {
                    // 処理確定
                    mysqli_commit($link);
                    $msg = '商品を削除しました';
                } else {
                    // 処理取消
                    mysqli_rollback($link);
                }
            }
        }
        
    }
    $sql = 'SELECT item_table.id, name, price, img, status, stock_count
            FROM item_table 
            JOIN stock_item_table 
            ON item_table.id = stock_item_table.item_id;';
    
    $stock_list = get_as_array($link, $sql);
    if ($stock_list !== FALSE) {
        $stock_list = entity_assoc_array($stock_list);
    } else {
        $err_msg[] = 'SQL失敗' . $sql;
    }
    
}

mysqli_close($link);

include_once './include/view/view.php';