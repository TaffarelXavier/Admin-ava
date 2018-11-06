<?php
include '../autoload.php';
header("Content-Type: text/event-stream");
header('Cache-Control: no-cache');

$time = date('r');

$connection->query("use ".DB_NAME.";");

$sth = $connection->prepare('SELECT * FROM `contador_de_visitas` ORDER BY `c_id`  DESC LIMIT 10');

$sth->execute();

//Alterar para 1000
echo "retry: 100000\n";
echo "event:visitas\n";
echo "data:<table class='table'>\n";
echo "data:<tr><th>Id e Data</th><th>Ip</th><th></th><th>Navegador</th><th>Caminho Arquivo</th><th>Pasta</th></tr>\n";
while ($linhas = $sth->fetch(PDO::FETCH_ASSOC)) {
    echo "data: <tr>"
    . "<td><a>" . $linhas['c_id'] . '-' . date('d/m/Y H:i:s', $linhas['c_data']) . "</a><td>"
    . "<td>" . $linhas['c_ip'] . "</td>"
    . "<td>" . $linhas['c_browser_name'] . "</td>"
    . "<td>" . $linhas['c_file_name'] . "</td>"
    . "<td>" . $linhas['c_nome_pasta'] . "</td>"
    . "</tr>\n";
}
echo "data:</table>\n";
echo "\n";


$sth1 = $connection->prepare('SELECT COUNT(*) FROM `contador_de_visitas`;');

$sth1->execute();
$total = $sth1->fetch();
echo "event:total\n";

echo "data:" . $total[0] . "\n\n";

flush();