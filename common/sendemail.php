<?php
/**
 * 发送邮件接口
 * Created by PhpStorm.
 * User: xxh
 * Date: 2016/8/15
 * Time: 17:15
 */

/**
 *  用法：
 *  先调用 InitEmailServer函数
 *  后调用 SendEmail函数
 */

require_once 'smtp.php';
/**
 * @param string $relay_host 源邮箱服务器，如smtp.163.com
 * @param int $smtp_port 源邮箱服务器端口 默认25
 * @param bool $auth 是否使用身份验证
 * @param $user 源邮箱账号
 * @param $pass 源邮箱账号密码
 * @return 返回email对象
 */
function InitEmailServer($relay_host = "", $smtp_port = 25,$auth = false,$user,$pass)
{
    $smtpServer = new smtp($relay_host, $smtp_port, $auth, $user, $pass);
    return $smtpServer;
}

/**
 * @param smtp $smtpserver email对象
 * @param $fromUserEmail 发件人邮箱账号
 * @param $toUserEmail  收件人邮箱账号
 * @param $emailSubject 邮件主题
 * @param $emailBody    邮件内容
 * @param string $emailType 邮件内容类型，html text
 */
function SendEmail(smtp $smtpserver, $fromUserEmail, $toUserEmail, $emailSubject, $emailBody, $emailType='text')
{
    $smtpserver->sendmail($toUserEmail, $fromUserEmail, $emailSubject, $emailBody, $emailType);
}