<?php
require_once './conf/const.php';
require_once './conf/const_db.php';
require_once './SearchCondition.php';
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
    $token = get_post("token");
    if(pressed_logout_button() === true && is_valid_csrf_token($token) === true) {
        logout();                                               // ログアウト処理   共通部分は別ファイルもしくは関数
    } 
}

//メモ　($hash = password_hash($password, PASSWORD_DEFAULT);

/********************** ここからメイン ************************/

$search_err_msg = array();    // エラーメッセージを格納
$all_item_data = get_all_items($dbh);    // 商品データを取得する
$search_condition = new SearchCondition();   // 検索条件の初期化

// フォームがgetされたときの処理
if (is_get() === true){
    if (isset($_GET['type']) === TRUE) {
            
        //postのtypeがsearch_conditionの場合 
        if ($_GET['type'] === 'search_condition') {    

            $search_condition = new SearchCondition(
                get_get('keyword'), 
                (int)get_get('category'), 
                (int)get_get('size'),
                (int)get_get('price_min'), 
                (int)get_get('price_max'), 
                get_get('order'), 
                (int)get_get('size_fit')
            );

            // キーワード検索のエラーチェック
            if (is_valid_length($search_condition->keyword, KEYWORD_MIN_LENGTH, KEYWORD_MAX_LENGTH) === false) {
                $search_err_msg[] = 'キーワードが長すぎます。';
            }
            
            // データベース操作
            $sql = '';  
            if (is_no_error($search_err_msg) === true) {                      // エラーがなかった場合の処理
                $sql = generate_query($search_condition, $userdata);          // 絞り込み検索のクエリを作成する 
                $all_item_data = get_filtered_items($dbh, $sql);              // 絞り込んだデータを取得する。
            }
            
        }
    }
}
$token = get_csrf_token(); 
include VIEW_PATH.'index_view.php';
?>