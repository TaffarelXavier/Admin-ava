<?php

include '../autoload.php';

if (!defined('_LOGADO_')) {
    header('location: ../');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {

        $dbname = val_input::sani_string('dbname');

        $connection->query("use $dbname;");

        echo '<label class="resultado_pesquisa"><b>Resultado da pesquisa:</b></label>';

        $sql = filter_input(INPUT_POST, 'sql');

        preg_match("/^\w+/", $sql, $result);

        switch (strtolower($result[0])) {

            case 'update':
                echo $sql, '<br/>';
                $sth4 = $connection->exec($sql);
                var_dump($sth4);
                break;
            case 'describe':
                echo $sql;
                $sth = $connection->prepare($sql);
                $sth->execute();
                echo '<table border="" style="table-layout: fixed;">';
                echo "<tr>";
                while ($linhas = $sth->fetch(PDO::FETCH_ASSOC)) {
                    echo '<th style="text-align:left;padding:5px 10px;">', $linhas['Field'], '</th>';
                }
                echo '</tr></table>';
                break;
            case 'select':

                preg_match("/\`\w+\`/i", $sql, $match);

                $sth1 = $connection->prepare("describe " . $match[0]);
                $sth1->execute();
                echo '<table border="" style="">';
                echo "<tr>";
                while ($linhas = $sth1->fetch(PDO::FETCH_ASSOC)) {
                    echo '<th style="text-align:left;padding:5px 10px;overflow:hidden;">', $linhas['Field'], '</th>';
                }
                echo '</tr>';
                $sth2 = $connection->query($sql);

                while ($linhas = $sth2->fetch(PDO::FETCH_ASSOC)) {
                    $sth3 = $connection->prepare("describe " . $match[0]);
                    $sth3->execute();
                    echo '<tr>';
                    while ($lin = $sth3->fetch()) {
                        echo '<td style="text-align:left;padding:2px 10px;overflow:hidden;">', $linhas[$lin[0]], '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table><br/>';

                break;
            case 'show':
                try {

                    $sth = $connection->prepare('show tables');
                    $sth->execute();
                    $i = 0;
                    echo "<b>Tabelas</b>:<table border=''>";
                    while ($linhas = $sth->fetch()) {
                        ++$i;

                        $n = $i % 3;
                        $class = '';
                        if ($n == 1) {
                            $class = '';
                        } else if ($n == 2) {
                            $class = '';
                        }
                        echo '<tr>'
                        . '<td style="padding:3px 1px;background-color:' . $class . '">'
                        . '<a href="?tablename=' . $linhas[0] . '">'
                        . '<span>' . $linhas[0] . '</span></a></td>'
                        . '</tr>';
                    }
                    echo "</table>";
                } catch (Exception $exc) {
                    echo $exc->getMessage();
                }
                break;
            default:
                $connection->query($sql);
                break;
        }
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }
}

