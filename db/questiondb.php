<?php
/**
 * 与问题相关的接口
 */

require_once 'dbbase.php';


/**
 * 我提问
 */
function ModeAsk($uid, $title, $content, $anonymity=0)
{
    $content = addslashes($content);
   $str = "insert into question (questionid, uid, 
title, content, createtime, anonymity) values (0, $uid, '$title','$content',now(), $anonymity)";
    $result = mysql_query($str);
    if(!$result)
    {
        eLog(mysql_error() . " " . $str, __FILE__, __LINE__);
        return false;
    }
    return $result;
}

/**
 * 根据questionid查找提问者uid
 */
function ModeGetUidByQuestionID($questionid)
{
    $strSql = "select uid from question where questionid=$questionid";
    $result = mysql_query($strSql);
    if(!$result)
        return false;
    $row = mysql_fetch_row($result);
    mysql_free_result($result);
    return $row[0];
}

//获取我的提问
function ModeGetMyQuestion($uid)
{
    $strSql = "select questionid, uid, title, content, createtime from question where uid=$uid";
    // mysql_query执行sql语句
    // 当执行的select语句成功时，返回一个资源描述符
    // 当执行失败（出错）或者查找语句为空时，返回false
    // 当执行失败（出错）时，定位问题：
    //       先检查sql语句是否正确：
    //              先把sql语句打印出来: echo $strSql
    //              然后在页面上查看该语句是否正确，
    //                  假如在页面上无法看出错误，则把该sql语句粘贴到mysql终端去执行，看是否能执行成功
    $result = mysql_query($strSql);
    if(!$result)
        return false;
    // mysql_fetch_row 传入的参数是一个资源符
    // 所以在使用该函数前，要保证$result是一个资源符
    // 如果该函数执行成功，返回一个索引数组（以查询字段的顺序为下标索引的数组）
    //
    $myQuestion = array();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        $myQuestion[$i]['questionid'] = $row[0];
        $myQuestion[$i]['uid'] = $row[1];
        $myQuestion[$i]['title'] = $row[2];
        $myQuestion[$i]['content'] = $row[3];
        $myQuestion[$i]['createtime'] = $row[4];
        $i++;
    }
    mysql_free_result($result);
    return $myQuestion;
}

/**
 * 获取最近提问信息：提问者信息和问题信息
 */
function ModeGetRecentQuestionInfo()
{
    $strSql = "select u.uid,u.nickname,u.telphone,u.icon,q.questionid,q.title,q.content,q.createtime,q.anonymity from user u, question q where u.uid = q.uid order by q.createtime desc";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog(mysql_error() . " " . $strSql, __FILE__, __LINE__);
        return false;
    }
    $questioninfos = array();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        $questioninfos[$i]['uid'] = $row[0];
        $questioninfos[$i]['nickname'] = $row[1];
        $questioninfos[$i]['telephone'] = $row[2];
        $questioninfos[$i]['icon'] = $row[3];
        $questioninfos[$i]['questionid'] = $row[4];
        $questioninfos[$i]['title'] = $row[5];
        $questioninfos[$i]['content'] = $row[6];
        $questioninfos[$i]['createtime'] = $row[7];
        $questioninfos[$i]['anonymity'] = $row[8];
        $i++;
    }
    mysql_free_result($result);
    /*print_r($questioninfos);
    echo "<hr>";*/
    return $questioninfos;
}

/**
 * 获取最热提问信息: 提问者信息和问题信息
 */
function ModeGetHotQuestionInfo()
{
    // 1 先查找出最热问题的questionid： 关注量最多
    // 2 在通过查找出来questionid获取 提问者信息和问题信息

    // 1
    $strSql = "select questionid from attention group by questionid order by count(questionid) desc";
  //  echo $strSql;
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog(mysql_error() . " " . $strSql, __FILE__, __LINE__);
        return false;
    }
    // 2
    $questioninfos = array();
    $i = 0;
    /*var_dump($result);*/
    while($row = mysql_fetch_row($result))
    {
        $questionid = $row[0];
        $qinfo = ModeGetQuestionInfoByQuestionID($questionid);
        $questioninfos[$i] = $qinfo;
        $i++;
    }
    mysql_free_result($result);
    return $questioninfos;
}

/**
 * 获取我关注的问题信息：提问者信息和问题信息
 */
function ModeGetMyAttentQuestionInfo($uid)
{
    // 1 根据uid查找该用户关注的questionid
    // 2 在通过查找出来questionid获取 提问者信息和问题信息
    $strSql = "select questionid from attention where uid = $uid group by questionid order by time desc ";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog(mysql_error() . " " . $strSql, __FILE__, __LINE__);
        return false;
    }
    // 2
    $questioninfos = array();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        $questionid = $row[0];
        $qinfo = ModeGetQuestionInfoByQuestionID($questionid);
        $questioninfos[$i] = $qinfo;
        $i++;
    }
    mysql_free_result($result);
    return $questioninfos;

}

/**
 * 获取我回答的问题信息：提问者信息和问题信息
 */
