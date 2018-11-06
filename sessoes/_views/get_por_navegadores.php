<?php
include '../../autoload.php';
header("Content-Type: text/event-stream");
header('Cache-Control: no-cache');
$time = date('d/m/Y \à\s H:i:s');
$Sessao = new Sessao_Usuarios($connection);
$ft12 = $Sessao->get_grupos_navegador();
$_array = array();
while ($lins = $ft12->fetch(PDO::FETCH_ASSOC)) {
    $t = $Sessao->count_por_browser($lins['sessao_navegador']);
    $_array[$t] = $lins['sessao_navegador'];
    krsort($_array);
}
echo "retry: 1000\n";
echo "event:visitas\n";
echo "data:<span style='display:block;border-bottom:1px dashed red;text-align:right;'><b>" . $time . "</b></span>\n";
echo "data: <table border=''><caption>Total Por Browsers</caption><tbody>\n";
$tootal = 0;
foreach ($_array as $key => $value) {
    $tootal+=$key;
    echo "data:<tr><td style='padding:5px;'><a title='' style='text-align: right;display: block;'>$value:</a></td>\n";
    echo "data:<td style='padding:5px;'><b>$key</b> logins</td></tr>\n";
}
echo "data:<tr><td colspan='2'><b>Total [$tootal]</b></td></tr></tbody>\n";
echo "data:</table>\n";
echo "\n";
echo "data:" . $total[0] . "\n\n";
flush();
