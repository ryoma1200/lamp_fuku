<!DOCTYPE html>
<html lang="ja">
    <meta charset="utf-8">
    <title>商品管理画面</title>
    <link rel="stylesheet" href = "./view/admin_common.css">
    
</html>
<body>
    <h1>商品管理画面</h1>
    <h2>商品の追加</h2>
        <?php
            foreach ($err_msg as $values) {
                print '<p class="err_msg">'.$values.'</p>'; 
            }

            if (empty($comp_msg) !== true) {
                print '<p class="comp_msg">'.$comp_msg.'</p>';
            }

        ?>
        &nbsp;「<span class="red">&nbsp;*&nbsp;</span>」がついた項目は必ず入力してください。
        <form method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <th><span class="red">*&nbsp;</span>Name：</th>
                    <td><input type="text" name="item_name" value="<?php print $item_name; ?>" class="large_box">&nbsp;<span class="instr">3〜50文字</span></td>  
                </tr>
                <tr>
                    <th><span class="red">*&nbsp;</span>Price：</th>
                    <td><input type="text" name="price" value="<?php print $price; ?>" class="small_box">&nbsp;<span class="instr">半角整数を入力</span></td>  
                </tr>
                <tr>
                    <th><span class="red">*&nbsp;</span>Category：</th>
                    <td>
                        <input type="radio" name="category" value="1">Tops
                        <input type="radio" name="category" value="2">Bottoms
                        <input type="radio" name="category" value="3">Shoes&nbsp;<span class="instr">該当するものにチェック</span>
                    </td>     
                </tr>
                <tr>
                    <th><span class="red">*&nbsp;</span>Size：</th>
                    <td>
                        <input type="radio" name="size" value="1">Free
                        <input type="radio" name="size" value="2">S
                        <input type="radio" name="size" value="3">M
                        <input type="radio" name="size" value="4">L
                        <input type="radio" name="size" value="5">XL&nbsp;<span class="instr">該当するものにチェック</span>
                    </td>  
                </tr>
                <tr>
                    <th><span class="red">*&nbsp;</span>Picture：</th>
                    <td>
                        <input type="file" name="file" value="" class="large_box">&nbsp;
                        <span class="instr">png形式またはjpg形式のみ有効です。</span>
                    </td>  
                </tr>
                <tr>
                    <th><span class="red">*&nbsp;</span>Stock：</th>
                    <td><input type="text" name="stock" value="<?php print $stock; ?>" class="small_box">&nbsp;個&nbsp;<span class="instr">半角整数を入力する</span></td>  
                </tr>
                <tr>
                    <th><span class="red">*&nbsp;</span>Status：</th>
                    <td>
                    <input type="radio" name="status" value="1">公開
                    <input type="radio" name="status" value="2">非公開
                    </td> 
                </tr>
                <tr>
                    <th>Comment：</th>
                    <td>
                        <textarea name="comment" cols="50" rows="5"><?php print $comment; ?></textarea>&nbsp;<span class="instr">300字以内</span>
                    </td>
                </tr>
                <tr>
                    <th>Column_URL：</th>
                    <td>
                        <span class="instr">/fukuya/column/</span><input type="text" name="column_name" value=""> &nbsp;<span class="instr">コラムページのファイル名を入力</span>
                    </td>
                </tr>
            </table>
            <div class="add">
                <input type="submit" name="sub" value="商品を追加する">
                <input type="hidden" name="type" value="add_item">
            </div>
        </form>
    <h2>商品情報の管理</h2>
        <?php
        // 更新エラーメッセージ
        if (!empty($up_err_msg)) {
            foreach ($up_err_msg as $values) {
                print '<p class="err_msg">'.$values.'</p>'; 
            }
        }
        
        // 更新完了メッセージ    
        if (!empty($up_comp_msg)) {
            print '<p class="comp_msg">'.$up_comp_msg.'</p>';
        }
        ?>
    <table class="display">
        <tr>
            <th>商品ID</th>
            <th>商品画像<br>Picture</th>
            <th>商品名<br>Name</th>
            <th>価格<br>Price</th>
            <th>カテゴリ<br>Category</th>
            <th>サイズ<br>Size</th>
            <th>在庫<br>Stock</th>
            <th>ステータス<br>Status</th>
            <th>コラム<br>Column Name</th>
            <th>コメント<br>Comment</th>
            <th>登録日<br>Registration Date</th>
            <th>変更</th>
            <th>削除</th>
        </tr>
        <?php
        foreach ($all_itemdata as $value) {
        ?>
            <tr>
                <form method="post" enctype="multipart/form-data">
                    
                    <!-- id -->
                    <td>
                        <p><?php print $value['id']; ?></p>
                    </td>                
                    
                    <!-- img -->
                    <td>
                        <div class="img_box">
                            <img src="./item_img/<?php print $value['img']; ?>" class="normal_box">
                        </div>
                        <input type="file" name="up_file" value="">
                    </td>
                    
                    <!-- name -->
                    <td>
                        <p><?php print $value['name']; ?></p>
                        <span class="up_text">変更</span><br>
                        <input type="text" class="normal_box" name="up_item_name" value="">
                    </td>
                    
                    <!-- price -->
                    <td>
                        <p><?php print $value['price']; ?></p>
                        <span class="up_text">変更</span><br>
                        <input type="text" class="small_box" name="up_price" value="">
                    </td>
                    
                    <!-- category -->
                    <td>
                        <p>
                        <?php
                            if ($value['category'] === 1) {
                                print 'Tops';
                            } else if ($value['category'] === 2) {
                                print 'Bottoms';
                            } else if ($value['category'] === 3) {
                                print 'Shoes';
                            }                        
                        ?>
                        </p>
                        <span class="up_text">変更</span><br>
                        <select name="up_category">
                            <option value="0">選択
                            <option value="1">Tops
                            <option value="2">Bottoms
                            <option value="3">Shoes          
                        </select>
            
                    <!-- size -->
                    <td>
                        <p>
                        <?php
                            if ($value['size'] === 1) {
                                print 'Free';
                            } else if ($value['size'] === 2) {
                                print 'S';
                            } else if ($value['size'] === 3) {
                                print 'M';
                            } else if ($value['size'] === 4) {
                                print 'L';
                            } else if ($value['size'] === 5) {
                                print 'XL';
                            }
                        ?>
                        </p>
                        <span class="up_text">変更</span><br>
                        <select name="up_size">
                            <option value="0">選択
                            <option value="1">Free
                            <option value="2">S
                            <option value="3">M
                            <option value="4">L
                            <option value="5">XL
                        </select>
                    </td>
                        
                    <!-- stock -->
                    <td>
                        <p><?php print $value['stock']; ?></p>
                        <span class="up_text">変更</span><br>
                        <input name="up_stock" class="small_box" value="">
                    </td>
                    
                    <!-- status -->
                    <td>
                        <p>
                        <?php
                            if ($value['status'] === 1) {
                                print '公開';
                            } else if ($value['status'] === 2) {
                                print '非公開';
                            }                  
                        ?>
                        </p>
                        <span class="up_text">変更</span><br>
                        <select name="up_status">
                            <option value="0">選択
                            <option value="1">公開
                            <option value="2">非公開
                        </select>
                    </td>
                        
                    <!-- column_name -->
                    <td>
                        <p><?php print $value['column_name']; ?></p>
                        <span class="up_text">変更</span><br>
                        <input type="text" class="normal_box" name="up_column_name" value="">
                    </td>
                    
                    
                    <!-- comment -->
                    <td>
                        <span class="up_text">変更</span><br>
                        <textarea name="up_comment" cols="50" rows="5"><?php print $value['comment']; ?></textarea>
                    </td>
                    
                    <!-- Registration date -->
                    <td>
                        <p><?php print $value['createdate']; ?></p>
                    </td>
                    
                    <!-- 変更 -->
                    <td>
                        <input type="submit" name="sub" value="変更">
                        <input type="hidden" name="type" value="update">
                        <input type="hidden" name="id" value="<?php print $value['id']; ?>">
                    </td>
                </form>
                <form method="post">
                    <!-- 削除 -->
                    <td>
                        <input type="submit" name="sub" value="削除">
                        <input type="hidden" name="type" value="delete">
                        <input type="hidden" name="id" value="<?php print $value['id']; ?>">
                    </td>
                </form>
            </tr>
            <?php
            }
            ?>
        
        
        
    </table>
</body>
</html>