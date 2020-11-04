<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>FUKUYA</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href = <?php print VIEW_PATH.'html5reset-1.6.1.css'; ?>>
        <link rel="stylesheet" href = <?php print VIEW_PATH.'common.css'; ?>>
        <link rel="stylesheet" href = <?php print VIEW_PATH.'index_view.css'; ?>>
    </head>
    <body>
        <!-- ヘッダー -->
        <?php include VIEW_PATH_HEADER; ?>

        <main>
            <section id="top">
                <img src="./view/img/top.jpeg">
                <p class="message">WELCOM TO FUKUYA!</p>
            </section>
            <h2 id="column">COLUMN</h2>
            <p>今年のトレンドをチェック！</p>
            <p>FUKUYAオリジナル商品の着こなし方を紹介しています！</p><br>
            <article>
                <div class="column">
                    <img src="./view/img/article_1.jpeg">
                    <p>秋のスニーカー特集</p>
                </div>
                <div class="column">
                    <img src="./view/img/article_2.jpeg">
                    <p>秋の洋服、始まります</p>                    
                </div>
                <div class="column">
                    <img src="./view/img/article_3.jpeg">
                    <p>ON・OFF使える快適パンツ</p>                    
                </div>
                <div class="column">
                    <img src="./view/img/article_4.jpeg">
                    <p>秋のチェックスタイル</p>                    
                </div>
            </article>
            <h2 id="shopping">SHOPPING</h2>
            <p>お探しの商品は？</p>
            <p>あなたにピッタリの商品を見つけて、豊かな生活をお楽しみください！</p><br>
            <?php
                if (!empty($search_err_msg)) {
                    foreach ($search_err_msg as $value) {
                        print $value;
                    }
                }
            ?>
            <div class="search_form">
                <p class="search_index">商品を検索する</p>
                <form method="get" action="#shopping">
                    <p>キーワード&nbsp;：&nbsp;
                        <input type="search" name="keyword" value="<?php print h($search_condition->keyword); ?>">
                        <input type="checkbox" name="size_fit" value="1" <?php if ($search_condition->size_fit === 1) { print 'checked'; } ?>>体型にフィット
                    </p>
                    <div class="narrowed_condition">
                        <div class="narrowed_condition_box">
                            <p>カテゴリ</p>
                            <select name="category">
                                <option value="">未選択
                                <option value="1" <?php if ($search_condition->category === 1) print 'selected'; ?>>トップス
                                <option value="2" <?php if ($search_condition->category === 2) print 'selected'; ?>>ボトムス
                                <option value="3" <?php if ($search_condition->category === 3) print 'selected'; ?>>シューズ
                            </select>
                        </div>
                        <div class="narrowed_condition_box">
                            <p>サイズ</p>
                            <select name="size">
                                <option value="">未選択
                                <option value="1" <?php if ($search_condition->size === 1) print 'selected'; ?>>FREE
                                <option value="2" <?php if ($search_condition->size === 2) print 'selected'; ?>>S
                                <option value="3" <?php if ($search_condition->size === 3) print 'selected'; ?>>M
                                <option value="4" <?php if ($search_condition->size === 4) print 'selected'; ?>>L
                                <option value="5" <?php if ($search_condition->size === 5) print 'selected'; ?>>XL
                            </select>
                        </div>
                        <div class="narrowed_condition_box">
                            <p>値段</p>
                            <select name="price_min">
                                <option value="0" <?php if ($search_condition->price_min === 0) print 'selected'; ?>>0円
                                <option value="2000" <?php if ($search_condition->price_min == 2000) print 'selected'; ?>>2,000円
                                <option value="5000" <?php if ($search_condition->price_min == 5000) print 'selected'; ?>>5,000円
                                <option value="10000" <?php if ($search_condition->price_min == 10000) print 'selected'; ?>>10,000円
                            </select>
                            〜
                            <select name="price_max">    
                                <option value="">未選択
                                <option value="2000" <?php if ($search_condition->price_max == 2000) print 'selected'; ?>>2,000円
                                <option value="5000" <?php if ($search_condition->price_max == 5000) print 'selected'; ?>>5,000円
                                <option value="10000" <?php if ($search_condition->price_max == 10000) print 'selected'; ?>>10,000円
                            </select>
                        </div>
                        <div class="narrowed_condition_box">
                            <p>表示順序</p>
                            <select name="order">
                                <option value="">未選択
                                <option value="order_new" <?php if ($search_condition->order === 'order_new') print 'selected'; ?>>新着順
                                <option value="order_exp" <?php if ($search_condition->order === 'order_exp') print 'selected'; ?>>価格が高い順
                                <option value="order_reas" <?php if ($search_condition->order === 'order_reas') print 'selected'; ?>>価格が安い順
                            </select>
                        </div>
                    </div>
                    <p>
                        <input type="submit" name="sub" value="商品を検索">
                        <input type="hidden" name="type" value="search_condition">
                    </p>
                </form>
            </div>

            <h3>商品一覧</h3>
            <div class="shopping">
            <?php
            if (!empty($all_item_data)) {
                foreach ($all_item_data as $value) {
                    if ($value['status'] === 1) {
                    ?>
                    <div class="item">
                        <form method="get" name="form<?php print h($value['id']); ?>" action="./item.php">
                            <a href="javascript:form<?php print h($value['id']); ?>.submit()">
                                <div class="item_img">
                                    <img src="./item_img/<?php print h($value['img']); ?>">
                                </div>
                                <h4><?php print h($value['name']); ?></h4>
                                <p><?php print h($value['price']); ?>円</p>
                                <p>
                                <?php
                                    if ($value['stock'] === 0) {
                                        print '売り切れ';
                                        
                                    } else if ($value['stock'] > 0) { 
                                        
                                        if ($value['stock'] >= 10) {
                                            print '在庫あり';
                                            
                                        } else if ($value['stock'] < 10) {
                                            print '残り'.h($value['stock']).'個'; 
                                        }
                                    } 
                                ?>
                                </p>
                            </a>
                            <input type="hidden" name="type" value="item_page">
                            <input type="hidden" name="id" value="<?php print h($value['id']); ?>">
                        </form>
                    </div>
                    <?php
                    } 
                }
            } else {
            ?>
                <br><p>該当商品が見つかりませんでした。</p><br>
            <?php   
            }
            ?>
            </div>
        </main>

        <!-- フッター -->
        <?php include VIEW_PATH_FOOTER; ?>
    </body>
</html>