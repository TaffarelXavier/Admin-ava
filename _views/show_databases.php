<?php
include "../autoload.php";
if (!defined('_LOGADO_'))
{
    header('location: ../');
    exit();
} else
{
    try
    {
        $sth = $connection->prepare('show databases');
        $sth->execute();
        $i = 0;
        echo "<b>Banco de Dados</b>:<table border=''>";
        while ($linhas = $sth->fetch())
        {
            ++$i;
            $n = $i % 2;
            $class='#71C5EF;';
            if($n==1){
                $class='#ACDDF5';
            }
            echo '<tr>'
            . '<td style="padding:3px 1px;background-color:'.$class.'">'
            . '<a href="?tablename=' . $linhas[0] . '" style="display:block;">' 
                    . $i . '-' . $linhas[0] . '<span class="badge pull-right blue"></span></a></td>'
            . '</tr>';
        }
        echo "</table>";
    } catch (Exception $exc)
    {
        echo $exc->getMessage();
    }
}
