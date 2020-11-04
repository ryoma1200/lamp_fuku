<?php
require_once './conf/const.php';
require_once MODEL_PATH.'function_model.php';
require_once MODEL_PATH.'user_model.php';
require_once MODEL_PATH.'items_model.php';
require_once MODEL_PATH.'db_model.php';

$dbh = db_connect();        // データベース接続

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
$cart_data = get_cart_by_username($dbh, $userdata['username']);      // カートの商品を$cart_dataに格納
$token = get_post("token");

if (is_post() === true && is_valid_csrf_token($token) === true) {
    if (isset($_POST['type']) === true && $_POST['type'] === 'checkout') {
        
        //在庫があるかどうかの確認
        foreach($cart_data as $value) {                                          // カートの商品ひとつずつチェック
            if (exists_stock($dbh, $value['item_id'], $value['amount']) === false) {
                $err_msg[] = $value['name'].'の在庫がございませんでした。';
            }
        }
        
        // 購入処理
        if (is_no_error($err_msg) === true) {           
            $dbh->beginTransaction();
            try {
                foreach ($cart_data as $value) {
                    decrement_stock($dbh, $value['item_id'], $value['amount']);
                }
                clear_cart($dbh, $userdata['username']);                                     // カートの商品を消去
                $dbh->commit(); 
            } catch (Exception $e) {
                $dbh->rollback(); 
            }
        }
        
    } 
} 

$total_amount = calculate_total_amount($cart_data);
$total_price = calculate_total_price($cart_data);

include './view/finish_view.php';
?>