<?php

//引入发送邮件类
include_once("smtp.php");
//使用163邮箱服务器
$smtpserver = "smtp.163.com";
//163邮箱服务器端口 
$smtpserverport = 25;
//你的163服务器邮箱账号 
$smtpusermail = "xiongxianhe@163.com";
//收件人邮箱
$smtpemailto = "360765409@qq.com";
//你的邮箱账号(去掉@163.com)
$smtpuser = "xiongxianhe";//SMTP服务器的用户帐号 
//你的邮箱密码 
$smtppass = "**************"; //SMTP服务器的用户密码

//邮件主题 
$mailsubject = "测试邮件发送";
//邮件内容 
$mailbody = '<html><head><title>邮箱验证</title></head><body>击以下链接进行验证\r\n<br><img src="../img/icon.png"> <a href="http://localhost/2016/20160811/">验证</a></body></html>';
//邮件格式（HTML/TXT）,TXT为文本邮件 
$mailtype = "HTML";
//这里面的一个true是表示使用身份验证,否则不使用身份验证. 
$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
//是否显示发送的调试信息 
$smtp->debug = TRUE;
//发送邮件
$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype); 

echo "xxx";
?>

<html>
<head>
</head>
<body>
fwf
</body>
</html>