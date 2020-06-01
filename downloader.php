<?php
$downloadURL = urldecode($_GET['link']);
//print  $downloadURL;exit;
$type = urldecode($_GET['type']);
$title = urldecode($_GET['title']);

//Finding file extension from the mime type
$typeArr = explode("/",$type);
$extension = $typeArr[1];

$fileName = $title.'.'.$extension;


if (!empty($downloadURL)) {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment;filename=\"$fileName\"");
    header("Content-Transfer-Encoding: binary");

    readfile($downloadURL);

}
