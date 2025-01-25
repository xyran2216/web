<?php
session_start();
$code = substr(md5(time()), 0, 4);
$_SESSION["code"] = $code;
$image = imagecreatetruecolor(50, 24);
$background = imagecolorallocate($image, 34, 39, 54);
$forground = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $background);
imagestring($image, 6, 6, 6, $code, $forground);
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);

$_SESSION['securecode'] = $code;

?>