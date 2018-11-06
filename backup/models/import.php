<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    include '../../autoload.php';

    if (val_input::sani_string('fazerBackup') == 'true')
    {
        if (isset($_FILES['filename']))
        {

            $file_name = $_FILES['filename']['name'];
            $file_type = $_FILES['filename']['type'];
            $file_tmp = $_FILES['filename']['tmp_name'];
            $file_error = $_FILES['filename']['error'];
            $file_size = $_FILES['filename']['size'];

            
            $tipos_permitidos =array('application/octet-stream','text/x-sql','text/plain');

            if ($file_error != 0)
            {
                exit('Houve um erro ao tentar enviar um novo arquivo.');
            } else if (!preg_match('/\.(sql)$/ui', $file_name))
            {
                exit('O tipo de arquivo não é permitido.');
            } else if (!in_array($file_type, $tipos_permitidos))
            {
                exit('Escolha um arquivo válido.');
            } else if ($file_size > 25165824) //25MB
            {
                exit('O arquivo é muito grande.');
            }
            if ($file_error == 0)
            {
                echo file_get_contents($file_tmp);
            }
        }
    }
}