<style scoped="">
    table, tr, td{
        border-radius: 0px !important;
    }
</style>

<div class="row-fluid">

    <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab">Início</a></li>
        <li><a href="#profile" data-toggle="tab"><b>Enviar Mensagem de Erro</b></a></li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="home">
            <div class="accordion" id="accordion2">
                <?php
                $sup = new Suporte($connection);
                $var = $sup->select('SELECT * FROM `tbl_erros` AS t1 ORDER BY t1.erro_status DESC, t1.erro_id DESC');
                $i = 0;
                $t = new T_PrintScreen($connection);
                ?>
                <div class="row-fluid">
                    <div class="span12">
                        <form action="" method="post" class="" id="formBuscarAlunos">

                            <div class="span9">
                                <div class="control-group">
                                    <label class="control-label bold" style="float:left;">
                                        <input type="radio" name='tipo' checked=""  value='aluno' required="" />
                                        Aluno&nbsp;</label>
                                </div><br/>
                                <div class="control-group">
                                    <label class="control-label bold">Data Inicial</label>
                                    <input type="date" name='data_inicial' value='<?php echo date("Y-m-d"); ?>' required=""  />
                                </div>
                                <div class="control-group">
                                    <label class="control-label bold">Data Final</label>
                                    <input type="date" name='data_final' value='<?php echo date("Y-m-d"); ?>' required=""  />
                                </div>
                                <div class="control-group">
                                    <label class="control-label bold">Limit 1</label>
                                    <?php
                                    $total = $t->contar() + 1;
                                    ?>
                                    <select name="inicio" requered="" id="id">
                                        <?php
                                        for ($i = 0; $i < $total; $i++) {
                                            echo "<option value='$i'>" . $i . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <label class="control-label bold">Limit 2</label>
                                    <select name="final" requered="" id="id">

                                        <?php
                                        for ($i = 0; $i < $total; $i++) {
                                            if (($total - 1) == $i) {
                                                echo "<option value='$i' selected>" . $i . "</option>";
                                            } else {
                                                echo "<option value='$i' >" . $i . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <label class="control-label bold">Id:</label>
                                <div class="">
                                    <div class="input-append">
                                        <input type="text" name="usuario" class="span2 search-query" autofocus="" />
                                        <button type="submit"  id='btnBuscar'  class="btn">Buscar</button>
                                    </div> 
                                </div>
                            </div>
                            <!--<label class="control-label bold" style="float:left;">
                                <input type="radio" name='tipo' value='professor' required=""  />
                                Professors&nbsp;</label>
                            <label class="control-label bold">
                                <input type="radio" name='tipo' value='administrador' required=""  /> 
                                Administrador
                            </label>-->
                        </form>  
                    </div>
                </div>

                <div class="row-fluid">
                    <div class="get_dados"></div>
                </div>
            </div>
        </div>
        <!--INÍCIO INCLUIR NOVO COMUNICADO-->
        <div class="tab-pane fade" id="profile">
            Taffarel
        </div>
        <!--FIM INCLUIR NOVO COMUNICADO-->
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#formBuscarAlunos').ajaxForm({
            beforeSend: function () {
                $('#btnBuscar').attr('disabled', true).html('<i class="icon-search"></i>Buscando, aguarde...');
            },
            url: "_views/select_printscreen.php",
            success: function (d) {
                $('.get_dados').html(d);
                $('#btnBuscar').attr('disabled', false).html('<i class="icon-search"></i>Buscar');
            }
        });

        $('#uploadFile')._neoPluginUpload({accept: '.jpeg,.jpg,.png,.pdf'});
        $('#formEnviarMensagemDeErro').ajaxForm({
            url: "_models/insert_msg_erro.php",
            beforeSend: function () {
                $('#btnEnviarArquivo').attr('disabled', true).html('Enviando, aguarde...');
            },
            success: function (responseText) {
                if (responseText == '1') {
                    alert('Nova mensagem enviada com sucesso!');
                    window.location.reload();
                } else {
                    alert(responseText);
                    $('#btnEnviarArquivo').attr('disabled', false).html('Enviar <i class="icon-save"></i>');
                }
            }
        });
        $('.inputSelected').click(function () {
            this.select();
        });

        $('.salvarNovoStatus').click(function () {
            var thisBtn = $(this);

            var myId = $(this).attr('id');

            var novoSts = $('#select' + myId + ' option:selected');

            $.post("_models/update_status.php", {
                novoStatus: novoSts.val(),
                erro_id: parseInt(myId)
            }, function (data) {
                thisBtn.attr('disabled', true).html('Salvando...');
                if (data == '1') {
                    alert('Operação realizada com sucesso!');
                    window.location.reload();
                }
                else if (data == '0') {
                    alert('Nenhum dado foi atualizado.');
                    thisBtn.attr('disabled', false).html('Salvar');
                }
                else {
                    alert('Houve um erro:' + data);
                    thisBtn.attr('disabled', false).html('Salvar');
                }
            });
        });
        $('#uploadFile input').change(function (e) {
            if (e.target.value != '') {
                $('#enviar_arquivo1').prop('checked', true);
            }
            else {
                $('#enviar_arquivo1').prop('checked', false);
            }
        });
    });
</script>