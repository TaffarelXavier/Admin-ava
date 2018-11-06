<div class="row-fluid">
    <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab">Início</a></li>
        <li><a href="#profile" data-toggle="tab">Enviar Novo Comunicado</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="home">
            <div class="accordion" id="accordion2">
                <?php
                $sup = new Suporte($connection);
                $var = $sup->select('SELECT * FROM `suporte` AS t1 GROUP BY t1.suporte_nivel');
                $i = 0;
                while ($linhas = $var->fetch()) {
                    ++$i;
                    $in = '';

                    if ($i == 1) {
                        $in = 'in';
                    }
                    ?>
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $i; ?>">
                                <?php echo $linhas['suporte_nivel']; ?>
                            </a>
                        </div>
                        <div id="collapse<?php echo $i; ?>" class="accordion-body collapse <?php echo $in; ?>">
                            <div class="accordion-inner">
                                <table class="table table-striped table-bordered table-hover dataTable" aria-describedby="sample_1_info">
                                    <thead>
                                        <tr role="row">
                                            <th>#Id:</th>   
                                            <th>#Mensagem:</th>   
                                            <th>#Destinatários:</th>   
                                            <th>#Data:</th>   
                                            <th>#Remetente:</th>   
                                            <th>#Ordem:</th>   
                                        </tr>
                                    </thead>
                                    <?php
                                    $d = $sup->select('SELECT * FROM `suporte` AS t1 WHERE suporte_nivel = "' . $linhas['suporte_nivel'] . '"  ORDER BY t1.suporte_id DESC  ');
                                    while ($linhas2 = $d->fetch(PDO::FETCH_ASSOC)) {
                                        $status = 'Fechar';
                                        $btncor = 'btn-warning';
                                        $classe = 'fechar-suporte';
                                        if ($linhas2['suporte_status'] == 'fechado') {
                                            $status = 'Abrir';
                                            $btncor = 'btn-success';
                                            $classe = 'abrir-suporte';
                                        }

                                        $array_json = array('suporte_id' => $linhas2['suporte_id'], 'suporte_remetente' => $linhas2['suporte_remetente'],
                                            'suporte_ordem' => $linhas2['suporte_ordem'], 'suporte_ordem' => $linhas2['suporte_ordem'], 'suporte_tipo' => $linhas2['suporte_tipo']);
                                        ?>
                                        <tbody role="alert" aria-live="polite" aria-relevant="all" id='tbody'>
                                            <?php
                                            echo '<tr><td>', $linhas2['suporte_id'], '</td>';
                                            echo '<td>', Cortar_Texto::cortar(htmlentities(stripslashes($linhas2['suporte_mensagem'])), 100), '</td>';
                                            echo '<td>', $linhas2['suporte_nivel'], '</td>';
                                            echo '<td>', date('d/m/Y \à\s H:i:s', $linhas2['suporte_data']), '</td>';
                                            echo '<td>', $linhas2['suporte_remetente'], '</td></td>';
                                            echo '<td>', $linhas2['suporte_ordem'], '</td></td>';
                                            echo '<td><a class="btn btnVisualizar" data-suporte-id="' . $linhas2['suporte_id'] . '" >Visualizar</a><button data-suporte-id=' . $linhas2['suporte_id'] .
                                            ' class="btn ' . $btncor . ' ' . $classe . ' span12">', $status, '</button></td>'
                                            . '<td class="text-center" style="text-align:center;">'
                                            . '<a href="#modalEditarSuporte" accesskey="e"  data-mensagem="' . htmlentities(stripslashes($linhas2['suporte_mensagem'])) . '" data-source=\'' . json_encode($array_json) . '\' data-toggle="modal" class="editar_mensgem text-center"><svg  class="text-center" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 20 20" focusable="false"><path fill="#000000" d="M10,6c1.104,0,2-0.896,2-2s-0.896-2-2-2S8,2.895,8,4S8.896,6,10,6z M10,8c-1.104,0-2,0.896-2,2s0.896,2,2,2s2-0.896,2-2  S11.104,8,10,8z M10,14c-1.104,0-2,0.896-2,2s0.896,2,2,2s2-0.896,2-2S11.104,14,10,14z"></path>
                            </svg></a></td></tr>';
                                            ?>
                                        </tbody>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <div id="myModal"  class="modal hide fade">
            <div  class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>Cabeçalho do modal</h3>
            </div>
            <div class="modal-body">
                <div class="get_dados_views"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger"  data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i>Fechar</button>
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
                <button class="btn btn-info" id="btnAbrirUsuarios"><i class="icon-cog"></i> Usuários-Views</button>
                <button class="btn btn-danger" id="btnExcluirSuporte"  aria-hidden="true"><i class="icon-trash"></i> Excluir</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> Fechar</button>
                <button class="btn btn-success" type="submit" form="formUpdateSuporte"><i class="icon-ok"></i> Salvar alterações</button>
            </div>
        </div><!--FIM MODAL EDITAR COMUNICADO-->

        <!--INÍCIO INCLUIR NOVO COMUNICADO-->
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
        <!--FIM INCLUIR NOVO COMUNICADO-->
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.btnVisualizar').click(function() {

            var _suporteID = $(this).attr('data-suporte-id');

            $('#myModal').modal('show');

            $('.get_dados_views').html('Carregando...');

            $.post('_views/alunos_views.php', {suporteID: _suporteID}, function(data) {
                $('.get_dados_views').html(data);
            });

        });
    });
</script>