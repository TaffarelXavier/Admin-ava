<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    include '../../autoload.php';

    $sup = new Suporte($connection);

    $suporte_id = val_input::sani_number_int('suporteId');
    
    $suporteOrdem = val_input::sani_number_int('suporteOrdem');

    $mensagemEdicao = addslashes($_POST['mensagemEdicao']);

    $rementeEdicao = val_input::sani_string('remetenteEdicao');
    
    $tipo  = val_input::sani_string('tipo');

    $sql = "UPDATE `suporte` SET `suporte_mensagem` = '$mensagemEdicao', "
            . " suporte_remetente = '$rementeEdicao', `suporte_ordem` = '$suporteOrdem', `suporte_tipo` = '$tipo' WHERE `suporte`.`suporte_id` = '$suporte_id' ";

    echo ($sup->upd_status($sql) > 0) ? '1' : '0';
}

