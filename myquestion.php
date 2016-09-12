<?php
session_start();
if(empty($_SESSION['uid']))
{
    echo '<script>alert("请先登录");location.href="login.php";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>你问我答</title>
    <link href="css/main.css" rel="stylesheet">
    <link href="css/answer.css" rel="stylesheet">
    <link href="css/myquestion.css" rel="stylesheet">

    <link rel="stylesheet" href="kindeditor-4.1.7/themes/default/default.css" />
    <script charset="utf-8" src="kindeditor-4.1.7/kindeditor-min.js"></script>
    <script charset="utf-8" src="kindeditor-4.1.7/lang/zh_CN.js"></script>
    <script>
        var editor;
        KindEditor.ready(function(K) {
            editor = K.create('textarea[name="content"]', {
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
            <strong>提问</strong>
        </div>

        <!-- 提问区 -->
        <div class="myquetion">
            <form action="process.php?cmd=ask" method="post">
                <div class="myquestion-title">
                    <input type="text" name="title" placeholder="问题标题" value="<?php echo empty($_COOKIE['title'])? "" : $_COOKIE['title']; ?>">
                </div>
                <div class="myquestion-content">
                    <textarea id='myquestion-content' name="content" rows="20"><?php echo empty($_COOKIE['content'])? "" : $_COOKIE['content']; ?></textarea>
                </div>
                <div class="myquestin-anonymity">
                    <label>匿名</label><input type="checkbox" name="anonymity" value="1">
                </div>
                <input id="myquestion" type="submit" name="submit">
            </form>
            <div class="errormsg">
                <?php
                if(!empty($_GET['errno']))
                {
                    $errno = $_GET['errno'];
                    switch ($errno)
                    {
                        case 1: echo '* 请填写标题';break;
                        case 2: echo '* 请填写问题内容';break;
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