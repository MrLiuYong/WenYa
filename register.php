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
<?php require_once 'header.php';?>
<div class="main-content">
    <div class="left-content">
        <div class="title">
            <strong>注册</strong>
        </div>

        <div class="register-contain">
            <form action="process.php?cmd=register" method="post">
                <input type="email" name="email" placeholder="【邮箱】是必须的哦，我们会发邮件验证的">
                <input type="text" name="nickname" placeholder="【网名】必须得彰显出我的个性">
                <input id="telphone" type="text" name="telphone" placeholder="telphone" value="<?php echo empty($_COOKIE['telphone'])? "" : $_COOKIE['telphone']; ?>">
                <input id="password" type="password" name="password" placeholder="【密码】我承诺，打死我都不告诉任何人">
                <input id="passwordsec" type="password" name="passwordsec" placeholder="【再次密码】我手有抖吗">
                <input id="bntregistere" type="submit" name="submit" value="必须要注册啊">
            </form>
            <div class="errormsg">
                <?php
                if(!empty($_GET['errno']))
                {
                    $errno = $_GET['errno'];
                    switch ($errno)
                    {
                        case 1: echo '* 电话号码不能为空';break;
                        case 2: echo '* 密码不能为空';break;
                        case 3: echo '* 两次密码不一致';break;
                        case 4: echo '* 该电话号码已经被注册';break;
                        case 5: echo '* 服务器繁忙，请稍后再试';break;
                    }
                }
                ?>
            </div>
        </div>

    </div>
    <?php require_once 'rightnav.php';?>
</div>
<?php require_once 'footer.php';?>
</body>
</html>