<?php
require_once './conf/const.php';
require_once MODEL_PATH.'db_model.php';
require_once MODEL_PATH.'function_model.php';

function get_userdata($dbh, $user_id) {
    try {
        $sql = 'SELECT 
                fukuya_info.id,
                fukuya_info.username,
                fukuya_info.password,
                fukuya_info.mail,
                fukuya_info.sex,
                fukuya_info.birthdate,
                fukuya_info.createdate,
                fukuya_phys.height,
                fukuya_phys.shoe_size
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

function get_all_userdata($dbh) {
    try {
        $sql = 'SELECT 
                fukuya_info.id,
                fukuya_info.username,
                fukuya_info.password,
                fukuya_info.mail,
                fukuya_info.sex,
                fukuya_info.birthdate,
                fukuya_info.createdate,
                fukuya_phys.height,
                fukuya_phys.shoe_size
                FROM fukuya_info 
                LEFT OUTER JOIN fukuya_phys
                ON fukuya_info.id = fukuya_phys.id';
        
        $stmt = $dbh->prepare($sql);
        return fetch_all_query($dbh, $stmt);
    } catch (PDOException $e) {
        throw $e;
    }
}

// DBのusernameに$usernameがあれば、ユーザ情報の配列を返す。
function get_userdata_by_username($dbh, $username) {
    try {
        $sql = 'SELECT * FROM fukuya_info WHERE username = ? ';
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        return fetch_query($dbh, $stmt);
    } catch (PDOException $e) {
        throw $e;
        return '';
    }
}

function get_user_id_by_username($dbh, $username){
    try {
        $sql = 'SELECT id FROM fukuya_info WHERE username = ? ';
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $row = fetch_query($dbh, $stmt);
        return $row['id'];
    } catch (PDOException $e) {
        throw $e;
        return '';
    }
}

// ユーザ情報をデータベースに追加する
function insert_new_userdata($dbh, $userdata) {
    $id = 0;
    $dbh->beginTransaction();
    try {
        $sql = 'INSERT INTO fukuya_info 
                (username, `password`, mail, sex, birthdate, createdate, updatedate)
                VALUES (?, ?, ?, ?, ?, now(), now())';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $userdata->username, PDO::PARAM_STR);
        $stmt->bindValue(2, $userdata->password,PDO::PARAM_STR);
        $stmt->bindValue(3, $userdata->mail, PDO::PARAM_STR);
        $stmt->bindValue(4, $userdata->sex, PDO::PARAM_INT);
        $stmt->bindValue(5, $userdata->birthdate.' 00-00-00', PDO::PARAM_STR);
        $stmt->execute();
        $id = $dbh->lastInsertId('id');
        
        $sql = 'INSERT INTO fukuya_phys 
                (id, height, shoe_size, createdate, updatedate)
                VALUES (?, ?, ?, now(), now())';      
        
        $stmt = $dbh->prepare($sql);                                  // sqlの実行準備
        $stmt->bindValue(1, $id, PDO::PARAM_INT);        
        $stmt->bindValue(2, $userdata->height, PDO::PARAM_INT);        
        $stmt->bindValue(3, $userdata->shoe_size, PDO::PARAM_INT);        
        $stmt->execute();
        
        $dbh->commit();            // トランザクションの終了処理
    } catch (PDOException $e) {
        $dbh->rollback();          // トランザクションのロールバック処理
        throw $e;
    }
    return $id;
}

// データベース　ユーザー情報の追加
function update_userdata_info($dbh, $user_id, $userdata) {
    try {
        $sql = 'UPDATE fukuya_info 
                SET username = ? , password = ?, mail = ?, sex = ?, birthdate = ?, updatedate = now()
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $userdata->username, PDO::PARAM_STR);
        $stmt->bindValue(2, $userdata->password,PDO::PARAM_STR);
        $stmt->bindValue(3, $userdata->mail, PDO::PARAM_STR);
        $stmt->bindValue(4, $userdata->sex, PDO::PARAM_INT);
        $stmt->bindValue(5, $userdata->birthdate, PDO::PARAM_STR);
        $stmt->bindValue(6, $user_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

// データベース　ユーザー情報の削除
function delete_userdata($dbh, $user_id) {
    $dbh->beginTransaction();
    try {
        $sql = 'DELETE FROM fukuya_info WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $sql = 'DELETE FROM fukuya_phys WHERE id = ?';
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $dbh->commit();
        
    } catch (PDOException $e) {
        $dbh->rollback();
        throw $e;
    }
}

// ユーザー名が固有のものかを確認する
function is_unique_username($dbh, $username) {
    $existing_username = get_all_username($dbh);                  // DB内の全てのusernameを取得する         
    foreach ($existing_username as $value) {                          // usernameが重複していないかを確認
        if ($value['username'] === $username) {                 // usernameが重複していた場合の処理
            return false;
        }
    }
    return true;
}

// 全てのユーザー名を取得する
function get_all_username($dbh) {
    try {
        $sql = 'SELECT username FROM fukuya_info';
        
        $stmt = $dbh->prepare($sql);
        return fetch_all_query($dbh, $stmt);
    } catch (PDOException $e) {
        throw $e;
        return '';
    }
}

// 有効なメールアドレスかを確認する
function is_valid_mail($mail) {
    if (preg_match(USER_MAIL, $mail) === 1) {                  // $mailが正規表現に当てはまるかの確認
        return true;
    } else {
        return false;
    }
}

// 有効な性別かどうかを確認する
function is_valid_sex($sex) {
    if($sex === SEX_MAN || $sex === SEX_WOMAN || $sex === SEX_OTHERS) {
        return true;
    } else {
        return false;
    }
}

// 日付データをハイフンでコネクトする
function connect_date($birthdate_year, $birthdate_month, $birthdate_day) {
    return $birthdate_year.'-'.$birthdate_month.'-'.$birthdate_day; 
}

function devide_birthdate($birthdate){
    if (!empty($birthdate)) {
        $date_array = explode(" ", $birthdate, 2);            // $birthdateの年月日のみを取り出して$date_arrayに代入
        $date_array = explode("-", $date_array[0], 4);        // $date_arrayを年・月・日に分けて配列に格納
        return $date_array;
    } else {
        return '';
    }
}

/*************** user page ****************/

// データベース　ユーザー情報の追加
function update_username($dbh, $user_id, $username) {
    try {
        $sql = 'UPDATE fukuya_info 
                SET username = ?, updatedate = now()
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        throw $e;
    }
}

// パスワードの変更
function update_passwd($dbh, $user_id, $password) {
    try {
        $sql = 'UPDATE fukuya_info 
                SET password = ?, updatedate = now()
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $password, PDO::PARAM_STR);
        $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        throw $e;
    }
}

// メールアドレスを変更
function update_mail($dbh, $user_id, $mail) {
    try {
        $sql = 'UPDATE fukuya_info 
                SET mail = ?, updatedate = now()
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $mail, PDO::PARAM_STR);
        $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        throw $e;
    }
}

// 生年月日のサイズを変更
function update_birthdate($dbh, $user_id, $birthdate) {
    try {
        $sql = 'UPDATE fukuya_info 
                SET birthdate = ?, updatedate = now()
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $birthdate.' 00:00:00', PDO::PARAM_STR);
        $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        throw $e;
    }
}

// 身長を変更
function update_height($dbh, $user_id, $height) {
    try {
        $sql = 'UPDATE fukuya_phys
                SET height = ?, updatedate = now()
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $height, PDO::PARAM_STR);
        $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        throw $e;
    }
}

