<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include '../../autoload.php';

    $ajuda = new Ajuda($connection);

    $ajuda_id = val_input::val_int('ajuda_id');

    $usuario_id = val_input::val_int('usuario_id');
    
    echo $ajuda->delete_inteiramente_ajuda_por_id($ajuda_id, $usuario_id) > 0 ? '1' : '0';
}


