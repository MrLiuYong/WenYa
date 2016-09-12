<?php
session_start();
error_reporting(0);
if(empty($_SESSION['uid']))
{
    echo '<script>alert("请先登录");location.href="login.php";</script>';
}
if(empty($_GET['uid']))
{
    header("location: index.php");
    return;
}
// 防止自己进入
if($_SESSION['uid'] === $_GET['uid'])
{
    header("location: myinfo.php");
    return;
}

// 记录访客信息
require_once 'db/visitdb.php';
$clientIP = $_SERVER['REMOTE_ADDR'];
ModeAddVisitRecord($_GET['uid'], $_SESSION['uid'], $clientIP);

$uid = $_GET['uid'];

require_once 'db/userdb.php';
$owninfo = ModeGetOneUserInfo($uid);
if(empty($owninfo['icon']))
    $owninfo['icon'] = 'img/icon.png';

// 粉丝
require_once 'db/fansdb.php';
$myFans = ModeGetMyFans($_GET['uid']);


// 总访问量
$totalVisit = ModeGetTotalVisit($_GET['uid']);
// 最近访客信息
$visitInfos = ModeGetRecentVisitorInfo($_GET['uid']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>问呀~ 个人主页</title>
    <link href="css/main.css" rel="stylesheet">
    <link href="css/answer.css" rel="stylesheet">
    <link href="css/myinfo.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">

    <script src="js/myinfo.js"></script>
</head>
<body>

<?php require_once 'header.php';?>

<div class="main-content">
    <div class="left-content">
        <div class="title">
            <strong style="color: #880000"><?php echo $owninfo['uname']?>个人主页</strong>
        </div>
        <div class="main-body">
            <div class="myinfo">
                <div class="myinfo-img">
                    <img id="icon" src="<?php echo $owninfo['icon']; ?>">
                    <div class="ul-ta-info">
                        <label ><a href="index.php?type=6&uid=<?php echo $uid;?>" style="color: #880000"><?php echo $owninfo['uname']?>的关注</a></label>
                        <label ><a href="index.php?type=7&uid=<?php echo $uid;?>" style="color: #880000"><?php echo $owninfo['uname']?>的回答</a></label>
                        <label ><a href="index.php?type=8&uid=<?php echo $uid;?>" style="color: #880000"><?php echo $owninfo['uname']?>的提问</a></label>
                    </div>
                </div>
                <div class="myinfo-base">
                    <div class="myinfo-nickname"><?php echo empty($owninfo['nickname'])? $owninfo['telphone'] : $owninfo['nickname'];?></div>
                    <div class="myinfo-gender"><img src="<?php echo empty($owninfo['gender'])? "img/female.png" : "img/male.png"; ?>"> </div>
                    <div class="myinfo-attach"><a href="process.php?cmd=addfans&uid=<?php echo $uid; ?>">关注+</a></div>
                    <div class="myinfo-motto"><?php echo $owninfo['motto']; ?></div>
                    <div class="myinfo-base-attr">
                        <ul>
                            <li><label>学校</label> <b>南昌大学</b></li>
                            <li>专业 <b>电子商务</b></li>
                            <li>行业 <b>互联网金融</b></li>
                            <li>就职公司 <b>深圳迅雷网络科技技术有限公司</b></li>
                            <li>职位 <b>高级软件工程师</b></li>
                        </ul>
                    </div>
                    <div class="myinfo-attach-me">
                        <div class="myinfo-attach-title">粉丝 <em><?php echo count($myFans);?></em></div>
<?php
for($i = 0; $i < count($myFans); $i++)
{
    $ffmt = '<div class="myinfo-attach-icon"><a href="personinfo.php?uid=%s"><img src="%s"></a></div>';
    $html = sprintf($ffmt, $myFans[$i]['fansuid'], $myFans[$i]['icon']);
    echo $html;
}
?>
                    </div>
                    <div class="myinfo-me-attach">
<?php
// 我的关注  偶像
$myIdols = ModeGetMyIdol($_GET['uid']);
$num = count($myIdols);
?>
                        <div class="myinfo-attach-title">关注 <em><?php echo $num; ?></em></div>

<?php
for($i=0; $i<$num; $i++)
{
    $format = '<div class="myinfo-attach-icon"><a href="personinfo.php?uid=%s"><img src="%s"></a></div>';
    $html = sprintf($format, $myIdols[$i]['idoluid'],$myIdols[$i]['icon']);
    echo $html;
}
?>
                    </div>
                    <div class="myinfo-recent-visit">
                        <div class="myinfo-attach-title">总访问 <em><?php echo $totalVisit;?></em></div>

<?php
for($i=0;$i<count($visitInfos);$i++)
{
    $format = '<div class="myinfo-attach-icon"><a href="personinfo.php?uid=%s"><img src="%s"></a></div>';
    echo sprintf($format, $visitInfos[$i]['visituid'], $visitInfos[$i]['icon']);
}
?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php require_once 'rightnav.php';?>
</div>

<?php require_once 'footer.php';?>


</body>
</html>