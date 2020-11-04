<?php
require_once './conf/const.php';
require_once MODEL_PATH.'db_model.php';
require_once MODEL_PATH.'sort_model.php';

// 絞り込み後の商品データを取得する
function get_filtered_items($dbh, $sql) {
    try {
        $stmt = $dbh->prepare($sql);
        return fetch_all_query($db, $stmt);
        
    } catch (PDOException $e) {
        throw $e;
    }
} 

// 全ての商品を取得する。
function get_all_items($dbh) {
    try {
        $sql = 'SELECT * FROM fukuya_items';
                
        $stmt = $dbh->prepare($sql);
        return fetch_all_query($dbh, $stmt);
    } catch (PDOException $e) {
        throw $e;
    }
} 

// 指定したidの商品データを取得する  item.phpで
function get_item_by_item_id($dbh, $item_id) {
    try {
        $sql = 'SELECT name, price, category, size, img, 
                stock, status, column_name, comment 
                FROM fukuya_items
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
        return fetch_query($dbh, $stmt);
        
    } catch (PDOException $e) {
        throw $e;
    }
} 

/**************** ここからさきこぴぺしただけ、要チェック ****************/

// 買い物カゴに追加する
function db_insert_item($dbh, $username, $item_id, $stock) {
    try {
        $sql = 'INSERT INTO fukuya_cart
                (username, item_id, amount, createdate, updatedate)
                VALUES (?, ?, ?, now(), now())';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->bindValue(3, $stock, PDO::PARAM_INT);
        return execute_query($dbh, $stmt);
        
    } catch (PDOException $e) {
        throw $e;
    }
}


// 商品を追加する




// カートの指定した商品の個数を変更する
function db_update_item_amount($dbh, $username, $item_id, $up_amount) {
    try {
        $sql = 'UPDATE fukuya_cart 
                SET amount = ?, updatedate = now()
                WHERE username = ? && item_id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $up_amount, PDO::PARAM_INT);
        $stmt->bindValue(2, $username, PDO::PARAM_STR);
        $stmt->bindValue(3, $item_id, PDO::PARAM_INT);
        return execute_query($dbh, $stmt);
        
    } catch (PDOException $e) {
        throw $e;
    }
}


// カートの商品データを読み込む
function get_cart_by_username($dbh, $username) {
    try {
        $sql = 'SELECT fukuya_cart.id, fukuya_cart.username, fukuya_cart.item_id, fukuya_cart.amount,
                fukuya_items.name, fukuya_items.price, fukuya_items.img, fukuya_items.stock
                FROM fukuya_cart
                LEFT OUTER JOIN fukuya_items
                ON fukuya_cart.item_id = fukuya_items.id
                WHERE fukuya_cart.username = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        return fetch_all_query($dbh, $stmt);
        
    } catch (PDOException $e) {
        throw $e;
    }
}

// 指定した商品を削除する
function db_delete_item($dbh, $username, $item_id) {
    try {
        $sql = 'DELETE FROM fukuya_cart 
                WHERE username = ? && item_id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        return execute_query($dbh, $stmt);
        
    } catch (PDOException $e) {
        throw $e;
    }
}

//カート
function get_duplicated_item($cart_data, $item_id) { 
    foreach ($cart_data as $value) {
        if ($value['item_id'] === $item_id) {          // postされたitem_idがcartテーブル内に存在する場合の処理  
            return $value;            // 重複する商品データを$duplicated_item_dataの配列に代入
        } 
    }
    return '';
}

function calculate_total_amount($cart_data){
    $total_amount = 0;
    if (empty($cart_data) === false) {
        foreach ($cart_data as $value) {
            $total_amount += $value['amount'];
        }
        return $total_amount;
    } else {
        return 0;
    }
}

function calculate_total_price($cart_data){
    $total_price = 0; 
    if (empty($cart_data) === false) {
        foreach ($cart_data as $value) {
            $total_price += $value['price'] * $value['amount'];
        }
        return $total_price;
    } else {
        return 0;
    }
}


