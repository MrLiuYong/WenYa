<?php
session_start();
error_reporting(0);
if(empty($_SESSION['uid']))
{
    header("location: index.php");
    return;
}
$uid = $_SESSION['uid'];
require_once 'db/userdb.php';
$myuserinfo = ModeGetOneUserInfo($uid);

// 关注我的 粉丝
require_once 'db/fansdb.php';
$myFans = ModeGetMyFans($_SESSION['uid']);
$totalNum = count($myFans);

// 我的关注  偶像
$myIdols = ModeGetMyIdol($_SESSION['uid']);
$totalIdolsNum = count($myIdols);

// 最近访客信息
require_once 'db/visitdb.php';
$visitInfos = ModeGetRecentVisitorInfo($_SESSION['uid']);
// 总访问量
$totalVisitNum = ModeGetTotalVisit($_SESSION['uid']);
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

<?php require_once 'header.php'; ?>

<div class="main-content">
    <div class="left-content">
        <div class="title">
            <strong style="color: #0066cc">个人主页</strong>
        </div>
        <div class="main-body">
            <div class="myinfo">
                <div class="myinfo-img">
                    <img id="icon" src="<?php echo $myuserinfo['icon']; ?>">
                    <input id="file" type="file" name="icon" onchange="upfile(this, 'upfile.php')" title="点击上传头像">
                    <div class="myinfo-attach-info">
                        <div class="myinfo-attach-me">
                            <div class="myinfo-attach-title" style="color: #0066cc">关注我的 <em><?php echo $totalNum; ?></em></div>

                                <?php
                                for($i=0; $i < $totalNum; $i++)
                                {
                                    $icon = empty($myFans[$i]['icon']) ? "img/icon.png" : $myFans[$i]['icon'];
                                    $format= '<div class="myinfo-attach-icon"><a href="personinfo.php?uid=%s"><img src="%s"></a></div>';
                                    echo sprintf($format, $myFans[$i]['fansuid'],$icon);
                                }
                                ?>
                        </div>

                        <div class="myinfo-me-attach">
                            <div class="myinfo-attach-title" style="color: #0066cc">我关注的 <em><?php echo $totalIdolsNum; ?></em></div>

                                    <?php
                                    for($i = 0; $i < $totalIdolsNum; $i++)
                                    {
                                        $icon = empty($myIdols[$i]['icon']) ? "img/icon.png" : $myIdols[$i]['icon'];
                                        $format = '<div class="myinfo-attach-icon"><a href="personinfo.php?uid=%s"><img src="%s"></a></div>';
                                        echo sprintf($format, $myIdols[$i]['idoluid'], $icon);
                                    }

                                    ?>
                        </div>
                        <div class="myinfo-recent-visit">
                            <div class="myinfo-attach-title" style="color: #0066cc">总访问 <em><?php echo $totalVisitNum; ?></em></div>

                                <?php
                                for($i=0; $i < count($visitInfos); $i++)
                                {
                                    $format = '<div class="myinfo-attach-icon"><a href="personinfo.php?uid=%s"><img src="%s"></a></div>';
                                    echo sprintf($format, $visitInfos[$i]['visituid'], $visitInfos[$i]['icon']);
                                }
                                ?>
                        </div>
                    </div>
                </div>
                <div class="myinfo-base">
                    <form action="process.php?cmd=modifyinfo" method="post">
                        <input id="txticon" type="text" name="icon" hidden value="<?php echo $myuserinfo['icon'];?>">
                        <div class="myinfo-info">
                            <i>邮箱</i><input class="txt-blue-bottom" type="text" name="email" value="<?php echo $myuserinfo['email'];?>" >
                            <i>昵称</i><input class="txt-blue-bottom" type="text" name="nickname" value="<?php echo $myuserinfo['nickname'];?>" >
                            <i>真实姓名</i><input class="txt-blue-bottom" type="text" name="uname" value="<?php echo $myuserinfo['uname'];?>" >
                            <i>电话号码</i><input class="txt-blue-bottom" type="tel" name="telephone" value="<?php echo $myuserinfo['telphone'];?>" >
                            <i>性别</i><select class="txt-blue-bottom" name="gender">
                                <?php
                                switch ($myuserinfo['gender'])
                                {
                                    case 0:
                                        echo '<option value="0">女</option><option value="1">男</option>';break;
                                    default:
                                        echo '<option value="1">男</option><option value="0">女</option>';break;
                                }

                                ?>
                            </select>

                            <i>非主流个性签名</i><input class="txt-blue-bottom" type="text" name="motto" value="<?php echo $myuserinfo['motto'];?>" >

                            <i>学校</i><input class="txt-blue-bottom" type="text" name="school" value="<?php echo $myuserinfo['school'];?>" >

                            <i>专业</i><input class="txt-blue-bottom" type="text" name="professional" value="<?php echo $myuserinfo['professional'];?>" >

                            <i>行业</i><input class="txt-blue-bottom" type="text" name="business" value="<?php echo $myuserinfo['business'];?>" >
                            <i>就职企业</i><input class="txt-blue-bottom" type="text" name="company" value="<?php echo $myuserinfo['company'];?>" >
                            <i>职位</i><input class="txt-blue-bottom" type="text" name="position" value="<?php echo $myuserinfo['position'];?>" >
                            <input class="myinfo-save" type="submit" value="保存修改">
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <?php
    require_once 'rightnav.php';
    ?>
</div>

<div class="footer"></div>


</body>
</html>