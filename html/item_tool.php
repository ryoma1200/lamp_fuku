<?php
require_once './conf/const.php';
require_once './NewItem.php';
require_once MODEL_PATH.'function_model.php';
require_once MODEL_PATH.'items_model.php';
require_once MODEL_PATH.'db_model.php';

$dbh = db_connect();        // データベース接続

// エラーチェック用 & 完了メッセージ
$err_msg = array();
$comp_msg = '';
$up_err_msg = array();
$up_comp_msg = '';

$itemdata = new NewItem();

// フォームがpostされたときの処理
if (is_post() === true && isset($_POST['type']) === TRUE){
        
    //postのtypeがadd_itemの場合 
    if ($_POST['type'] === 'add_item') {
        
        $itemdata = new NewItem(
            get_post('item_name'),      
            (int)get_post('price'),    
            (int)get_post ('category'),
            (int)get_post('size'),
            '',
            (int)get_post('stock'),
            (int)get_post('status'),
            get_post('column_name'),
            get_post('comment')
        );
        
        // item_nameのエラーチェック
        if (empty($itemdata->item_name) === true) {                // $item_nameが空かどうかの確認
            $err_msg[] = 'Nameに文字を入力してください';    
        } else {
            if (is_valid_length($itemdata->item_name, ITEM_NAME_MIN_LENGTH, ITEM_NAME_MAX_LENGTH) === false) {
                $err_msg[] = 'Nameが長すぎます';
            }
        }

        // Priceのエラーチェック
        if (empty($itemdata->price) === true) {            // $passwdが空かどうかの確認
            $err_msg[] = 'Priceに数値を入力してください';       
        } else {
            if(is_positive_integer($itemdata->price) === false) {      // $passwdが正規表現に当てはまるかの確認
                $err_msg[] = 'Priceに正しい数字を入力してください';  
            }
        }        
        
        // Categoryのエラーチェック
        if (empty($itemdata->category) || ($itemdata->category !== CATEGORY_TOPS && $itemdata->category !== CATEGORY_BOTTOMS && $itemdata->category !== CATEGORY_SHOES)) {      // $itemdata->categoryに正しい値 (1,2,3のいずれか)が入っているかの確認
            $err_msg[] = 'Categoryを選択してください';    
        }
    
        // Sizeのエラーチェック
        if (empty($itemdata->size) || ($itemdata->size !== SIZE_F && $itemdata->size !== SIZE_S && $itemdata->size !== SIZE_M && $itemdata->size !== SIZE_L && $itemdata->size !== SIZE_XL)) {      // $sizeに正しい数値 (1~53いずれか)が入っているかの確認
            $err_msg[] = 'Sizeを選択してください';    
        }
        
        // ファイル形式のエラーの確認
        if (is_uploaded_file($_FILES['file']['tmp_name']) === false) {         // ファイルがアップロードされているかの確認
            $err_msg[] = '画像ファイルを選択してください';
        } else {     
            $extension = get_extension($_FILES['file']['name']);            // 拡張子を取得して$extensionに代入
            if (is_valid_extension($extension) === false) {                  // $extensionが正規表現の条件を満たす場合の処
                $err_msg[] = '画像ファイルはpng形式またはjpg形式を選択してください';
            } 
        }
        if (is_no_error($err_msg) === true) {       
            $itemdata->img_file = make_random_filename($extension);          // $fileにファイル名「乱数.拡張子」を代入
            if (is_file(IMAGE_PATH.$img_file) === true) {                         // フォルダ内に同一名のファイルがないかを確認
                $err_msg[] = '画像をアップロードできませんでした. 再度お試しください';
            } else {
                if (move_uploaded_file($_FILES['file']['tmp_name'], IMAGE_PATH.$itemdata->img_file) !== true) {        // ファイルの移動
                    $err_msg[] = '画像をアップロードできませんでした';
                }
            }
        }

        // Stockのエラーチェック
        if (is_positive_integer($itemdata->stock) === false) {      // $stockが空の場合
            $err_msg[] = 'Stockに数値を入力してください';  
        }   

        // Statusのエラーチェック
        if (empty($itemdata->status) === true || ($itemdata->status !== 1 && $itemdata->status !== 2)) {      // $itemdata->statusに正しい数値 (1,2のいずれか)が入っているかの確認
            $err_msg[] = 'Statusを選択してください'; 
        }
        
        // Commentのエラーチェック
        if (empty($itemdata->comment) === false) {                                                   // $itemdata->commentが空かどうかの確認      
            if (is_valid_length($itemdata->comment, COMMENT_MIN_LENGTH, COMMENT_MAX_LENGTH) === false) {
                $err_msg[] = 'Commentには0〜500字の文字列を入力してください';
            }  
        }
        
        // Column_nameのエラーチェック
        if (empty($itemdata->column_name) === false) {   
            if (is_valid_length($itemdata->column_name, COLUMN_NAME_MIN_LENGTH, COLUMN_NAME_MAX_LENGTH) === false) {    // この仕様ではユニークかどうかチェックしていない
                $err_msg[] = 'Column_nameには10〜150字の文字列を入力してください';
            }  
        }
        
        if(is_no_error($err_msg) === true){
            try {
                insert_item($dbh, $itemdata);
                $comp_msg = '商品を追加しました';
            } catch (Exception $e) {
                $err_msg[] = 'データベースエラー';
            }
        }

    } else if ($_POST['type'] === 'update') {
        
        $item_id = (int)get_post('id'); 
        $itemdata = new NewItem(
            get_post('up_item_name'),      
            (int)get_post('up_price'),    
            (int)get_post ('up_category'),
            (int)get_post('up_size'),
            '',
            (int)get_post('up_stock'),
            (int)get_post('up_status'),
            get_post('up_column_name'),
            get_post('up_comment')
        );

        // 商品名のエラーチェック
        if (empty($itemdata->item_name) === false) {    // $item_nameが空かどうかの確認
            if (is_valid_length($itemdata->item_name, ITEM_NAME_MIN_LENGTH, ITEM_NAME_MAX_LENGTH) === false) {
                $up_err_msg[] = 'キーワードが長すぎます。';
            }
        }

        // Priceのエラーチェック
        if (empty($itemdata->price) === false) {         // $passwdが空かどうかの確認
            if(is_positive_integer($itemdata->price) === false) {     // $passwdが正規表現に当てはまるかの確認
                $up_err_msg[] = 'Priceに正しい数字を入力してください';  
            }
        }        
        
        // Categoryのエラーチェック
        if (empty($itemdata->category) === false) {
            if ($itemdata->category !== CATEGORY_TOPS && $itemdata->category !== CATEGORY_BOTTOMS && $itemdata->category !== CATEGORY_SHOES) {    // $itemdata->categoryに正しい値 (1,2,3のいずれか)が入っているかの確認
                $up_err_msg[] = '適切なCategoryを選択してください';  
            }  
        }
        
        // Sizeのエラーチェック
        if (empty($itemdata->size) === false) {
            if ($itemdata->size !== SIZE_F && $itemdata->size !== SIZE_S && $itemdata->size !== SIZE_M && $itemdata->size !== SIZE_L && $itemdata->size !== SIZE_XL) {      // $sizeに正しい数値 (1~53いずれか)が入っているかの確認
                $up_err_msg[] = '適切なSizeを選択してください';
            }
        }
        
        // ファイル形式のエラーの確認
        if (is_uploaded_file($_FILES['up_file']['tmp_name']) === true) {         // ファイルがアップロードされているかの確認
            $extension = get_extension($_FILES['up_file']['name']);                         // 拡張子を取得して$extensionに代入
            if (is_valid_extension($extension) === false) {                                 // $extensionが正規表現の条件を満たす場合の処
                $up_err_msg[] = '画像ファイルはpng形式またはjpg形式を選択してください';
            } 
        }
        if (is_no_error($up_err_msg) === true && isset($extension) === true) {       
            $itemdata->img_file = make_random_filename($extension);          // $fileにファイル名「乱数.拡張子」を代入
            if (is_file(IMAGE_PATH.$img_file) === true) {                         // フォルダ内に同一名のファイルがないかを確認
                $up_err_msg[] = '画像をアップロードできませんでした. 再度お試しください';
            } else {
                if (move_uploaded_file($_FILES['up_file']['tmp_name'], IMAGE_PATH.$img_file) !== true) {        // ファイルの移動
                    $up_err_msg[] = '画像をアップロードできませんでした';
                }
            }
        }

        // Stockのエラーチェック
        if (empty($itemdata->stock) === false) {
            if (is_positive_integer($itemdata->stock) === false) {                                               // $stockが空の場合
                $up_err_msg[] = 'Stockに数値を入力してください';  
            }   
        }

        // Statusのエラーチェック
        if (empty($itemdata->status) === false) {
            if ($itemdata->status !== 1 && $itemdata->status !== 2) {      // $itemdata->statusに正しい数値 (1,2のいずれか)が入っているかの確認
                $up_err_msg[] = 'Statusを選択してください'; 
            }
        }
        
        // Commentのエラーチェック
        if (empty($itemdata->comment) === false) {                        // $itemdata->commentが空かどうかの確認      
            if (is_valid_length($itemdata->comment, COMMENT_MIN_LENGTH, COMMENT_MAX_LENGTH) === false) {
                $up_err_msg[] = 'Commentには0〜500字の文字列を入力してください';
            }  
        }
        
        // Column_nameのエラーチェック
        if (empty($itemdata->column_name) === false) {   
            if (is_valid_length($itemdata->column_name, COLUMN_NAME_MIN_LENGTH, COLUMN_NAME_MAX_LENGTH) === false) {    // この仕様ではユニークかどうかチェックしていない
                $up_err_msg[] = 'Column_nameには10〜150字の文字列を入力してください';
            }  
        }
        
        if(is_no_error($up_err_msg) === true) {

            $item = get_item_by_item_id($dbh, $item_id);

            // item_id
            $itemdata->item_name = input_data_to_empty($itemdata->item_name, $item['name']);
            $itemdata->price = input_data_to_empty($itemdata->price, $item['price']);
            $itemdata->category = input_data_to_empty($itemdata->category, $item['category']);
            $itemdata->size = input_data_to_empty($itemdata->size, $item['size']);
            $itemdata->img_file = input_data_to_empty($itemdata->img_file, $item['img']);
            $itemdata->item_name = input_data_to_empty($itemdata->item_name, $item['item_name']);
            $itemdata->status = input_data_to_empty($itemdata->status, $item['status']);
            $itemdata->comment = input_data_to_empty($itemdata->comment, $item['comment']);
            $itemdata->column_name = input_data_to_empty($itemdata->column_name, $item['column_name']);

            try {
                update_itemdata($dbh, $item_id, $itemdata);  // itemdataにあるitem_idをいれとく
                $up_comp_msg = '商品情報を更新しました';
            } catch(Exception $e) {
                $up_err_msg[] = 'データベースエラー';
            }
        }

    } else if ($_POST['type'] === 'delete') {

        $item_id = (int)get_post('id'); 
        try {
            delete_itemdata($dbh, $item_id);
            $up_comp_msg = '商品ID:'.$item_id.'を削除しました';   
        } catch(Exception $e) {
            $up_err_msg[] = 'データッベースエラー';
        }
    }
}
$all_itemdata = get_all_items($dbh);
include_once './view/item_tool_view.php';