/***************** カート処理 *******************/

// 指定した商品データを取得する
function db_read_item($dbh, $item_id) {
    try {
        $sql = 'SELECT name, price, category, size, img, 
                stock, status, column_name, comment 
                FROM fukuya_items
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetch();
        return $rows;
    } catch (PDOException $e) {
        throw $e;
    }
} 

// 指定した商品の在庫を変更する
function decrement_stock($dbh, $item_id, $amount) {
    try {
        $sql = 'UPDATE fukuya_items 
                SET stock = stock - ?, updatedate = now()
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $amount, PDO::PARAM_INT);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        execute_query($dbh, $stmt);
        
    } catch (PDOException $e) {
        throw $e;
    }
}

// カートテーブルのデータを全て削除する
function clear_cart($dbh, $username) {
    try {
        $sql = 'DELETE FROM fukuya_cart WHERE username = ?';
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        execute_query($dbh, $stmt);
    } catch (PDOException $e) {
        throw $e;
    }
}

// 在庫が存在するかを確認する
function exists_stock($dbh, $item_id, $amount){
    $cart_item_data = db_read_item($dbh, $item_id); 
    if ($amount <= $cart_item_data['stock']) {                  
        return true;
    }
    return false;
}

function get_extension($filename){
    return pathinfo($filename, PATHINFO_EXTENSION);    
}

function is_valid_extension($extension){
    if (preg_match(EXTENSION, $extension) === 1) {                                 // $extensionが正規表現の条件を満たす場合の処
        return true;
    } else {
        return false;
    }
}

function make_random_filename($extension) {
    return sha1(uniqid(mt_rand(), true)).'.'.$extension; 
}

function insert_item($dbh, $itemdata) {
    $id = '';
    try {
        $sql = 'INSERT INTO fukuya_items
                (name, price, category, size, img, stock, status, column_name, comment, createdate, updatedate)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, now(), now())';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $itemdata->item_name, PDO::PARAM_STR);
        $stmt->bindValue(2, $itemdata->price,PDO::PARAM_INT);
        $stmt->bindValue(3, $itemdata->category, PDO::PARAM_INT);
        $stmt->bindValue(4, $itemdata->size, PDO::PARAM_INT);
        $stmt->bindValue(5, $itemdata->img_file, PDO::PARAM_STR);
        $stmt->bindValue(6, $itemdata->stock, PDO::PARAM_INT);
        $stmt->bindValue(7, $itemdata->status, PDO::PARAM_INT);
        $stmt->bindValue(8, $itemdata->column_name, PDO::PARAM_STR);
        $stmt->bindValue(9, $itemdata->comment, PDO::PARAM_STR);
        $stmt->execute();
        
    } catch (PDOException $e) {
        throw $e;
    }
}

function update_itemdata($dbh, $item_id, $data) {
    try {
        $sql = 'UPDATE fukuya_items
                SET name = ? , price = ?, category = ?, size = ?, img = ?, 
                stock = ?, status = ?, comment = ?, column_name = ?, updatedate = now()
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $data->item_name, PDO::PARAM_STR);
        $stmt->bindValue(2, $data->price,PDO::PARAM_INT);
        $stmt->bindValue(3, $data->category, PDO::PARAM_INT);
        $stmt->bindValue(4, $data->size, PDO::PARAM_INT);
        $stmt->bindValue(5, $data->img_file, PDO::PARAM_STR);
        $stmt->bindValue(6, $data->stock, PDO::PARAM_INT);
        $stmt->bindValue(7, $data->status, PDO::PARAM_INT);
        $stmt->bindValue(8, $data->comment, PDO::PARAM_STR);
        $stmt->bindValue(9, $data->column_name, PDO::PARAM_STR);
        $stmt->bindValue(10, $item_id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        throw $e;
    }
}

function delete_itemdata($dbh, $item_id) {
    try {
        $sql = 'DELETE FROM fukuya_items WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        throw $e;
    }
}
