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
        
        @media(min-width: 768px) {
            .family_name {
                margin-left: -50px;
            }
            .first_name {
                margin-left: -50px;
            }
            .smk-error-msg {
                display: block;
                color: red;
                position: static!important;
            }
        }
        
        @media(max-width: 767px) {
            h2 {
                padding-left: 0;
                text-align: center;
                font-size: 1.38rem;
            }
            label {
                display: block;
            }
            .name {
                display: block;
                padding-left: 5px;
                margin-right: -5px;
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
        }
    </style>
   
    <!--ツールチップを使用するために入れる-->
        <script type="text/javascript">
            $(function () {
              $('[data-toggle="tooltip"]').tooltip();
              $('#submit').on('click', function(){
                    if (!$('form').smkValidate()) {
                        // smokeの検証ツールを使う ifの中に入れることで実行しTRUEが入る 引っかかるとfalseが返ってくる
                        return false;
                        // ＪＳでFALSEが入るとsubmitがキャンセルされる
                    }
                    
              })
            
            // フォームのフォーカスが外れたら＝blur
            // $('#post_code')でもよい
                $('input[name="post_code"]').on('blur', function(){
                    var post_code = $(this).val();
                    // $(this).val(); = blurのターゲット先のDOMのこと('input[name="post_code"]')、その値
                    get_post_code(post_code);// 取ってきたpost_codeを渡す
                })
            })
        
        
        
        // Ajaxファンクションで郵便番号検索
        function get_post_code(post_code) {
            $.ajax({
              // リクエストタイプ（省略するとGETがデフォルトになります。）
               type: "GET",
               // リクエスト送信先URLの設定 郵便番号のAPIプログラム
               url: 'https://api.zipaddress.net/',
               // 非同期通信フラグ 書かない（デフォルトはtrue  FALSEにすると待っている間動かなくなる）
            //   data:{ = このデータ(データオブジェクトの中の)を送信しますよ(リクエストを)
               data:{
               'zipcode' : post_code,
               // 'item_id': 1, ←カンマで区切ると複数POSTすることも出来る。（左がキー　右が値）
               }
            // 今回はphpを使わないので、↑functionで、外部APIからデータをもらう
            
            // 実行結果   res = 結果のJSONが返ってくる(codeとdataという名前の配列)
            }).done(function(res, textStatus, jqXHR) {
                 console.log(res);
                //  取ってきたデータ(連想配列)をテキストボックスに入れる
                // JSONを使用するときは.で繋いで中身が取れる
                // HTMLとして成功パターンは200番
                if (res.code ==200) {
                    // inputのvalueに値を当てはめる
                    $('input[name="address"]').val(res.data.fullAddress);
                } else {
                    alert('郵便番号が間違っています')
                }　
            });  
        }
        
        // 登録反映ボタン押すと登録住所が入るAjax
        function get_user_data(user_id) {
            console.log(user_id);
            $.ajax({
              // リクエストタイプ（省略するとGETがデフォルトになります。）
               type: "GET",
               // リクエスト送信先URLの設定 郵便番号のAPIプログラム
               url: 'get_user_data.php',
               // 非同期通信フラグ 書かない（デフォルトはtrue  FALSEにすると待っている間動かなくなる）
                
                // これを書くと下でJSON.parseする必要がなくなるよ
                dataType: 'json',
            
            //   data:{ = このデータ(データオブジェクトの中の)を送信しますよ(リクエストを)
               data:{
               'user_id' : user_id 
               // 'user_id': 1, ←カンマで区切ると複数POSTすることも出来る。（左がキー　右が値）
               }
            
            
            // 実行結果  user_data = 結果のJSONが返ってくる(codeとdataという名前の配列)
            }).done(function(user_data, textStatus, jqXHR) {
                 console.log(user_data);//  確認用
                
                //  取ってきたデータ(連想配列)をテキストボックスに入れる
                // JSONを使用するときは.で繋いで中身が取れる
                // user_data.lemgth = 配列の数をチェックする
                
                // var user_data = JSON.parse(user_data);
                
                // user_data.length = 配列の数をチェックする
                if (user_data !== 0) {
                    $('input[name="family_name"]').val(user_data.family_name);
                    $('input[name="first_name"]').val(user_data.first_name);
                    $('input[name="post_code"]').val(user_data.post_code);
                    $('input[name="address"]').val(user_data.address);
                    $('input[name="phone"]').val(user_data.phone);
                    $('input[name="mail"]').val(user_data.mail);
                } 
            });  
        }
        
        </script>
</head>
<body>
    <?php include_once './include/view/common/header.php'; ?>
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
                    <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php } ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
            </div>
        <?php } ?>
        
        
        <div class="clearfix">
            <button onclick="get_user_data(<?php print $_SESSION['user_id'] ?>)" class="btn btn-primary float-right d-md-block d-none mt-3">前回の履歴を反映する</button>
            <h2>お客さま情報（発送先）入力</h2>
            <div class="text-right">
                <button onclick="get_user_data(<?php print $_SESSION['user_id'] ?>)" class="btn btn-primary d-md-none text-right mb-3">前回の履歴を反映する</button>
            </div>
        </div>

        <form action="form.php" method="POST">
            <!--smokeはform-groupの中に対して有効になる-->
            <div class="form-row form-group">
                <label for="name" class="col-form-label col-md-2 col-12" id="name">お名前 </label>
                
                <label for="family_name" class="col-form-label col-md-1 col-1 mb-2 mb-md-0">姓 </label> 
                <input type="text" class="family_name tname form-control d-inline col-md-4 col-11" name="family_name" id="family_name" value="<?php print empty($family_name) || isset($err_msg['family_name'])? '' : $family_name ?>" required><!--参考演算子 $family_nameが空なら '' : そうでないなら$family_name-->
                
                
                <label for="first_name" class="col-form-label col-md-1 col-1 offset-md-1">名 </label>
                <input type="text" class="first_name name form-control d-inline col-md-4 col-11" name="first_name" id="first_name" value="<?php print empty($first_name)? '' : $first_name ?>" required>
                
            </div>
            
            
            <!--半角数字で入力（ハイフンを除く）-->
            <div class="form-row form-group">
                <label for="post_code" class="col-form-label col-md-2 col-12">郵便番号 </label> 
                <input type="number" class="form-control d-inline col-md-2 col-12" name="post_code" id="post_code" value="<?php print empty($post_code)? '' : $post_code ?>" required>
            </div>
            
            <div class="form-row form-group">
                    <label for="address" class="col-form-label col-md-2 col-12">住所</label> 
                    <input type="text" class="form-control d-inline col-md-10 col-12" name="address" id="address" value="<?php print empty($address)? '' : $address ?>" required>
            </div>
            
            
            <!--半角数字で入力（ハイフンを除く）-->
            <div class="form-row form-group">
                <label for="phone" class="col-form-label col-md-2 col-12">電話番号</label> 
                <input type="tell" class="form-control d-inline col-md-3 col-12" name="phone" id="phone" value="<?php print empty($phone)? '' : $phone ?>">
            </div>
            
            <div class="form-row form-group">
                <label for="mail" class="col-form-label col-md-2 col-12">E-mailアドレス</label> 
                <input type="email" class="form-control d-inline col-md-8 col-12" name="mail" id="mail" value="<?php print empty($mail)? '' : $mail ?>">
            </div>
            
            
            <div class="form-row form-group">
                <label class="col-form-label col-md-2 col-12">お支払方法選択</label>
                <select name="payment" class="form-control d-inline col-md-4 col-12">
                    <option value="" >お支払方法を選択してください</option>
                    <option value="bank" <?php print (isset($payment) && ($payment === 'bank'))? 'selected' : '' ?>>銀行振込</option><!--$paymentがあり、'bank'　ならば　selected  そうでなければ''-->
                    <option value="paypal" <?php print (isset($payment) && ($payment === 'paypal'))? 'selected': '' ?>>PayPal</option>
                    <option value="card" <?php print (isset($payment) && ($payment === 'card'))? 'selected': '' ?>>クレジットカード</option>
                </select>
            </div>
            
            <div class="form-row form-group">
                <label class="col-form-label col-md-2 col-12">発送方法選択</label>
                <div class="form-check form-check-inline mr-4" >
                    <label for="normal" class="form-check-label"><input type="radio" class="form-check-input" name="send" value="normal" id=normal checked>通常発送</label>
                </div>
                <div class="form-check form-check-inline">
                    <label for="quick" class="form-check-label"><input type="radio" class="form-check-input" name="send" value="quick" id="quick" <?php print (isset($send) && ($send === 'quick'))? 'checked': '' ?>>お急ぎ便</label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" id="submit">確認画面に進む</button>
            <!--<input type="hidden" name="search_method" value="amount_update">-->
            <!--<input type="hidden" name="item_id" value="">-->
        </form>    
        

    <?php include_once './include/view/common/hooter.php'; ?>
    
</body>
</html>