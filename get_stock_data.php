<?php

if($_SERVER['REQUEST_METHOD'] === 'POST') {
// 　チェックはチェックはしょうりゃくチェックは省略

require_once './include/conf/const.php';
require_once './include/model/function.php';

// print_r($_POST);
$user_id = get_post_data('user_id');


$link = get_db_connect();

// AJAXからuser_id($user_idに代入)を飛ばしてうけとり
$stock_data = get_stock_data($link, $user_id);
// print_r($stock_data);
foreach($stock_data as $value) {
    $res[$value['item_id']] = $value['stock_count'];
} 
// print_r($res);
print json_encode($res);// JS用のオブジェクトが出来る。配列をそのまま送れないから {"5":"4",}の形になる　5番が4個



mysqli_close($link);
}