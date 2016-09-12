<?php
/**
 * 记录访客信息
 * Created by PhpStorm.
 * User: xxh
 * Date: 2016/8/11
 * Time: 15:06
 */

require_once 'dbbase.php';

function ModeAddVisitRecord($hostuid, $visituid, $visitip)
{
    $vtime = time();
    $strSql = "insert into visit(hostuid,visituid,visitip,visittime) VALUES ($hostuid, $visituid, '$visitip', $vtime)";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    return true;
}

/**
 * 获取最近访客信息 10个  访客uid、昵称、电话、头像、访问时间
 */
function ModeGetRecentVisitorInfo($uid)
{
    $strSql = "select b.visituid, b.visittime, b.visitip,a.nickname, a.telphone, a.icon from user a, visit b where b.hostuid=$uid and b.visituid = a.uid group by b.visituid order by b.visittime desc limit 10";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    $visitorInfos = array();
    $i = 0;
    while ($row = mysql_fetch_row($result))
    {
        $visitorInfos[$i]['visituid'] = $row[0];
        $visitorInfos[$i]['visittime'] = $row[1];
        $visitorInfos[$i]['ip'] = $row[2];
        $visitorInfos[$i]['nickname'] = $row[3];
        $visitorInfos[$i]['telphone'] = $row[4];
        $visitorInfos[$i]['icon'] = $row[5];
        $i++;
    }
    mysql_free_result($result);
    return $visitorInfos;
}
/**
 * 获取浏览量
 */
function ModeGetTotalVisit($uid)
{
    $strSql = "select count(*) from visit where hostuid=$uid";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    $row = mysql_fetch_row($result);
    if(!$row)
        return 0;
    return $row[0];
}