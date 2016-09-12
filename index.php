<?php
session_start();
//error_reporting(0);
require_once 'db/questiondb.php';
require_once 'db/answerdb.php';
require_once 'db/userdb.php';
/*
 * type: 1: 热门问题  默认
 *       2: 最近问题
 *       3: 我的关注问题
 *       4:我回答的问题
 *       5:我的提问
 *       6:***的关注
 *       7:***的回答
 *       8:***的提问
 */

global $uid,$info;
if(isset($_GET['uid']))
{
    $uid=$_GET['uid'];
    $info=ModeGetOneUserInfo($uid);
}


/*if(empty($_GET['uid']))
{

}*/
$type = 2;
$typeTitle = "最新问题";
!empty($_GET['type']) && $type = $_GET['type'];

switch ($type)
{
    case 1:
        $questionInfos = ModeGetHotQuestionInfo();
        $typeTitle = "最热问题";
        break;
    case 2:
        $questionInfos = ModeGetRecentQuestionInfo();
        $typeTitle = "最新问题";
        break;
    case 3:
        $questionInfos = ModeGetMyAttentQuestionInfo($_SESSION['uid']);
        $typeTitle = "我的关注问题";
        break;
    case 4:
        $questionInfos = ModeGetMyAnswerQuestionInfo($_SESSION['uid']);
        $typeTitle = "我回答的问题";
        break;
    case 5:
        $questionInfos = ModeGetMyAskQuestionInfo($_SESSION['uid']);
        $typeTitle = "我的提问";
        break;
    case 6:
        $questionInfos = ModeGetMyAttentQuestionInfo($uid);
        //print_r( $questionInfos);
        $uid=$_GET['uid'];
        $typeTitle ="<a href='personinfo.php?uid=$uid' style='color: #0fbb99'>{$info['uname']}</a>的关注";
        break;
    case 7:
        $questionInfos = ModeGetMyAnswerQuestionInfo($uid);
        $uid=$_GET['uid'];
        $typeTitle = "<a href='personinfo.php?uid=$uid' style='color: #0fbb99'>{$info['uname']}</a>的回答";
        break;
    case 8:
        $questionInfos = ModeGetMyAskQuestionInfo($uid);
        $uid=$_GET['uid'];
        $typeTitle = "<a href='personinfo.php?uid=$uid' style='color: #0fbb99'>{$info['uname']}</a>的提问";
        break;
    case 9:
        $key='';
        if(!isset($_GET['search']))
        {
            $questionInfos=false;
            break;
        }
        $key=trim($_GET['search']);

        $questionInfos=ModeSearchQuestionInfo($key);
       // print_r($questionInfos);
        $typeTitle='搜索结果';
        break;
    default:
        $questionInfos = ModeGetHotQuestionInfo();
        $typeTitle = "最热问题";
        break;
}

$questionHtmlFormat = '<div class="question-contain">
                <div class="brief-person-info">
                    <div class="icon">
                        <a href="%s"><img src="%s" alt="icon"></a>
                    </div>
                    <div class="uname">%s</div>
                </div>
                <div class="question-content">
                    <div class="question-title"><a href="questiondetail.php?questionid=%s">%s</a></div>
                    <div class="createtime">%s</div>
                    <div class="question-detail"><a href="questiondetail.php?questionid=%s">%s</a></div>
                    <div class="question-bottom">
                        <div class="attation"><a href="process.php?cmd=attention&questionid=%s">+关注</a></div>
                        <div class="attation-amount">关注量：%s</div>
                        <div class="question-amount">回答量：%s</div>
                    </div>
                </div>
            </div>
';

$questionHtmls = "";
if($questionInfos)
{
   /* if ($type == 7 or $type == 6)
    {
        for ($i = 0; $i < count($questionInfos); $i++)
        {
            for ($j = $i + 1; $j < count($questionInfos); $j++)
            {
                if ($questionInfos[$j]['questionid'] == $questionInfos[$i]['questionid'])
                {
                    if ($j != count($questionInfos))//判断j是否是最后一个
                    {
                        $m = $j;
                        while(true)
                        {
                            if($m !=count($questionInfos)-1)
                            {
                                $questionInfos[$m] = $questionInfos[$m + 1];
                                $m++;
                            }
                            else{
                                unset($questionInfos[$m]);
                                break;
                            }
                        }
                    }
                    else
                    {
                        unset($questionInfos[$j]);
                    }
                }
            }
        }

    }*/
    for ($i = 0; $i < count($questionInfos); $i++)
    {
        $anonymity = $questionInfos[$i]['anonymity'];
        if ($anonymity == 1)
        {
            $questionInfos[$i]['nickname'] = '匿名';
            $questionInfos[$i]['telephone'] = '';
            $questionInfos[$i]['icon'] = 'img/icon.png';
            if($type == 5)
                $questionInfos[$i]['nickname'] .= "(自己)";

            if ($type == 8 or $type ==7)
                continue;
        }
        $nickname = empty($questionInfos[$i]['nickname']) ? $questionInfos[$i]['telephone'] : $questionInfos[$i]['nickname'];
        $content = $questionInfos[$i]['content'];
        if (strlen($content) > 100) {
            $content = mb_substr($content, 0, 200) . " ... ...";
        }
        $questionid = $questionInfos[$i]['questionid'];
        $attentionNum = ModeGetAttentionNumByQuestionID($questionid);
        $anserNum = ModeGetAnswerNumByQuestionID($questionid);//
        $myspaceURL = "personinfo.php?uid=" . $questionInfos[$i]['uid'];
        if ($anonymity == 1)
        {
            $myspaceURL = "javascript:";
        }
        if (!empty($_SESSION['uid']))
        {
            if ($questionInfos[$i]['uid'] == $_SESSION['uid'])
                $myspaceURL = "myinfo.php";
        }

        if (empty($questionInfos[$i]['icon']))
        {
            $questionInfos[$i]['icon'] = 'img/icon.png';
        }

        $temp = sprintf($questionHtmlFormat,
            $myspaceURL,
            $questionInfos[$i]['icon'],
            $nickname/*$questionInfos[$i]['telephone']*/,
            $questionid,
            $questionInfos[$i]['title'],
            $questionInfos[$i]['createtime'],
            $questionid,
            $content,
            $questionid,
            $attentionNum,
            $anserNum
        );
        $questionHtmls .= $temp;
    }
}
else
{
    $questionHtmls = "没找到您要查找的信息";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>你问我答</title>
    <link href="css/main.css" rel="stylesheet">
</head>
<body>

<?php require_once 'header.php';?>

<div class="main-content">
    <div class="left-content">
        <div class="title">
            <strong><?php echo $typeTitle;?></strong>
        </div>
        <div class="main-body">

            <?php echo $questionHtmls; ?>

        </div>
    </div>
<?php require_once 'rightnav.php';?>
</div>
<?php require_once 'footer.php';?>
</body>
</html>