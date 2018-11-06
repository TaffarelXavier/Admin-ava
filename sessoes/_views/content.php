<style>
    td{
        vertical-align: middle;
        text-align: center;
    }
</style>
<?php
$Sessao = new Sessao_Usuarios($connection);
?>
<div class="row-fluid">
    <div class="row-fluid">
        <ul id="tabulação" class="nav nav-tabs">
            <li class="active">
                <a href="#tab1" data-toggle="tab"><b>Usuário</b></a></li>
            <li><a href="#tab2" data-toggle="tab"><b>Data</b></a></li>
            <li><a href="#tab3" data-toggle="tab"><b>Navegador</b></a></li>
            <li><a href="#tab4" data-toggle="tab"><b>Nível</b></a></li>
            <li><a href="#tab5" id="get_alunos_para_desconectar" data-toggle="tab"><b>Desconectar</b></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab1">
                <form action="" method="post" class="" id="formUsuario">
                    <div class="input-append">
                        <input class="span5" type="text" name="" placeholder="Digite um ID ou nome" autofocus="">
                        <button class="btn" type="submit">Pesquisar</button>
                    </div>
                </form>
                <div class="row-fluid">
                    <div class="get_result_1"></div>
                </div>
            </div>
            <!--INÍCIO INCLUIR NOVO COMUNICADO-->
            <div class="tab-pane fade" id="tab2">
                <form action="" method="post" class="" id="datasForm">
                    <label class="control-label bold">Data Inicial:</label>
                    <input class="" type="text" name="" placeholder="Digite um ID ou nome" autofocus="">
                    <label class="control-label bold">Data Final:</label>
                    <div class="input-append">
                        <input type="text" name="" placeholder="Digite um ID ou nome" autofocus="">
                        <button class="btn" type="submit">Pesquisar</button>
                    </div>
                </form>
                <div class="row-fluid">
                    <div class="get_result_2"></div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab3">
                <form action="_views/get_navegadores.php" method="post" id="navegadorForm">
                    <label class="control-label bold"><b>Navegadores:</b></label>
                    <div class="input-append">
                        <select name="navegador" requered="" id="navegadorSelect">
                            <option value="">Selecione...</option>
                            <?php
                            $f3 = $Sessao->get_grupos_navegador();
                            while ($r = $f3->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . $r['sessao_navegador'] . '">' . $r['sessao_navegador'] . '</option>';
                            }
                            ?>
                        </select>
                        <button class="btn" type="submit">Pesquisar</button>
                    </div>
                </form>
                <div class="row-fluid">
                    <div class="get_result_3"></div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab4">
                <form  action="_views/get_niveis.php" method="post" class="" id="nivelForm">
                    <label class="control-label bold"><b>Nivéis:</b></label>
                    <div class="input-append">
                        <select name="nivel" requered="" id="nivelSelect">
                            <option value="">Selecione...</option>
                            <?php
                            $f4 = $Sessao->get_grupos_nivel();
                            while ($r2 = $f4->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . $r2['sessao_nivel'] . '">' . ucfirst($r2['sessao_nivel']) . '</option>';
                            }
                            ?>
                        </select>
                        <button class="btn" type="submit">Pesquisar</button>
                    </div>
                </form>
                <div class="row-fluid">
                    <div class="get_result_4"></div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab5">
                <div class="get_div_alunos_conectar"></div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="resultado_visitas">Carregando...</div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        if (typeof (EventSource) !== "undefined") {

            var source = new EventSource("_views/get_por_navegadores.php");

            source.addEventListener("visitas", function(e) {
                $('.resultado_visitas').html(e.data);
            }, false);

        } else {
            document.getElementById("result").innerHTML = "Desculpa, mas seu navegador não suporta o objeto DOM EventSource.";
        }


        $('#get_alunos_para_desconectar').click(function(ev) {
            $('.get_div_alunos_conectar').html('Carregando...');
            $.post('_views/get_desconectar_alunos.php', function(data) {
                $('.get_div_alunos_conectar').html(data);
            });

        });

        //
        //
        //

        $('#navegadorForm').ajaxForm({
            beforeSend: function() {
                $('.get_result_3').html('Pesquisando...');
            },
            success: function(responseText) {
                $('.get_result_3').html(responseText);
            }
        });

        //
        //
        //

        $('#nivelForm').ajaxForm({
            beforeSend: function() {
                $('.get_result_4').html('Pesquisando...');
            },
            success: function(responseText) {
                $('.get_result_4').html(responseText);
            }
        });


        function mostrar() {

        }
        $('#selectFiltros').change(function() {

            switch (this.selectedIndex) {
                case 1:
                    break;
                case 2:
                    break;
                case 3:
                    break;
                case 4:
                    break;
            }
        });
    });
</script>

