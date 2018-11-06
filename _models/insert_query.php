<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../autoload.php';

    if (!defined('_LOGADO_')) {
        header('location: ../');
        exit();
    }
    $T_Query = new T_Query($connection);
    
    $dbname = val_input::sani_string('dbname');
    
    $tablename = val_input::sani_string('tablename');
    
    $query = val_input::_default('query');
    
    $titulo = val_input::_default('titulo');
  
    echo ($T_Query->inserir($dbname, $tablename, $query,$titulo)>0) ? '1' : '0';
}

