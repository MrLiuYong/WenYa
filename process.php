<?php
/**
 * 处理逻辑
 */
session_start();

require_once 'db/userdb.php';
require_once 'db/questiondb.php';
require_once 'db/answerdb.php';

/**
 * 用户注册
 */
function Register()
{
    if(empty($_POST['telphone']))
    {
        header("location: register.php?errno=1");
        exit();
    }
    if(empty($_POST['password']))
    {
        setcookie("telphone", $_POST['telphone']);
        header("location: register.php?errno=2");
        exit();
    }
    $telphone = $_POST['telphone'];
    $password1 = $_POST['password'];
    $password2 = "";
    !empty($_POST['passwordsec']) && $password2 = $_POST['passwordsec'];
    if($password1 != $password2)
    {
        setcookie("telphone", $_POST['telphone']);
        header("location: register.php?errno=3");
        exit();
    }
    if(IsExist($telphone))
    {
        setcookie("telphone", $_POST['telphone']);
        header("location: register.php?errno=4");
        exit();
    }
    $ret = ModeRegister($telphone, $password1);
    if(!$ret)
    {
        setcookie("telphone", $_POST['telphone']);
        header("location: register.php?errno=5");
        exit();
    }
    echo "<script>alert('注册成功');location.href='login.php';</script>";
}

/**
 * 用户登录
 */
function Login()
{
    if(empty($_POST['telphone']))
    {
        
        header("location:login.php?errno=1");
        exit();
    }
    if(empty($_POST['password']))
    {
        setcookie('telphone',$_POST['telphone']);
        header("location:login.php?errno=2");
        exit();
    }
    $telphone=$_POST['telphone'];
    $password=$_POST['password'];
    $user=ModeLogin($telphone, $password);
  // print_r($user) ;
    if(!$user)
    {
        setcookie('telphone',$_POST['telphone']);
        header("location:login.php?errno=3");
        exit();
    }
    $_SESSION['uid'] = $user['uid'];
    echo "<script>alert('登录成功');location.href='index.php';</script>";
}

/*
 *重置密码
 */
function ResetPassword(){
    if(empty($_POST['telphone']))
    {
        header('location:reset.php');
        exit();
    }
    if(empty($_POST['password']))
    {
        header('location:reset.php?errno=1');
        exit();
    }
    if(empty($_POST['passwordsec']))
    {
        header('location:reset.php?errno=1');
        exit();
    }
    $telphone=$_POST['telphone'];
    $password1=$_POST['password'];
    $password2=$_POST['passwordsec'];
    if($password1!=$password2)
    {
        header('location:reset.php?errno=2');
        exit();
    }
    ModeResetPassword($telphone,$password1);
   /* echo"<pre>";
    print_r( ModeResetPassword($telphone,$password1));
    echo"</pre>";*/
   echo"<script>alert('重置密码成功,请登录!');location.href='login.php'</script>";
}



/**
 * 用户退出
 */
function Quit()
{
    if(empty($_SESSION['uid']))
    {
        header("location: index.php");
        return;
    }
    session_destroy();
    setcookie(session_name(),'', time()-1);
    unset($_SESSION);
    header("location: index.php");
}

/**
 * 用户提问
 */
function Ask()
{
    if(empty($_SESSION['uid']))
    {
        echo '<script>alert("请先登录");location.href="login.php";</script>';
        return ;
    }
    $uid = $_SESSION['uid'];
    $title = empty($_POST['title'])? "" : $_POST['title'];
    $content = empty($_POST['content'])? "" : $_POST['content'];
    $anonymity = empty($_POST['anonymity'])? 0 : $_POST['anonymity'];
    setcookie('title', $title);
    setcookie('content', $content);

    if(empty($title))
    {
        header('location: myquestion.php?errno=1');
        return;
    }
    if(empty($content))
    {
        header('location: myquestion.php?errno=2');
        return;
    }
    setcookie('title', "");
    setcookie('content', "");
    ModeAsk($uid, $title, $content, $anonymity);
    echo '<script>alert("提问成功，请查看最近的题信息");location.href="index.php";</script>';
}

/**
 * 用户关注问题
 */
