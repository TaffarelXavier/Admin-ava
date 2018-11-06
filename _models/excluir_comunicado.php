<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    include '../autoload.php';
    
    $sup = new Suporte($connection);
    
    $suporte_id=val_input::val_int('suporte_id');
    
    if ($suporte_id != false)
    {
        echo ($sup->excluir_comnunicado($suporte_id) > 0) ? '1' : '0';
    }
}
