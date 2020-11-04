<?php
require_once './conf/const.php';
require_once MODEL_PATH.'function_model.php';
require_once MODEL_PATH.'user_model.php';
require_once MODEL_PATH.'db_model.php';

$dbh = db_connect();      // データベースに接続する

/********************** ここからログイン・ログアウトの確認 ************************/

session_start();

// ログイン、ログアウト
$userdata = '';
if(is_logined() === true){
    $userdata = get_userdata($dbh, $_SESSION['user_id']);
    if(pressed_logout_button() === true) {
        logout();  
    } 
}

/*************************************** ユーザ情報の変更処理 ********************************************/

$err_msg = array();  //エラーメッセージ
$comp_msg = '';      // 更新完了メッセージ
$token = get_post("token");

// ここからエラーチェック・データベース操作
if (is_post() && is_valid_csrf_token($token) === true) {
    if (isset($_POST['type']) === true) {
        
        // <1> ユーザ名の変更
        if ($_POST['type'] === 'up_name') {
            $new_username = get_post('username');    
            
            // バリデーション
            if (empty($new_username) === true) {      // ユーザー名が空かどうかの確認
                $err_msg[] = 'User_nameに文字を入力してください';    
            } else {
                if (is_alphanumeric($new_username) === false) {               // ユーザー名が半角英数字かの確認
                    $err_msg[] = 'User_nameには半角英数字を入力してください';     
                } else {
                    if(is_valid_length($new_username, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false) {    // ユーザー名が指定文字数内か確認
                        $err_msg[] = 'User_nameには6〜20字の半角英数を入力してください。';      
                    } 
                }      
            }
            if(is_no_error($err_msg) && is_unique_username($dbh, $new_username) === false) {      // ユーザー名が既存のものかどうか
                $err_msg[] = '既に存在するUser_nameです'; 
            }

            // テーブルの更新
            if (is_no_error($err_msg) === true) {
                update_username($dbh, $userdata['id'], $new_username); 
                $comp_msg = 'ユーザ名を変更しました';
            }

            
        // <2> パスワードの変更
        } else if ($_POST['type'] === 'new_passwd') {
            $passwd = get_post('current_passwd');    
            $new_passwd1 = get_post('new_passwd1'); 
            $new_passwd2 = get_post('new_passwd2'); 

            // バリデーション
            if ($passwd !== $userdata['password']) {                       // 現在のパスワードが正しいかどうかの確認
                $err_msg[] = '現在のパスワードが間違っています。';  
            } else { 
                if ($new_passwd1 !== $new_passwd2) {                       // 新しいパスワードが正しいかどうかの確認
                    $err_msg[] = '二つの新しいパスワードが一致しませんでした。'; 
                } 
            }
            if(is_alphanumeric($new_passwd1) === false) {                   // 半角英数かどうかの確認
                $err_msg[] = '新しいパスワードには半角英数字を入力してください';  
            } else {                          
                if (is_valid_length($new_passwd1, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false) {         // 指定文字数以内かの確認
                    $err_msg[] = '新しいパスワードには6〜20字の半角英数を入力してください。';
                }              
            }
      
            // テーブルの更新
            if (is_no_error($err_msg) === true) {
                update_passwd($dbh, $userdata['id'], $new_passwd1); 
                $comp_msg = 'パスワードを変更しました';
            }
        
        
        // <3> メールアドレスの変更
        } else if ($_POST['type'] === 'new_mail') {
            $new_mail = get_post('new_mail'); 
        
            // バリデーション
            if (empty($new_mail)) {
                $err_msg[] = 'メールアドレスには文字を入力してください';  
            } else {
                if (is_valid_mail($new_mail) === false) { 
                    $err_msg[] = '無効なメールアドレスです。'; 
                }
            }
        
            // テーブルの更新
            if (is_no_error($err_msg) === true) {                                       
                update_mail($dbh, $userdata['id'], $new_mail); 
                $comp_msg = 'メールアドレスを変更しました';
            }


        // <5> 身長の変更
        } else if ($_POST['type'] === 'new_height') {
            $new_height = (int)get_post('height'); 
            var_dump($new_height);
            // バリデーション
            if(is_valid_height($new_height) === false) {  
                $err_msg[] = '不正なアクセスです';
            }

            // テーブルの更新
            if (is_no_error($err_msg) === true) {         
                update_height($dbh, $userdata['id'], $new_height);    
                $comp_msg = '身長を変更しました';
            } 


        // <6> 靴のサイズの変更
        } else if ($_POST['type'] === 'new_shoe_size') {
            $new_shoe_size = (int)get_post('shoe_size');  

            // バリデーション
            if(is_valid_shoe_size($new_shoe_size ) === false) {  
                $err_msg[] = '不正なアクセスです';
            }

            // テーブルの更新
            if (is_no_error($err_msg) === true) {   
                update_shoe_size($dbh, $userdata['id'], $new_shoe_size);  
                $comp_msg = '靴のサイズを変更しました';
            }
            
        }
    }
}
$userdata = get_userdata($dbh, $_SESSION['user_id']);
$token = get_csrf_token();
include './view/user_page_view.php';
?>