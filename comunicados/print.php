<?php
include '../autoload.php';
if (!defined('_LOGADO_')) {
    header('location: ../');
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>View</title>
        <style>
            body{
                text-align: center;
            }
            .row-fluid{
                max-width: 600px;
                margin:0px auto;
                word-break: keep-all;
                border:1px solid #ccc;
                padding:20px;
                line-height: 29px;
                font-size:18px;
                text-align: justify;
            }
            h2{
                margin:2px;
                padding:0;
            }
        </style>

    </head>
    <body>
        <div class="row-fluid">
            <?php
            $sup = new Suporte($connection);

            $suporte_id = val_input::val_int('suporte-id');

            $reg = $sup->get_suporte_por_id($suporte_id);

            echo ($reg['suporte_mensagem']);
            
            echo '<hr/><h2>Data:</h2>';
            echo (date('d/m/Y \à\s H:i', $reg['suporte_data']));
            echo '<h2>Nível:</h2>';
            echo ($reg['suporte_nivel']);
            echo '<h2>Remetente:</h2>';
            echo ($reg['suporte_remetente']);
            ?>  
        </div>
    </body>
</html>