<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include '../../autoload.php';

    $ajuda = new Ajuda($connection);

    $ajuda_id = val_input::val_int('ajuda_id');

    $aju_id = val_input::val_int('ajud_id');

    $lido = val_input::sani_string('lido');

    
    
    echo $ajuda->marcar_como_lida($lido, $aju_id, $ajuda_id) > 0 ? '1' : '0';
}

