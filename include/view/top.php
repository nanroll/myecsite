<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ログイン</title>
    <?php include_once './include/view/common/stylesheet.php'; ?>
    
    <style>
        p {
            margin-top: 30px;
            margin-bottom: 1px;
        }
        .block {
            display: block;
            margin-bottom: 10px;
        }
        .small {
            font-size: 0.8em;
        }
        .form-row {
            margin-top: 20px;   
        }
        
        .btn {
            margin-top: 15px;
        }

    </style>
</head>
<body>
    <?php include_once './include/view/common/header.php'; ?>
   
    <div class="container">
        <h2>ログイン</h2>
        
        
        <?php if ($login_err_flag === TRUE) { ?>
            <div class="alert alert-danger alert-dismissiable fade show" role="alert">
                メールアドレス または パスワードが違います
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
            </div>
        <?php } ?>
        
        <form action="login.php" method="post">
            <div class="form-row">
                <label for="user_name" class="col-md-2 col-12 col-form-label">ユーザーネーム</label>
                <div class="col-md-5 col-12">
                    <input type="text" class="form-control" id="user_name" name="user_name" value="<?php print $user_name; ?>">
                </div>
            </div>
            <div class="form-row">
                <label for="passwd" class="col-md-2 col-12 col-form-label">パスワード</label>
                <div class="col-md-5 col-12">
                    <input type="password" class="block form-control" id="password" name="password" value="">
                </div>
            </div>
            <span class="block small"><input type="checkbox" name="cookie_check" value="checked" <?php print $cookie_check;?>>次回からユーザ名の入力を省略</span>
            
            <button type="submit" class="btn btn-primary">ログイン</button>
        </form>
        
        <p>当SHOPは会員制（無料）です。会員登録がお済でない方はこちら</p>
        <button type="button" onclick="location.href='user_signup.php'" class="btn btn-primary">新規会員登録</button>
        <!--<input type="button" onclick="location.href='user_signup.php'" value="新規会員登録">-->
    </div>
<?php include_once './include/view/common/js.php'; ?>
</body>
</html>