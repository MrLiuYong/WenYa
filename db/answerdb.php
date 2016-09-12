<?php
/**
 * 与回答相关的接口
 */
require_once 'dbbase.php';

/**
 * 回答一个问题
 */
function ModeAddAnser($questionid, $replyuid, $content, $anonymity=0)
{
    $strSql = sprintf("insert into answer(answerid, questionid, replyuid, replycontent, createtime, anonymity) VALUES (0, %s, %s, '%s', now(), %s)", $questionid, $replyuid, $content, $anonymity);
    // mysql_query: 执行成功时返回true，否则返回false
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
    }
    return $result;
}

// 修改回答内容
function ModeUpdateAnser($answerid, $replayuid, $questionid, $replaycontent)
{
    $strSql = "update answer set replaycontent='$replaycontent' where answerid=$answerid and replayuid=$replayuid and questionid = $questionid";
    $result = mysql_query($strSql);
    return $result;
}

// 用户点赞
// 赞  踩  取消
function ModeSupport($supportuid, $answerid, $up, $down)
{
    if($up + $down == 2)
        return false;
    $strSql = "select answerid, up, down from support where answerid = $answerid and supportuid=$supportuid";
    $result = mysql_query($strSql);
    $row = mysql_fetch_row($result);
    $r = mysql_affected_rows();
    if($r > 0)
    {
        if($row[1] == $up)
            $up = 0;
        if($row[2] == $down)
            $down = 0;
        $strSql = "update support set up=$up, down=$down where supportuid=$supportuid and answerid=$answerid";
    }
    else
    {
        $strSql = "insert into support(answerid,supportuid,up,down) VALUES ($answerid, $supportuid, $up, $down)";
    }
    mysql_free_result($result);
    $result = mysql_query($strSql);
    return $result;
}

/**
 * 统计答案的赞 踩
 */
function ModeGetSupport($answerid)
{
    $strSql = "select sum(up),sum(down) from support where answerid=$answerid";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    $info = mysql_fetch_row($result);
    mysql_free_result($result);
    return array(
        "up" => $info[0] == NULL ? 0 : $info[0],
        "down" => $info[1] == NULL ? 0 : $info[1]
    );
}

/**
 * 统计某个问题的回答量
 */
function ModeGetAnswerNumByQuestionID($questionid)
{
    $strSql = "select count(answerid) from answer where questionid=$questionid";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog(mysql_error() . " " . $strSql, __FILE__, __LINE__);
        return 0;
    }
    $row = mysql_fetch_row($result);
    mysql_free_result($result);
    return $row[0];
}

/**
 * 根据QuestionID获取回答信息：回答者信息 和 回答内容
 */
function ModeGetAnswerInfoByQuestionID($questionid)
{
    $strSql = "select a.answerid,a.questionid,a.replyuid,a.replycontent,a.createtime,b.nickname,b.uname,b.telphone,b.icon,a.anonymity,b.motto from answer a, user b where a.replyuid=b.uid and a.questionid=$questionid";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog($strSql . " " . mysql_error(), __FILE__, __LINE__);
        return false;
    }
    $infos = array();
    $i = 0;
    while ($row = mysql_fetch_row($result))
    {
        $infos[$i]['answerid'] = $row[0];
        $infos[$i]['questionid'] = $row[1];
        $infos[$i]['replyuid'] = $row[2];
        $infos[$i]['replycontent'] = $row[3];
        $infos[$i]['createtime'] = $row[4];
        $infos[$i]['nickname'] = $row[5];
        $infos[$i]['uname'] = $row[6];
        $infos[$i]['telephone'] = $row[7];
        $infos[$i]['icon'] = $row[8];
        $infos[$i]['anonymity'] = $row[9];
        $infos[$i]['motto'] = $row[10];
        $i++;
    }
    return $infos;
}