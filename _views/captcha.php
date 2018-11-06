<?php

include '../autoload.php';

if (!defined('_LOGADO_'))
{
//======================================================================
//                      CAPTCHA. Autor: Taffarel Xaier
//======================================================================

    $im = imagecreate(130, 19)or die('Erro ao tentar acessar a biblioteca GD');

    $black = imagecolorallocate($im, 255, 255, 255);

    $background = imagecolorallocate($im, 0xAA, 0xAA, 0xFF);

    $white = imagecolorallocate($im, 0xAA, 0xAA, 0xFF);

//Desenha linhas na imagem
    imageline($im, 200, rand(0, 20), 0, rand(5, 20), $white);
//    imageline($im, 200, rand(5, 10), 0, rand(0, 10), $white);
//carrega uma fonte
    $font = imageloadfont(FONTE_NAME);

//Cria os caracteres
    $caract = array('!', '@', '#', '$', '%', '&', '*', '(', ')', '', '/', '\\');
    $numero = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
    $l_max = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $l_min = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

//array_rand
    $rand_keys = array_rand($caract, 2);
    $key = array_rand($numero, 2);
    $key_l = array_rand($l_max, 2);
    $key_m = array_rand($l_min, 2);

    $srt = $numero[$key[0]] . $l_min[$key_m[1]] . $numero[$key[1]] . $caract[$rand_keys[1]] . $l_min[$key_m[0]] . $caract[$rand_keys[0]];

    $textoCaptcha = str_shuffle($srt);

    $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);

    //$_SESSION['captcha'] = password_hash($textoCaptcha, PASSWORD_BCRYPT, array("cost" => 14, "salt" => $salt));
    $_SESSION['captcha'] = $textoCaptcha;

    imagestring($im, $font, 10, -4, $textoCaptcha, $background);

    imagepng($im);

    imagedestroy($im);
}