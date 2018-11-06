<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../autoload.php';
    $arr = Tokens::inserir_nova();
    setcookie('tokenKey', $arr[0].'*'.$arr[1], 0,'/');
}
   
