<?php

$str = "อ้างถึงใบจองสินค้า#BK.1256608/096:10/08/2566";

$pos1= strpos($str,"B");
$pos2= strpos($str,":");
$pos3= $pos2 - $pos1;


echo $pos1 . " - "  . $pos2 . "\n\r";
echo $pos3 . "\n\r";

$str_sub = substr($str,$pos1,$pos3);

echo $str_sub ;
