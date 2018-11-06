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
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/style.man.css" rel="stylesheet">
        <link href="../css/main-css.css" rel="stylesheet">
        <link href="../css/bootstrap-responsive.css" rel="stylesheet">
        <style>
            body{
                text-align: center !important;
            }
        </style>
    </head>
    <body>
        <?php
        include '../_views/topo.php';
        ?>
        <div class="container">
            <div class="row-fluid text-center">
                <div class="span12">
                    <a href="./" class="btn btn-primary" target="" title>Voltar</a>
                    <hr />
                    <?php
                    $array = array('aluno', 'professor', 'administrador');

                    $haystack = val_input::sani_string('tipo');

                    $usuario_id = trim(val_input::sani_string('id'));

                    $t = new T_PrintScreen($connection);

                    $var = $t->select_imagem($usuario_id);

                    $r = $var->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <img src="<?php echo $r['printscr_imagem']; ?>" />
                </div>
            </div>
        </div>
    </body>
</html>
