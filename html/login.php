<?php
require_once './conf/const.php';
require_once MODEL_PATH.'function_model.php';
require_once MODEL_PATH.'user_model.php';
require_once MODEL_PATH.'items_model.php';
require_once MODEL_PATH.'db_model.php';

$dbh = db_connect();

session_start();

if(is_logined() === true){     // ログインしていたときはhomeにリダイレクト
    redirect_to(HOME_URL);
}

/********************** ここからメイン ************************/

$username = '';
$passwd = '';
$userdata = array();
$err_msg = array();
$token = get_post("token");

if (is_post() === true && is_valid_csrf_token($token) === true){
    if (isset($_POST['type']) === true && $_POST['type'] === 'login') {
        $username = get_post('username');
        $passwd = get_post('passwd');    
        $userdata = get_userdata_by_username($dbh, $username);
        
        // アカウントの照合
        if (empty($username) || empty($passwd)) {
            $err_msg[] = 'ユーザー名またはパスワードが正しく入力されていません';
        } else {
            if ($passwd !== $userdata['password']) {
                $err_msg[] = 'ユーザー名またはパスワードが間違っています';
            }
        }
        
        // ログイン処理
        if (is_no_error($err_msg) === true) {  
            set_cookie_user_id($userdata['id']);
            redirect_to(HOME_URL);
            exit;
        }
            
    }
}

$token = get_csrf_token(); 
include './view/login_view.php';
?>