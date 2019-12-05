<?php
// ログインチェック(何も引数で来ていないときは'0'とする)
function login_check($status = '0') {
    global $user_id;
    if (isset($_SESSION['user_id']) === TRUE) {
        if ($_SESSION['status'] === $status) {
            $user_id = $_SESSION['user_id'];
        } else {
            header('Location: http://' . SERVERNAME . '/myecsite/logout.php');
            exit;
        }
    } else {
         header('Location: http://' . SERVERNAME . '/myecsite/top.php');
         exit;
    }
}

// ヘッダー用ログインチェック　最近はisを頭につけるのが流行っている
function is_login($status = '0') {
    global $user_id;
    if (isset($_SESSION['user_id']) === TRUE) {
        if ($_SESSION['status'] === $status) {
            // $user_id = $_SESSION['user_id'];
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
         return FALSE;
    }
}


/**
* 【税込み価格にする計算(端数は切り上げ)】
* @return(str)税込み価格
*/
function price_before_tax($price) {
    return floor($price * TAX);
}
 
/**
* 【商品の値段を税込みに変換する(配列)】
* @$assoc_array 税抜き商品一覧配列データ
* @return(array) 税込み商品一覧配列データ
*/
function price_before_tax_assoc_array($assoc_array) {
    foreach ($assoc_array as $key => $value) {
        // 税込み価格へ変換(端数は切り上げ)
        $assoc_array[$key]['price'] = price_before_tax($assoc_array[$key]['price']);
    }
    return $assoc_array;
}


/**
* 【特殊文字をHTMLエンティティに変換する】
* @$str(str) 変換前文字
* @return(str) 変換後文字
*/
function entity_str($str) {
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
    // HTMLで判断されそうな文字を（<>とか&とか)　特殊文字に変換
    // ■　htmlspecialcharsの多重掛け htmlspacialchars('<h1>'); -> &lt;h1&gt;　に変換される htmlspacialchars(htmlspacialchars('<h1>')); -> htmlspacialchars('&lt;h1&gt;')　と同じ意味 -> &amp;;lt;h1&amp;gt;　に変換される
}
 
/**
* 【特殊文字をHTMLエンティティに変換する(2次元配列の値)】
* @$assoc_array 変換前配列
* @return(array) 変換後配列
*/
function entity_assoc_array($assoc_array) {
    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
            // 特殊文字をHTMLエンティティに変換
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    return $assoc_array;
}


//【DB接続(ハンドルを取得)】DBを使用するためのハンドル
function get_db_connect() {
    // コネクション取得（定数を使う）
    if (!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
        die('error: ' . mysqli_connect_error());
        // mysqli_connect_error= sqlのエラー。エラー内容を文字列にして返す
        // 通常エラー内容はログに残す（ログテーブルを作る！！！！）
    }
 
    // [文字コードセット]
    mysqli_set_charset($link, DB_CHARACTER_SET);

    return $link;
}
 

//【DBとのコネクション切断】
function close_db_connect($link) {
    mysqli_close($link);
}


// 【クエリを実行しその結果を配列で取得する】
// 1件のみ取り出す時は「type引数に1を入れる」
function get_as_array($link, $sql, $type = '') {
 
    // 返却用配列
    $data = array();
 
    // クエリ実行
    if ($result = mysqli_query($link, $sql)) {
        // 実行後の配列が1件(1行)以上あったら
        if (mysqli_num_rows($result) > 0) {
             if ($type === '') {
                // １件(1行)ずつ取り出す
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            } else if ($type === 1) {// 1行のみの場合
                $data = mysqli_fetch_assoc($result);
            }
        }
 
        // 結果セットを開放
        mysqli_free_result($result);
     } else {
        return false;
    }
     return $data;
}


// ORDER BY = 金額かつid順とかにもできる。
// 【商品情報取得ファンクション（税計算含む）（並べ替えも出来る）】home.phpで使用
// select_item_table($link, $statusの引数1=公開を表示 0=非公開のみ取得 -1なら両方表示, taxflgの引数TRUE=税込 FALSE=税別);
// $taxflg='' <=何も入力しなければ  「''」となる
// $order引数＝並べ替えのvalue  ORDER_BYは最後に記述
function select_item_table($link, $taxflg = FALSE, $status = 1, $order='') {
    
    // 3パターンある場合は、-1, 0 1 で管理する（数字型）
    $where_status = '';
    if ($status === 0 || $status === 1) {
        $where_status = ' WHERE status =' . $status;
    }
    if($order === 'high') {
        $order_by = ' ORDER BY price DESC, id ASC';
        // 金額順（降順）に並び、次にid順で並ぶ（昇順）
    } else if ($order === 'low') {
        $order_by = ' ORDER BY price ASC';
    } else if ($order === 'new') {
        $order_by = ' ORDER BY date DESC';
    } else {
        $order_by = '';
    }
    
    // created_date はasで別名にした
    $sql = 'SELECT item_table.id, name, price, img, stock_count, stock_item_table.created_date as date
            FROM item_table 
            JOIN stock_item_table 
            ON item_table.id = stock_item_table.item_id' . $where_status . $order_by;

    // flgのような2パターンの場合は論理型のほうが良い。
    $item_data = get_as_array($link, $sql);
    
    if ($taxflg === TRUE) {
        $item_data = price_before_tax_assoc_array($item_data);
    }
    if ($item_data !== FALSE) {
        $item_list = entity_assoc_array($item_data);
    } else {
        $err_msg[] = 'SQL失敗' . sql;
    }
    return $item_list;
}




// ★★

//【カートテーブルの中身を取得するファンクション】cart.php  buying.php  で使用
function select_cart_table($link, $user_id, $taxflg = FALSE) {
    global $err_msg;
    $sql = 'SELECT item_table.id, name, price, img, status, amount, stock_count FROM cart_table 
            JOIN item_table 
            ON cart_table.item_id = item_table.id 
            JOIN stock_item_table 
            ON cart_table.item_id = stock_item_table.item_id 
            WHERE user_id=' . $user_id;

    // クエリ実行とtaxflg税計算
    $item_data = get_as_array($link, $sql);
    if ($taxflg === TRUE) {
        $item_data = price_before_tax_assoc_array($item_data);
    }
    if ($item_data !== FALSE) {
        $item_list = entity_assoc_array($item_data);
    } else {
        $err_msg[] = 'SQL失敗' . sql;
    }
    return $item_list;
}


// home.phpで使用
// 【item_tableからステータス情報とstock_item_tableから在庫情報を取得し、チェックする】
function check_status_stock($link, $item_id) {
    $result = '';
    $sql = 'SELECT status, stock_count 
            FROM item_table 
            JOIN stock_item_table 
            ON item_table.id = stock_item_table.item_id WHERE item_table.id =' . $item_id; 

    $item_data = get_as_array($link, $sql);
    
    if ($item_data[0]['status'] === '0') {
        $result = '商品がありません';
    } else if ($item_data[0]['stock_count'] <= 0) {
        $result = '在庫切れです';
    }
    return $result;
}    


// cart.phpで使用
// 【数量変更ボタンを押されたその商品の在庫をチェックする】
function single_stock_check($link, $user_id, $item_id, $amount) {
    global $err_msg;
    $alart = '';
    // 数量変更ボタンを押されたitem_idに対応するstock_countを取得する
    $sql = 'SELECT stock_count FROM stock_item_table WHERE item_id=' . $item_id;
    $data = get_as_array($link, $sql);
    if ($data === FALSE) {
        $err_msg[] = 'SQL失敗:' . $sql;
    } else if (isset($data[0]['stock_count']) === TRUE ) {
        if ($data[0]['stock_count'] < $amount) {
            $err_msg[] = '在庫が足りません';
            $alart = '数量を変更してください';
        }
    } else {
        $err_msg[] = '不正な処理です';
    }

    return $alart;
}


// cart.phpで使用
// 【カートテーブルからitem_idを取得（user_idをもとに）】
// 【item_tableからステータス情報とstock_item_tableから在庫情報を取得し、チェックする】
function stock_check_0000test($link, $user_id) {
    global $err_msg;
    // $result = array();
    $flug = array();
    // 先ずはuser_idに対するitem_idを取得する
    $sql = 'SELECT item_id FROM cart_table WHERE user_id=' . $user_id;
    // cart_dataとして情報を代入する。
    $cart_data = get_as_array($link, $sql);
    // $cart_count = mysqli_num_rows($cart_data);

    foreach ($cart_data as $value) {
        // SQLを実行し、在庫が数量よりも小さいときは$stock_countに[0]['name']をいれる
        $sql = 'SELECT name 
                FROM stock_item_table 
                JOIN cart_table 
                ON cart_table.item_id = stock_item_table.item_id 
                JOIN item_table 
                ON cart_table.item_id = item_table.id 
                WHERE stock_item_table.stock_count < cart_table.amount AND status = 1 AND user_id=' . $user_id . ' AND cart_table.item_id =' . $value['item_id'];
        // sqlで取得できる＝在庫数が少ない配列レコード　stock_countに代入
        $stock_count = get_as_array($link, $sql);
        // $flugの中には0か1が入る（sqlの実行結果（文字列）のカウントの結果（数字、件数）
        $flug[$value['item_id']] = count($stock_count);

        // だから、$stock_countが0以外だとifを通る (===0だと-1とかでも通っちゃう）1件以上取れたらの意
        if (count($stock_count) > 0) {
            // ifを通ったということは、在庫がない商品がある
            $err_msg[] = $stock_count[0]['name'] . 'の在庫がありません';
        } 
    }
    // 両方の配列が入った配列がリターンされる
    return array($result, $flug);
}    


// cart.phpで使用
// 【item_tableからステータス情報とstock_item_tableから在庫情報を取得し、チェックする】
function check_cart_item($link, $user_id) {
    global $err_msg;// コントローラーの変数を使うので、global化しておく
    // user_idに対するname と statusを取得する
    $sql = 'SELECT name, status, amount, stock_count 
            FROM cart_table 
            JOIN item_table 
            ON cart_table.item_id = item_table.id
            JOIN stock_item_table
            ON cart_table.item_id = stock_item_table.item_id
            WHERE user_id=' . $user_id;
    // cart_dataとしてsql取得情報を代入する。
    $cart_data = get_as_array($link, $sql);
    // cart_table内を隅々までチェックする
    foreach ($cart_data as $value) {
        $name   = $value['name'];
        $status = (int)$value['status'];
        $amount = (int)$value['amount'];
        $stock  = (int)$value['stock_count'];
        
        if ($amount > $stock) {
            $err_msg[] = $name . ' の在庫がありません';
        }
        if ($status !== 1) {
            $err_msg[] = $name . ' の取り扱いは中止しました';
        }
    }
}


// header.phpで使用
// 【カートに放り込まれている商品数をチェックする】
function check_cart_count($link, $user_id) {
    $sql = 'SELECT id 
            FROM cart_table 
            WHERE user_id=' . $user_id;
    $cart_data = get_as_array($link, $sql);

    return count($cart_data);
}



// 【statusの状態をチェックする】
function status_check($link, $user_id) {
    $result = array();
    $flug = array();
    $sql = 'SELECT item_id FROM cart_table WHERE user_id=' . $user_id;
    $cart_data = get_as_array($link, $sql);
    foreach ($cart_data as $value) {
        $sql = 'SELECT status
                FROM item_table 
                WHERE item_table.id =' . $value['item_id'];
    $status_count = get_as_array($link, $sql);
    // $resultの中には0か1が入る(sqlの実行結果（文字列)）
    $result[$value['item_id']] = $status_count[0]['status'];
    }
    return $result;
}



// ★★☆
// 在庫情報を取得するファンクション（Ajax用 cartで表示させるため）
function get_stock_data($link, $user_id){
$sql = 'SELECT stock_item_table.item_id, stock_count 
        FROM stock_item_table 
        JOIN cart_table 
        ON stock_item_table.item_id = cart_table.item_id 
        WHERE user_id =' . $user_id;
    
    $stock_data = get_as_array($link, $sql);
    if ($stock_data !== FALSE) {
        $stock_data = entity_assoc_array($stock_data);
    } 
    return $stock_data;
}

// ★★
// お客登録情報を取得するファンクション（Ajax用 formで登録情報を表示させるため）
function get_user_data($link, $user_id){
$sql = 'SELECT family_name, first_name, post_code, address, phone, mail 
        FROM user_table 
        WHERE id =' . $user_id;
    
    $user_data = get_as_array($link, $sql);
    if ($user_data !== FALSE) {
        $user_data = entity_assoc_array($user_data);
    }

    return $user_data;
}



// buying.phpで使用  不要となりました（user_tableには既にidとかデータが入っているから）
// 【お客さま情報をチェックする（新規書き込みINSERTかどうか）】 
// function is_user_info($link, $user_id) {
//     global $err_msg;
//     $sql = 'SELECT first_name FROM user_table WHERE user_id=' . $user_id;
//     $data = get_as_array($link, $sql);
//     if ($data !== FALSE) {
//         return !empty($data);
//         // emptyの結果 ある=TRUE ない=FALSE
//     } else {
//         $err_msg[] = 'SQL失敗:' . $sql;
//     }
// }


// ☆buying.phpで使用j☆
// 【お客さま情報をチェックする（変更追記UPDATEかどうか）】
function is_update_user_info($link, $user_id, $family_name, $first_name, $post_code, $address, $phone, $mail) {
    global $err_msg;
    $sql = 'SELECT first_name FROM user_table WHERE id=' . $user_id . 
    ' AND family_name=' . "'" .$family_name . "'" .
    ' AND first_name=' . "'" .$first_name . "'" .
    ' AND post_code=' . $post_code .
    ' AND address=' . "'" .$address . "'" .
    ' AND phone=' . $phone .
    ' AND mail=' . "'" .$mail. "'" ;
    $data = get_as_array($link, $sql);
    if ($data !== FALSE) {
        return empty($data);
        // emptyの結果 　ない（とれない）=TRUE 　とれた（updateの必要ない）=FALSE
    } else {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
}




























/**
* insertを実行する
*
* @param obj $link DBハンドル
* @param str SQL文
* @return bool
*/
function insert_db($link, $sql) {
   // クエリを実行する
   if (mysqli_query($link, $sql) === TRUE) {
       return TRUE;
   } else {
       return FALSE;
   }
}
/**
* 新規商品を追加する
*
* @param obj $link DBハンドル
* @param str $goods_name 商品名
* @param int $price 価格
* @return bool
*/
function insert_goods_table($link, $goods_name, $price) {
   // SQL生成
   $sql = 'INSERT INTO goods_table(goods_name, price) VALUES(\'' . $goods_name . '\', ' . $price . ')';
   // クエリ実行
   return insert_db($link, $sql);
}
/**
* リクエストメソッドを取得
* @return str GET/POST/PUTなど
*/
function get_request_method() {
   return $_SERVER['REQUEST_METHOD'];
}
/**
* POSTデータを取得
* @param str $key 配列キー
* @return str POST値
*/
function get_post_data($key) {
   $str = '';
   if (isset($_POST[$key]) === TRUE) {
       $str = $_POST[$key];
   }
   return $str;
}