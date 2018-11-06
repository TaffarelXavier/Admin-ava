<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include '../../autoload.php';

    echo unlink(SYS_DOC_ROOT.'/admin/backup/'.val_input::sani_string('arquivo')) ? '1' : '0';
    
}
