<?php

if ($_POST) {

    session_start();

    $tasdffpasswssd = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (isset($_SESSION['ERRO']) && $_SESSION['ERRO'] == 5) {
        exit('ERRO_5');
    }

    if (password_verify($tasdffpasswssd, '$2y$11$9tnodUM1S8xCZC27AGRuV.kd.PAAkOISOR6rdu0EwQZj8WY5LhRdG')) {

        $file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'ava/file_config_ini.php';

        $fp = fopen($file, 'w');

        $valor = '';

        if (!isset($_POST['tipo'])) {
            $valor = '2';
        } else {
            if ($_POST['tipo'] == 'manutencao') {
                $valor = '1';
            } else if ($_POST['tipo'] == 'desativar') {
                $valor = '0';
            }
        }

        $resource_str = "<?php \r\n \$fp = '" . $valor . "';
switch (\$fp) {
    case '1':
        header('location: ../manuntecao.php');
        break;
    case '0':
        header('location: ../');
        break;
    case '2':
        break;
}";

        fwrite($fp, $resource_str);

        $array = array('tipo' => 'criar', 'valor' => '1');

        echo json_encode($array);
    } else {

        if (!isset($_SESSION['ERRO'])) {
            $_SESSION['ERRO'] = 1;
        } else {
            $_SESSION['ERRO']+=1;
        }

        echo 'A senha está errada.';
    }
}