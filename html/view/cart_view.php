<?php
    // カートの中身がからの場合にフッターを固定するCSS
    // $footer_style = 'width: 100%; position: absolute; bottom: 0';   
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>FUKUYA</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href = "./view/html5reset-1.6.1.css">
        <link rel="stylesheet" href = "./view/common.css">
        <link rel="stylesheet" href = "./view/cart_view.css">
    </head>
    <body>
        <!-- ヘッダー -->
        <?php include VIEW_PATH_HEADER; ?>
        <main>
            <h2>CART</h2>
            <div class="cart_box">
                <h3>商品一覧</h3>
                <?php if (!empty($cart_data)) { ?>
                    <h4>カートには<span><?php print h($total_amount); ?>個</span>の商品が入っています</h4>
                    <p class="err_msg"> 
                    <?php
                        foreach ($err_msg as $value) {
                            print $value;
                        }
                    ?>
                    </p>
                    <table>
                        <tr>
                            <th>商品</th><th>商品名</th><th>単価(税込)</th><th>数量</th><th>小計 (税込)</th><th></th>
                        </tr>
                        <?php
                            foreach($cart_data as $value) {
                        ?>
                        <tr>
                            <td><img src="./item_img/<?php print h($value['img']); ?>"></td>
                            <td><p><?php print h($value['name']); ?></p></td>
                            <td><p><?php print h($value['price']); ?></p></td>
                            <td>
                                <form method="post">
                                    <p>
                                    <select name="amount" class="amount">
                                        <?php

                                            $max_amount = 0;  // $max_amountの初期化
                                            if ($value['stock'] < CART_MAX_AMOUNT) {
                                                $max_amount = $value['stock'];
                                            } else {
                                                $max_amount = CART_MAX_AMOUNT;
                                            }
                                            
                                            for ($i = 1; $i <= $max_amount; $i++) {
                                            ?>
                                                <option value="<?php print $i; ?>" <?php if ($i === $value['amount']) { print 'selected'; } ?>><?php print $i; ?></option>
                                            <?php
                                            }
                                        ?>
                                    </select>個
                                    
                                    <input type="submit" class="update_btn" name="sub" value="変更する">
                                    <input type="hidden" name="type" value="update">
                                    <input type="hidden" name="item_id" value="<?php print h($value['item_id']); ?>">
                                    <input type="hidden" name="token" value="<?php print $token; ?>">
                                    </p>
                                </form>
                            </td>
                            <td><?php h(print $value['price'] * $value['amount']); ?></td>
                            <td>
                                <form method="post">
                                    <input type="submit" class="delete_btn" name="delete" value="削除">
                                    <input type="hidden" name="type" value="delete">
                                    <input type="hidden" name="item_id" value="<?php print h($value['item_id']); ?>">
                                    <input type="hidden" name="token" value="<?php print $token; ?>">
                                </form>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
                    <div class="price_sum">
                        小計（<?php print h($total_amount); ?>点）：<?php print h($total_price); ?>円
                    </div>
                    <form method="post" action="./finish.php">
                        <input type="submit" name="sub" class="checkout_btn" value="レジに進む">
                        <input type="hidden" name="type" value="checkout">
                        <input type="hidden" name="token" value="<?php print $token; ?>">
                    </form>
                <?php } else { ?>
                    <br><br><h4>カートに商品が入っていません。</h4><br><br>
                <?php } ?>
            </div>
        </main>
        <!-- フッター -->
        <?php include VIEW_PATH_FOOTER; ?>
    </body>
</html>