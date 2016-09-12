<?php
session_start();
error_reporting(0);
if(empty($_SESSION['uid']))
{
    echo '<script>alert("请先登录");location.href="login.php";</script>';
    return;
}
if(empty($_GET['questionid']))
{
    header("location: index.php");
    return;
}

$questionid =  $_GET['questionid'];
require_once 'db/questiondb.php';
require_once 'db/answerdb.php';
$info = ModeGetQuestionInfoByQuestionID($questionid);
if(empty($info['icon']))
    $info['icon'] = 'img/icon.png';

$myspaceURL = "personinfo.php?uid=".$info['uid'];
if($info['uid'] == $_SESSION['uid'])
    $myspaceURL = "myinfo.php";

if($info['anonymity'] == 1)
{
    $info['icon'] = 'img/icon.png';
    $info['nickname'] = '匿名';
    $myspaceURL = 'javascript:';
}

$nickname = $info['nickname'];

$attentionNum = ModeGetAttentionNumByQuestionID($questionid);
$answerNum = ModeGetAnswerNumByQuestionID($questionid);


$questionHtmlFormat = '<div class="question-contain">
                <div class="brief-person-info">
                    <a href="%s">
                        <div class="icon">
                            <img src="%s" alt="icon">
                        </div>
                    </a>
                    <div class="uname">%s</div>
                </div>
                <div class="question-content">
                    <div class="question-title"><a href="javascript:">%s</a></div>
                    <div class="createtime">%s</div>
                    <div class="question-detail"><a href="javascript:">%s</a></div>
                    <div class="question-bottom">
                        <div class="attation"><a href="process.php?cmd=attention&questionid=%s&page=questiondetail">+关注</a></div>
                        <div class="attation-amount">关注量：%s</div>
                        <div class="question-amount">回答量：%s</div>
                    </div>
                </div>
            </div>
';
// 问题
$questionHtml = sprintf($questionHtmlFormat,
    $myspaceURL,
    $info['icon'],
    $nickname,
    $info['title'],
    $info['createtime'],
    $info['content'],
    $questionid,
    $attentionNum,
    $answerNum);


// 评论区
$replyHtmlFormat = '<div class="answer-contain">
            <div class="answerer-info">
                <div class="answerer-name"><a href="%s"> %s</a></div>
                <div class="anserer-attr">%s</div>
            </div>
            <div class="answer-content">
                <pre>%s</pre>
            </div>
            <div class="answer-attr">
                <div class="edit-time">编辑于 %s</div>
                <div class="answer-up"><a href="process.php?cmd=support&questionid=%s&answerid=%s&&up=1&down=0"><b>点赞</b></a> %s</div>
                <div class="answer-down"><a href="process.php?cmd=support&questionid=%s&answerid=%s&up=0&down=1"><b>踩</b></a> %s</div>
            </div>
        </div>';

$replayinfos = ModeGetAnswerInfoByQuestionID($questionid);
$replyHtml = "";
for($i=0; $i < count($replayinfos); $i++)
{
    $myspaceInfoURL = "personinfo.php?uid=".$replayinfos[$i]['replyuid'];
    $nickname = $replayinfos[$i]['nickname'];
    $anonymity = $replayinfos[$i]['anonymity'];
    if($anonymity == 1)
    {
        $nickname = '匿名';
        if($replayinfos[$i]['replyuid'] != $_SESSION['uid'])
        {
            $myspaceInfoURL = "javascript:";
        }
        $replayinfos[$i]['motto'] = '我就是我，不一样的烟火';
    }
    $answerid = $replayinfos[$i]['answerid'];
    $supportnum = ModeGetSupport($answerid);
    $tmp = sprintf($replyHtmlFormat,
        $myspaceInfoURL,
        $nickname,
        $replayinfos[$i]['motto'],
        $replayinfos[$i]['replycontent'],
        $replayinfos[$i]['createtime'],
        $questionid,
        $answerid,
        $supportnum['up'],
        $questionid,
        $answerid,
        $supportnum['down']
    );
    $replyHtml .= $tmp;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>你问我答</title>
    <link href="css/main.css" rel="stylesheet">
    <link href="css/answer.css" rel="stylesheet">
    <link rel="stylesheet" href="kindeditor-4.1.7/themes/default/default.css" />
    <script charset="utf-8" src="kindeditor-4.1.7/kindeditor-min.js"></script>
    <script charset="utf-8" src="kindeditor-4.1.7/lang/zh_CN.js"></script>
    <script>
        var editor;
        KindEditor.ready(function(K) {
            editor = K.create('textarea[id="replaycontent"]', {
                resizeType : 1,
                allowPreviewEmoticons : true,
                allowImageUpload : true,
                items : [
                    'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'image', 'link']
            });
        });
    </script>
</head>
<body>

<?php require_once 'header.php';?>

<div class="main-content">
    <div class="left-content">
        <div class="title">
            <strong style="color: #0000F6">热门问题</strong>
        </div>
        <div class="main-body">

            <?php echo $questionHtml; ?>

        </div>

        <!-- 评论区 -->
        <?php echo $replyHtml; ?>
        <!-- 评论区 end -->


        <div class="split">
            <?php include_once'pagelist.php';?>
            <!--<a href="">首页</a>
            <a href="">上一页</a>
            <a href="">下一页</a>
            <a href="">最后一页</a>-->
        </div>
        <hr>

        <!-- 回答区 -->
        <div class="replay-contain">
            <form action="process.php?cmd=answer&questionid=<?php echo $questionid; ?>" method="post">
                <textarea id='replaycontent' name="replay" rows="10"></textarea>
                <div class="myquestin-anonymity">
                    <label>匿名回答</label><input type="checkbox" name="anonymity" value="1">
                </div>
                <input id='replay' type="submit" name="submit">
            </form>
        </div><!-- 回答区 end -->

    </div>
    <?php require_once 'rightnav.php';?>
</div>
<?php require_once 'footer.php';?>
</body>
</html>