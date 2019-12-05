<?php

$err_msg = array();

require_once './include/conf/const.php';
require_once './include/model/function.php';


// セッション開始
session_start();

login_check('1');


// DB接続
$link = get_db_connect();


    $sql = 'SELECT user_table.id, user_name, created_date 
            FROM user_table'; 
    
    $user_list = get_as_array($link, $sql);        
    // 成功しなければエラーが入らない
    $user_list = entity_assoc_array($user_list);
    
mysqli_close($link);


include_once './include/view/user_management.php';