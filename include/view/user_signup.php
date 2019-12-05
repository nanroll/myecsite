<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>新規ユーザー登録</title>
    <?php include_once './include/view/common/stylesheet.php'; ?>
    <?php include_once './include/view/common/js.php'; ?>
    <style>
        .h4 {
            font-size: 1.2rem;
        }
        caption {
            text-align: left;
        }
        .smk-error-msg {
            display: block;
            color: red;
            position: static!important;
        }
        .alert {
            padding: 1.75rem 1.25rem;
        }
       
    </style>
    <!--smoke用-->
        <script type="text/javascript">
            $(function () {
                $('#submit').on('click', function(){
                    if (!$('form').smkValidate()) {
                        // smokeの検証ツールを使う ifの中に入れることで実行しTRUEが入る 引っかかるとfalseが返ってくる
                        return false;
                        // ＪＳでFALSEが入るとsubmitがキャンセルされる
                    }
                })
            })
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
                <?php if (count($err_msg) > 0) { ?>
                    <?php foreach($err_msg as $value) { ?>
                    <p class="d-inline"><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php } ?>
                <?php } ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
            </div>
        <?php } 
        if (empty($msg)) { ?>
            <p class="h4 mb-4">ユーザー名とパスワードは<br>
            それぞれ、半角英数字6文字以上20文字以内で入力してください</p>
        
            <form method="POST">
                <!--smokeはform-groupの中に対して有効になる-->
                <div class="form-row">
                    <div class="col-md-6 col-12 form-group">
                        <label for="user_name">ユーザー名 </label> 
                        <input type="text" class="form-control d-inline  w-75" name="user_name" id="user_name" maxlength="20" minlength="6" placeholder="6文字以上20文字以内で入力" value="<?php print empty($user_name) || isset($err_msg['user_name'])? '' : $user_name ?>" required><!--参考演算子 $family_nameが空なら '' : そうでないなら$family_name-->
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 col-12 form-group">
                        <label for="first_name">パスワード </label>
                        <input type="text" class="form-control d-inline w-75" name="password" id="password" maxlength="20" minlength="6" placeholder="6文字以上20文字以内で入力" value="<?php print empty($password)? '' : $password ?>" required>
                    </div>
                </div>
                <button class="btn btn-primary d-block" id="submit">ユーザー登録する</button>
            </form>
        <?php } ?>
        <p class="mt-5"><a href=<?php print 'http://' . SERVERNAME . '/myecsite/top.php' ?>>ログインページへ</a></p>
</body>    
</html>    