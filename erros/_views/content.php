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
                ?>
                <a style="background:lime;padding:4px;color:white;">Resolvido</a>
                <a style="background:gold;padding:4px;color:white;">Resolvendo...</a>
                <a style="background:red;padding:4px;color:white;">Não resolvido</a><br/><br/>
                
                <table class="table table-striped table-bordered table-hover dataTable" 
                       aria-describedby="sample_1_info" style="border-radius: 0px !important;">
                    <thead>
                        <tr role="row">
                            <th style="text-align: center;">#Id:</th>   
                            <th>#Página do Erro:</th>   
                            <th style="text-align: left;">#Mensagem:</th>   
                            <th style="text-align: left;">#Data do Envio:</th>   
                            <th style="text-align: center;">#Arquivo:</th> 
                            <th></th>
                        </tr>
                    </thead>
                    <?php
                    while ($linhas = $var->fetch(PDO::FETCH_ASSOC))
                    {
                        ++$i;
                        $in = '';

                        $status = "style='text-align: center;border-left:10px solid " . $linhas['erro_status'] . " !important;'";
                        ?>

                        <tbody role="alert" aria-live="polite" aria-relevant="all" id='tbody' style="border-radius: 0px !important;">
                            <tr style="background: red !important;border-radius: 0px !important;">
                                <td <?php echo $status; ?> >
                                    <?php echo $linhas['erro_id']; ?></td>
                                <td style="text-align: left;width:300px;">
                                    <input type="text" style="width:95%;" class="inputSelected"
                                           name="" value="<?php echo $linhas['erro_pagina_do_erro']; ?>" /></td>
                                <td style="text-align: left;"><?php echo $linhas['erro_mensagem']; ?></td>
                                <td style="text-align: left;"><?php echo date('d/m/Y \à\s H:i:s', $linhas['erro_data']); ?></td>
                                <td style="text-align: center;">
                                    <?php
                                    if ($linhas['erro_file_md5'] != '')
                                    {
                                        $url = '../arquivos/' . $linhas['erro_file_md5'];
                                        echo '<a href="' . $url . '" title="'.$linhas['erro_file_name'] .'" class="btn btn-info" target="_blank" >Visualizar</a>';
                                    }
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <select class="selectStatus" style="width:130px;" id="select<?php echo $linhas['erro_id']; ?>">
                                        <option value="lime">Resolvido</option>
                                        <option value="gold">Resolvendo...</option>
                                        <option value="red">Não resolvido x</option>
                                    </select>
                                    <script>
                                        $(document).ready(function () {

                                            var selectss = $('#select<?php echo $linhas['erro_id']; ?>');

                                            var _indexSelect = "<?php echo $linhas['erro_status']; ?>";

                                            if (_indexSelect == 'lime') {
                                                selectss.prop('selectedIndex', 0);
                                            }
                                            if (_indexSelect == 'gold') {
                                                selectss.prop('selectedIndex', 1);
                                            }
                                            if (_indexSelect == 'red') {
                                                selectss.prop('selectedIndex', 2);
                                            }
                                        });
                                    </script>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-success salvarNovoStatus" 
                                            id="<?php echo $linhas['erro_id']; ?>"> <i class="icon-cog"></i> Salvar</button>
                                </td>
                            </tr>
                        </tbody>

                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
        <!--INÍCIO MODAL EDITAR COMUNICADO-->
        <div  id="modalEditarSuporte" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>CEMVS</h3>
            </div>
            <div class="modal-body">
                <form id="formUpdateSuporte" method="post">
                    <div class="control-group">
                        <div class="controls">
                            <label class="pull-left" >
                                <input type="radio" name="tipo" value="text" id="text-alter" class="pull-left btn" required="" />
                                &nbsp;Somente texto&nbsp;&nbsp;</label>
                            <label class="pull-left" >
                                &nbsp;<input type="radio" name="tipo" value="alert" id="alert-alter" class="pull-left btn" required=""  />
                                Alert&nbsp;&nbsp;</label>
                            <label class="pull-left" >
                                &nbsp;<input type="radio" name="tipo" value="modal" id="modal-alter" class="pull-left btn" required=""  />
                                Modal&nbsp;</label>&nbsp;<br/>
                        </div>
                    </div>
                    <label>Ordem:
                        <input type="number" name="suporteOrdem" id="suporteOrdem" /></label>
                    <label>Mensagem:</label>
                    <textarea rows="4" class="span12" name="mensagemEdicao" id="mensagemEdicao"></textarea>
                    <label>Remetente:</label>
                    <input class="span12" type="text" name="remetenteEdicao" id="remetenteEdicao" />
                    <input type="hidden" name="suporteId" id="suporteId"/>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="btnExcluirSuporte"  aria-hidden="true"><i class="icon-trash"></i> Excluir</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> Fechar</button>
                <button class="btn btn-success" type="submit" form="formUpdateSuporte"><i class="icon-ok"></i> Salvar alterações</button>
            </div>
        </div><!--FIM MODAL EDITAR COMUNICADO-->

        <!--INÍCIO INCLUIR NOVO COMUNICADO-->
        <div class="tab-pane fade" id="profile">
            <form class="form-horizontal" enctype="multipart/form-data" id="formEnviarMensagemDeErro" method="post">
                <div class="row-fluid">
                    <div class="control-group" hidden="" id="get_error_group">
                        <div class="controls">
                            <div class="alert alert-block alert-danger" style="padding:5px;">
                                <label class="text-error" id="get_error"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword" style="font-weight: bold;">Url do erro:</label>
                    <div class="controls">
                        <input name='pagina_do_erro' class='span12' type="url" required="" />
                    </div>

                </div>
                <div class="control-group">
                    <div class="controls">
                        <div id="uploadFile" style="text-align: center;font-weight: bold;">
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <input name='enviar_arquivo' type="checkbox" id="enviar_arquivo1"  />
                        <label for="enviar_arquivo1" style="font-weight: bold;">Com arquivo</label>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword" style="font-weight: bold;">Mensagem:</label>
                    <div class="controls">
                        <textarea rows="7" class="span12" required="" id='mensagem' name="mensagem" autofocus=""></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-success" id="btnEnviarArquivo">Enviar <i class="icon-upload"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <!--FIM INCLUIR NOVO COMUNICADO-->
    </div>
</div>

<script>
    $(document).ready(function () {
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