<?php
include '../autoload.php';
if (!defined('_LOGADO_')) {
    header('location: ../');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Suporte 1.0</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/style.man.css" rel="stylesheet">
        <link href="../css/main-css.css" rel="stylesheet">
        <link href="../css/bootstrap-responsive.css" rel="stylesheet">
        <script src="../js/jquery-1.10.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery.form.js"></script>
        <script src="../js/jquery.cookie.js"></script>
        <script src="../js/plugins_neoinovat.js"></script>
        <meta http-equiv="refresh" content="20">
    </head>
    <body>
        <?php
        include '../_views/topo.php';
        ?>
        <div class="container">
            <div class="row-fluid">
                <?php
                $caminho = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'ava/logs-do-sistema.log';
                $string = file_get_contents($caminho);
                $nome = date('d-m-Y H\h:i\m:s\s');
                ?>
                <a href="<?php echo '../../ava/logs-do-sistema.log'; ?>"  class="btn btn-primary"
                   download="<?php echo "logs-do-sistema-$nome.log"; ?>">Download do Arquivo</a>
                
                <a id="deleteLog" class="btn btn-danger" ><i class="icon-trash"></i> Excluir Este Log</a><hr />
                   
                <textarea name='textarea1' id='textarea1' rows="25" style='width: 100%;'><?php echo $string; ?></textarea>
            </div>
            <script>

                $(document).ready(function () {
                    //==============================
                    //  EXCLUI UM ARQUIVO DE LOG
                    //==============================
                    $('#deleteLog').click(function () {
                        if (confirm('Deseja realmente excluir este arquivo de log?')) {
                            $.post('models/delete_log.php', function (data) {
                                if (data == '1') {
                                    alert('Log excluído com sucesso!');
                                }
                                else {
                                    alert(data);
                                }
                            });
                        }

                    });
                });
            </script>
        </div>
    </body>
</html>
