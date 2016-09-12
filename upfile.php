<?php
/**
 * 上传文件保存
 * 异步提交，成功发回图片的url
 * User: xxh
 * Date: 2016/8/9
 * Time: 10:57
 */
// 异步提交

session_start();
$retinfo = array([
   'ret' => 200,
    'msg' => 'ok',
    'url' => "",
]);
if(empty($_SESSION['uid']))
{
    echo json_encode($retinfo);
    return;
}
if(empty($_FILES['file']))
{
    echo json_encode($retinfo);
    return;
}
$file = $_FILES['file'];
$type = $file['type'];
if(!stristr($type, "image"))
{
    $retinfo['msg'] = 'not image';
    echo json_encode($retinfo);
    return;
}
$newfilename = time() . strrchr($file['name'], '.');
$workpath = strstr($_SERVER['REQUEST_URI'], "upfile.php", true);
$abpath = "img/" . $newfilename;
$httpnewfile = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}{$workpath}/{$abpath}";
move_uploaded_file($file['tmp_name'], $abpath);

$retinfo['url'] = $httpnewfile;
echo json_encode($retinfo);