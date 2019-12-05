<?php
require_once './include/conf/const.php';
require_once './include/model/function.php';

// print_r($_POST);

$link = get_db_connect();


// AJAXからuser_id($user_idに代入)を飛ばして配列データをうけとり
$user_data = get_user_data($link, $_GET['user_id']);
// print_r($stock_data);

// 今回は一行しかないから回さない  ↓配列を作っている
// foreach($user_data as $value) {
//     $res[$value['item_id']] = $value['stock_count'];
// } 
// print_r($res);

// 配列の０番目を明示的に書く
print json_encode($user_data[0]);// JS用のオブジェクトが出来る。配列をそのまま送れないから {"5":"4",}の形になる　5番が4個

mysqli_close($link);
