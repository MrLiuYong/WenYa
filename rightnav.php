<div class="right-nav" disabled="true">
    <div class="menu">
        <ul class="menu-nav-list">
            <li><a href="index.php?type=1" style="color: #880000">热门问题</a></li>
            <li><a href="index.php?type=2" style="color: #880000">最新问题</a></li>
            <?php
            if(!empty($_SESSION['uid']))
            {
                echo '
                <li><a href="index.php?type=3" style="color: #880000">我的关注</a></li>
                <li><a href="index.php?type=4" style="color: #880000">我回答的问题</a></li>
                <li><a href="index.php?type=5" style="color: #880000"> 我的提问</a></li>
                ';
            }
            else
            {
                /*echo '
                <li><a href="index.php?type=3" readonly="readonly">我的关注</a></li>
            <li><a href="#">我的回答</a></li>
            <li><a href="#">我的提问</a></li>';*/
            }
            ?>

        </ul>
    </div>
</div>