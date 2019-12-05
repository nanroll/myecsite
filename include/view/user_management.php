<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー管理</title>
    <style>
        h1 {
            margin: 0;
            padding: 10px;
            font-size: 30px;
        }
        h2 {
            border-top: solid 1px #000000;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <?php foreach($err_msg as $value) { ?>
        <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>
    <h1>CodeSHOP 管理ページ</h1>
    <form action="logout.php" method="post">
        <input type="submit" value="ログアウト">
    </form>
    <a href="tool.php">商品管理ページ</a>
    <h2>登録ユーザー情報一覧</h2>
    <table border=1px>
        <tr>
            <th>ユーザー名</th>
            <th>登録日</th>
        </tr>
        <?php foreach($user_list as $value) { ?>
        <tr>
            <td><?php print $value['user_name']; ?></td>
            <td><?php print $value['created_date']; ?></td>
        </tr>
        <?php } ?>
  </table>
</body>
</html>