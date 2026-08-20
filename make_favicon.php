<?php
$src = "C:\\Users\\LENOVO\\.gemini\\antigravity-ide\\brain\\d83e2959-fdd1-4688-8bef-f1f538a5b798\\media__1787143784743.png";
$img = imagecreatefrompng($src);
$w = imagesx($img);
$h = imagesy($img);

$size = max($w, $h) + 20; // add a little padding

$dest = imagecreatetruecolor($size, $size);
imagesavealpha($dest, true);
$white = imagecolorallocate($dest, 255, 255, 255);
imagefill($dest, 0, 0, $white);

$dst_x = ($size - $w) / 2;
$dst_y = ($size - $h) / 2;

imagecopy($dest, $img, $dst_x, $dst_y, 0, 0, $w, $h);

imagepng($dest, "c:\\laragon\\www\\RMEEE\\landingpage\\public\\favicon.png");
imagepng($dest, "c:\\laragon\\www\\RMEEE\\admin\\public\\favicon.png");
echo "Done";
