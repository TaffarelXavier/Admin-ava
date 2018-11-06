<?php
include 'autoload.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <title>Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css"/>
        <link rel="stylesheet" href="css/style.man.css"/>
        <link rel="stylesheet" href="css/style_responsive.css"/>
        <link rel="stylesheet" href="css/chat.css"/>
        <script src="js/jquery-1.10.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.form.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <link href="img/favicon.png" rel="shortcut icon"/>	
    </head>
    <body>
        <div class="container-fluid"><br/>
            <div class="row-fluid"></div><br/>
            <?php
            include '_views/topo.php';
            ?>
            <div class="row-fluid">
                <?php
                if (!defined('_LOGADO_')) {
                    include '_views/login.php';
                } else {
                    $dbname = val_input::sani_string('dbname');
                    ?>
                    <div class="row-fluid">
                        <?php
                        $T_User = new T_User($connection);
                        $privilegios = $T_User->get_privilegios($_SESSION['user_id']);
                        ?>
                        <div class="row-fluid">
                            <!--PRIMEIRO COLUNA-->
                            <div class="span3">
                                <div class="btn-group">
                                    <a  href="./" class="pull-left btn-mini btn btn-success"><i class="icon-home"></i>Início</a>
                                    <a class="pull-left btn-mini btn btn-danger desconectar" href="javascript:void(0);"  >
                                        <i class="icon-off"></i>Desconectar</a>
                                    <a href="#modalComparador"  class="pull-left btn-mini btn btn-info" data-toggle="modal"  >
                                        <i class="icon-folder-open"></i>Comparador</a>
                                    <a href="/ava/" class="pull-left btn-mini btn btn-success" target="_blank" title="">
                                        Ava <i class="icon-share-alt"></i></a>
                                </div>
                                <?php
                                if ($privilegios->user_acesso_aos_bancos == 'sim') {
                                    ?>
                                    <div style="max-height: 200px;border:1px solid #ccc;overflow: auto;">
                                        <b>Banco de Dados:</b>
                                        <?php
                                        $sth = $connection->query('show databases;');
                                        $idb = 0;
                                        while ($r = $sth->fetch(PDO::FETCH_ASSOC)) {
                                            if ($r['Database'] != 'information_schema') {
                                                ++$idb;
                                                echo '<a class="databases" id="', $r['Database'], '"'
                                                . ' style="display:block;" href="?dbname=', $r['Database'], '">', $idb, '-', $r['Database'], '</a>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                include '_views/menus.php';
                                ?>
                                <!--GET USUÁRIOS ON-LINE-->
                                <b class='t-chat-usuarios-online'>[<span id='totalDeAlunosOn'>0</span>]Usuários On-line</b>
                                <div id="usuarios-online" style='max-height: 260px;overflow: auto;'></div>
                                <!---->
                                <div class="get-chats"></div>
                                <!--JAVASCRIPT PARA CHAT-->
                            </div>
                            <!--SEGUNDA COLUNA-->
                            <div class="span9">
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div id="resultadoAjudaRespostaNaoLidas"></div>
                                        <?php
                                        if ($privilegios->user_acesso_aos_bancos == 'sim') {
                                            $connection->query("use $dbname;");
                                            $sth2 = $connection->prepare('show tables;');
                                            $sth2->execute();
                                            $i = 0;
                                            if ($dbname != false) {
                                                echo "<h3 style='margin:0px;'>Tabelas</h3>";
                                                ?>
                                                <input type="text" id="getTable" autofocus="" class="span12" placeholder="Pesquisar tabelas" />
                                                <ul id="menu-table">
                                                    <?php
                                                    while ($linhas = $sth2->fetch()) {
                                                        try {
                                                            $contar = $connection->prepare("SELECT count(*) FROM `$linhas[0]`");
                                                            $contar->execute();
                                                            $total = $contar->fetch();
                                                        } catch (Exception $exc) {
                                                            echo $exc->getMessage();
                                                        }
                                                        ++$i;
                                                        $n = $i % 2;
                                                        $bgcolor = '#71C5EF;';
                                                        if ($n == 1) {
                                                            $bgcolor = '#ACDDF5';
                                                        }
                                                        echo "<li  class='chat-alunos taffa-selected' style='padding:3px 1px;background-color:$bgcolor'>"
                                                        . "<a href='?dbname=$dbname&tablename=$linhas[0]#$linhas[0]' style='display:block;color:black;font-size:16px;'>"
                                                        . "$i-$linhas[0]<span class='badge pull-right blue'>$total[0]</span></a></li>";
                                                    }
                                                    ?>
                                                </ul>
                                                <?php
                                            }

                                            if (val_input::sani_string('criarUsuario')) {
                                                include 'usuarios/cadastrar_usuario.php';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="span8">
                                        <!--DADOS SOBRE AS VISITAS-->
                                        <?php
                                        if (!isset($tablename)) {
                                            ?>
                                            <div class="row-fluid">
                                                <div class="span12"><h3 class="total" style='margin:0px;'></h3></div>
                                            </div>
                                            <div class="row-fluid">
                                                <div class="span12 resultado_visitas" style="max-height: 300px;overflow: auto;"></div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <h3 style="margin:0px;padding:0px;">Meu IP:<?php echo $_SERVER['REMOTE_ADDR']; ?></h3>
                                        <?php
                                        if ($privilegios->user_execultar_query == 'sim') {
                                            $url = val_input::sani_url('modalExecultarQuery');
                                            if ($url != false) {
                                                ?>
                                                <form id="formExeculteQuery" class="" method="post">
                                                    <label for=""><span>Para execultar pressione enter.
                                                            <!--para quebra de linha pressione SHIFT+ENTER--></span></label>
                                                    <textarea rows="10" name="query" placeholder="Digite algum comando aqui e execulte-o."
                                                              class="span12" style="max-width: 100%;" autofocus=""></textarea><br/>
                                                    <input name='dbname' type='hidden' value='<?php echo $dbname; ?>' />
                                                    <button form="formExeculteQuery"  
                                                            class="btn btn-primary" disabled="" id="btnExeultarQuery">Execultar</button>
                                                </form>
                                                <div class="resultadoQuery"></div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <?php
                                    if ($privilegios->user_acesso_tables == 'sim') {
                                        $tablename = filter_input(INPUT_GET, "tablename", FILTER_SANITIZE_STRING);
                                        if (isset($tablename)) {
                                            include '_views/detalhes.php';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src="js/ess_chat.js"></script>
                    <!--SEÇÃO DE BACKUP-->
                    <div class="row-fluid">
                        <?php
                        if (val_input::sani_string('fazerBackup')) {
                            include 'backup/models/iniciar_backup.php';
                        }
                        ?>
                    </div>
                    <script src="js/event_source.js"></script>
                    <?php
                    include '_views/modals.php';
                }
                ?>
            </div>
            <div class="row-fluid">
                <div class="footer" style="text-align: center;">
                    <?php echo date('Y'); ?> &copy Desenvolvido por <a href="http://neoinovat.com.br" 
                                              target="_blank" style="color:#2195C9;" class="text-info">Neoinovat</a>
                    <div class="span pull-right">
                        <a href="#top"><span class="go-top"><i class="icon-angle-up"></i></span></a>
                    </div>
                </div>   
            </div>
        </div>
        <script>
            $(document).ready(function() {

                $("#getTable").keyup(function(ev) {

                    if (ev.keyCode == 27) {
                        this.value = '';
                    }
                    var texto = $(this).val();

                    $("#menu-table li").show();

                    $("#menu-table li").each(function() {
                        if ($(this).text().toUpperCase().indexOf(texto.toUpperCase()) < 0) {
                            $(this).hide();
                        }
                    });

                    ev.preventDefault();

                });

                $('.desconectar').click(function() {
                    if (confirm('Deseja realmente sair?')) {
                        $.post('_models/sair.php', function(data) {
                            if (data === '1') {
                                window.location.reload();
                            }
                            else {
                                alert(data);
                            }
                        });
                    }
                });

                //Selcionando o DB
                $("#<?php echo $dbname; ?>").prepend('<i class="icon-share-alt"></i>').css({textDecoration: 'underline', fontWeight: 'bold', backgroundColor: '#ccc', color: 'black'});
            });
        </script>
    </body>
</html>