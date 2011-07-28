<?php

require('class/zip.lib.php');
$zipfile=new zipfile();
$zipfile->addDirContent($_GET['pasta'],1);
$name=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).".zip";
$zipfile->save("/var/www/tmp/".$name);
echo '<a href="'."../tmp/".$name."".'">Download</a>';
?>