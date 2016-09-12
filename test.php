<?php
/**
 * Created by PhpStorm.
 * User: xxh
 * Date: 2016/8/9
 * Time: 7:32
 */
$a = array('1','2','3','4');
unset($a[0]);
for($i = 1;$i<=count($a);$i++)
{

        echo $a[$i]."<br>";

}
