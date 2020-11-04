<?php
require_once './model/user_model.php';

// エンティティ化
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
}

function redirect_to($url){
    header('Location: ' . $url);
    exit;
}

/************************************　ログイン・ログアウト　*****************************************/

function is_logined() {
    if (isset($_SESSION['user_id']) === true) {
        return true;
    } else {
        return false;
    }
}

function pressed_logout_button() {
    if (is_post() === true) {
        if (isset($_POST['type']) === true) {  
            if ($_POST['type'] === 'logout') {            // ログアウトが押されたら
                return true;
            }
        }
    }
    return false;
}

// ログアウト処理
function logout() {
    session_start();                                       // セッションを開始する
    $session_name = session_name();                        // 現在のセッション名を取得する
    $_SESSION = array();                                   
    
    if (isset($_COOKIE[$session_name]) === true) {        
        $params = session_get_cookie_params();
        setcookie($session_name, '', time() - 42000, 
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
    header('Location: ./index.php');
    exit;
}

function set_cookie_user_id($user_id){ 
    session_start();
    delete_cookie('user_id');
    setcookie('user_id', $user_id, COOKIE_RETENTION_TIME);
    $_SESSION['user_id'] = $user_id;
}

function delete_cookie($cookie_name){
    if (isset($_COOKIE[$cookie_name]) === true) { 
        setcookie($cookie_name, '', time() - 42000);
    }
}

/************************************　バリデーション　*****************************************/

function get_post($name){
    if(isset($_POST[$name]) === true){
      return $_POST[$name];
    };
    return '';
}

function get_get($name){
    if(isset($_GET[$name]) === true){
      return $_GET[$name];
    };
    return '';
}

function is_post() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return true;
    } else {
        return false;
    }
}

function is_get() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return true;
    } else {
        return false;
    }
}

function is_valid_length($string, $minimum_length, $maximum_length) {
    $length = mb_strlen($string);
    if ($minimum_length <= $length && $length <= $maximum_length) {
        return true;
    } else {
        return false;
    }
}
  
function is_alphanumeric($string){
    return is_valid_format($string, REGEXP_ALPHANUMERIC);
}
  
function is_positive_integer($string){
    return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}
  
function is_valid_format($string, $format){
    return preg_match($format, $string) === 1;
}

function is_no_error($err_msg_array) {
    if (count($err_msg_array) === 0) {
        return true;
    } else {
        return false;
    }
}

function input_data_to_empty($data1, $data2) {
    if(empty($data1) === true) {
        $data1 = $data2;
    }
    return $data1;
}

/********************************** データベース操作 *************************************/

// 本人のユーザ情報を取得する
function db_read_my_userdata($dbh, $user_id) {
    try {
        $sql = 'SELECT * 
                FROM fukuya_info 
                LEFT OUTER JOIN fukuya_phys
                ON fukuya_info.id = fukuya_phys.id
                WHERE fukuya_info.id = ?';
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
        return fetch_query($dbh, $stmt);
    } catch (PDOException $e) {
        throw $e;
    }
}

// すべてのユーザ名を取得する
function db_read_all_username($dbh) {
    try {
        $sql = 'SELECT username FROM fukuya_info';
        
        $stmt = $dbh->prepare($sql);
        return fetch_all_query($dbh, $stmt);
    } catch (PDOException $e) {
        throw $e;
    }
}

// 全ての商品データを取得する
function db_read_all_items($dbh) {
    try {
        $sql = 'SELECT * FROM fukuya_items';
                
        $stmt = $dbh->prepare($sql);
        return fetch_all_query($dbh, $stmt);
    } catch (PDOException $e) {
        throw $e;
    }
} 

/***********************************************************************/

function set_session($name, $value){
    $_SESSION[$name] = $value;
}

function get_session($name){
    if(isset($_SESSION[$name]) === true){
      return $_SESSION[$name];
    };
    return '';
}

// CSRF対策
function get_csrf_token(){
    $token = get_random_string(30);			  	
    set_session('csrf_token', $token);		 			
    return $token;
}

function get_random_string($length = 20){
    return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}
  
function is_valid_csrf_token($token){
    if($token === '') {
        return false;
    }
    return $token === get_session('csrf_token');  		 // get_session()はユーザー定義関数
}
  
function delete_csrf_token() {
    clear_session('csrf_token');
}
