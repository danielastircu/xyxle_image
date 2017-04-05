<?php


$coordinates = $_POST['coordinates'];
$rotate      = $_POST['rotate'];

$dst_x = 0;   // X-coordinate of destination point
$dst_y = 0;   // Y-coordinate of destination point
$src_x = floor($coordinates['x']); // Crop Start X position in original image
$src_y = floor($coordinates['y']); // Crop Srart Y position in original image
$dst_w = ceil($coordinates['w']); // Thumb width
$dst_h = ceil($coordinates['h']); // Thumb height
$src_w = ceil($coordinates['w']); // Crop end X position in original image
$src_h = ceil($coordinates['h']); // Crop end Y position in original image


//$coordinates['x'] = floor($coordinates['x']);
//$coordinates['y'] = floor($coordinates['y']);
//$coordinates['w'] = ceil($coordinates['w']);
//$coordinates['h'] = ceil($coordinates['h']);

var_dump($_POST);
var_dump($coordinates);





$dst_image = imagecreatetruecolor($dst_w, $dst_h);
// Get original image
$src_image = imagecreatefromjpeg('cropper/picture.jpg');

$src_image = imagerotate($src_image, $rotate, 0);
// Cropping
imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
// Saving
imagejpeg($dst_image, 'cropper/crop.jpg');
