<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    include '../../autoload.php';

    $sup = new Suporte($connection);

    $post_id = val_input::sani_number_int('post_id');

    $mensagemEdicao = $_POST['texto'];

    $Ajuda = new Ajuda($connection);
    
    echo ($Ajuda->update_post_por_id($mensagemEdicao, $post_id));
    
}

