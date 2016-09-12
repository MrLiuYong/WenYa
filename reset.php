<?php
/*if(empty($_GET['telphone']))
{
    header('location: index.php');
    exit();
}*/




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>你问我答</title>
    <link href="css/main.css" rel="stylesheet">
    <link href="css/answer.css" rel="stylesheet">
    <link href="css/myquestion.css" rel="stylesheet">
    <link href="css/register.css" rel="stylesheet">
</head>
<body>
<div class="nav">
    <div class="nav-contain">
        <div class="logo">
            <h5>问呀~</h5>
        </div>
        <div class="serach">
            <form action="#" method="post">
                <input id="serach" type="text" name="search"><input id="submit" type="submit" name="submit">
            </form>
        </div>
        <div class="opertor">
            <button id="question"><a href="myquestion.php">提问</a></button>
            <button id="login"><a href="login.php">登录</a></button>
            <button id="register"><a href="register.php">注册</a></button>
        </div>
    </div>

</div>
<div class="main-content">
    <div class="left-content">
        <div class="title">
            <strong>密码重置</strong>
        </div>

        <div class="register-contain">
            <form action="process.php?cmd=resetpassword" method="post">
                <input id="telphone" type="email" name="telphone" placeholder="Telphone" value="<?php echo empty($_COOKIE['telphone'])?"":$_COOKIE['telphone'];?>" readonly>
                <input id="password" type="password" name="password" placeholder="password">
                <input id="passwordsec" type="password" name="passwordsec" placeholder="second password">
                <input id="bntregistere" type="submit" name="submit" value="重置密码">
            </form>
            <?php
            if(!empty($_GET['errno']))
            {
                $errno = $_GET['errno'];
                switch ($errno)
                {
                    case 1:
                        echo '* 请输入密码';break;
                    case 2:
                        echo '* 两次密码不相等';break;
                }
            }
            ?>
        </div>

    </div>
    <div class="right-nav">
        <div class="menu">
            <ul>
                <li><a href="#">热门问题</a></li>
                <li><a href="#">最新问题</a></li>
                <li><a href="#">我的关注</a></li>
                <li><a href="#">我的回答</a></li>
                <li><a href="#">我的提问</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="footer"></div>
</body>
</html>