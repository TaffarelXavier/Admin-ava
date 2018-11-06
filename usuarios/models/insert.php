<?php

if (!defined('_LOGADO_')) {
    header('location: ../../');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../autoload.php';

    $usuarios = new Usuarios($connection);

    $username = val_input::sani_string('username');
    $userpass = val_input::sani_string('senha');
    $nivel = val_input::sani_string('nivel');
    $key = val_input::sani_number_int('key');
    $token = val_input::sani_string('token');
    if (Tokens::validar_sessao($key, $token)) {
        $niveis = array('basico', 'medio', 'avancado');


        if (is_null(_USER_ID_)) {
            exit('Nossa, houve um erro, saia do sistema e entre novamente.');
        }

        if (trim($username) == '' || !preg_match('/^[a-z0-9]{6,15}$/i', trim($username))) {
            exit('O nome do usuário está inválido.');
        } elseif (preg_match('/\s+/i', $userpass) || trim($userpass) == '' || strlen(trim($userpass)) > 20 || strlen(trim($userpass)) < 5) {
            exit("Suspeitamos que há algo errado com a senha, reveja e tente novamente.");
        } elseif (preg_match('/\s+/i', $nivel) || trim($nivel) == '' || !in_array($nivel, $niveis)) {
            exit("Suspeitamos que há algo errado com o nível, reveja e tente novamente.");
        } else if ($usuarios->verificar_se_usuario_existe($username) > 0) {
            exit('Este usuário já existe.');
        } else {
            if ($usuarios->criar_novo_usuario($username, $userpass, $nivel) > 0) {
                echo '1';
            } else {
                echo '0';
            }
        }
    }
}
