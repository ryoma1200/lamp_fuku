<?php
require_once './conf/const.php';
require_once MODEL_PATH.'function_model.php';
require_once MODEL_PATH.'user_model.php';
require_once MODEL_PATH.'items_model.php';
require_once MODEL_PATH.'db_model.php';

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

$item_id = '';


if (is_get() === true){
    if (isset($_GET['id']) === true) {
        $item_id = (int)(get_get('id'));  
    }
}

$item_data = array();
$item_data = get_item_by_item_id($dbh, $item_id);

$token = get_csrf_token(); 
include VIEW_PATH.'item_view.php';
