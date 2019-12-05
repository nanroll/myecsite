<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>カートページ</title>
    <?php include_once './include/view/common/stylesheet.php'; ?>
    <style>
        h2 {
            color: #7b8eea;
            /*2.0倍*/
            line-height: 2;
            padding-left: 30px;
            border-bottom: solid 3px #97a3d8;
        }
        /*    border-top: solid 1px #000000;*/
        /*    padding-top: 20px;*/
        /*}*/
        .table td {
            vertical-align: middle; 
        }
        .table td p {
            margin-bottom: 0; 
        }
        .icon {
            display: flex;
        }
        .logout {
            margin: auto 0;
        }
        .font {
            color: red; 
        }
        #order {
            width: 300px;
            height: 40px;
        }
        #order_btn {
            clear: both;
            text-align: center;
            /*padding-bottom: 20px;*/
            padding-top: 20px;
        }
        
        #sum {
           text-align: right;
        }
        #return { 
            float: right;
            
        }
        
        @media(max-width: 767px) {
        th {
            font-size: 10px;
            text-align: center;
        }
        
        .alert {
            font-size: 14px;
        }
        
        h2 {
           font-size: 22px;
           padding-left: 10px;
           }
       
        .table {
           font-size: 12px;
        }
        .table .btn {
            padding: 3px;
        }
        .table img {
            width: 100px;
        }
        .table td {
            padding: 10px 0 ;
        }
        #price {
            padding-left: 15px;
        }
        
        #delete {
            text-align: center;
        }
        
        #sum {
            text-align: right;
        }
    
        footer a {
            font-size: 14px; 
        }
        
        /*なにか:after { content: "\A"; white-space: pre; }*//*brクラスの後ろに改行*/
        .br { 
             display: block; 
        }
    　　}
            
    }
    
        
    </style>
    <!--// jQuery CDN-->
    <script src="http://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        
// onclickとかにすれば、北海道をセレクトした際に直ぐに市区町村が出てくる　とかもできる
//http://black-flag.net/devel/jQueryMoreClickJson/jQueryMoreClickJson/（参考サイト）
// 「もっと見る」ボタンを押すと、サーバーにストックされている画像と文章を読み込んでくる
// 番号で画像と文章を管理している（データベース側）
// Ajaxの引数はオブジェクト

        // 2秒ごとに監視する（2秒ごとにfunctionを読んでいる）
        setInterval(get_stock, 2000);
        function get_stock() {
            $.ajax({
              // リクエストタイプ（省略するとGETがデフォルトになります。）
               type: "POST",
               // リクエスト送信先URLの設定 このphp=APIプログラム
               url: 'get_stock_data.php',
               // 非同期通信フラグ 書かない（デフォルトはtrue  FALSEにすると待っている間動かなくなる）
            //   data:{ = このデータ(データオブジェクトの中の)を送信しますよ
               data:{
               'user_id': <?php print $_SESSION['user_id']; ?>,
               // 'item_id': 1, ←カンマで区切ると複数POSTすることも出来る。（左がキー　右が値）
               }
               
            //   ↑単純な構造user_id:(SESSIONのuser_id)　というものだけだからJSON不要
            // 　　オブジェクト配列を送るときには　JSON.stringify(データオブジェクト)と書いて
            // 　　"{item_id:1, user_id: 5}" のように文字列にしてからでないと送れない 
               　// 　JSONのフィルターライブラリの中の stringifyを呼び出している
               　// 　　（Math.randomみたいな）　
               
               
            }).done(function(data, textStatus, jqXHR) {
              // 成功時の場合の処理
            //   dataの中に成功した値が入っている（PHPで作られたJSONで作られた（文字列にされた）配列データ　{}の形)
              console.log(data);
            //  JSON = オブジェクト配列
            //   JSON.parse(data) = phpから返ってきた配列データをJSで使える形に変換する
            // 　　（JSONのフィルターライブラリの中の parseを呼び出している）
               var res = JSON.parse(data);
            //   $.each = foreachと同じ指令（順番に回す）。　(res,←（配列）オブジェクト　function(keyと valueとして)
            //   console.log(res[5]);//   keyの在庫(value)を１個取るとき
               $.each(res,function(key,value) {
                //   console.log(key + ':' + value);
                //   教科書10-3を参照
                   $('#stock_' + key).html(value);
               });
            }).fail(function(jqXHR, textStatus, errorThrown) {
              // エラーの場合の処理
              $("div.result").text("エラーが発生しました。ステータス：" + jqXHR.status);
            });
        }
        
        // amountの中身をとる
        $(function() {
            $('.amount').keyup(function(e) {
                console.log(e.keyCode);
                if (e.keyCode === 38) {
                    alert('おすな');
                }
                
                // this.value　= 変更された値を取る 
                // console.log(this.value);
                var str=this.value;
                // var pattern=/^[0-9]+$/;
                // ↑正規表現は代入出来ない
                
                var check=str.match(/^[0-9]+$/);
                if (check === null && (e.keyCode !== 8 && e.keyCode !== 46)) {
                    alert('数量は数値で入れてください');
                }
            });
            // スクロールを検知して処理をする
            $(window).on('scroll', function(){
                // 一番下に来たというのを検知　（ページ高さ）―（window高さ）= ページの一番下
                // ページ全体の高さを代入 document.innerHeightする
            var dh = $(document).innerHeight();
            var wh = $(window).innerHeight(); // window高さを代入
            console.log(dh);
            })    
        })
        
        
        
        
        
    </script>
