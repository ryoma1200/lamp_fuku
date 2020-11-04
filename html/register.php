<?php
require_once './conf/const.php';
require_once './NewUser.php';
require_once MODEL_PATH.'function_model.php';
require_once MODEL_PATH.'user_model.php';
require_once MODEL_PATH.'db_model.php';

$dbh = db_connect();     // データベースに接続する
$err_msg = array();

session_start();
if(is_logined() === true){     // ログインしていたときはhomeにリダイレクト
    redirect_to(HOME_URL);
}

// ユーザーデータを格納する配列を用意
$userdata = new NewUser();   // ユーザー情報の初期化
$token = get_post("token");

// フォームがpostされたときの処理
if (is_post() === true && is_valid_csrf_token($token) !== false) {
    if (isset($_POST['type']) === true && $_POST['type'] === 'submit_new_data') {
        $userdata = new NewUser(
            get_post('name'),
            get_post('passwd'),
            get_post('passwd_check'),
            get_post('mail'),
            (int)get_post('sex'), 
            '',
            (int)get_post('birthdate_year'),
            (int)get_post('birthdate_month'),
            (int)get_post('birthdate_day')
        );

        // User_nameエラーチェック
        if (empty($userdata->username) === true) {      // ユーザー名が空かどうかの確認
            $err_msg[] = 'User_nameに文字を入力してください';    
        } else {
            if (is_alphanumeric($userdata->username) === false) {               // ユーザー名が半角英数字かの確認
                $err_msg[] = 'User_nameには半角英数字を入力してください';     
            } else {
                if(is_valid_length($userdata->username, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false) {    // ユーザー名が指定文字数内か確認
                    $err_msg[] = 'User_nameには6〜20字の半角英数を入力してください。';      
                }     
            }      
        }
        if (is_no_error($err_msg) === true) {       // ネストがふかくなりすぎないよに
            if(is_unique_username($dbh, $userdata->username) === false) {      // ユーザー名が既存のものsかどうか
                $err_msg[] = '既に存在するUser_nameです'; 
            }
        }


        // Passwordのエラーチェック
        if (empty($userdata->password) === true) {          // パスワードが空かどうかの確認
            $err_msg[] = 'Passwordに文字を入力してください';                      
        } else {
            if ($userdata->password !== $userdata->password_check) {       // パスワードと確認用パスワードが一致するかを確認
                $err_msg[] = 'Passwordが適切ではありません';
            } else { 
                if(is_alphanumeric($userdata->password) === false) {        // パスワードが英数字か確認
                    $err_msg[] = 'Passwordには半角英数字を入力してください';       
                } else {
                    if(is_valid_length($userdata->password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false) {   // パスワードが指定文字数内か確認
                        $err_msg[] = 'Passwordには6〜20字の半角英数を入力してください。';       
                    }            
                }    
            }
        }
        
        // E-mailのエラーチェック
        if (empty($userdata->mail) === true) {     // $mailが空かどうかの確認
            $err_msg[] = 'E-mailに文字を入力してください';           
        } else {
            if(is_valid_mail($userdata->mail) === false) {
                $err_msg[] = '無効なメールアドレスです。';              
            }
        }
        
        // Sexのエラーチェック
        if (empty($userdata->sex) === true || is_valid_sex($userdata->sex) === false) {    // $sexに正しい数値 (1,2,3のいずれか)があいっているかの確認
            $err_msg[] = 'Sexを選択してください';                
        }

        // Birthdateのエラーチェック
        if (checkdate($userdata->birthdate_month, $userdata->birthdate_day, $userdata->birthdate_year) === false) {          // 有効な日付であるかの確認
            $err_msg[] = 'Birthdayが無効な値です。';                                    
        } else {
            $userdata->birthdate = connect_date($userdata->birthdate_year, $userdata->birthdate_month, $userdata->birthdate_day);        
        }

        // ここからDB処理
        if (is_no_error($err_msg) === true) {    
            try {
                $user_id = (int)insert_new_userdata($dbh, $userdata);
                set_cookie_user_id($user_id);         
                redirect_to(HOME_URL);
            } catch (Exception $e) {
                $err_msg[] = 'データベースのエラー'; 
            }
        }

    } 
}
$token = get_csrf_token();
include './view/register_view.php';
?>