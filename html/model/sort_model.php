<?php
require_once './conf/const.php';
require_once MODEL_PATH.'items_model.php';
require_once MODEL_PATH.'db_model.php';

// 絞り込みデータを格納した配列を与えると、それに準じたクエリを返す。
function generate_query($search_condition, $userdata) {
    $query = '';
    $query .= generate_query_filter_category($query, $search_condition->category);                //カテゴリを絞り込むクエリ
    if (empty($search_condition->size_fit) === false && empty($userdata['height']) === false && empty($userdata['shoe_size']) === false) {    
        $query .= generate_query_filter_special_size($query, $userdata);                            //自分の体型にあったサイズを絞り込むクエリ
    } else if (empty($search_condition->size) === false) {
        $query .= generate_query_filter_size($query, $search_condition->size);                    //サイズを絞り込むクエリ
    }
    $query .= generate_query_filter_price($query, $search_condition->price_min, $search_condition->price_max);          // 値段を絞り込むクエリ
    $query .= generate_query_filter_keyword($query, $search_condition->keyword);                  // キーワードで絞り込むクエリ
    $query .= generate_query_sort($search_condition->order);                                      // 表示順序を指定するクエリ

    $query = 'SELECT * FROM fukuya_items'. $query;     // クエリを作成
    
    return $query; 
}

// 接頭語は WHERE か AND か決める
function add_prefix_to_query($prior_query, $new_query) {
    if (empty($prior_query) === true) {
        return ' WHERE'.$new_query;
    } else {
        return ' AND'.$new_query;
    }
}

// カテゴリを絞り込むクエリ
function generate_query_filter_category($query, $category) {
    $new_query = '';
    if (empty($category) === false) {
        $new_query = ' category = '.$category;
        $new_query =  add_prefix_to_query($query, $new_query);
    }
    return $new_query;
}

// サイズを絞り込むクエリ１
function generate_query_filter_size($query, $size) {
    $new_query = '';
    if (empty($size) === false) {
        $new_query = ' size = '.$size;
        $new_query =  add_prefix_to_query($query, $new_query);
    }
    return $new_query;
}

// サイズを絞り込むクエリ２（ユーザの体型に合った商品を絞り込むクエリをつくる）
function generate_query_filter_special_size($query, $userdata) { 
    $new_query = '';
    if (empty($userdata['height']) === false && empty($userdata['shoe_size']) === false) {
        $new_query .= generate_query_clothes_condition($userdata['height']);                  // トップス & ボトムス のフィッティング
        $new_query .= ' OR';
        $new_query .= generate_query_shoes_condition($userdata['shoe_size']);                 // 靴 のフィッティング
        $new_query = add_prefix_to_query($query, $new_query);                                 // クエリに接頭語（WHERE か AND か）をつける
    }
    return $new_query;
}

// 服の絞り込み条件
function generate_query_clothes_condition($height){
    $query = generate_query_free_clothes_size();      // フリーサイズ
    $query .= ' OR';
    $query .= generate_query_specified_clothes_size($height);       // 自分にあったサイズ
    return $query;
}

// 靴の絞り込み条件
function generate_query_shoes_condition($shoe_size){
    $query = generate_query_free_shoes_size();                       // フリーサイズ
    $query .= ' OR';        
    $query .= generate_query_specified_shoes_size($shoe_size);      // 自分にあったサイズ
    return $query;
}

// フリーサイズの服を抽出するクエリを生成する
function generate_query_free_clothes_size(){
    $query_size = assign_size(SIZE_F);
    return ' ('. $query_size. ' AND (category = '.CATEGORY_TOPS.' OR category = ' .CATEGORY_BOTTOMS.'))';
}

// 自分にあったサイズの服を抽出するクエリを生成する
function generate_query_specified_clothes_size($height){
    $query_size = generate_query_determine_shoes_size($height);
    return ' ('. $query_size. ' AND (category = '.CATEGORY_TOPS.' OR category = ' .CATEGORY_BOTTOMS.'))';
}

