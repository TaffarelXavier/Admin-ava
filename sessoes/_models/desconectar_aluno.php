<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../autoload.php';

    //A mensagem
    $mensagem = trim(val_input::sani_string('mensagem'));

    //Os id dos alunos
    $ids = trim(val_input::sani_string('alunosIds'));

    $arrayId = explode(',', $ids);

    if (strlen($arrayId[0]) == 0) {
        exit('Escolha um ID.');
    }

    $TX_Desconectar_Alunos = new TX_Desconectar_Alunos($connection);

    $succes = '0';

    foreach ($arrayId as $value) {
        if ($TX_Desconectar_Alunos->tx_inserir($value, $mensagem, time()) > 0) {
            $succes = '1';
        }
    }

    echo $succes;
}

