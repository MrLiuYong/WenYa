<?php
/**
 * 日志系统
 */

define('LOGFILE', 'logs/answer.log');
date_default_timezone_set('Asia/Shanghai');

// 警告日志
function wLog()
{
}

// 错误日志
function eLog($msg, $file, $line)
{
    $msg = sprintf("[%s] [%s] [%s:%s] %s\n", date('Y-m-d H:i:s'), 'ERROR', $file, $line, $msg);
    file_put_contents(LOGFILE, $msg, FILE_APPEND);
}