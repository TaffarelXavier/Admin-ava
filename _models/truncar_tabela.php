<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../autoload.php';

    if (!defined('_LOGADO_')) {
        header('location: ../');
        exit();
    }

    try {
        $tablename = filter_input(INPUT_POST, 'truncar', FILTER_SANITIZE_STRING);

        $sth1 = $connection->prepare("SELECT * FROM `" . $tablename . "`");
        $sth1->execute();
        $totalinicial = $sth1->rowCount();


        $sth2 = $connection->prepare("truncate `" . $tablename . "`");
        $sth2->execute();


        $sth3 = $connection->prepare("SELECT * FROM `" . $tablename . "`");
        $sth3->execute();
        $totalfinal = $sth3->rowCount();

        if ($totalinicial > 0 && $totalfinal == 0) {
            echo "Trucado com sucesso!";
        } else {
            echo $totalinicial, $totalfinal;
        }
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }
}

