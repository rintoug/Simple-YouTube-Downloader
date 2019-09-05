<?php
$downloadURL = urldecode($_GET['link']);
$type = urldecode($_GET['type']);
$title = urldecode($_GET['title']);

$fileName = $title.'.'.$type;
//exit;
if(!empty($downloadURL)){
    // Define headers
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$fileName");
    header("Content-Transfer-Encoding: binary");

    // Read the file
    readfile($downloadURL);
}