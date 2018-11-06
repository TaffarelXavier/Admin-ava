<style>
    @import url("../css/emoctions.css");
    @import url("../css/tx_emoji.css");
    td{
        vertical-align: middle !important;
        text-align: left !important;
    }
    .emoji{background-image:url(../img/twitter-20151211-2.min.png);}

    .apple .emoji {background-image:none;}

    .emoji:active{
        background:white;
        cursor:pointer;
    }
    .emoji{
        border:1px solid transparent; 
    }
    .emoji:hover{
        border:1px solid #ccc; 
        cursor:pointer;
    }
</style>
<div class="row-fluid">
    <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab">Início</a></li>
        <li id="enviar-novo-comunicado"><a href="#profile" data-toggle="tab">Enviar Novo Comunicado</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="home">
            <table style='table-layout: fixed;overflow: hidden;'
                   class="table" id="table1">
                <thead>
                    <tr role="row">
                        <th style="width: 5%;">#Id:</th>   
                        <th style="width: 10%;">#Aluno ID:</th>   
                        <th>#Mensagem:</th>   
                        <th>#Aluno:</th>   
                        <th>#Data:</th>   
                        <th>#Pasta:</th>   
                        <th style="width: 10%;text-align:center !important;">#Tipo:</th>   
                        <th>#Opção:</th>   
                    </tr>
                </thead>
                <tbody>
                    <?php

                    function Porcentagem($acertosOuErros, $totalDeQuestoes) {
                        return ($acertosOuErros * 100) / $totalDeQuestoes;
                    }

                    $sup = new Ajuda($connection);

                    $var = $sup->select('SELECT ajuda_id,ajuda_caminho_pasta,ajuda_tipo,ajuda_nivel,ajuda_mensagem,ajuda_data_envio,ajuda_status,alu_nome,alu_id,ajuda_visto,ajuda_file FROM `ajuda` AS t1 JOIN alunos AS t2 ON t1.ajuda_usuario_id = t2.alu_id ORDER BY t1.ajuda_id DESC');

                    $i = 0;
                    $url = '';
                    $problema = $sugestao = $curtida = 0;
                    while ($linhas = $var->fetch(PDO::FETCH_ASSOC)) {

                        switch ($linhas['ajuda_tipo']) {
                            case 'curtida':
                                ++$curtida;
                                break;
                            case 'problema':
                                ++$problema;
                                break;
                            case 'sugestão':
                                ++$sugestao;
                                break;
                        }
                        ++$i;

                        $in = '';

                        if ($i == 1) {
                            $in = 'in';
                        }

                        $rrToken = Tokens::inserir_nova();

                        $key = $rrToken[0];

                        $token = $rrToken[1];

                        $arr = json_encode($linhas);

                        $url = "/ava/administrativo/admin-sistema/models/get_conectar_aluno.php?aluno-id="
                                . $linhas['alu_id'] . "&nome=" . $linhas['alu_nome'] . "&key=" . $key . "&token=$token";

                        $arr_tipos = array('white', 'normal');

                        if ($linhas['ajuda_visto'] == 'nao') {
                            $arr_tipos = array('#ccc', 'bold');
                        }

                        $dadosExclusaoInteiramente = array('usuario_id' => $linhas['alu_id'], 'ajuda_id' => $linhas['ajuda_id']);

                        //Total de mensagens não lidas, advindas dos alunos;

                        $totalMensagensNaoLidas = $sup->contar_mensagens_nao_lidas($linhas['ajuda_id']);
                        if ($totalMensagensNaoLidas > 0) {
                            echo '<label>ID:<b>' . $linhas['ajuda_id'] . '--</b>';
                            echo "Mensagen(s) não lida(s)--:<b>[" . $totalMensagensNaoLidas, ']</b></label>';
                        }

                        echo '<tr id="ajuda-' . $linhas['ajuda_id'] . '"  style="background: ' . $arr_tipos[0] .
                        ' !important;font-weight:' . $arr_tipos[1] . '"><span style="position:absoluy"></span>'
                        . '<td style="width: 5%;text-align:center !important;">', $linhas['ajuda_id'], '</td>';

                        echo '<td style="width: 10%;text-align:center !important;">', $linhas['alu_id'], '</td>';
                        echo '<td style="">', Cortar_Texto::cortar(htmlentities(stripslashes($linhas['ajuda_mensagem'])), 100), '</td>';
                        echo '<td style="">', $linhas['alu_nome'], '</td>';
                        echo '<td style="">', date('d/m/Y \à\s H:i:s', $linhas['ajuda_data_envio']), '</td>';
                        echo '<td style="overflow: hidden;text-overflow:ellipsis;">', $linhas['ajuda_caminho_pasta'], '</td>';
                        echo '<td style="width: 10%;text-align:center !important;">', $linhas['ajuda_tipo'], '</td>';
                        echo '<td style="">';
                        ?>
                    <div class="btn-group">
                        <a <?php echo 'data-dados=\'' . $arr . '\' ' . $linhas['ajuda_id'] . ''; ?>
                            class="pull-left btn-mini btn btn-success btnEnviar"><i class="icon-edit"></i></a>
                        <a class="btn pull-left  btn-mini btn-danger excluirTopicoInteiro" data-exclusao='<?php echo json_encode($dadosExclusaoInteiramente); ?>' title="Excluir este tópico."><i class="icon-trash"></i></a>
                        <a href="<?php echo $url; ?>" class="pull-left btn-mini btn btn-info" target="_blank">Conectar</a>
                    </div>
                    <?php
                    echo '</td></tr>';
                    ?>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <?php
            $totalAjduas = $problema + $sugestao + $curtida;
            ?>
            <h2>Porcentagem:</h2>
            <table class="table">
                <tbody>
                    <tr><td style="text-align: right !important;">Problemas:</td>
                        <td style="text-align: center !important;"><?php echo $problema; ?></td>
                        <td><?php echo number_format(Porcentagem($problema, $totalAjduas), '2') . '%'; ?></td></tr>
                    <tr><td style="text-align: right !important;">Sugestões:</td>
                        <td style="text-align: center !important;"><?php echo $sugestao; ?></td>
                        <td><?php echo number_format(Porcentagem($sugestao, $totalAjduas), '2') . '%'; ?></td></tr>
                    <tr><td style="text-align: right !important;">Curtidas:</td>
                        <td style="text-align: center !important;"><?php echo $curtida; ?></td>
                        <td><?php echo number_format(Porcentagem($curtida, $totalAjduas), '2') . '%'; ?></td></tr>
                    <tr><td style="text-align: right !important;"></td>
                        <td style="text-align: center !important;"><b><?php echo $totalAjduas; ?></b></td>
                        <td><b><?php echo number_format(100, '2') . '%'; ?></b></td></tr>
                </tbody>
            </table>
        </div><br/><br/><br/><br/>

        <!--INÍCIO INCLUIR NOVO COMUNICADO-->
        <div class="tab-pane fade" id="profile">
            <table class="table" style="table-layout: fixed;">
                <tbody>
                    <tr>
                        <td style="width: 10%;text-align: right !important;font-weight: bold;">ID:</td>
                        <td><span id='get_ajuda_id'></span></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;text-align: right !important;font-weight: bold;">Aluno:</td>
                        <td><span id='get_aluno_nome'></span> <a style="cursor: pointer;">[<span id='get_aluno_id'></span>]</a></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;text-align: right !important;font-weight: bold;">Tipo:</td>
                        <td><span id='get_tipo'></span></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;text-align: right !important;font-weight: bold;">Caminho:</td>
                        <td><span id='get_caminho'></span></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;text-align: right !important;font-weight: bold;">Mensagem:</td>
                        <td><span id='get_mensagem'></span></td>
                    </tr>
                    <tr id="trGetFile" hidden="">
                        <td style="width: 10%;text-align: right !important;font-weight: bold;">Arquivo:</td>
                        <td><span id='get_file'></span></td>
                    </tr>
                </tbody>
            </table>
            <div class="row-fluid">
                <form class="form-horizontal" id="ajudaFormEnviarResposta" method="post">
                    <div class="span3">
                        <label class="bold " for="inputEmail"><b>Remetente:</b></label>
                        <input type="text" class="span12" value="Taffarel Xavier" 
                               id="inputEmail" placeholder="Remetente" required="" name="remetente">
                        <input type="hidden" name="ajuda_id" id="ajuda_id"  class="" />
                    </div>
                    <div class="span9">
                        <div class="span12" contenteditable="" tabindex="-1"
                             id="editor" style="background:white;height: 100px;border:1px solid #ccc;
                             overflow: auto;padding:10px;border-radius:10px !important;"><span class="text-focus" style="color:#ccc;font-style: italic;">Digite a mensagem aqui.</span></div>
                        <label class="bold" for="inputPassword"><b>Mensagem:</b></label>
                        <textarea style="display: none;"
                                  id='mensagem' name="mensagem"></textarea>
                        <div class="alert alert-success get_resultado" hidden=''></div>
                        <!--BOTÃO PARA SUBMIT-->
                        <button type="submit" class="btn" id='ajudaBtnEnviar'>Enviar</button>
                        <button class="btn green" id="atualizarLista" type="button">Atualizar</button>
                        <button class="btn green" id="limparEditor" type="button">Limpar editor</button><br/>
                    </div>
                </form>
            </div><br/>
            <div id="navbar-example">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active">
                        <a data-toggle="tab" href="#menu8">Fb</a></li>
                    <li><a data-toggle="tab" href="#home1">Símbolos Pessoais</a></li>
                    <li><a data-toggle="tab" href="#menu2">Natureza Símbolos</a></li>
                    <li><a data-toggle="tab" href="#menu3">Símbolo Objeto</a></li>
                    <li><a data-toggle="tab" href="#menu4">Coloque símbolos</a></li>
                    <li><a data-toggle="tab" href="#menu5">Símbolos especiais</a></li>
                    <li><a data-toggle="tab" href="#menu6">Bandeira</a></li>
                    <li><a data-toggle="tab" href="#menu7">Letras</a></li>
                </ul>
                <div class="tab-content">
                    <div id="menu8" class="tab-pane fade in active text-center" style="text-align: center;">
                        <div class="tx_emoji tx_emoji4" style="float:left;" title="Curtir"></div>
                        <div class="tx_emoji tx_emoji5" style="float:left;" title="Amei"></div>
                        <div class="tx_emoji tx_emoji1" style="float:left;" title="Enfurecido"></div>
                        <div class="tx_emoji tx_emoji2" style="float:left;" title="Flor"></div>
                        <div class="tx_emoji tx_emoji3" style="float:left;" title="Haha!"></div>
                        <div class="tx_emoji tx_emoji6" style="float:left;" title="Triste"></div>
                        <div class="tx_emoji tx_emoji7" style="float:left;" title="Uau!"></div>
                    </div>
                    <div id="home1" class="tab-pane fade">
                        <?php
                        Emoctions::bloco1();
                        ?>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <?php
                        Emoctions::bloco2();
                        ?>
                    </div>
                    <div id="menu3" class="tab-pane fade">
                        <?php
                        Emoctions::bloco3();
                        ?>
                    </div>
                    <div id="menu4" class="tab-pane fade">
                        <?php
                        Emoctions::bloco4();
                        ?>
                    </div>
                    <div id="menu5" class="tab-pane fade">
                        <?php
                        Emoctions::bloco5();
                        ?>
                    </div>
                    <div id="menu6" class="tab-pane fade">
                        <?php
                        Emoctions::bloco6();
                        ?>
                    </div>
                    <div id="menu7" class="tab-pane fade">
                        <?php
                        Emoctions::bloco7();
                        ?>
                    </div>
                </div>    
                <hr style="border:0;border-top:1px solid #ccc; " />
            </div>
            <div class="row-fluid">
                <div class="get_mensagens"></div>
            </div>
        </div>
        <!--FIM INCLUIR NOVO COMUNICADO-->
    </div>
</div>
<!--Modal Contrato-->
<div id="modalEditar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h3 id="myModalLabel3">Editar Post</h3>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <form enctype="multipart/form-data" id="formEditarPublicacao" method="post">
                <input name="post_id" readonly="" size="10" type="text" id="form_editar_aju_id" />
                <textarea name="texto" class="span12" cols="" rows="8" id="form_editar_texto" style="max-width: 100%;" ></textarea>
            </form> 
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success" form="formEditarPublicacao" id="btnEditarPublicacao">Confirmar</button>
        <button type="button" class="btn btn-danger" id="btnExcluirPublicacao">Excluir</button>
        <button class="btn btn-info" data-dismiss="modal" aria-hidden="true">Fechar</button>
    </div>
</div>

<?php $arr = Tokens::inserir_nova(); ?>

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
            <textarea rows="4" class="span12" name="mensagemEdicao" id="mensagemEdicao" style="max-width: 100%;"></textarea>
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
<script src="../js/emoji.js"></script>
<script>
    $(document).ready(function() {

        var handleChartGraphs = function() {
            
            var suge = "<?php echo $sugestao; ?>";
            var curt = "<?php echo $curtida; ?>";
            var prob = "<?php echo $problema; ?>";
            
            var dataSet = [
                {label: "Sugestão", data: parseInt(suge), color: "#005CDE"},
                {label: "Curtidas", data: parseInt(curt), color: "#00A36A"},
                {label: "Problemas", data: parseInt(prob), color: "#7D0096"}
            ];

            $.plot('#placeholderGrafico', dataSet, {
                series: {
                    pie: {
                        show: true,
                        label: {
                            show: true,
                            radius: 3/4,
                            formatter: function(label, series) {
                                return '<div style="border:1px solid grey;font-size:8pt;text-align:center;padding:5px;color:white;">' +
                                        label + ' : ' + Math.round(series.percent) + '%</div>';
                            },
                            background: {
                                opacity: 0.8,
                                color: '#000'
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        };

        /*handleChartGraphs();*/

        function insertEmoji(classEmoji) {
            var _thisClass = $(classEmoji).attr('class');
            var d = _thisClass.split(/\s+/gmi);
            return '<span class="tx16emoji tx_emoji16_' + d[1] + '" contenteditable="false"  title="" ></span>';
        }

        function insertAtCaret(areaId, text) {
            var editor = document.getElementById(areaId);
            var scrollPos = editor.scrollTop;
            var strPos = 0;
            var br = ((editor.selectionStart || editor.selectionStart == '0') ?
                    "ff" : (document.selection ? "ie" : false));
            if (br == "ie") {
                editor.focus();
                var range = document.selection.createRange();
                range.moveStart('character', -editor.value.length);
                strPos = range.text.length;
            }
            else if (br == "ff")
                strPos = editor.selectionStart;

            var front = (editor.innerHTML).substring(0, strPos);
            var back = (editor.innerHTML).substring(strPos, editor.innerHTML.length);
            editor.innerHTML = front + text + back;
            strPos = strPos + text.length;
            if (br == "ie") {
                editor.focus();
                var range = document.selection.createRange();
                range.moveStart('character', -editor.innerHTML.length);
                range.moveStart('character', strPos);
                range.moveEnd('character', 0);
                range.select();
            }
            else if (br == "ff") {
                editor.selectionStart = strPos;
                editor.selectionEnd = strPos;
                editor.focus();
            }
            editor.scrollTop = scrollPos;
        }


        /*Insere o emoction*/
        
        $('.tx_emoji').click(function() {
            $(".text-focus").remove();
            $('#editor').append(insertEmoji(this));
            $('#mensagem').val($('#editor').html());
        });
        $('.excluirTopicoInteiro').click(function() {

            var dados = $(this).attr('data-exclusao');

            var _obj = JSON.parse(dados);

            if (confirm('Deseja realmente excluir este tópico permanente e inteiramente?')) {
                $('#modalEditarSuporte').modal('hide');
                $.post('_models/delete_topico_inteiro.php', {
                    ajuda_id: _obj.ajuda_id,
                    usuario_id: _obj.usuario_id
                }, function(data) {
                    if (data == '1') {
                        alert('Dados excluídos com sucesso!');
                        window.location.reload();
                    }
                    else {
                        alert('Houve um erro! Código:' + data);
                    }
                });
            }
        });

        $('#limparEditor').click(function() {
            $('#mensagem').val("");
            $('#editor').html('');
        });

        //=============================
        //  Enviar mensagem
        //=============================
        $("#ajudaFormEnviarResposta").ajaxForm({
            type: 'post',
            url: '_models/insert_ajuda.php',
            beforeSend: function() {
                $('#mensagem').val($('#editor').html());
                $('#ajudaBtnEnviar').attr('disabled', true).html('Enviando, aguarde por favor...');
            },
            success: function(data) {
                if (data == '1') {
                    $('#ajudaBtnEnviar').attr('disabled', false).html('Enviar');
                    $('.get_resultado').slideDown().html('Mensagem enviada com sucesso.').delay(2000).slideUp('');
                    $('#mensagem').val('');
                    $('#editor').html('').focus();
                    $.post('_views/mensagens.php', {
                        ajuda_id: dadosParaAtualizar.ajuda_id
                    }, function(data) {
                        $('.get_mensagens').html(data);
                    });
                }
                else {
                    $('#ajudaBtnEnviar').attr('disabled', false).html('Tentar novamente');
                }
            }
        });

        /*Focus*/
        var fo = '<span class="text-focus" style="color:#ccc;font-style: italic;">Digite a mensagem aqui.</span>';
        $('#editor').focus(function() {
            console.log($(this).html());
            $('.text-focus').remove();
            if ($(this).html().trim() == fo) {
                $(this).html('');
            }
        }).blur(function() {
            if ($(this).html().trim() == '') {
                $(this).html('<span class="text-focus" style="color:#ccc;font-style: italic;">Digite a mensagem aqui.</span>');
            }
        });

        dadosParaAtualizar = undefined;

        $('#enviar-novo-comunicado').click(function() {
            if (dadosParaAtualizar == undefined) {
                alert('Clique em editar em algum registro.');
                $('#myTab a[href="#home"]').tab('show');
                return false;
            }
        });

        $("#formEditarPublicacao").ajaxForm({
            type: 'post',
            url: '_models/update_topico.php',
            beforeSend: function() {
                $('#texto-' + $('#form_editar_aju_id').val()).html($('#form_editar_texto').val());
                $('#modalEditar').modal('hide');
            },
            success: function(data) {

            }
        });

        $('#btnExcluirPublicacao').click(function() {
            if (confirm('Deseja realmente excluir este tópico?')) {
                $('#modalEditar').modal('hide');
                $.post('_models/delete_topico.php', {
                    aju_id: $('#form_editar_aju_id').val(),
                    ajuda_id: dadosParaAtualizar.ajuda_id,
                    usuario_id: 0
                }, function(data) {
                    if (data == '1') {
                        $.post('_views/mensagens.php', {
                            ajuda_id: dadosParaAtualizar.ajuda_id
                        }, function(data) {
                            $('.get_mensagens').html(data);
                        });
                    }
                    else {
                        alert('Houve um erro! Código:' + data);
                    }
                });
            }
        });

        $('.btnEnviar').click(function() {

            var dados = $(this).attr('data-dados');

            var obj = JSON.parse(dados);

            dadosParaAtualizar = obj;

            $.post('_models/insert_ajuda_visualizado.php', {
                ajuda_id: obj.ajuda_id,
                lido: 'sim'
            }, function(data) {
                if (data == '1') {
                    $('#ajuda-' + obj.ajuda_id).css({fontWeight: 'normal', background: 'white'});
                } else if (data == '0') {

                }
                else {
                    alert('Houve um erro!');
                }
            });

            $('#ajuda_id').val(obj.ajuda_id);
            $('#get_ajuda_id').html(obj.ajuda_id);
            $('#get_aluno_nome').html(obj.alu_nome);
            $('#get_aluno_id').html(obj.alu_id);
            $('#get_mensagem').html(obj.ajuda_mensagem);
            $('#get_tipo').html(obj.ajuda_tipo);
            $('#get_caminho').html(obj.ajuda_caminho_pasta);

            var SERVER_NAME = window.location.origin;

            if (obj.ajuda_file != '') {
                $('#trGetFile').show();
                $('#get_file').html('<a href="' + SERVER_NAME + '/ava/arquivos/ajuda/' +
                        obj.ajuda_file + '" target="_blank" >' + obj.ajuda_file + '</a>');
            }
            else {
                $('#trGetFile').hide();
            }


            $('#myTab a[href="#profile"]').tab('show'); // Select tab by name  

            $('.get_mensagens').html('Carregando mensagens...');

            var __key = "<?php echo $arr[0]; ?>";
            var __token = "<?php echo $arr[1]; ?>";

            var url = "/ava/administrativo/admin-sistema/models/get_conectar_aluno.php?aluno-id=" + obj.alu_id + "&nome=" + obj.alu_nome + "&key=" + __key + "&token=" + __token;

            $('#get_aluno_id').click(function() {
                window.open(url, '_blank');
            });

            $.post('_views/mensagens.php', {
                ajuda_id: obj.ajuda_id
            }, function(data) {
                $('.get_mensagens').html(data);
            });

        });

        $('#table1').dataTable({
            "aLengthMenu": [
                [10, 25, 50, -1],
                [5, 15, 20, "All"]
            ],
            "sAjaxDataProp": "aaData",
            "sPaginationType": "bootstrap",
            "bScrollCollapse": true,
            "oSearch": {"sSearch": ""},
            "iDisplayLength": 5,
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": "Anterior",
                    "sNext": "Próximo",
                    "sFirst": "Primeira página"
                },
                "sZeroRecords": "Nenhum dado encontrado",
                "sSearch": "Pesquisar",
                "sEmptyTable": "Nenhum dado foi encontrado na tabela",
                "sInfo": "Mostrando (_START_ até _END_) de _TOTAL_ registros."
            },
            "aaSorting": [
                [0, 'desc']
            ]
        });

        $('#mensagem').select();
        $('#mensagem').focus();

    });
</script>