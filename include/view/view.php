<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>tool</title>
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
        caption {
            text-align: left;
        }
    </style>
</head>
<body>
    <section>
        <?php print $msg; ?>
        <?php foreach($err_msg as $value) { ?>
            <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
        <h1>CodeSHOP 管理ページ</h1>
        <form action="logout.php" method="post">
            <input type="submit" value="ログアウト">
        </form>
        <a href="user_management.php">ユーザー管理ページ</a>
        <h2>新規商品追加</h2>
        <form method="POST" enctype="multipart/form-data">
            <p>商品名:<input type="text" name="name"></p>
            <p>値段:<input type="number" name="price" max="10000" min="0">(税抜)</p>
            <!--<input type="number" name="ninzu" min="1" max="4">　※1人以上4人以下  <数字専用type　桁数ではなくなる　スマホで見るとテンキーが立ち上がる--> 
            <p>在庫数:<input type="text" name="stock_count"></p>
            <p>商品画像:<input type="file" name="new_img"></p>
            <p>公開ステータス:<select name="status">
                <option value="1">公開</option>
                <option value="0">非公開</option>
            </select></p>
            <input type="submit" value="■□■□■商品追加■□■□■">
            <input type="hidden" name="search_method" value="insert">
        </form>
        <h2>商品情報一覧・変更</h2>
        <table border="1">
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格（税抜）</th>
                <th>在庫数</th>
                <th>ステータス</th>
                <th>商品削除</th>
            </tr>
            <?php foreach ($stock_list as $value) { ?>
                <tr>
                    <td><img src="<?php print $value['img']; ?>" width="100px"></td>
                    <td><?php print $value['name']; ?></td>
                    <td><?php print $value['price']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="text" name="stock_count" value="<?php print $value['stock_count']; ?>">
                            <input type="submit" value="変更">
                            <input type="hidden" name="search_method" value="stock_update">
                            <input type="hidden" name="item_id" value="<?php print $value['id']; ?>">
                        </form>
                    </td>
                    <td>
                        <form method="POST">
                        <?php if ($value['status'] === '0') { ?>
                            <input type="submit" value="非公開→公開">
                        <?php } else { ?>
                            <input type="submit" value="公開→非公開">
                        <?php } ?>
                        <input type="hidden" name="search_method" value="status_update">
                        <input type="hidden" name="item_id" value="<?php print $value['id']; ?>">
                        <input type="hidden" name="status" value="<?php print $value['status']; ?>">
                        </form>
                    </td>
                    <td>
                        <form method="POST">
                        <input type="submit" value="商品削除">
                        <input type="hidden" name="search_method" value="delete">
                        <input type="hidden" name="item_id" value="<?php print $value['id']; ?>">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
</body>　　　　    
</html>　　　　    