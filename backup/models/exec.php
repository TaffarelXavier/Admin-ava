<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../autoload.php';
    
    $dbname = val_input::sani_string('dbname');
    
    if ($_POST['sql'] == '') {
        exit('<p class="text-error">Executa alguma consulta.</p>');
    }
    
    $connection->query("use $dbname;");
    
    $Backup_Class = new Backup_DB($connection, $dbname);
    
    $Backup_Class->import($_POST['sql']);
    
    echo 'Import executado com sucesso!';
   
}