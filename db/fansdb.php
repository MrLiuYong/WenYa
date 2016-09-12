<?php
/**
 * 与关注相关的接口
 * Created by PhpStorm.
 * User: xxh
 * Date: 2016/8/10
 * Time: 16:04
 */
require_once 'dbbase.php';

/**
 * 判断是否关注
 * 判断$fansuid是否关注了$idoluid
 * 当已经关注返回true，否则返回false
 */
function ModeIsFans($idoluid, $fansuid)
{
    $strSql = "select * from fans where idoluid=$idoluid and fansuid=$fansuid";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    $num = mysql_affected_rows();
    mysql_free_result($result);
    if($num >= 1)
        return true;
    return false;
}
/**
 * 关注
 */
function ModeAddFans($idoluid, $fansuid)
{
    if(ModeIsFans($idoluid, $fansuid))
        return false;
    $ftime = time();
    $strSql = "insert into fans(idoluid, fansuid, ftime) VALUES ($idoluid, $fansuid, $ftime)";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    return true;
}

/**
 * 获取粉丝信息：uid 昵称 电话 头像
 */
function ModeGetMyFans($uid)
{
    /*$strSql = "select a.idoluid, a.fansuid, a.ftime from fans awhere idoluid=$uid";*/

    $strSql = "select a.idoluid, a.fansuid, a.ftime, b.nickname, b.telphone, b.icon from fans a, user b where idoluid=$uid and a.fansuid=b.uid";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    $fans = array();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        $fans[$i]['idoluid'] = $row[0];
        $fans[$i]['fansuid'] = $row[1];
        $fans[$i]['ftime'] = $row[2];
        $fans[$i]['nickname'] = $row[3];
        $fans[$i]['telphone'] = $row[4];
        $fans[$i]['icon'] = $row[5];
        $i++;
    }
    return $fans;
}

function ModeGetMyIdol($uid)
{
    $strSql = "select a.idoluid, a.fansuid, a.ftime,b.nickname,b.telphone,b.icon from fans a, user b where fansuid=$uid and a.idoluid=b.uid";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    $idols = array();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        $idols[$i]['idoluid'] = $row[0];
        $idols[$i]['fansuid'] = $row[1];
        $idols[$i]['ftime'] = $row[2];
        $idols[$i]['nickname'] = $row[3];
        $idols[$i]['telphone'] = $row[4];
        $idols[$i]['icon'] = $row[5];
        $i++;
    }
    return $idols;
}
