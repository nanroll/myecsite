<!DOCTYPE html>
<html lang="ja">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>商品一覧</title>
   <?php include_once './include/view/common/stylesheet.php'; ?>
   <?php include_once './include/view/common/js.php'; ?>
   
   <style>
        h2 {
            color: #7b8eea;
            /*2.0倍*/
            line-height: 2;
            padding-left: 30px;
            border-bottom: solid 3px #97a3d8;
        }
        
        .alert {
            padding: 1.75rem 1.25rem;
        }
        
        p {
            display: block;
            color: red;
            /*text-align: center;*/
            font-weight: bold;
        }
        /*.icon {*/
        /*    display: flex;*/
        /*}*/
        .logout {
            margin: auto 0;
        }
        
        .smk-error-msg {
            display: block;
            color: red;
            position: static!important;
        }
        .modal-footer {
            justify-content: center;
        }
        .modal-footer>:not(:last-child) {
            margin: 0;
        }
        .modal-footer>:not(:first-child) {
            margin: 0;
        }
    </style>
</head>

<body>
    <?php include_once './include/view/common/header.php'; ?>
   
    <div class="container">
        
        <h2>お客様情報　入力確認画面</h2>

        <table class="table">
            <tr>
                <th scope="col">姓</th>
                    <td><?php print $_SESSION['customer_info']['family_name'] ?></td>
            </tr>    
            <tr>
                <th scope="col">名</th>
                    <td><?php print $_SESSION['customer_info']['first_name'] ?></td>
            </tr>
            <tr>
                <th scope="col">郵便番号</th>
                    <td><?php print $_SESSION['customer_info']['post_code'] ?></td>
            </tr>
            <tr>
                <th scope="col">住所</th>
                    <td><?php print $_SESSION['customer_info']['address'] ?></td>
            </tr>
            <tr>
                <th scope="col">電話番号</th>
                    <td><?php print $_SESSION['customer_info']['phone'] ?></td>
            </tr>
            <tr>
                <th scope="col">E-mailアドレス</th>
                    <td><?php print $_SESSION['customer_info']['mail'] ?></td>
            </tr>
            <tr>
                <th scope="col">お支払方法</th>
                    <?php if($_SESSION['customer_info']['payment'] === 'bank') {
                        $payment = '銀行振込';
                    } else if($_SESSION['customer_info']['payment'] === 'paypal') {
                        $payment = 'PayPal';
                    } else if($_SESSION['customer_info']['payment'] === 'card') {
                        $payment = 'クレジットカード';
                    } else {
                        $payment = '支払方法が未入力です';
                    } ?>
                    <td><?php print $payment ?></td>
            </tr>    
            <tr>
                <th scope="col">発送方法</th>
                    <?php if($_SESSION['customer_info']['send'] === 'normal') {
                        $send = '通常発送';
                    } else if($_SESSION['customer_info']['send'] === 'quick') {
                        $send = 'お急ぎ便';
                    } else {
                        $send = '指定なし（通常発送）';
                    } ?>
                    <td><?php print $send ?></td>
            </tr>
        </table>
        
            <button type="button" class="btn btn-primary" data-target="#my-modal1" data-toggle="modal">確認しました</button>
            <button type="button" class="btn btn-secondary d-block mt-3" onclick="location.href='./form.php'">戻って入力を訂正する</button>
            
            
        <!-- 確認ボタン押した後のモーダル -->
        <div class="modal fade" id="my-modal1">
            <!--モーダルの領域　ここでいう外枠-->
            <div class="modal-dialog">
                <!--modal-content 今からモーダルを書くよの合図　白い表示領域も作る-->
                <div class="modal-content">
                    <div class="modal-body">
                    <!--data-dismiss="modal" modalの閉じる機能を有効にする（閉じるボタン以外でもクリックしたら閉じる）-->
                    <!--モーダルの動き（機能）＝（枠の外か、閉じる）を押されたら閉じる-->
                    <!--imgタグの中、buttonタグの中　それぞれにdismissを付けることで閉じる機能を付与している-->

                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-nowrap">商品名</th>
                                    <th scope="col" class="text-nowrap">価格(税込)</th>
                                    <th scope="col" class="text-nowrap">数量</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_list as $value) { ?>
                                    <tr>
                                        <td><img src="<?php print $value['img']; ?>" width="100px"><?php print $value['name']; ?></td>
                                        <td id="price"><?php print $value['price']; ?> 円</td>
                                        <td id="amount"><?php print $value['amount']; ?> 個</td>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
            <!--modal-footer = bodyとfooterの境界に線が作られる-->
                    
                    <div class="modal-footer d-block">
                        <P class="text-center mb-3">注文を確定してもよろしいですか？</P>
                        <form method="POST" id="order_btn" class="text-center">
                            <button id="order" type="submit" class="btn btn-primary mb-3">注文を確定する</button>
                            <input type="hidden" name="search_method" value="buying">
                            <button type="button" class="btn btn-sm btn-secondary m-auto d-block" onclick="location.href='./cart.php'">カートに戻る</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>    

        

    <?php include_once './include/view/common/hooter.php'; ?>
    
</body>
</html>