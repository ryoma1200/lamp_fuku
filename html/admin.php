<?php
require_once './conf/const.php';
require_once './UpdateUser.php';
require_once './NewUser.php';
require_once MODEL_PATH.'function_model.php';
require_once MODEL_PATH.'user_model.php';
require_once MODEL_PATH.'db_model.php';

// ここから各変数の定義と初期化

$new_userdata = new NewUser();   // ユーザー情報の初期化
$update_userdata = new NewUser();   

// エラーチェック用 & 完了メッセージ
$err_msg = array();
$up_err_msg = array();
$comp_msg = '';
$up_comp_msg = '';

$dbh = db_connect();      

// フォームがpostされたときの処理
if (is_post() === true && isset($_POST['type']) === true ) {

    //postのtypeがaddの場合 
    if ($_POST['type'] === 'add') {

        $new_userdata = new NewUser(
            get_post('name'), 
            get_post('passwd'),
            '',
            get_post('mail'), 
            (int)get_post('sex'),
            '', 
            (int)get_post('birthdate_year'),
            (int)get_post('birthdate_month'),
            (int)get_post('birthdate_day'),
            (int)get_post('height'),
            (int)get_post('show_size')
        ); 

        // User_nameエラーチェック
        if (empty($new_userdata->username) === true) {      // ユーザー名が空かどうかの確認
            $err_msg[] = 'User_nameに文字を入力してください';    
        } else {
            if (is_alphanumeric($new_userdata->username) === false) {               // ユーザー名が半角英数字かの確認
                $err_msg[] = 'User_nameには半角英数字を入力してください';     
            } else {
                if(is_valid_length($new_userdata->username, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false) {    // ユーザー名が指定文字数内か確認
                    $err_msg[] = 'User_nameには6〜20字の半角英数を入力してください。';      
                }     
            }      
        }
        if (is_no_error($err_msg) === true) {    
            if(is_unique_username($dbh, $new_userdata->username) === false) {      // ユーザー名が既存のものsかどうか
                $err_msg[] = '既に存在するUser_nameです'; 
            }
        }

        // Passwordのエラーチェック
        if (empty($new_userdata->password) === true) {          // パスワードが空かどうかの確認
            $err_msg[] = 'Passwordに文字を入力してください';                      
        } else {
            if(is_alphanumeric($new_userdata->password) === false) {        // パスワードが英数字か確認
                $err_msg[] = 'Passwordには半角英数字を入力してください';       
            } else {
                if(is_valid_length($new_userdata->password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false) {   // パスワードが指定文字数内か確認
                    $err_msg[] = 'Passwordには6〜20字の半角英数を入力してください。';       
                }            
            }    
        }

        // E-mailのエラーチェック
        if (empty($new_userdata->mail) === true) {     // $mailが空かどうかの確認
            $err_msg[] = 'E-mailに文字を入力してください';           
        } else {
            if(is_valid_mail($new_userdata->mail) === false) {
                $err_msg[] = '無効なメールアドレスです。';              
            }
        }

        // Sexのエラーチェック
        if (empty($new_userdata->sex) === true || is_valid_sex($new_userdata->sex) === false) {    // $sexに正しい数値 (1,2,3のいずれか)があいっているかの確認
            $err_msg[] = 'Sexを選択してください';                
        }
        
        // Birthdateのエラーチェック
        if (checkdate($new_userdata->birthdate_month, $new_userdata->birthdate_day, $new_userdata->birthdate_year) === false) {          // 有効な日付であるかの確認
            $err_msg[] = 'Birthdayが無効な値です。';                                    
        } else {
            $new_userdata->birthdate = connect_date($new_userdata->birthdate_year, $new_userdata->birthdate_month, $new_userdata->birthdate_day);        
        }

        // データベース操作
        if (is_no_error($err_msg) === true) {
            try {
                insert_new_userdata($dbh, $new_userdata);
            } catch (Exception $e) {
                $err_msg[] = 'データベースのエラー'; 
            }
        }

    // postのtypeがupdateの場合 
    } else if ($_POST['type'] === 'update') {
        
        $update_userdata = new NewUser(
            get_post('up_name'), 
            get_post('up_passwd'),
            '',
            get_post('up_mail'), 
            (int)get_post('up_sex'),
            '', 
            (int)get_post('up_birthdate_year'),
            (int)get_post('up_birthdate_month'),
            (int)get_post('up_birthdate_day')
        );

        $user_id = get_post('id'); 

        // User_nameエラーチェック
        if (empty($update_userdata->username) === false) {      // ユーザー名が空かどうかの確認
            if (is_alphanumeric($update_userdata->username) === false) {               // ユーザー名が半角英数字かの確認
                $up_err_msg[] = 'User_nameには半角英数字を入力してください';     
            } else {
                if(is_valid_length($update_userdata->username, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false) {    // ユーザー名が指定文字数内か確認
                    $up_err_msg[] = 'User_nameには6〜20字の半角英数を入力してください。';      
                } else {
                    if(is_unique_username($dbh, $update_userdata->username) === false) {      // ユーザー名が既存のものsかどうか
                        $up_err_msg[] = '既に存在するUser_nameです'; 
                    }   
                }
            }      
        }

        // Passwordのエラーチェック
        if (empty($update_userdata->password) === false) {          // パスワードが空かどうかの確認
            if(is_alphanumeric($update_userdata->password) === false) {        // パスワードが英数字か確認
                $up_err_msg[] = 'Passwordには半角英数字を入力してください';       
            } else {
                if(is_valid_length($update_userdata->password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false) {   // パスワードが指定文字数内か確認
                    $up_err_msg[] = 'Passwordには6〜20字の半角英数を入力してください。';       
                }            
            }    
        }
        
        // E-mailのエラーチェック
        if(empty($update_userdata->mail) === false){
            if (is_valid_mail($update_userdata->mail) === false) {
                $up_err_msg[] = '無効なメールアドレスです。';        
            }      
        }

        // Sexのエラーチェック
        if (empty($update_userdata->sex) === false) {
            if (is_valid_sex($update_userdata->sex) === false) {    // $sexに正しい数値 (1,2,3のいずれか)があいっているかの確認
                $up_err_msg[] = '適切な性別を選択してください'; 
            }
        }
        
        // Birthdateのエラーチェック
        if(empty($update_userdata->birthdate_year) === false|| empty($update_userdata->birthdate_month) === false || empty($update_userdata->birthdate_year) === false){
            if (checkdate($update_userdata->birthdate_month, $update_userdata->birthdate_day, $update_userdata->birthdate_year) === false) {          // 有効な日付であるかの確認
                $up_err_msg[] = 'Birthdayが無効な値です。';                                    
            } else {
                $update_userdata->birthdate = connect_date($update_userdata->birthdate_year, $update_userdata->birthdate_month, $update_userdata->birthdate_day);        
            }
        }

        // データベース操作
        if (is_no_error($up_err_msg) === true) {
            $userdata = get_userdata($dbh, $user_id);
            $update_userdata->username = input_data_to_empty($update_userdata->username, $userdata['username']);
            $update_userdata->password = input_data_to_empty($update_userdata->password, $userdata['password']);
            $update_userdata->mail = input_data_to_empty($update_userdata->mail, $userdata['username']);
            $update_userdata->sex = input_data_to_empty($update_userdata->sex, $userdata['sex']);
            $update_userdata->birthdate = input_data_to_empty($update_userdata->birthdate, $userdata['birthdate']);
                
            update_userdata_info($dbh, $user_id, $update_userdata);
            $up_comp_msg = 'ユーザ情報を更新しました';
        }

    } else if ($_POST['type'] === 'delete') {
        $user_id = (int)get_post('id');              // postされたidを$user_idに代入する
        delete_userdata($dbh, $user_id);
        $up_comp_msg = 'ユーザ情報を削除しました';

    }
}
$all_userdata = get_all_userdata($dbh);              // ユーザー情報を取得して$data配列に代入 
include_once './view/admin_view.php'; 