// 靴のサイズを変更
function update_shoe_size($dbh, $user_id, $shoe_size) {
    try {
        $sql = 'UPDATE fukuya_phys
                SET shoe_size = ?, updatedate = now()
                WHERE id = ?';
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $shoe_size, PDO::PARAM_STR);
        $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
    } catch (PDOException $e) {
        throw $e;
    }  
}

function is_valid_height($height){
    if($height === HEIGHT__150 || $height === HEIGHT_151_155 || $height === HEIGHT_156_160 || 
    $height === HEIGHT_161_165 || $height === HEIGHT_166_170 || $height === HEIGHT_171_175 || 
    $height === HEIGHT_176_180 || $height === HEIGHT_181_185 || $height === HEIGHT_186_) {
        return true;
    } else {
        return false;
    }
}

function is_valid_shoe_size($shoe_size) {
    if($shoe_size === SHOE_SIZE__22 || $shoe_size === SHOE_SIZE_22_23 || $shoe_size === SHOE_SIZE_23_24 || 
     $shoe_size === SHOE_SIZE_24_25 || $shoe_size === SHOE_SIZE_25_26 || $shoe_size === SHOE_SIZE_26_27 || 
     $shoe_size === SHOE_SIZE_27_28 || $shoe_size === SHOE_SIZE_28_) {
        return true;
    } else {
        return false;
    }   
}