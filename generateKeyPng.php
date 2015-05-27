<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Including all required classes
require_once('lib/BCGFontFile.php');
require_once('lib/BCGColor.php');
require_once('lib/BCGDrawing.php');

// Including the barcode technology
require_once('lib/BCGcode128.barcode.php');

// Loading Font
//$font = new BCGFontFile('./font/Arial.ttf', 18);
// Don't forget to sanitize user inputs
$text = isset($_GET['text']) ? $_GET['text'] : 'HELLO';

// The arguments are R, G, B for color.
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255);

$drawException = null;
try {
    $code = new BCGcode128();
    $code->setScale(5); // Resolution
    $code->setThickness(30); // Thickness
    $code->setForegroundColor($color_black); // Color of bars
    $code->setBackgroundColor($color_white); // Color of spaces
//    $code->setFont($font); // Font (or 0)
    $code->parse($text); // Text
} catch (Exception $exception) {
    $drawException = $exception;
}
//echo "<pre><div class='hgPrintR' style='border:1px solid red;'>";
//print_r(get_class_methods($code));
//echo "</div></pre>";
/* Here is the list of the arguments
  1 - Filename (empty : display on screen)
  2 - Background color */
$drawing = new BCGDrawing('', $color_white);
if ($drawException) {
    $drawing->drawException($drawException);
} else {
    $drawing->setBarcode($code);
    $drawing->draw();
}

// Header that says it is an image (remove it if you save the barcode to a file)
header('Content-Type: image/png');
header('Content-Disposition: inline; filename="barcode.png"');
//header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');

// Draw (or save) the image into PNG format.
//$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

$imBarCode = $drawing->get_im();
$width = imagesx($imBarCode);
$height = imagesy($imBarCode);
//imagepng($im);
$imCanvas = imagecreatetruecolor(945, 590);

$imCard = imagecreatefrompng("img/llave.png");
//imagepng($imCard);

imagealphablending($imCanvas, true);
imagealphablending($imBarCode, true);
imagealphablending($imCard, true);
imagesavealpha($imCanvas, true);
//362 183
imagecopyresized($imCanvas, $imBarCode, 362, 183, 0, 0, 500, 180, $width, $height);
imagecopy($imCanvas, $imCard, 0, 0, 0, 0, 945, 590);
imagepng($imCanvas);
?>