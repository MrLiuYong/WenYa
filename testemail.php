<?php
require_once 'common/sendemail.php';

$relay_host = "smtp.163.com";
$smtp_port = 25;
$auth = true;
$user = 'xiongxianhe@163.com';
$pass = '**************';

$emailSerever = InitEmailServer($relay_host, $smtp_port, $auth, $user, $pass);

$body = '<html><head><title></title></head><body><a href="http://localhost/2016/20160815/index.php">你问呀</a></body></html>';
SendEmail($emailSerever, $user, '360765409@qq.com', '这是发送主题2', $body, 'HTML');
