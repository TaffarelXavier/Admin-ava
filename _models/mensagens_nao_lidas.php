<?php

include '../autoload.php';
header("Content-Type: text/event-stream");
header('Cache-Control: no-cache');

echo "retry:3000\n";

echo "event:mensagens_nao_lidas\n";

$Ajuda = new Ajuda($connection);

$total = $Ajuda->sse_msg_nao_lidas();

$f = $Ajuda->get_msg_nao_lidas();

$array = array('0' => $total);

while ($lin = $f->fetch(PDO::FETCH_ASSOC)) {
    $array[] = $lin;
}

echo "data:".  json_encode($array)."\n\n";