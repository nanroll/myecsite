<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>購入完了ページ</title>
    <?php include_once './include/view/common/stylesheet.php'; ?>
    <style>
        .text {
            /*width: 50%;*/
            margin: 0 auto;
            text-align: center;
            margin-bottom: 50px;
        }
        h1 {
            margin: 0 auto;
            padding: 10px;
            font-size: 25px;
        }
        #ojigi {
            width: 100px;
            height: 100px;

        }
        h2 {
            border-top: solid 1px #000000;
            padding-top: 20px;
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
            padding-bottom: 20px;
            padding-top: 20px;
        }
        
        #sum {
           text-align: right;
        }
        #return { 
            float: right;
            
        }
        
        @media(max-width: 767px) {
        .text {
            margin-top: 30px;
        }    
        
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
           width: 80px;
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
            
    }
    
        
    </style>
    
    
    
    
    
    
</head>

<body>
    <?php include_once './include/view/common/header.php'; ?>
    
    
    
    
    <div class="container">
        <?php if (count($cart_list) === 0) {
            $err_msg[] = '商品がありません';
        } else { ?>
            <div class="text">
            <h1><?php print $msg; ?></h1>
            <img id="ojigi" src="ojigi.png"><br><br>
            </div>
        <?php } ?>
        
        <?php if (count($err_msg) >0) { ?>
            <div class="alert alert-danger alert-dismissiable fade show" role="alert">
                <?php foreach($err_msg as $value) { ?>
                    <span><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php } ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
            </div>
        <?php } else { ?>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <!--text-nowrap = テキストを折り返さない-->
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
            <p id="sum">合計金額: <?php print $sum; ?>円（税込）</p>
        <?php } ?>    
    <button onclick="location.href='home.php'" class="btn btn-secondary">商品一覧に戻る</button>
    </div>
    
        <?php include_once './include/view/common/js.php'; ?>
</body>
</html>