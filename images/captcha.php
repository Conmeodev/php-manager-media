<?php
session_start();

$operand1 = rand(1, 9);
$operand2 = rand(1, 9);
$operators = ['+', '-', '*'];
$operator = $operators[rand(0, count($operators) - 1)];
switch ($operator) {
    case '+':
        $result = $operand1 + $operand2;
        break;
    case '-':
        $result = $operand1 - $operand2;
        break;
    case '*':
        $result = $operand1 * $operand2;
        break;
}
$_SESSION['captcha_result'] = $result;
$image = imagecreatetruecolor(80, 40);
$bgColor = imagecolorallocate($image, 255, 255, 255);
$textColor = imagecolorallocate($image, 0, 0, 0);
imagecolortransparent($image, $bgColor);
imagefilledrectangle($image, 0, 0, 120, 40, $bgColor);
$mathExpression = "$operand1 $operator $operand2 =";
imagestring($image, 5, 10, 10, $mathExpression, $textColor);
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>