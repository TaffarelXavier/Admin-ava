<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    include '../../autoload.php';

    if (val_input::sani_string('filename'))
    {
         echo file_get_contents(BACKUP_PASTA.val_input::sani_string('filename'));
    }
}