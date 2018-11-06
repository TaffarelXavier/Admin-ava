<?php

include '../autoload.php';

if (!defined('_LOGADO_'))
{
    header('location: ../');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    try
    {
        $tablename = filter_input(INPUT_POST, "truncar", FILTER_SANITIZE_STRING);

        $sth2 = $connection->prepare("drop table `" . $tablename . "`");
        $sth2->execute();
        echo $sth2->rowCount();
    } catch (Exception $exc)
    {
        if ($exc->getCode() == 1146)
        {
            echo 'Tabela excluída com sucesso';
        } else
        {
            echo $exc->getMessage();
        }
    }
}

