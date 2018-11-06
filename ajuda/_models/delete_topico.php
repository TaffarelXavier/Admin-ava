<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include '../../autoload.php';

    $ajuda = new Ajuda($connection);

    $aju_id = val_input::val_int('aju_id');

    $ajuda_id = val_input::val_int('ajuda_id');

    $usuario_id = val_input::val_int('usuario_id');

    echo $ajuda->delete_topico($aju_id, $ajuda_id, $usuario_id) > 0 ? '1' : '0';
}

