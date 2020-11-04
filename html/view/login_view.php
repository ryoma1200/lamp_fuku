<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>FUKUYA</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href = <?php print VIEW_PATH.'html5reset-1.6.1.css'; ?>>
        <link rel="stylesheet" href = <?php print VIEW_PATH.'common.css'; ?>>
        <link rel="stylesheet" href = <?php print VIEW_PATH.'login_view.css'; ?>>
    </head>
    <body>
        <!-- ヘッダー -->
        <?php include VIEW_PATH_HEADER; ?>

        <main>
            <h2>Log in</h2>
            <section>
                <div class="login_box">
                    <form method="post">
                        <h3>ログイン</h3>
                        <p>アカウントをお持ちの方はこちらからログインできます</p>
                        <?php foreach ($err_msg as $value) {
                            print '<p class="err_msg">'.$value.'</p>';
                        } ?>
                        <p>User ID :</p>
                        <input type="text" class="form" name="username" value="">
                        <p>Password :</p>
                        <input type="password" class="form" name="passwd" value=""><br>
                        <input type="submit" name="sub" value="ログイン">
                        <input type="hidden" name="type" value="login">
                        <input type="hidden" name="token" value="<?php print $token; ?>">
                    </form>
                </div>
                <div class="create_account">
                    <form method="post" action="./register.php">
                        <h3>新規登録</h3>
                        <p>初めてご利用される方は</p><p>こちらからご登録ください</p>
                        <input type="submit" name="sub" value="アカウント作成">
                        <input type="hidden" name="token" value="<?php print $token; ?>">
                    </form>
                </div>
            </section>
        </main>
        <!-- フッター -->
        <?php include VIEW_PATH_FOOTER; ?>
    </body>
</html>