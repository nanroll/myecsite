<?php

// カートページ。同一ユーザーの「カートテーブル」の情報を一覧で表示させる場所

// このユーザーは既に使われていますとかはAjaxでデータベースを見ている


// 11月3日
// JSでやる課題　
// フォームの入力途中で注意するやつ（Jqueryでできる？）
// JSにも正規表現がある　Ajaxは不要です


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

// セッション開始
session_start();

login_check();


// データベース接続
$link = get_db_connect();

// カートページは。、　同一ユーザーの「カートテーブル」の情報を一覧で表示させる場所




close_db_connect($link);

 
// ログイン済みユーザのホームページ表示
include_once './include/view/customer_form.php';