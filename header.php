<?php

$myinfo = '<button id="login" onclick="javascirpt:location.href=\'login.php\';">登录</button>
            <button id="register" onclick="javascirpt:location.href=\'register.php\';">注册';
if(!empty($_SESSION['uid']))
{
    require_once 'db/userdb.php';
    $userinfo = ModeGetOneUserInfo($_SESSION['uid']);
    $nm = empty($userinfo['nickname']) ? $userinfo['telphone'] : $userinfo['nickname'];
    $myinfoformat = '<div class="my-icon"><a href="myinfo.php"><img src="%s"></a></div>
            <div class="my-uname">%s</div><div class="my-quit"><a href="process.php?cmd=quit">退出</a></div> ';
    $myinfo = sprintf($myinfoformat, $userinfo['icon'], $nm);
}
?>

<div class="nav">
    <div class="nav-contain">
        <div class="logo">
            <a href="index.php" style="color: #ffffff"><h5>问呀~</h5></a>
        </div>
        <div class="serach">
            <form action="index.php" method="get">
                <input type="text" name="type" value="9" hidden>
                <input id="serach" type="text" name="search" value="<?php echo empty($_GET['search'])?"":$_GET['search']; ?>"><input id="submit" type="submit" name="submit" value="搜索">
            </form>
        </div>
        <div class="opertor">
            <button id="question" onclick="javascirpt:location.href='myquestion.php';">提问</button>
            <?php echo $myinfo; ?>
            <!--<div class="my-icon"><img src="img/icon.png"></div>
            <div class="my-uname">熊贤贺</div>-->
            <!--<button id="login" onclick="javascirpt:location.href='login.php';">登录</button>
            <button id="register" onclick="javascirpt:location.href='register.php';">注册</button>-->
        </div>
    </div>

</div>