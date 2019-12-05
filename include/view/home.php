<!DOCTYPE html>
<html lang="ja">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>商品一覧</title>
   <?php include_once './include/view/common/stylesheet.php'; ?>
   <?php include_once './include/view/common/js.php'; ?>
   <style>
   
/*   並べ変えのORDER BY 句ありました。*/
/*ORDER BY 指定したカラムの値を昇順降順で並べられる*/
/*16章の　16-7　DB操作の使用例で使われています。*/
/*SELECT カラム名 FROM テーブル名 ORDER BY 並び替えの基準となるカラム名 <ASC or DESC>*/
/*参照されて、データの並べ替えができるいいですね^^*/

/*bootstrap は　細かい　margin などのclass も用意されていますので、書きながらこういうclass を入れるだけでも整ってきます。*/
/*https://webnetamemo.com/coding/bootstrap4/201710065870*/
   
        /*li {　 Bootstrap前*/
        /*    text-align: center;*/
        /*    list-style: none;*/
        /*    float: left;*/
        /*    width: 120px;*/
        /*    height: 250px;*/
        /*}*/
        
        h2 {
            color: #7b8eea;
            /*2.0倍*/
            line-height: 2;
            padding-left: 30px;
            border-bottom: solid 3px #97a3d8;
        }
        
        p {
            display: block;
            color: red;
            text-align: center;
            font-weight: bold;
        }
        /*.icon {*/
        /*    display: flex;*/
        /*}*/
        .logout {
            margin: auto 0;
        }
    </style>
   
    <script>
        $(function() {
            // $('footer').hide();// ページが読み込まれた状態であらかじめ消しておく footerに設定した
        // スクロールを検知して処理をする
            $(window).on('scroll', function(){
                // 一番下に来たというのを検知　（ページ高さ）―（window高さ）= ページの一番下
                // ページ全体の高さを代入 document.innerHeightする
                var dh = $(document).innerHeight();
                var wh = $(window).innerHeight(); // window高さを代入
                var pe = dh - wh;
                // console.log($(window).scrollTop());
                // scrollTop = scroll現在位置を特定する
                if (pe <= $(window).scrollTop()) {
                    // ページを出したり消したり  .hide(Jqueryのメソッド　消すための) .show(出すため)
                    // PHPが先に読み込まれる　→　footerタグに対して
                    $('footer').fadeIn();
                } else {
                    $('footer').fadeOut();
                }
                
                
            }); 
        });
        
       
   </script>
   <!--ツールチップを使用するために入れる-->
        <script type="text/javascript">
            $(function () {
              $('[data-toggle="tooltip"]').tooltip();
            })
        </script>
</head>
<body>
    <?php include_once './include/view/common/header.php'; ?>
    <!--<h1>ようこそCodeSHOPへ</h1>-->
    <!--<div class="icon">-->
    <!--    <a href="cart.php"><img src="/PHP/25kadai_ec/cart_on.png" width=50px></a>-->
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
        
        
        <h2>商品一覧</h2>

       
        <div class="container mb-3">
            <form action="#" class="form-inline form-row" method="GET">
                
                <span class="col-auto ml-md-auto">商品の並べ替え: </span>
            <select name="order" id="job" class="form-control col-auto">
                <option value="low">金額の安い順</option>
                <option value="high">金額の高い順</option>
                <option value="new">入荷順</option>
            </select>
            <!--submit=フォームの1パーツとして定義されている。送信ボタンを押すとフォーム内の[input][textarea][select]の中身が飛ぶ-->
            <!--[select]の内容＝nameとvalueが結合したもの（サーバーはnameがついているvalueだけを受け取る）-->
            <input type="submit" value="実行" class="col-auto">
            </form>
        </div>
        
        <div class="container">
            <div class="row">
                <?php foreach($item_list as $value) { ?>
                    <div class="col-md-4 col-12 mb-4 text-center">
                        <style>
                            .item {
                                width: 200px;
                                height: 200px;
                            }
                            .item > img {
                                width: 100%;
                                height: auto;
                            } 
                            
                        </style>
                        
                        <div class="item m-auto" data-toggle="tooltip" data-html=true data-placement="top" 
                        title="<?php print $value['name'] ?>です～。<br>買ってね！" style=" 
                            background-image:url(<?php print $value['img']; ?>); 
                            background-size: contain;
                            background-position: center;
                            background-repeat: no-repeat">
                        </div>
                        <?php print $value['name']; ?><br>
                        <?php print $value['price'] . '円' ?><br>
                        <?php if ($value['stock_count'] <= 0) { ?>
                            <p>売り切れ</p> 
                        <?php } else { ?>
                            <form method="POST">
                                <button type="submit" class="btn btn-primary">カートに入れる</button>
                                <!--カートに入れたのがどのitem_idかを判別するためにhidden-->
                                <input type="hidden" name="item_id" value="<?php print $value['id']; ?>">
                            </form>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php include_once './include/view/common/hooter.php'; ?>
    
</body>
</html>