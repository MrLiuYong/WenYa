<?php
/*
 * user表相关的接口文件
 */

require_once 'dbbase.php';

/*
 * 根据telphone判断用户是否存在
 * 如果存在，返回true
 * 如果不存在，返回false
 */
function IsExist($telphone)
{
    $sqlSql = "select count(uid) from user where telphone='$telphone'";
    $result = mysql_query($sqlSql);
    $row = mysql_fetch_row($result);
    mysql_free_result($result);
    if($row[0] == 0)
        return false;
    return true;
}
/*
 * 用户注册
 * 注册成功返回true，否则返回false
 */
function ModeRegister($telphone, $password)
{
    if(IsExist($telphone))
        return false;// 当用户存在时，不允许注册
    $password = md5($password);
    $strSql = "insert into user(uid, telphone, password, registertime) VALUES (0, '$telphone', '$password','". time() . "')";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog("注册失败 " . mysql_error(), __FILE__, __LINE__);
    }
    return $result;
}

/*
 * 用户登录
 * 登录成功返回该用信息，否则返回false
 */
function ModeLogin($telphone, $password)
{
    $password = md5($password);
    // 233' or 1=1 or uname='
    $strSql = sprintf("select uid, uname, nickname, telphone, password,email, gender, icon, privilege, registertime from user where telphone='%s' and password='%s'", $telphone, $password);
   // echo $strSql;
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog("登录失败 $strSql " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    $row = mysql_fetch_row($result);
    if(!$row)
        return false;
    $user=array();
    $user['uid']=$row[0];
    $user['uname']=$row[1];
    $user['nickname']=$row[2];
    $user['telphone']=$row[3];
    $user['password']=$row[4];
    $user['email']=$row[5];
    $user['gender']=$row[6];
    $user['icon']=$row[7];
    $user['privilege']=$row[8];
    $user['registertime']=$row[9];
    return $user;
}

/**
 * 更新用户信息
 * 更新成功返回true，失败返回false
 */
function ModeUpdateUserInfo($uid, $uname, $nickname, $telphone, $gender, $email, $icon, $motto,$school,$professional,$business,$company,$position)
{
    $strSqlFormat = "update user set uname='%s', nickname='%s', telphone='%s', gender=%s, email='%s', icon='%s',motto='%s',school='%s',professional='%s',business='%s',company='%s',position='%s'  where uid=%s";
    $strSql = sprintf($strSqlFormat, $uname, $nickname, $telphone, $gender, $email, $icon, $motto,$school,$professional,$business,$company,$position,$uid);
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    $num = mysql_affected_rows();
    if ($num == 0)
        return false;
    return true;
}

/**
 * 获取用户信息
 * @param $uid
 * @return array|bool
 */
function ModeGetOneUserInfo($uid)
{
    $stru="select uname, nickname ,telphone, email, gender, icon, privilege, registertime, password,uid,motto,school,professional,business,company,position from user where uid=$uid";
    //echo $stru;
    $struu=mysql_query("$stru");
    $strr=mysql_fetch_row($struu);
    if(!$strr)
        return false;
    mysql_free_result($struu);
    $user=array();

    $user['uname']=$strr[0];
    $user['nickname']=$strr[1];
    $user['telphone']=$strr[2];
    //$user['password']=$strr[4];
    $user['email']=$strr[3];
    $user['gender']=$strr[4];
    $user['icon']=$strr[5];
    $user['privilege']=$strr[6];
    $user['registertime']=$strr[7];
    //$user['password']=$strr[8];
    $user['uid']=$strr[9];
    $user['motto'] = $strr[10];
    $user['school'] = $strr[11];
    $user['professional'] = $strr[12];
    $user['business'] = $strr[13];
    $user['company'] = $strr[14];
    $user['position'] = $strr[15];
    return $user;
}

/**
 * 用户关注问题
 * 成功返回true，否则返回false
 */
function ModeAddAttention($uid, $questionid)
{
    require_once 'questiondb.php';
    // 防止自己关注自己提出问题
    $askuid = ModeGetUidByQuestionID($questionid);
    print_r($askuid);
    if($askuid == $uid)
        return false;
    $atime = time();
    $strSql = "insert into attention(uid, questionid, time) VALUES ($uid, $questionid, $atime)";
    //echo $strSql;
    $rst =  mysql_query($strSql);
    return $rst;
}

function ModeCancelAttention($uid, $questionid)
{
    $strSql = "delete from attention where uid=$uid and questionid=$questionid";
    $result = mysql_query($strSql);
    return $result;
}

/*
 * 功能：
 * 1 获取某个用户关注的所有问题ID列表
 *  select questionid from attention where uid=$uid
 * 2 获取某个用户关注的所有问题的详细信息
 *  select a.questionid, a.uid, a.title, a.content,a.createtime from question a, attention b where a.questionid = b.questionid and b.uid = $uid
 * 3 获取某个用户关注的某个问题的详细信息
 *  select a.questionid, a.uid, a.title, a.content,a.createtime from question a, attention b where a.questionid = b.questionid and b.uid = $uid and b.questionid = $questionid
 *
 *
 */
//获取某个用户关注的所有问题的详细信息
function ModeGetMyAttentionQuestionDetails($uid)
{
    $sqlSql = "select a.questionid, a.uid, a.title, a.content,a.createtime from question a, attention b where a.questionid = b.questionid and b.uid = $uid";
    $result = mysql_query($sqlSql);
    if(!$result)
        return false;
    $questiondetails = array();
    $i = 0;
    while($row = mysql_fetch_row($result)) {
        $questiondetails[$i]['questionid'] = $row[0];
        $questiondetails[$i]['uid'] = $row[1];
        $questiondetails[$i]['title'] = $row[2];
        $questiondetails[$i]['content'] = $row[3];
        $questiondetails[$i]['createtime'] = $row[4];
        $i ++;
    }
    mysql_free_result($result);
    return $questiondetails;
}

//修改密码

function ModeResetPassword($telphone,$password)
{
    $password=md5($password);
    $strSql="update user set password='$password' where telphone=$telphone";
    //echo $strSql;
    $result=mysql_query($strSql);
    if(!$result)
    {
        return false;
    }
    return true;
}