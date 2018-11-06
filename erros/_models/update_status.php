<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    include '../../autoload.php';

    $sup = new Suporte($connection);

    $erro_id = val_input::sani_number_int('erro_id');

    $status_nome = val_input::sani_string('novoStatus');

    $Erros = new Erros($connection);

    if ($Erros->atualiazar($status_nome, $erro_id) > 0)
    {
        echo '1';
    } else
    {
        echo '0';
    }
}

