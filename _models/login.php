<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include '../autoload.php';

    $captcha = val_input::sani_string('captcha');
    
    if ($_SESSION['captcha'] != $captcha) {
        exit('Insira corretamente o captcha.');
    } else {

        $username = val_input::sani_string('usuario');
        
        $password = val_input::sani_string('password');

        $Login = new Login($connection);
        
        echo $Login->verificar_acesso($username, $password);
        
    }
} else {
    header('location: ../');
}