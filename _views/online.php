<?php

include '../autoload.php';
header("Content-Type: text/event-stream");
header('Cache-Control: no-cache');

$time = time();

$UserOn = new User_Online($connection);

$linOn = $UserOn->get_users_online();

echo "retry:6000\n";

echo "event:online\n";

$outp = "[";
while ($reg = $linOn->fetch(PDO::FETCH_ASSOC)) {
    $foto = $UserOn->get_foto_perfil($reg["alu_id"]);
    if ($outp != "[") {
        $outp .= ",";
    }
    $outp .= '{"id":"' . $reg["alu_id"] . '",';
    $outp .= '"nome":"' .  TX_String::replaceNome($reg["alu_nome"]) . '",';
    $outp .= '"foto":"' . $foto . '",';
    $outp .= '"dispositivo":"' . $reg["chat_device"] . '",';
    $outp .= '"data":"' . date('d/m/Y H:i', $reg["chat_data"]) . '"}';
}
$outp .="]";

echo "data:$outp\n";

echo "data\n\n";

flush();