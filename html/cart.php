<?php
require_once './conf/const.php';
require_once MODEL_PATH.'function_model.php';
require_once MODEL_PATH.'user_model.php';
require_once MODEL_PATH.'items_model.php';
require_once MODEL_PATH.'db_model.php';

// データベース処理用
$dbh = db_connect();

/********************** ここからログイン・ログアウトの確認 ************************/

session_start();

// ログイン、ログアウト
$userdata = '';
if(is_logined() === true){
    $userdata = get_userdata($dbh, $_SESSION['user_id']);
    if(pressed_logout_button() === true) {
        logout();                                               // ログアウト処理   共通部分は別ファイルもしくは関数
    } 
}

/********************** ここからメイン ************************/

$err_msg = array();
$token = get_post("token");

$cart_data = get_cart_by_username($dbh, $userdata['username']);        // cartテーブルのデータを$cart_dataに代入

if (is_post() === true && is_valid_csrf_token($token) === true){
    if (isset($_POST['type']) === true) {
        
        // ここからカート追加処理
        if ($_POST['type'] === 'add_to_cart') {                // カートにアイテムが追加されたときの処理
            
            $item_id = (int)get_post('item_id');             // postされたitem_idを$item_idに代入
            $amount = (int)get_post('amount');               // postされたstockを$amountに代入

            // 追加したい商品 + カートの商品 が10こを超えてしまった場合
            $duplicated_item_data = get_duplicated_item($cart_data, $item_id);
            if (empty($duplicated_item_data) === false) {                                 // 商品が重複していた場合の処理 
                if (($amount + $duplicated_item_data['amount']) > CART_MAX_AMOUNT) {         // 追加した商品 + カートの商品 が10こを超えてしまった場合           
                    $amount = CART_MAX_AMOUNT - $duplicated_item_data['amount'];             // 個数が最大値(10)になるように$amountを減算する。
                    $err_msg[] = 'カートに入れられるのは一つの商品につき１０点までです';
                }   
                db_update_item_amount($dbh, $userdata['username'], $item_id, $amount + $duplicated_item_data['amount']);         // 商品の個数を変更
            } else {                          // 商品が重複していなかった場合の処理 
                db_insert_item($dbh, $userdata['username'], $item_id, $amount);            // cartテーブルにデータを追加
            }
            
            
        // ここからカートの更新処理
        } else if ($_POST['type'] === 'update') {  

            // エンティティ化
            $item_id =  (int)get_post('item_id');   // 変更するitemのidを取得
            $amount = (int)get_post('amount');      // 新しい個数を取得
            
            // 商品の個数を変更する// 
            if (is_no_error($err_msg) === true) {   // エラーがない場合
                db_update_item_amount($dbh, $userdata['username'], $item_id, $amount);         // 商品の個数を変更
            } 
            

        // ここから商品の削除処理
        } else if ($_POST['type'] === 'delete') {
        
            $item_id =  (int)h($_POST['item_id']);         // エンティティ化
            db_delete_item($dbh, $userdata['username'], $item_id);               // 指定された商品を削除する
        }
        
    }
}

$cart_data = get_cart_by_username($dbh, $userdata['username']);       // cartテーブルのデータを$cart_dataに代入

$total_amount = calculate_total_amount($cart_data);
$total_price = calculate_total_price($cart_data);

$token = get_csrf_token();
include './view/cart_view.php'
?>