<!DOCTYPE html>
<html lang="ja">
<head>
    <title>FUKUYA</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./view/html5reset-1.6.1.css">
    <link rel="stylesheet" href = "./view/common.css">
    <link rel="stylesheet" href = "./view/user_page_view.css">
</head>
<body>
    <!-- ヘッダー -->
    <?php include VIEW_PATH_HEADER; ?>
    <main>
        <h2>MY PAGE</h2>
        <div class="user_page_box">
            <p class="user_page_msg">
            <?php
            foreach($err_msg as $value) {
                print $value;
            }
            if (isset($comp_msg)) {
                print $comp_msg;
            }
            ?>
            </p>
            <table>
                <tr>
                    <td class="entry">ユーザ名</td>
                    <td class="user_info">
                        <p><?php print h($userdata['username']); ?></p>
                        <div class="edit_box" onclick="obj=document.getElementById('user_id').style; obj.display=(obj.display=='none')?'block':'none';">
                        <a style="cursor:pointer;" class="edit_btn">編集する</a>
                        </div>
                        
                        <div id="user_id" style="display:none;clear:both;" class="hidden_box">
                            <p class="small_text">新しいユーザ名</p>
                            <form method="post">
                                <input type="text" class="middle_box" name="username" value="">
                                <input type="submit" name="sub" value="変更">
                                <input type="hidden" name="type" value="up_name">
                                <input type="hidden" name="token" value="<?php print $token; ?>">
                            </form></p>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td class="entry">パスワード</td>
                    <td class="user_info">
                        <p>●●●●●●●●<?php //print h($userdata['password']); ?></p>
                        <div class="edit_box" onclick="obj=document.getElementById('user_passwd').style; obj.display=(obj.display=='none')?'block':'none';">
                        <a style="cursor:pointer;" class="edit_btn">編集する</a>
                        </div>
                        <div id="user_passwd" style="display:none;clear:both;" class="hidden_box">
                            <form method="post">
                                <p class="small_text">現在のパスワード</p><input type="text" class="middle_box" name="current_passwd" value="">
                                <p class="small_text">新しいパスワード</p><input type="text" class="middle_box" name="new_passwd1" value="">
                                <p class="small_text">新しいパスワード(確認)</p><input type="text" class="middle_box" name="new_passwd2" value="">
                                <input type="submit" name="sub" value="変更">
                                <input type="hidden" name="type" value="new_passwd">
                                <input type="hidden" name="token" value="<?php print $token; ?>">
                            </form>
                        </div>
                    </td>
                </tr>   
                <tr>
                    <td class="entry">メールアドレス</td>
                    <td class="user_info">
                        <p><?php print h($userdata['mail']); ?></p>
                        <div class="edit_box" onclick="obj=document.getElementById('user_email').style; obj.display=(obj.display=='none')?'block':'none';">
                        <a style="cursor:pointer;" class="edit_btn">編集する</a>
                        </div>
                        <div id="user_email" style="display:none;clear:both;" class="hidden_box">
                            <p><form method="post">
                                <p class="small_text">新しいメールアドレス</p><input type="email" class="large_box" name="new_mail" value="">
                                <input type="submit" name="sub" value="変更">
                                <input type="hidden" name="type" value="new_mail">
                                <input type="hidden" name="token" value="<?php print $token; ?>">
                            </form></p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="entry">あなたの体型</td>
                    <td class="user_info">
                        <table class="user_phys">
                            <tr>
                                <td><p>身長：</p></td>
                                <td><p>
                                <?php
                                switch ($userdata['height']) {
                                    case 0:
                                    echo '未選択です'; 
                                    break;
                                    
                                    case 1:
                                    echo '〜 150'; 
                                    break;
                                    
                                    case 2:
                                    echo '151 〜 155'; 
                                    break;
                                    
                                    case 3:
                                    echo '156〜160'; 
                                    break;
                                    
                                    case 4:
                                    echo '161〜165'; 
                                    break;
                                    
                                    case 5:
                                    echo '166〜170'; 
                                    break;
                                    
                                    case 6:
                                    echo '171〜175'; 
                                    break;
                                    
                                    case 7:
                                    echo '176〜180'; 
                                    break;
                                    
                                    case 8:
                                    echo '181〜185'; 
                                    break;
                                    
                                    case 9:
                                    echo '186〜'; 
                                    break;
                                }
                                ?>&nbsp;cm</p></td>
                            </tr>
                            <tr>
                                <td><p>靴のサイズ：</p></td>
                                <td><p>
                                <?php 
                                switch ($userdata['shoe_size']) {
                                    case 0:
                                    echo '未選択です'; 
                                    break;
                                    
                                    case 1:
                                    echo '〜22.0'; 
                                    break;
                                    
                                    case 2:
                                    echo '22.0〜23.0'; 
                                    break;
                                    
                                    case 3:
                                    echo '23.0〜24.0'; 
                                    break;
                                    
                                    case 4:
                                    echo '24.0〜25.0'; 
                                    break;
                                    
                                    case 5:
                                    echo '25.0〜26.0'; 
                                    break;
                                    
                                    case 6:
                                    echo '26.0〜27.0'; 
                                    break;
                                    
                                    case 7:
                                    echo '27.0〜28.0'; 
                                    break;
                                    
                                    case 8:
                                    echo '28.0〜'; 
                                    break;
                                }
                                ?>&nbsp;cm</p></td>
                            </tr>
                        </table>
                        <div class="edit_box" onclick="obj=document.getElementById('user_body').style; obj.display=(obj.display=='none')?'block':'none';"  class="hidden_box">
                        <a style="cursor:pointer;" class="edit_btn">編集する</a>
                        </div>
                        <div id="user_body" class="hidden_box phys_box" style="display:none;clear:both;">
                            <form method="post">
                                <span class="select_text">身長</span>
                                
                                <select name="height" class="select_box">
                                    <option value="0">未選択</option>
                                    <option value="1">〜150</option>
                                    <option value="2">151〜155</option>
                                    <option value="3">156〜160</option>
                                    <option value="4">161〜165</option>
                                    <option value="5">166〜170</option>
                                    <option value="6">171〜175</option>
                                    <option value="7">176〜180</option>
                                    <option value="8">181〜185</option>
                                    <option value="9">186〜</option>
                                </select>&nbsp;cm
                                <input type="hidden" name="type" value="new_height">
                                <input type="submit" name="sub" value="変更">
                                <input type="hidden" name="token" value="<?php print $token; ?>">
                            </form>
                            <form method="post">
                                <span class="select_text">靴のサイズ</span>
                                <select name="shoe_size" class="select_box">
                                    <option value="0">未選択</option>
                                    <option value="1">〜22.0</option>
                                    <option value="2">22.0〜23.0</option>
                                    <option value="3">23.0〜24.0</option>
                                    <option value="4">24.0〜25.0</option>
                                    <option value="5">25.0〜26.0</option>
                                    <option value="6">26.0〜27.0</option>
                                    <option value="7">27.0〜28.0</option>
                                    <option value="8">28.0〜</option>
                                </select>&nbsp;cm
                                <input type="hidden" name="type" value="new_shoe_size">
                                <input type="submit" name="sub" value="変更">
                                <input type="hidden" name="token" value="<?php print $token; ?>">
                            </form>
                        </div>
                    </td>
                </tr>                
            </table>
        </div>
    </main>
    <!-- フッター -->
    <?php include VIEW_PATH_FOOTER; ?>
</body>
</html>