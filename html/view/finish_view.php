<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>FUKUYA</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./view/html5reset-1.6.1.css">
        <link rel="stylesheet" href = "./view/common.css">
        <link rel="stylesheet" href = "./view/finish_view.css">
    </head>
    <body>
        <!-- ヘッダー -->
        <?php include VIEW_PATH_HEADER; ?>
        <main>
            <section>
                <?php 
                if (!empty($err_msg)) {
                    foreach ($err_msg as $value) {
                    ?>
                        <br><h2><?php print $value; ?></h2>
                    <?php } ?>
                    <h2>もう一度お試しください</h2>
                <?php } else { ?> 
                <h2>お買い上げいただきありがとうございました。</h2>
                <div class="result_box">
                    <h2>今回購入した商品</h2>
                    <table>
                        <tr>
                            <th>商品</th><th>商品名</th><th>単価(税込)</th><th>数量</th><th>小計 (税込)</th>
                        </tr>
                        <?php foreach($cart_data as $value) { ?>
                        <tr>
                            <td><img src="./item_img/<?php print h($value['img']); ?>"></td>
                            <td><p><?php print h($value['name']); ?></p></td>
                            <td><p><?php print h($value['price']); ?></p></td>
                            <td>
                                <form method="post">
                                    <p><?php print h($value['amount']); ?>個</p>
                                </form>
                            </td>
                            <td><?php print h($value['price'] * $value['amount']); ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <div class="price_sum">
                        <p>小計（<?php print h($total_amount); ?>点）：<?php print h($total_price); ?>円</p>
                    </div>
                </div>
                <?php } ?>
            </section>
        </main>
        <!-- フッター -->
        <?php include VIEW_PATH_FOOTER; ?>
    </body>
</html>