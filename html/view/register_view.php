<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>FUKUYA</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./view/html5reset-1.6.1.css">
        <link rel="stylesheet" href="./view/common.css">
        <link rel="stylesheet" href = "./view/register_view.css">
    </head>
    <body>
        <!-- ヘッダー -->
        <?php include VIEW_PATH_HEADER; ?>
        <main>
            <h2>Register</h2>
            <section>
                <form method="post">
                    <h3>アカウント作成</h3>
                    <div class="err_msg_box">
                    <?php 
                    foreach ($err_msg as $value) {
                        print '<p class="err_msg">※'.$value.'</p>';
                    }
                    ?>
                    </div>
                    <table>
                        <tr>
                            <td>
                                <h4>USER ID</h4>
                                <p class="notice">6〜20字の半角英数文字を入力</p>
                            </td>
                            <td>
                                <input type="text" name="name" value="<?php print h($userdata->username); ?>" class="middle_box">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Password</h4>
                                <p class="notice">6〜20字の半角英数文字を入力</p>
                            </td>
                            <td>
                                <input type="password" name="passwd" value="<?php print h($userdata->password); ?>" class="middle_box">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="password" name="passwd_check" value="" class="middle_box"><span>&nbsp;（確認）</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4>E-mail</h4>
                                <p class="notice">半角英数文字を入力</p>
                            </td>
                            <td>
                                <input type="text" name="mail" value="<?php print h($userdata->mail); ?>" class="large_box">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Sex</h4>
                                <p class="notice">該当するものを選択してください</p>
                            </td>
                            <td>
                                <p>
                                    <input type="radio" name="sex" value="1" <?php if ($userdata->sex === SEX_MAN) {print 'checked';} ?>>男
                                    <input type="radio" name="sex" value="2" <?php if ($userdata->sex === SEX_WOMAN) {print 'checked';} ?>>女
                                    <input type="radio" name="sex" value="3" <?php if ($userdata->sex === SEX_OTHERS) {print 'checked';} ?>>その他
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Birthdate</h4>
                                <p class="notice">半角整数を入力する</p>
                            </td>
                            <td>
                                <p><select name="birthdate_year" class="box_year">
                                    <?php 
                                        $year = (int)date('Y');
                                        for ($i = $year; $i >= 1930; $i--) { 
                                    ?>
                                        <option value="<?php print h($i); ?>"<?php if ($i === $userdata->birthdate_year) { print 'selected';} ?>><?php print h($i); ?></option>    
                                    <?php } ?>
                                </select>&nbsp;年
                                <select name="birthdate_month" class="box_days">
                                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                                        <option value="<?php print h($i); ?>"<?php if ($i === $userdata->birthdate_month) { print 'selected';} ?>><?php print h($i); ?></option>    
                                    <?php } ?>
                                </select>&nbsp;月
                                <select name="birthdate_day" class="box_days">
                                    <?php for ($i = 1; $i <= 31; $i++) { ?>
                                    <option value="<?php print h($i); ?>" <?php if ($i === $userdata->birthdate_day) { print 'selected';} ?>><?php print h($i); ?></option>    
                                <?php } ?>
                                </select>&nbsp;日</p>
                            </td>
                        </tr>
                    </table>
                    <input type="submit" name="sub" class="add" value="アカウントを作成する">
                    <input type="hidden" name="type" value="submit_new_data">
                    <input type="hidden" name="token" value="<?php print $token; ?>">
                </form>
            </section>
        </main>
        <!-- フッター -->
        <?php include VIEW_PATH_FOOTER; ?>
    </body>
</html>