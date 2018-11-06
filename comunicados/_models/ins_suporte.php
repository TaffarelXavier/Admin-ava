<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    include '../../autoload.php';

    $sup = new Suporte($connection);

    $mensagem = addslashes($_POST['mensagem']);

    $content = $mensagem;
    
    $nivel = val_input::sani_string('nivel');
    $remetente = val_input::sani_string('remetente');
    $status = val_input::sani_string('status');
    $ordem = val_input::sani_string('ordem');
    $tipo = val_input::sani_string('tipo');

    if ($sup->inserir($content, $nivel, $remetente,$ordem,$tipo))
    {
        $relacao = val_input::neo_str_array('relacao');

        foreach ($relacao as $key => $value)
        {
            $sup->insert_relacao($sup->last_id(), $value);
        }
        echo '1';
    }
    else{
        echo '0';
    }
}

