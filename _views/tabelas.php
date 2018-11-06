<?php
include '../autoload.php';
header("Content-Type: text/event-stream");
header('Cache-Control: no-cache');

echo "retry:1000\n";

$sth = $connection->prepare('show tables');
$sth->execute();
$i = 0;

echo "event:show_tables\n";
echo "data: <b>Tabelas</b>:<table class='table'>\n";

while ($linhas = $sth->fetch()) {
    $contar = $connection->prepare("SELECT count(*) FROM `$linhas[0]`");
    $contar->execute();
    $total = $contar->fetch();
    ++$i;
    $n = $i % 2;
    $class = '#71C5EF;';
    if ($n == 1) {
        $class = '#ACDDF5';
    }
    echo "data:<tr><td style='padding:3px 1px;background-color:$class'><a href='?tablename=$linhas[0]' style='display:block;color:black;font-size:16px;'>"
            . "$i-$linhas[0]<span class='badge pull-right blue'>$total[0]</span></a></td></tr>\n";
}

echo "</table>\n\n";

echo "event:envet1\n";
echo "data:".time()."\n\n";
