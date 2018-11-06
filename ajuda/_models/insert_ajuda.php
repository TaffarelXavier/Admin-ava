<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../autoload.php';

    $ajuda = new Ajuda($connection);

    $ajuda_id = val_input::sani_string('ajuda_id');

    $remetente = val_input::sani_string('remetente');

    $mensagem = $_POST['mensagem'];

    echo $ajuda->inserir($ajuda_id, $remetente, $mensagem, time()) > 0 ? '1' : '0';
}

