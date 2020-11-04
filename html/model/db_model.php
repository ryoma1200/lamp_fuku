<?php
require_once './conf/const_db.php';

// データベース接続
function db_connect() {
    try {
        $dbh = new PDO(DSN, DB_USERNAME, DB_PASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));   // DBに接続
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);         // 例外処理の設定
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);                 // プリペアドステートメント の設定        
    } catch(PDOException $e) {
        throw $e;
    }
    return $dbh;
}


//　クエリ実行
function execute_query($db, $statement){
    try{
        return $statement->execute();
    }catch(PDOException $e){
        set_error('更新に失敗しました。');
    }
        return false;
}
  

// クエリ実行＆配列の取得
function fetch_query($db, $statement){
    try{
        $statement->execute();
        return $statement->fetch();
    }catch(PDOException $e){
        set_error('データ取得に失敗しました。');
    }
        return false;
  }
  

// クエリ実行＆連想配列の取得
function fetch_all_query($db, $statement){
    try{
        $statement->execute();
        return $statement->fetchAll();
    }catch(PDOException $e){
        set_error('データ取得に失敗しました。');
    }
        return false;
}

  







