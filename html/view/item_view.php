<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>FUKUYA</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./view/html5reset-1.6.1.css">
        <link rel="stylesheet" href = "./view/common.css">
        <link rel="stylesheet" href = "./view/item_view.css">
    </head>
    <body>
        <!-- ヘッダー -->
        <?php include VIEW_PATH_HEADER; ?>>
        <main>
            <br><br><br>
            <h2>・・・&nbsp;Catalog&nbsp;・・・</h2>
            <a href="./index.php#shopping" class="back_btn"><div class="arrow"></div>Back</a>
            <section>
                <div class="item_img">
                    <img src="./item_img/<?php print h($item_data['img']);?>">
                </div>
                <div class="item_info">
                    <h4>NAME：</h4>
                    <?php print h($item_data['name']);?>
                    
                    <h4>CATEGORY：</h4>
                    
                    <?php
                    if ($item_data['category'] === 1) {
                        print 'Tops';
                    } else if ($item_data['category'] === 2) {
                        print 'Bottoms';
                    } else if ($item_data['category'] === 3) {
                        print 'Shoes';
                    } else {
                        print '-';
                    }
                    ?>
                    
                    <h4>PRICE：</h4>
                    <?php print h($item_data['price']);?>&nbsp;yen
                    
                    <h4>SIZE：</h4>
                    <?php
                    if ($item_data['size'] === 1) {
                        print 'Free';
                    } else if ($item_data['size'] === 2) {
                        print 'S';
                    } else if ($item_data['size'] === 3) {
                        print 'M';
                    } else if ($item_data['size'] === 4) {
                        print 'L';
                    } else if ($item_data['size'] === 5) {
                        print 'XL';
                    } else {
                        print '-';
                    }
                    ?>   
                        
                    <?php if (empty($item_data['comment']) === false) { ?>
                        <h4>COMMENT：</h4>
                        <div class="comment"><?php print h($item_data['comment']);?></div>
                    <?php } ?>
                    
                    <?php if ($item_data['stock'] > 0) {     // 在庫があるときフォームを表示する
                        $action_url = ''; 
                        if(is_logined() === true) {
                            $action_url = CART_URL;
                        } else {
                            $action_url = LOGIN_URL;
                        }
                    ?>
                        <form method="post" action="<?php print $action_url; ?>">
                            <div class="add_to_cart">
                                <p>個数：
                                    <select name="amount">
                                    <?php for ($i = 1; $i <= $item_data['stock'] && $i <= 10; $i++) { ?>
                                        <option volume="<?php print $i; ?>"><?php print $i; ?></option>
                                    <?php } ?>
                                    </select>
                                    <input type="submit" name="sub" value="カートに入れる">
                                </p>
                            </div>
                            <input type="hidden" name="type" value="add_to_cart">
                            <input type="hidden" name="item_id" value="<?php print h($item_id); ?>">
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                        </form>
                    <?php
                    }
                    ?>
                </div>
            </section>
        </main>

        <!-- フッター -->
        <?php include VIEW_PATH_FOOTER; ?>
    </body>
</html>