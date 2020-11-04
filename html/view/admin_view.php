<!DOCTYPE html>
<html lang="ja">
    <meta charset="utf-8">
    <title>ユーザー管理画面</title>
    <link rel="stylesheet" href = "./view/admin_common.css">
</html>
<body>
    <?php
    $year = (int)date('Y');   // 現在の西暦を取得(生年月日のセレクトボックス用)
    ?>
    <h1>ユーザー管理画面</h1>
    <h2>ユーザーの追加</h2>
        <?php
            if (!empty($err_msg)) {
                foreach ($err_msg as $values) {
                    print '<p class="err_msg">'.$values.'</p>'; 
                }
            }
            
            if (!empty($comp_msg)) {
                print '<p class="comp_msg">'.$comp_msg.'</p>';
            }
        ?>
        <form method="post">
            <table>
                <tr>
                    <th>User_name：</th>
                    <td><input type="text" name="name" value="<?php print $name; ?>" class="large_box">&nbsp;<span class="instr">6〜12桁の半角英数文字を入力</span></td>  
                </tr>
                <tr>
                    <th>Password：</th>
                    <td><input type="text" name="passwd" value="<?php print $passwd; ?>" class="large_box">&nbsp;<span class="instr">6桁〜20の半角英数文字を入力</span></td>  
                </tr>
                <tr>
                    <th>E-mail：</th>
                    <td><input type="text" name="mail" value="<?php print $mail; ?>" class="large_box">&nbsp;<span class="instr">半角英数文字を入力</span></td>  
                </tr>
                <tr>
                    <th>Sex：</th>
                    <td>
                        <input type="radio" name="sex" value="1" <?php if (1 === $sex) { print 'checked';} ?>>男
                        <input type="radio" name="sex" value="2" <?php if (2 === $sex) { print 'checked';} ?>>女
                        <input type="radio" name="sex" value="3" <?php if (3 === $sex) { print 'checked';} ?>>その他&nbsp;<span class="instr">該当するものにチェック</span>
                    </td>  
                </tr>
                <tr>
                    <th>Birthdate：</th>
                    <td>
                        <select name="birthdate_year">
                            <?php
                                for ($i = $year; $i >= 1930; $i--) {
                            ?>
                                    <option value="<?php print $i; ?>"<?php if ($i === $birthdate_year) { print 'selected';} ?>><?php print $i; ?></option>    
                            <?php
                                }
                            ?>
                        </select>&nbsp;年
                        <select name="birthdate_month">
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                            ?>
                                <option value="<?php print $i; ?>"<?php if ($i === $birthdate_month) { print 'selected';} ?>><?php print $i; ?></option>    
                            <?php
                            }
                            ?>
                        </select>&nbsp;月
                        <select name="birthdate_day">
                            <?php
                            for ($i = 1; $i <= 31; $i++) {
                            ?>
                                <option value="<?php print $i; ?>" <?php if ($i === $birthdate_day) { print 'selected';} ?>>
                                <?php print $i; ?>
                                </option>    
                            <?php
                            }
                            ?>
                        </select>&nbsp;日
                    </td>  
                </tr>
                <tr>
                    <th>Height：</th>
                    <td>
                        <select name="height">
                            <option value="0" <?php if (0 === $height) { print 'selected';} ?>>未選択</option>
                            <option value="1" <?php if (1 === $height) { print 'selected';} ?>>〜150</option>
                            <option value="2" <?php if (2 === $height) { print 'selected';} ?>>151〜155</option>
                            <option value="3" <?php if (3 === $height) { print 'selected';} ?>>156〜160</option>
                            <option value="4" <?php if (4 === $height) { print 'selected';} ?>>161〜165</option>
                            <option value="5" <?php if (5 === $height) { print 'selected';} ?>>166〜170</option>
                            <option value="6" <?php if (6 === $height) { print 'selected';} ?>>171〜175</option>
                            <option value="7" <?php if (7 === $height) { print 'selected';} ?>>176〜180</option>
                            <option value="8" <?php if (8 === $height) { print 'selected';} ?>>181〜185</option>
                            <option value="9" <?php if (9 === $height) { print 'selected';} ?>>186〜</option>
                        </select>&nbsp;cm&nbsp;
                    </td>  
                </tr>
                <tr>
                    <th>Shoe_size：</th>
                    <td>
                        <select name="shoe_size">
                            <option value="0" <?php if (0 === $shoe_size) { print 'selected';} ?>>未選択</option>
                            <option value="1" <?php if (1 === $shoe_size) { print 'selected';} ?>>〜22.0</option>
                            <option value="2" <?php if (1 === $shoe_size) { print 'selected';} ?>>22.0〜23.0</option>
                            <option value="3" <?php if (2 === $shoe_size) { print 'selected';} ?>>23.0〜24.0</option>
                            <option value="4" <?php if (3 === $shoe_size) { print 'selected';} ?>>24.0〜25.0</option>
                            <option value="5" <?php if (4 === $shoe_size) { print 'selected';} ?>>25.0〜26.0</option>
                            <option value="6" <?php if (5 === $shoe_size) { print 'selected';} ?>>26.0〜27.0</option>
                            <option value="7" <?php if (6 === $shoe_size) { print 'selected';} ?>>27.0〜28.0</option>
                            <option value="8" <?php if (7 === $shoe_size) { print 'selected';} ?>>28.0〜</option>
                        </select>&nbsp;cm
                    </td>  
                </tr>
            </table>
            <div class="add">
                <input type="submit" name="sub" class="add" value="追加する">
                <input type="hidden" name="type" value="add">
            </div>
        </form>
    <h2>ユーザー情報の管理</h2>
    <?php
    foreach ($up_err_msg as $values) {
        print '<p class="err_msg">'.$values.'</p>'; 
    }
    
    if (!empty($up_comp_msg)) {
        print '<p class="comp_msg">'.$up_comp_msg.'</p>';
    }
    ?>
    <table class="display">
        <tr>
            <th>ユーザ名<br>User_name</th>
            <th>パスワード<br>Password</th>
            <th>メールアドレス<br>E-mail</th>
            <th>性別<br>Sex</th>
            <th>生年月日<br>Birthdate</th>
            <th>登録日<br>Registration date</th>
            <th>変更</th>
            <th>削除</th>
        </tr>
        <?php
        foreach ($all_userdata as $value) {
        ?>
            <tr>
                <form method="post">
                    <!-- User_name -->
                    <td>
                        <p><?php print $value['username']; ?></p>
                        <span class="up_text">変更</span><br>
                        <input type="text" class="normal_box" name="up_name" value=""></td>
                    </td>
                    
                    <!-- Password -->
                    <td>
                        <p><?php print $value['password']; ?></p>
                        <span class="up_text">変更</span><br>
                        <input type="text" class="normal_box" name="up_passwd" value="">
                    </td>
                    
                    <!-- E-mail -->
                    <td>
                        <p><?php print $value['mail']; ?></p>
                        <span class="up_text">変更</span><br>
                        <input type="text" class="large_box" name="up_mail" value="">
                    </td>
                    
                    <!-- Sex -->
                    <td>
                    <p>
                    <?php
                        if ($value['sex'] === 1) {
                            print '男性';
                        } else if ($value['sex'] === 2) {
                            print '女性';
                        } else if ($value['sex'] === 3) {
                            print 'その他';
                        }                        
                    ?>
                    </p>
                    <span class="up_text">変更</span><br>
                    <select name="up_sex">
                        <option value="0">選択
                        <option value="1">男
                        <option value="2">女
                        <option value="3">その他
                    </select>
                    </td>
                        
                    <!-- Birthdate -->
                    <td>
                        <p><?php 
                        $table_date = explode(' ', $value['birthdate'], 2);
                        print $table_date[0]; 
                        ?></p>
                        <span class="up_text">変更</span><br>
                        <select name="up_birthdate_year">
                            <option value="">-選択-</option>    
                            <?php
                                for ($i = $year; $i >= 1930; $i--) {
                            ?>
                                    <option value="<?php print $i; ?>"<?php if ($i === $birthdate_year) { print 'selected';} ?>><?php print $i; ?></option>    
                            <?php
                                }
                            ?>
                        </select>&nbsp;年
                        <select name="up_birthdate_month">
                            <option value="">-選択-</option>    
                            <?php for ($i = 1; $i <= 12; $i++) { ?>
                                <option value="<?php print $i; ?>"<?php if ($i === $birthdate_month) { print 'selected';} ?>><?php print $i; ?></option>    
                            <?php } ?>
                        </select>&nbsp;月
                        <select name="up_birthdate_day">
                            <option value="">-選択-</option>    
                            <?php for ($i = 1; $i <= 31; $i++) { ?>
                                <option value="<?php print $i; ?>" <?php if ($i === $birthdate_day) { print 'selected';} ?>>
                                <?php print $i; ?>
                                </option>    
                            <?php } ?>
                        </select>&nbsp;日
                    </td>                   
    
                    
                    <!-- Registration date -->
                    <td>
                        <?php print $value['createdate']; ?>
                    </td>
                    <td>
                        <input type="submit" name="up" class="small_box" value="変更">
                        <input type="hidden" name="type" value="update">
                        <input type="hidden" name="id" value="<?php print $value['id']; ?>">
                    </td>
                </form>  
                <form method="post">
                    <td>
                        <input type="submit" name="up" class="small_box" value="削除">
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