</head>
<body>
    <?php include_once './include/view/common/header.php'; ?>
    <!--<h1>ショッピングカート</h1>-->
    <!--<div class="icon">-->
    <!--    <a href="#"><img src="/PHP/cart_on.png" width=50px></a>-->
    <!--    <form class="logout" action="logout.php" method="post">-->
    <!--    <input type="submit" value="ログアウト">-->
    <!--    </form>-->
    <!--</div>-->
    <div class="container">
        <?php if (!empty($msg) || count($err_msg) >0) { ?>
            <?php if (!empty($msg)) {
                $alert_color = "success";
                
            }else if (count($err_msg) > 0) {
                $alert_color = "danger";
            
            }else {
                $alert_color = "info";
            } ?>
                 <div class="alert alert-<?php print $alert_color ?> alert-dismissiable fade show" role="alert">
                    <?php print $msg; ?>
                    <?php foreach($err_msg as $value) { ?>
                        <span><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php } ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                </div>
            <?php } ?>
        
        <h2>ショッピングカート一覧</h2>
            <?php if (count($cart_list) === 0) { ?>
                <p>商品がありません</p>
                <button onclick="location.href='home.php'" class="btn btn-secondary">商品一覧に戻る</button>
            <?php } else { ?>
        
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <!--text-nowrap = テキストを折り返さない-->
                    <th scope="col" class="text-nowrap">商品名</th>
                    <th scope="col" class="text-nowrap">価格(税込)</th>
                    <th scope="col" class="text-nowrap">現在在庫</th>
                    <th scope="col" class="text-nowrap">数量</th>
                    <th scope="col" class="text-nowrap">削除</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_list as $value) { ?>
                    <tr>
                        <td><img src="<?php print $value['img']; ?>" width="100px" class="br"><?php print $value['name']; ?></td>
                        <td id="price"><?php print $value['price']; ?> 円</td>
                        <!--$value['stock_count']が(<span>の中)置き換わる-->
                        <td>あと<br><span id="stock_<?php print $value['id'];?>"><?php print $value['stock_count']; ?></span>個です</td>
                        <td>
                            <form method="POST" class="form-inline">
                                <!--JS正規表現で数字以外をはじく-->
                                <input type="text" class="amount form-control" name="amount" value="<?php print $value['amount']; ?>">
                                <button type="submit" class="btn btn-secondary">変更</button>
                                <input type="hidden" name="search_method" value="amount_update">
                                <input type="hidden" name="item_id" value="<?php print $value['id']; ?>">
                            </form>    
                            <div class="d-block">
                                <?php if (isset($stock_alart[$value['id']]) === TRUE) { ?>
                                    <p class="font"><?php print $stock_alart[$value['id']]; ?></p>
                                <?php } ?>
                                <?php if((int)$value['amount'] > (int)$value['stock_count']) { ?>
                                    <p class="font">数量を変更してください</p>
                                <?php } ?>
                                <?php if((int)$value['status'] !== 1) { ?>
                                    <p class="font">取り扱い中止につき購入できません</p>
                                <?php } ?>
                                <?php if(isset($status_check) === TRUE) { ?>
                                    <?php if($status_check[$value['id']] === '0') { ?>
                                    <p class="font">取り扱い中止</p>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </td>
                        <td id="delete">
                            <form method="POST">
                            <button type="submit" class="btn btn-outline-danger delete">削除</button>
                            <input type="hidden" name="search_method" value="delete">
                            <input type="hidden" name="item_id" value="<?php print $value['id']; ?>">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    
        <?php /* ?>
            <!--<table border="1">-->
            <!--    <tr>-->
            <!--        <th>商品画像</th>-->
            <!--        <th>商品名</th>-->
            <!--        <th>価格（税込）</th>-->
            <!--        <th>現在在庫</th>-->
            <!--        <th>数量</th>-->
            <!--        <th>削除</th>-->
            <!--    </tr>-->
                <?php foreach ($cart_list as $value) { ?>
                    <tr>
                        <td><img src="<?php print $value['img']; ?>" width="100px"></td>
                        <td><?php print $value['name']; ?></td>
                        <td><?php print $value['price']; ?> 円</td>
                        <!--$value['stock_count']が(<span>の中)置き換わる-->
                        <td>あと<span id="stock_<?php print $value['id'];?>"><?php print $value['stock_count']; ?></span>個です</td>
                        <td>
                            <form method="POST">
                                <!--JS正規表現で数字以外をはじく-->
                                <input type="text" class="amount" name="amount" value="<?php print $value['amount']; ?>">
                                <input type="submit" value="変更">
                                <input type="hidden" name="search_method" value="amount_update">
                                <input type="hidden" name="item_id" value="<?php print $value['id']; ?>">
                                <?php if (isset($stock_alart[$value['id']]) === TRUE) { ?>
                                    <p class="font"><?php print $stock_alart[$value['id']]; ?></p>
                                <?php } ?>
                                <?php if((int)$value['amount'] > (int)$value['stock_count']) { ?>
                                    <p class="font">数量を変更してください</p>
                                <?php } ?>
                                <?php if((int)$value['status'] !== 1) { ?>
                                    <p class="font">取り扱い中止につき購入できません</p>
                                <?php } ?>
                                <?php if(isset($status_check) === TRUE) { ?>
                                    <?php if($status_check[$value['id']] === '0') { ?>
                                    <p class="font">取り扱い中止</p>
                                    <?php } ?>
                                <?php } ?>
                            </form>
                        </td>
                        <td>
                            <form method="POST">
                            <input type="submit" value="削除">
                            <input type="hidden" name="search_method" value="delete">
                            <input type="hidden" name="item_id" value="<?php print $value['id']; ?>">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <?php */ ?>
                
            <p id="sum">合計金額: <?php print $sum; ?>円（税込）</p>
            
            <button onclick="location.href='home.php'" class="btn btn-secondary" id="return">戻って買い物を続ける</button>
            <form method="POST" id="order_btn">
                <button id="order" type="submit" class="btn btn-primary">商品を決定し進む</button>
                <input type="hidden" name="search_method" value="buying">
            </form>
                
    
        <?php } ?>
    </div>
        <?php include_once './include/view/common/hooter.php'; ?>
        <?php include_once './include/view/common/js.php'; ?>
</body>
</html>