<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    include '../autoload.php';
    
    if (!defined('_LOGADO_')) {
        header('location: ../');
        exit();
    }
    
    try {

        $sql = val_input::sani_string('query');
        $dbname = val_input::sani_string('dbname');
        $connection->query("use $dbname;");
        
        preg_match("/^\w+/", $sql, $result);

        switch (strtolower($result[0])) {
            case 'update':
                echo $sql, '<br/>';
                $sth4 = $connection->exec($sql);
                var_dump($sth4);
                break;
            case 'describe':
                $sth2 = $connection->prepare($sql);
                $sth2->execute();
                $x = 0;
                echo "<table border='' style='text-align:left;'>";
                echo "<tr>"
                . "<th>Nome</th>"
                . "<th>Tipo</th>"
                . "<th class='hidden-phone'>Null</th>"
                . "<th>Primay Key</th>"
                . "<th class='hidden-phone'>Default</th>"
                . "<th class='hidden-phone'>Extra</th>"
                . "</tr>";
                while ($lin = $sth2->fetch(PDO::FETCH_ASSOC)) {
                    ++$x;
                    echo '<tr>'
                    . '<td>' . $x . '-' . $lin['Field'] . '</td>'
                    . '<td>' . $lin['Type'] . '</td>'
                    . '<td class="hidden-phone">' . $lin['Null'] . '</td>'
                    . '<td>' . $lin['Key'] . '</td>'
                    . '<td class="hidden-phone">' . $lin['Default'] . '</td>'
                    . '<td class="hidden-phone">' . $lin['Extra'] . '</td>'
                    . '</tr>';
                }
                echo "</table><br/>";
                break;
            case 'select':
                preg_match("/(FROM\s+)(\w+)/i", $sql, $match);
                $sth1 = $connection->prepare("describe " . $match[2]);
                $sth1->execute();
                echo '<table border="" style="">';
                echo "<tr>";
                while ($linhas = $sth1->fetch(PDO::FETCH_ASSOC)) {
                    echo '<th style="text-align:left;padding:5px 10px;overflow:hidden;">', $linhas['Field'], '</th>';
                }
                echo '</tr>';
                $sth2 = $connection->query($sql);

                while ($linhas = $sth2->fetch(PDO::FETCH_ASSOC)) {
                    $sth3 = $connection->prepare("describe " . $match[2]);
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