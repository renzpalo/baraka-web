<?php
$file = "http://barakaph.com/images/baraka.apk";

header("Content-Description: File Transfer"); 
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=" . basename($file)); 

readfile($file);
exit(); 
?>