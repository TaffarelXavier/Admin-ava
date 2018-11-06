<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    include '../../autoload.php';

    $sup = new Suporte($connection);

    $suporte_id = val_input::sani_number_int('suporte_id');

    $status = val_input::sani_string('status');

    if ($status == 'fechar')
    {
        echo ($sup->upd_status("UPDATE `suporte` SET `suporte_status` = 'fechado' WHERE `suporte`.`suporte_id` = '$suporte_id'") > 0) ? '1' : '0';
    }
    if ($status == 'abrir')
    {
        echo ($sup->upd_status("UPDATE `suporte` SET `suporte_status` = 'aberto' WHERE `suporte`.`suporte_id` = '$suporte_id'") > 0) ? '1' : '0';
    }
}

