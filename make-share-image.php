<?php
// One-time generator for the default 1200x630 share image (needs PHP GD + DejaVu fonts).
// Edit the text and the output path, then run: php make-share-image.php
$im=imagecreatetruecolor(1200,630);
$bg=imagecolorallocate($im,15,27,48);
$ac=imagecolorallocate($im,56,189,248);
$wh=imagecolorallocate($im,255,255,255);
$lb=imagecolorallocate($im,186,230,253);
$gr=imagecolorallocate($im,148,163,184);
imagefilledrectangle($im,0,0,1200,630,$bg);
imagefilledrectangle($im,0,0,14,630,$ac);
$b="/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf";
$r="/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf";
imagettftext($im,66,0,80,250,$wh,$b,"Launch AI Works");
imagettftext($im,34,0,80,340,$lb,$r,"AI systems that actually work");
imagettftext($im,34,0,80,395,$lb,$r,"for small businesses");
imagefilledrectangle($im,80,440,260,446,$ac);
imagettftext($im,24,0,80,500,$gr,$r,"launchaiworks.com  |  Rapid City, SD");
imagepng($im,"laiw-share-default.png");
echo "done";
