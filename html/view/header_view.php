<header>
    <div class="wrapper">
        <h1><a class="logo" href="<?php print HOME_URL; ?>">FUKUYA</a></h1>
        <div class="menu">
            <?php if (is_logined() === true) {?>
                <div class="top_msg">
                    <span>ようこそ&nbsp;<?php print h($userdata['username']); ?>&nbsp;さん</span>
                    <form method="post">
                        <input type="submit" class="logout" name="sub" value="ログアウト">
                        <input type="hidden" name="type" value="logout">
                        <input type="hidden" name="token" value="<?php print $token; ?>">
                    </form>
                </div>
                <ul>
                    <a class="menu_nav" href="<?php print HOME_URL.'#top'; ?>"><li>TOP</li></a>
                    <a class="menu_nav" href="<?php print HOME_URL.'#column'; ?>"><li>COLUMN</li></a>
                    <a class="menu_nav" href="<?php print HOME_URL.'#shopping'; ?>"><li>SHOPPING</li></a>
                    <a class="menu_nav" href="<?php print CART_URL; ?>"><li>CART</li></a>
                    <a class="menu_nav" href="<?php print USER_PAGE_URL; ?>"><li>MY PAGE</li></a>
                </ul>

            <?php } else { ?>
                <div class="top_msg">
                    <span>ようこそ&nbsp;ゲスト&nbsp;さん&emsp;</span>
                </div>
                <ul>
                    <a class="menu_nav" href="<?php print HOME_URL.'#top'; ?>"><li>TOP</li></a>
                    <a class="menu_nav" href="<?php print HOME_URL.'#column'; ?>"><li>COLUMN</li></a>
                    <a class="menu_nav" href="<?php print HOME_URL.'#shopping'; ?>"><li>SHOPPING</li></a>
                    <a class="menu_nav" href="<?php print LOGIN_URL; ?>"><li>LOG IN</li></a>
                </ul>
            <?php
            }
            ?>
        </div>
    </div>
</header>