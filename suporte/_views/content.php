<div class="row-fluid">
    <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab">Início</a></li>
        <!--<li><a href="#profile" data-toggle="tab">Enviar Novo Comunicado</a></li>-->
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="home">
            <?php
            $suporte = new Assistencia($connection);
            $connection->query('use ' . DB_NAME . ';');
            $suporte_id = val_input::sani_string('suporte-id');
            if ($suporte_id == false)
            {
                ?>
                <div class="accordion in collapse" id="accordion1" style="height: auto;">
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1">
                                <i class="icon-align-left"></i>
                                Suportes
                            </a>
                        </div>
                        <div id="collapse_1" class="accordion-body collapse in">
                            <div class="accordion-inner">
                                <table class="table table-striped table-bordered table-hover dataTable" aria-describedby="sample_1_info">
                                    <thead>
                                        <tr role="row">
                                            <th>#Id:</th>   
                                            <th>#Assunto:</th>   
                                            <th>#Criado em:</th>    
                                            <th>#Nível:</th>   
                                            <th>#Professor:</th>
                                            <th>#Status:</th>  
                                        </tr>
                                    </thead>
                                    <?php
                                    $var = $suporte->select('SELECT * FROM `assistencia` AS t1 JOIN professores AS t2 ON t1.assistencia_user_id  = t2.prof_id ORDER BY t1.assistencia_status, t1.assistencia_id DESC,t1.assistencia_data DESC');
                                    while ($linhas2 = $var->fetch(PDO::FETCH_ASSOC))
                                    {
                                        ?>
                                        <tbody role="alert" aria-live="polite" aria-relevant="all" id='tbody'>
                                            <tr>
                                                <td><?php echo $linhas2['assistencia_id']; ?></td>
                                                <td><?php echo $linhas2['assistencia_assunto']; ?></td>
                                                <td><?php echo date('d/m/Y \à\s H:i:s', $linhas2['assistencia_data']); ?></td>
                                                <td><?php echo $linhas2['assistencia_nivel']; ?></td>
                                                <td><?php echo $linhas2['prof_nome']; ?></td>
                                                <td><?php echo $linhas2['assistencia_status']; ?></td>
                                                <td style="text-align:center;">
                                                    <a href="?suporte-id=<?php echo $linhas2['assistencia_id']; ?>" class="btn">
                                                        <i class="icon-chevron-right"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else
            {
                $suporte_id = val_input::sani_number_int('suporte-id');
                $assunto = val_input::sani_string('assunto');
                if ($suporte_id != false)
                {
                    ?>
                    <div class="ftxb-suporte">
                        <form id="formInsertResposta" class="" method="post">
                            <textarea rows="4" name="mensagem" class="span12" autofocus=""></textarea>
                            <input name="suporte-id" class="" type="hidden" id="suporte-id" value="<?php echo $suporte_id; ?>" />
                            <div class="row-fluid">
                                <div class="span12 text-right">
                                    <button class="btn btn-success"><i class=""></i>Enviar Mensagem</button>
                                    <a href="./" class="text-error btn btn-danger"><i class="icon-off"></i>Encerrar Suporte</a>
                                    <a href="./" class="text-error btn"><i class="icon-off"></i>Fechar</a>
                                </div>
                            </div>
                        </form>
                        <script>
                            $(document).ready(function () {
                                $("#formInsertResposta").ajaxForm({
                                    url: '_models/insert_resposta.php',
                                    beforeSend: function () {
                                    },
                                    uploadProgress: function (event, position, total, percentComplete) {
                                    },
                                    success: function (responseText) {
                                        if (responseText == '1') {
                                            alert('Mensagem enviada com sucesso!');
                                            window.location.reload();
                                        }
                                        else {
                                            alert(responseText);
                                        }
                                    }
                                });
                            });
                        </script>
                        <h3 class='bold' id="suporte<?php echo $suporte_id; ?>">Id do Suporte: <?php echo $suporte_id; ?></h3>
                        <label class='bold'>Assunto: <?php echo $assunto; ?></label>
                        <hr style="border:0;border-top:1px solid #ccc" />
                        <?php $suporte->show_mensagens($suporte_id); ?>
                    </div>
                    <?php
                }
            }
            ?>
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
        <!--
        <div class="tab-pane fade" id="profile">
            <form class="form-horizontal" id="formEnviarNovoComunicado" method="post">
                <div class="row-fluid">
                    <div class="control-group">
                        <div class="controls">
                            <label class="pull-left" >
                                <input type="radio" name="tipo" value="text" id="insertText" class="pull-left btn" required="" />
                                &nbsp;Somente texto&nbsp;&nbsp;</label>
                            <label class="pull-left" >
                                &nbsp;<input type="radio" name="tipo" value="alert" id="insertAlert" class="pull-left btn" required=""  />
                                Alert&nbsp;&nbsp;</label>
                            <label class="pull-left" >
                                &nbsp;<input type="radio" name="tipo" value="modal" id="insertModal" class="pull-left btn" required=""  />
                                Modal&nbsp;</label>&nbsp;<br/>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <label class="pull-left" >Ordem:
                                <input type="number" name="ordem" id="ordem" pattern="/\d/" class="span12 pull-left" required="" /></label>
                        </div>
                    </div>
                    <div class="control-group" hidden="" id="get_error_group">
                        <div class="controls">
                            <div class="alert alert-block alert-danger" style="padding:5px;">
                                <label class="text-error" id="get_error"></label>
                            </div>
                        </div>
                    </div>
                    <div id="fooddmenu" >Click inside this div</div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Remetente:</label>
                    <div class="controls">
                        <input type="text" class="span12" value="Taffarel Xavier" id="inputEmail" placeholder="Remetente" required="" name="remetente">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">Mensagem:</label>
                    <div class="controls">
                        <textarea rows="7" class="span12" required="" id='mensagem' name="mensagem" autofocus=""></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">Destinatário:</label>
                    <div class="controls">
                        <select class="m-wrap span12" required="" id="selectDestinatario" name="nivel">
                            <option value=''>Selecione...</option>
                            <option value="Administração">Administração</option>
                            <option value="Professores">Professores</option>
                            <option value="Alunos">Alunos</option>
                        </select>
                    </div>
                </div>
                <div class="control-group destinatário" hidden="">
                    <label class="control-label selDestis" for="inputPassword">Destinatário:</label>
                    <div class="controls">
                        <span class="texto-info"></span>
                        <div class="mostrar-select">
                            <select class="m-wrap span12" multiple="" size="10" name="relacao[]" required=""></select>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">Status:</label>
                    <div class="controls">
                        <input type="text" readonly="" value="aberto" name="status"/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn">Enviar</button>
                    </div>
                </div>
            </form>
        </div>
        -->
        <!--FIM INCLUIR NOVO COMUNICADO-->
    </div>
</div>