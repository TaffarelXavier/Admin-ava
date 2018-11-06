<?php

include '../autoload.php';
header("Content-Type: text/event-stream");
header('Cache-Control: no-cache');

$ajuda = new Ajuda($connection);

echo "retry:3000\n";

echo "event:event_notificacao\n";

$var = $ajuda->MSG_ajuda_respostas_nao_lidas();

$array = array();

$total = 0;

while ($lin = $var->fetch(PDO::FETCH_ASSOC)) {
    ++$total;
    $array[] = $lin;
}

$array['total'] = $total;

echo "data:" . json_encode($array) . "\n\n";

ob_flush();