function ModeGetMyAnswerQuestionInfo($replyuid)
{
    $strSql = "select questionid from answer where replyuid=$replyuid group by questionid order by createtime desc";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog(mysql_error() . " " . $strSql, __FILE__, __LINE__);
        return false;
    }
    $questioninfos = array();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        $questionid = $row[0];
        $qinfo = ModeGetQuestionInfoByQuestionID($questionid);
        $questioninfos[$i] = $qinfo;
        $i++;
    }
    mysql_free_result($result);
    return $questioninfos;
}

/*
 * 获取我的提问信息：提问者信息和问题信息
 */
function ModeGetMyAskQuestionInfo($uid)
{
    $strSql = "select a.uid, a.nickname, a.telphone, a.icon, b.questionid, b.title, b.content, b.createtime, b.anonymity from user a, question b where a.uid = b.uid and b.uid=$uid ORDER by b.createtime desc";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog(mysql_error() . " " . $strSql, __FILE__, __LINE__);
        return false;
    }
    $questioninfos = array();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        $questioninfos[$i]['uid'] = $row[0];
        $questioninfos[$i]['nickname'] = $row[1];
        $questioninfos[$i]['telephone'] = $row[2];
        $questioninfos[$i]['icon'] = $row[3];
        $questioninfos[$i]['questionid'] = $row[4];
        $questioninfos[$i]['title'] = $row[5];
        $questioninfos[$i]['content'] = $row[6];
        $questioninfos[$i]['createtime'] = $row[7];
        $questioninfos[$i]['anonymity'] = $row[8];
        $i++;
    }
    mysql_free_result($result);
    return $questioninfos;
}

/**
 * 根据QuestionID获取信息：提问者信息和问题信息
*/
function ModeGetQuestionInfoByQuestionID($questionid)
{
    $strSql = "select a.questionid, a.title, a.content, a.createtime,b.uid,b.nickname,b.telphone,b.icon,a.anonymity from question a, user b where a.uid=b.uid and a.questionid=$questionid";
    $result = mysql_query($strSql);
    if(!$result)
    {
        eLog(mysql_error(), __FILE__, __LINE__);
        return false;
    }
    $row = mysql_fetch_row($result);
    mysql_free_result($result);
    $info = array();
    $info['questionid'] = $row[0];
    $info['title'] = $row[1];
    $info['content'] = $row[2];
    $info['createtime'] = $row[3];
    $info['uid'] = $row[4];
    $info['nickname'] = $row[5];
    $info['telephone'] = $row[6];
    $info['icon'] = $row[7];
    $info['anonymity'] = $row[8];
    return $info;
}

/**
 * 统计某个问题的关注量
 */
function ModeGetAttentionNumByQuestionID($questionid)
{
    $strSql = "select count(uid) from attention where questionid=$questionid";
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



/*
 *搜索问题信息根据关键字
 * 提问者信息，问题信息
 */
function ModeSearchQuestionInfo($key)
{
   $find="";
    for($i=0;$i<strlen($key);$i++)
    {
        $tmp=mb_substr($key,$i,1,'utf-8');

        if($tmp=="")
            break;
            $find .= '%' . $tmp;
    }
    $find .= '';
    $strSql="select questionid from question where content like '%$find%' or title like '%$find%' order by createtime desc";
    //$strSql="select * from question where content like '%$key_more[0]%'or content like '%$key_more[1]%' or title like '%$key_more[0]%'or title like '%$key_more[1]%' order by createtime desc";
    //echo $strSql ;
    $result=mysql_query($strSql);
    if(!$result)
    {
        eLog(mysql_error()."".$strSql,__FILE__,__LINE__);
        return false;
    }
    $questioninfos=array();
    $i=0;
    while($row=mysql_fetch_row($result))
    {

        //$row['content']=preg_replace("/$key_more[0]/i","<font color=red><b><$key_more[0]></b></font>",$row['content']);
        //$row['content']=preg_replace("/$key_more[0]/i","<font color=red><b><$key_more[1]></b></font>",$row['content']);
        //$row['title']=preg_replace("/($key_more[1])/i","<font color=red><b><$key_more[0]></b></font>",$row['title']);
        //$row['title']=preg_replace("/($key_more[1])/i","<font color=red><b><$key_more[1]></b></font>",$row['title']);
        $questionid=$row[0];
        $qinfo = ModeGetQuestionInfoByQuestionID($questionid);
        $questioninfos[$i]=$qinfo;
        $i++;
    }
     //echo $row['content'];
    // echo $row['title'];
    mysql_free_result($result);
    return $questioninfos;
}


// 根据replyuid来查找该用户所回答的问题编号和是否匿名回答了问题

function ModeGetQuestionidByReplyuid($replyuid)
{
    $strSql="select anonymity questionid from answer where replyuid=$replyuid";
    $result=mysql_query($strSql);
    if(!$result)
    {
        eLog(mysql_error().''.$strSql,__FILE__,__LINE__);
        return false;
    }
    $i=0;
    while($row = mysql_fetch_row($result))
    {
        //如果anonymity=1,则把所有的questionid都输出来
       if($row[0]==1)
       {
           $uid[$i]=$row[1];//把questionid赋值给$uid[$i]
       }
        else
            $uid[$i]=0;
        $i++;
    }
    mysql_free_result($result);
    return $uid;

}