// 身長に応じたサイズ返す
function generate_query_determine_clothes_size($height) {
    if($height >= HEIGHT_181_185) {
        return assign_size(SIZE_XL);
        
    } else if ($height === HEIGHT_176_180) {
        return assign_size(SIZE_L, SIZE_XL);
        
    } else if ($height === HEIGHT_171_175) {
        return assign_size(SIZE_L);
        
    } else if ($height === HEIGHT_166_170) {
        return assign_size(SIZE_M, SIZE_L);
        
    } else if ($height === HEIGHT_161_165) {
        return assign_size(SIZE_M);
        
    } else if ($height === HEIGHT_156_160) {
        return assign_size(SIZE_S, SIZE_M);
        
    } else if ($height <= HEIGHT_151_155) {
        return assign_size(SIZE_S);
    } 
}


// フリーサイズの靴を抽出するクエリを生成する
function generate_query_free_shoes_size(){
    $query_size = assign_size(SIZE_F);
    return ' ('. $query_size. ' AND (category = '.CATEGORY_SHOES.'))';
}

// 自分にあったサイズの靴を抽出するクエリを生成する
function generate_query_specified_shoes_size($shoe_size){
    $query_size = generate_query_determine_shoes_size($shoes_size);
    return ' ('. $query_size. ' AND (category = '.CATEGORY_SHOES.'))';
}


// 自分にあったサイズの靴を抽出するクエリを生成する
function generate_query_determine_shoes_size($shoes_size) {
    if ($shoes_size >= SHOE_SIZE_28_) {
        return assign_size(SIZE_XL);               // XL
        
    } else if ($shoes_size === SHOE_SIZE_27_28) {
        return assign_size(SIZE_L, SIZE_XL);       // L + XL
        
    } else if ($shoes_size === SHOE_SIZE_26_27) {
        return assign_size(SIZE_L);                // L
        
    } else if ($shoes_size === SHOE_SIZE_25_26) {
        return assign_size(SIZE_M, SIZE_L);        // M + L
        
    } else if ($shoes_size === SHOE_SIZE_24_25) {
        return assign_size(SIZE_M);                // M
        
    } else if ($shoes_size === SHOE_SIZE_23_24) {
        return assign_size(SIZE_S, SIZE_M);        // S + M
        
    } else if ($shoes_size <= SHOE_SIZE_22_23) {
        return assign_size(SIZE_S);                // S
    }
}

// サイズを割り当てる
function assign_size($size1 = 0, $size2 = 0) {    
    if($size2 !== 0) {
        return '(size = '.$size1.' OR size = '.$size2.')'; 
    } else {
        return 'size = '.$size1; 
    }
}

// 値段を絞り込むクエリ
function generate_query_filter_price($query, $price_min, $price_max) {
    $new_query = '';

    if (empty($price_min) === false || $price_min === 0) {         // price_minが選択されている場合
            
        if (empty($price_max) === false) {                         // price_maxが選択されている場合
            
            if ($price_min < $price_max) {                                // price_min < price_max の場合
                $new_query .= ' price BETWEEN '.$price_min.' AND '.$price_max;

            } else if ($price_min > $price_max) {                         // price_min > price_max の場合(大小逆に入力されてしまったとき)
                $new_query .= ' price BETWEEN '.$price_max.' AND '.$price_min;

            } else if ($price_min === $price_max) {                       // price_min = price_max の場合
                $new_query .= ' price = '.$price_min;
            }
            
        } else if (empty($price_max) === true) {           // price_min のみ選択されているの場合
            $new_query .= ' price >= '.$price_min;
        } 
            
    } else if (empty($price_min) === true) {         // price_minが空でprice_maxが選択されている場合の

        if (empty($price_max) === false) {           // price_min のみ選択されているの場合
            $new_query .= ' price >= '.$price_min;
        } 

    }
    return add_prefix_to_query($query, $new_query);
}

// キーワードを絞り込むクエリ
function generate_query_filter_keyword($query, $keyword) {
    $new_query = '';
    if (empty($keyword) === false) {
        $new_query .= ' name LIKE "%' . $keyword . '%"';
        $new_query = add_prefix_to_query($query, $new_query);
    }
    return $new_query;
}

// 並び替えを決めるクエリ
function generate_query_sort($order) {
    $new_query = '';
    if (!empty($order)) {
        if ($order === 'order_new') {
            $new_query = ' ORDER BY createdate ASC';
        } else if ($order === 'order_exp') {
            $new_query = ' ORDER BY price DESC';
        } else if ($order === 'order_reas') {
            $new_query = ' ORDER BY price ASC';
        }
    }
    return $new_query;
}
