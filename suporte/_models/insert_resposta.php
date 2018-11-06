<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    include '../../autoload.php';

    $assistencia_id = val_input::sani_number_int('suporte-id');

    $mensagem = val_input::sani_string('mensagem');

    $suporte = new Assistencia($connection);

    if ($suporte->insert_mensagem(0, $assistencia_id, $mensagem, 'sistema', 'null') > 0)
    {
        echo '1';
    } else
    {
        echo '0';
    }
}
