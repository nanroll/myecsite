<?php

//お客さま情報　発送先入力ページ

$err_msg = array();
$msg = '';
$created_date = date('Y-m-d H:i:s');
$updated_date = date('Y-m-d H:i:s');

require_once './include/conf/const.php';
require_once './include/model/function.php';

$title = 'なぁーんのフォーム';

// セッション開始
session_start();

login_check();


// データベース接続は不要

// カートページは。、　同一ユーザーの「カートテーブル」の情報を一覧で表示させる場所

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['family_name']) === TRUE) {
                $family_name = trim($_POST['family_name']);
                $family_name = preg_replace('/^(　)*/', '', $family_name);// 左側の全角スペースのカット
                $family_name = preg_replace('/(　)*$/', '', $family_name);// 右側の全角スペースのカット
        }
        if (isset($_POST['first_name']) === TRUE) {
                $first_name = trim($_POST['first_name']);
                $first_name = preg_replace('/^(　)*/', '', $first_name);// 左側の全角スペースのカット
                $first_name = preg_replace('/(　)*$/', '', $first_name);// 右側の全角スペースのカット
        }    
        if (isset($_POST['post_code']) === TRUE) {
                $post_code = $_POST['post_code'];
        }    
        if (isset($_POST['address']) === TRUE) {
                $address = $_POST['address'];
        }    
        if (isset($_POST['phone']) === TRUE) {
                $phone = $_POST['phone'];
        }
        if (isset($_POST['mail']) === TRUE) {
                $mail = $_POST['mail'];
        }
        if (isset($_POST['payment']) === TRUE) {
                $payment = $_POST['payment'];
        }
        
        $send = $_POST['send'];
        
        if ($family_name === '') {
                $err_msg[] = '姓を入力してください';
            } else if (preg_match("/^(.*)[!-\/@<>;:=?~\^]+(.*)$/", $family_name) === 1) {
                $err_msg['family_name'] = '正しい形式で姓を入力してください';
        }    
        if ($first_name === '') {
                $err_msg[] = '名を入力してください';       
            } else if (preg_match("/^(.*)[!-\/@<>;:=?~\^]+(.*)$/", $first_name) === 1) {
                $err_msg[] = '正しい形式で名を入力してください';
        }            
        if ($post_code === '') {
                $err_msg[] = '郵便番号を入力してください';       
            } else if (preg_match('/^(.*)[\-]+(.*)$/', $post_code) === 1) {  // 文字に挟まれて-が1個以上入ったらダメ　頭に-が入ってもダメ
                $err_msg[] = '郵便番号はハイフンなしの半角数字で入力してください';
            } else if (preg_match("/^[0-9]{7}$/", $post_code) !== 1) {
                $err_msg[] = '郵便番号は半角数字7ケタで入力してください';
        }    
                
        if ($address === '') {
                $err_msg[] = '住所を入力してください';       
            } else if (preg_match("/^(.*)[!-\/@<>;:=?~\^]+(.*)$/", $address) === 1) {
                $err_msg[] = '正しい形式で住所を入力してください';
        }     
        if ($phone === '') {
                $err_msg[] = '電話番号を入力してください';       
            } else if (preg_match('/^(.*)[\-]+(.*)$/', $phone) === 1) {  // 文字に挟まれて-が1個以上入ったらダメ　頭に-が入ってもダメ
                $err_msg[] = '電話番号はハイフンなしの半角数字で入力してください';
            } else if (preg_match("/^[0-9]{10,11}$/", $phone) !== 1) {
                $err_msg[] = '正しい電話番号を入力してください';
        }    
        
        if ($payment === '') {
                $err_msg[] = 'お支払方法を選択してください';       
        }     
        
        

        if (count($err_msg) === 0){
            $_SESSION ['customer_info'] = array(
                'family_name'=>$family_name, 'first_name'=>$first_name, 'post_code'=>$post_code, 'address'=>$address, 'phone'=>$phone,
                'mail'=>$mail, 'payment'=>$payment, 'send'=>$send);
            header('Location: http://' . SERVERNAME . '/myecsite/form_check.php');
                exit;
        }
    } else if (isset($_SESSION['customer_info'])) {
        $customer_info = $_SESSION ['customer_info'];
        $family_name = $customer_info['family_name'];
        $first_name = $customer_info['first_name'];
        $post_code = $customer_info['post_code'];
        $address = $customer_info['address'];
        $phone = $customer_info['phone'];
        $mail = $customer_info['mail'];
        $payment = $customer_info['payment'];
        $send = $customer_info['send'];
    }
    



 
// ログイン済みの場合ホームページ表示
include_once './include/view/form.php';