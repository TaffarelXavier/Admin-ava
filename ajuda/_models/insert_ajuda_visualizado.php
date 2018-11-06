<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    include '../../autoload.php';

    $ajuda = new Ajuda($connection);

    $ajuda_id = val_input::val_int('ajuda_id');

    $lido =  val_input::sani_string('lido');
    
    echo ($ajuda->update_view_ajuda($lido, $ajuda_id)> 0) ? '1' : '0';
}