function Attention()
{
    if(empty($_SESSION['uid']) || empty($_GET['questionid']))
    {
        echo '<script>alert("请先登录，再关注");location.href="login.php";</script>';
        return ;
    }
    $uid = $_SESSION['uid'];
    $questionid = $_GET['questionid'];
    //echo $uid.$questionid;
    /*if(isset($uid,$questionid))
    {
        ModeCancelAttention($uid, $questionid);
    }*/
    ModeAddAttention($uid, $questionid);

    $page = "index.php";
    if(!empty($_GET['page']))
        $page = $_GET['page'] . '.php?questionid=' . $questionid;

    header("location: $page");
}

/**
 * 回答问题
 */
function Answer()
{
    if(empty($_SESSION['uid']))
    {
        echo '<script>alert("请先登录，再关注");location.href="login.php";</script>';
        return ;
    }
    if(empty($_GET['questionid']))
    {
        echo '<script>alert("非法访问");location.href="index.php";</script>';
        return ;
    }
    if(empty($_POST['content']) || strlen($_POST['content'] < 10))
    {

    }
    $anonymity = empty($_POST['anonymity']) ? 0 : $_POST['anonymity'];
    $questionid = $_GET['questionid'];
    $replyuid = $_SESSION['uid'];
    $content = $_POST['replay'];
    ModeAddAnser($questionid, $replyuid, $content, $anonymity);
    header("location: questiondetail.php?questionid=$questionid");
}

/**
 * 点赞
 */
function Support()
{
    if(empty($_SESSION['uid']))
    {
        echo '<script>alert("请先登录，再关注");location.href="login.php";</script>';
        return ;
    }
    if (empty($_GET['questionid']) || empty($_GET['answerid'])){
        header("location: index.php");
        return ;
    }

    $questionid=$_GET['questionid'];
    $answerid=$_GET['answerid'];
    $up=$_GET['up'];
    $down=$_GET['down'];
    $supportuid=$_SESSION['uid'];
    $ret=ModeSupport($supportuid, $answerid, $up, $down);
    header("location:questiondetail.php?questionid=$questionid");
}

/**
 * 修改用户信息
 */
function ModifyInfo()
{
    if(empty($_SESSION['uid']))
    {
        echo '<script>alert("请先登录，再关注");location.href="login.php";</script>';
        return ;
    }
    $uid = $_SESSION['uid'];
    $icon = empty($_POST['icon'])? "http://wenya.xiongxianhe.com/img/icon.png" : $_POST['icon'];
    $uname = empty($_POST['uname'])? "" : $_POST['uname'];
    $nickname = empty($_POST['nickname'])? "" : $_POST['nickname'];
    $motto = empty($_POST['motto'])? "" : $_POST['motto'];
    $telphone = empty($_POST['telephone'])? "" : $_POST['telephone'];
    $email = empty($_POST['email'])? "" : $_POST['email'];
    $gender = empty($_POST['gender'])? "0" : $_POST['gender'];
    $school = empty($_POST['school'])? "" : $_POST['school'];
    $professional = empty($_POST['professional'])? "" : $_POST['professional'];
    $business = empty($_POST['business'])? "" : $_POST['business'];
    $company = empty($_POST['company '])? "" : $_POST['company '];
    $position = empty($_POST['position'])? "" : $_POST['position'];

    ModeUpdateUserInfo($uid, $uname, $nickname, $telphone, $gender, $email, $icon, $motto,$school,$professional,$business,$company,$position);    header('location: myinfo.php');
}

/**
 * 增加关注粉丝
 */
function AddFans()
{
    if(empty($_SESSION['uid']))
    {
        echo '<script>alert("请先登录，再关注");location.href="login.php";</script>';
        return ;
    }
    if(empty($_GET['uid']))
    {
        header("location: personinfo.php");
        return;
    }
    require_once 'db/fansdb.php';
    $fanseduid = $_GET['uid'];
    $fansuid = $_SESSION['uid'];
    ModeAddFans($fanseduid, $fansuid);
    header("location: personinfo.php?uid=$fanseduid");
    return;
}
/**
 * 入口
 */
function main()
{
    if(empty($_GET['cmd']))
    {
        header('location: index.html');
        exit();
    }
    $fun = $_GET['cmd'];
    if(function_exists($fun))
    {
        $fun();
    }
    else {
        header('location: index.html');
        exit();
    }
}

main();