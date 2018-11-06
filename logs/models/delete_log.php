<?php
include '../../autoload.php';
if (!defined('_LOGADO_')) {
    header('location: ../');
}


$caminho = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'ava/logs-do-sistema.log';

if(unlink($caminho)){
    echo '1';
}
else{
    echo '0';
}