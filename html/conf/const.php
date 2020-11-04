<?php
define('MODEL_PATH', './model/');
define('VIEW_PATH', './view/');
define('VIEW_PATH_HEADER', './view/header_view.php');
define('VIEW_PATH_FOOTER', './view/footer_view.php');
define('CONF_PATH', './conf/');
define('IMAGE_PATH', './item_img/');

define('HOME_URL', '/index.php');
define('LOGIN_URL', '/login.php');
define('RESISTER_URL', '/resister.php');
define('CART_URL', '/cart.php');
define('ITEM_URL', '/item.php');
define('ITEM_TOOL_URL', '/item_tool.php');
define('FINISH_URL', '/finish.php');
define('USER_PAGE_URL', '/user_page.php');
define('ADMIN_URL', '/admin.php');

// cart
define('CART_MAX_AMOUNT', 10);

define ('PATTERN_HALFWIDTH', '/^[a-zA-Z0-9]+$/');
define ('PATTERN_MAIL', '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/');
define ('PATTERN_VALUE', '/^[0-9]+$/');
define ('PATTERN_DECIMAL', '/^[0-9]+$|^[0-9]+\.[0-9]+$/');
define ('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
define ('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');

//有効なファイルの拡張子
define ('EXTENSION', '/^[pP][nN][gG]$|^[jJ][pP][gG]$|^[jJ][pP][eE][gG]$/');

define ('COOKIE_RETENTION_TIME', 60 * 60 * 24 * 365);

//キーワード検索
define('KEYWORD_MIN_LENGTH', 0);
define('KEYWORD_MAX_LENGTH', 100);

//身長
define('HEIGHT__150', 1);
define('HEIGHT_151_155', 2);
define('HEIGHT_156_160', 3);
define('HEIGHT_161_165', 4);
define('HEIGHT_166_170', 5);
define('HEIGHT_171_175', 6);
define('HEIGHT_176_180', 7);
define('HEIGHT_181_185', 8);
define('HEIGHT_186_', 9);

//靴サイズ
define('SHOE_SIZE__22', 1);
define('SHOE_SIZE_22_23', 2);
define('SHOE_SIZE_23_24', 3);
define('SHOE_SIZE_24_25', 4);
define('SHOE_SIZE_25_26', 5);
define('SHOE_SIZE_26_27', 6);
define('SHOE_SIZE_27_28', 7);
define('SHOE_SIZE_28_', 8);

// 商品のカテゴリ
define('CATEGORY_TOPS', 1);
define('CATEGORY_BOTTOMS', 2);
define('CATEGORY_SHOES', 3);

// 服・靴のサイズ
define('SIZE_F', 1);
define('SIZE_S', 2);
define('SIZE_M', 3);
define('SIZE_L', 4);
define('SIZE_XL', 5);

define('USER_NAME_LENGTH_MIN', 6);
define('USER_NAME_LENGTH_MAX', 20);
define('USER_PASSWORD_LENGTH_MIN', 6);
define('USER_PASSWORD_LENGTH_MAX', 20);
define('USER_MAIL', '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/');

define('ITEM_NAME_MIN_LENGTH', 3);
define('ITEM_NAME_MAX_LENGTH', 50);
define('COMMENT_MIN_LENGTH', 0);
define('COMMENT_MAX_LENGTH', 500); 
define('COLUMN_NAME_MIN_LENGTH', 5);
define('COLUMN_NAME_MAX_LENGTH', 150);

define('SEX_MAN', 1);
define('SEX_WOMAN', 2);
define('SEX_OTHERS', 3);

?>