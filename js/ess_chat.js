/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    t = false;

    var msgESS = new EventSource("_models/mensagens_nao_lidas.php");

    var not2;
    //Envento
    msgESS.addEventListener("mensagens_nao_lidas", function(ev) {

        var arr = JSON.parse(ev.data);

        if (arr[0] == 0) {
            //console.log("Nenhuma notificação");
        }
        else {

            for (i = 1; i < arr.length; i++) {

                var options = {
                    icon: window.location.origin + "/ava/imagens/speaker_icon_rtl.png",
                    tag: "notif" + arr[i].ajuda_id,
                    sound: window.location.origin + '/ava/arquivos/media/audio1.wav',
                    body: arr[i].ajuda_mensagem + "\nID:" + arr[i].ajuda_usuario_id,
                    data: 'dados',
                    dir: 'ltr'
                };

                not2 = new Notification("CEMVS-Notificação - " + arr[i].ajuda_tipo, options);
            }

            not2.addEventListener('click', function() {
                window.location.href = "ajuda/";
                this.close();
            });
            not2.addEventListener('close', function() {
                this.close();
            });
            not2.addEventListener('show', function() {
            });
        }
    });

    switch (msgESS.readyState) {
        case 0:
            break;
        case 1:
            break;
        case 2:
            msgESS = new EventSource("_models/mensagens_nao_lidas.php");
            break;
    }




    /**
     * <p></p>
     * @param {type} _tag
     * @param {type} _texto
     * @param {type} _data
     * @param {type} _icon
     * @returns {undefined}
     */
    function notifyMe(_tag, _texto, _data, _icon) {

        // Let's check if the browser supports notifications
        if (!("Notification" in window)) {
            console.log('%c Este navegador não suporta notificações', "font-size:50px;color:red;");
        }

        // Let's check whether notification permissions have already been granted
        else if (Notification.permission === "granted") {
            // If it's okay let's create a notification
            var titulo = _texto;
            //Opções

            var options = {
                icon: _icon,
                tag: _tag,
                sound: window.location.origin + '/ava/arquivos/media/audio1.wav',
                body: _data,
                data: 'dados',
                dir: 'ltr'
            };
            var permitirNotificao = false;

            if (permitirNotificao === true) {

                var not = new Notification(titulo, options);

                not.addEventListener('click', function() {
                    this.close();
                    t = true;
                });
                not.addEventListener('close', function() {
                    //console.log('Fechando...' + this.data);
                    t = true;
                });

                not.addEventListener('show', function() {
                    //console.log('Abrindo...');
                });
            }


        }
    }
    atotal = 0;
    //Gera o Token
    $.post('_models/gerar_token.php', function() {
    });

    //=================================================
    //      MOSTRA OS ALUNOS ONLINE DO LADO ESQUERDO
    //=================================================

    if (typeof (EventSource) !== "undefined") {

        //Fazendo a conexão com o arquivo.
        var source = new EventSource("_views/online.php");
        //Envento

        source.addEventListener("online", function(e) {
            // console.log(e.lastId
            var arr = JSON.parse(e.data);

            console.log(arr);

            var i;

            var out = '';

            if (arr.length != 0) {
                out += "";
            }

            for (i = 0; i < arr.length; i++) {

                var foto = arr[i].foto;

                if (arr[i].foto == '') {
                    foto = 'avatar.png';
                }
                if (t == false) {
                    notifyMe(i, arr[i].nome, arr[i].data + " [" + arr[i].id + "]", window.location.origin + "/ava/arquivos/alunos-imagem-perfil/" + foto);
                }
                var nomeHref = '';

                var r = $.cookie('tokenKey').split('*');

                nomeHref = "/ava/administrativo/admin-sistema/models/get_conectar_aluno.php?aluno-id=" + arr[i].id + "&nome=" + arr[i].nome + "&key=" + r[0] + "&token=" + r[1];
                out += "<div class='user-online-gui' id='" + arr[i].id + "' title='" + arr[i].id + "'>"
                        + "<img src='../ava/arquivos/alunos-imagem-perfil/" + foto + "' 'alt='alternativa_para_imagem' />"
                        + "<b id='nome-" + arr[i].id + "'>" + arr[i].nome + '<a href="' + nomeHref + '" target="_blank" class="pull-right">[' + arr[i].id + ']</a>' + "</b><br/>"
                        + "<time style='font-size:12px;position:relative;top:12px;'>" + arr[i].data + "</time>"
                        + "<span style='font:bold 12px arial;float:right;position:relative;top:17px;'>" + arr[i].dispositivo.toUpperCase() + "</span>"
                        + "<div class='clearfix'></div></div>";
            }

            if (i == 0) {
                $('#totalDeAlunosOn').text('0');
            }
            else {
                $('#totalDeAlunosOn').text(i);
            }

            //Preenche a lista de usuários online.
            $('#usuarios-online').html(out);


            $('.user-online-gui').click(function() {

                //Pega o ID
                var _thID = this.id;

                //Pega o nome do usuário
                var _nome = $('#nome-' + _thID);

                var chat = "<div class='t-chat' id='taffa-chat-" + _thID + "'>"
                        + "<b class='t-chat-titulo'>" + _nome.text() + "<span class='t-chat-close close'>x</span></b>"
                        + "<div class='row-fluid t-char-mensageiro' id='get-mensagens-" + _thID + "'></div>"
                        + "<textarea class='span12' placeholder='Digite uma mensagem e clique em enviar.'"
                        + "id='mensagem-" + _thID + "'></textarea>"
                        + "<button class='btn btn-info btn-enviar-mensagem' id='" + _thID + "'>Enviar</button></div>";

                //Conta o total de elementos por classe
                var chatsAbertos = document.getElementsByClassName('t-chat');

                var totalDeChat = chatsAbertos.length + 1;

                //Se o elemento não existir, então ele é adicionado ao navegador.
                if ($("#taffa-chat-" + _thID).size() == 0) {

                    $('.get-chats').append(chat);

                    var _left = $('#taffa-chat-' + this.id).prop('offsetWidth') + 5;

                    $('#taffa-chat-' + this.id).css({left: (totalDeChat * _left) + 'px'});
                    //Focus no campo de enviar a mensagem.
                    $('#mensagem-' + _thID).focus();

                    //Quando o cliente clica no botão de enviar 
                    $('.btn-enviar-mensagem').click(function() {
                        var _thisId = this.id;

                        var _novaMsg = $('#mensagem-' + _thisId).val();

                        if (_novaMsg != '') {
                            var _mensagem = "<div class='row-fluid t-chat-mensagens'><div class='span12'>" + _novaMsg + "</div></div>";
                            $('#get-mensagens-' + _thisId).append(_mensagem);
                            $('#mensagem-' + _thisId).select();
                        }
                    });

                    //Limpando a variável
                    $('#mensagem-' + _thID).val('');
                    $('#mensagem-' + _thID).keyup(function(ev) {
                        if (ev.keyCode == 13) {
                            var _novaMsg = $('#mensagem-' + _thID).val();

                            if (_novaMsg != '') {
                                var _mensagem = "<div class='row-fluid t-chat-mensagens'><div class='span12'>" + _novaMsg + "</div></div>";
                                $('#get-mensagens-' + _thID).append(_mensagem);
                                $('#mensagem-' + _thID).select();
                            }
                        }
                    });

                    $('.t-chat-close').click(function() {
                        $('#taffa-chat-' + _thID).hide();
                    });

                }
                else {
                    $('#taffa-chat-' + _thID).show();
                }
            });

        }, false);

        source.onerror = function(ev) {
            //console.log(ev);

        };
        source.onopen = function(ev1) {
            //console.log(ev1);
        };

        source.onmessage = function(event) {
            console.log(event);
        };

        switch (source.readyState) {
            case 0:
                //console.log('%c A conexão está fechada.', 'font-size:30px;color:red;border:4px dashed red;margin:0px auto;display:block;');
                break;
            case 1:
                //console.log('%c A conexão está aberta', 'font-size:30px;color:green;border:4px dashed green;');
                break;
            case 2:
                //console.log('%c Inicializando...', 'font-size:30px;color:green;border:4px dashed lime');
                source = new EventSource("_views/online.php");
                break;
        }
    } else {
        console.log('%c O seu navegador não aceita o EventSource', "font-size:50px;color:red;");
    }



    /**
     * Notificação
     * Notification.permission 
     * Notification.permission
     * Otherwise, we need to ask the user for permission
     * At last, if the user has denied notifications, and you 
     * want to be respectful there is no need to bother them any more.
     * @returns {undefined}
     */

    var caminho = window.location.origin;

    var criarModalNotificao = '<div id="modalNotificao1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button><h3 id="myModalLabel4">Notificação</h3></div><div class="modal-body text-center" style="text-align:center;"><img src="' + caminho + '/admin/img/icon_notificacao_1.png' + '" alt="" /><img src="' + caminho + '/admin/img/icon_notificacao_2.png' + '" class="img-responsive" alt="" /></div><div class="modal-footer"><button data-dismiss="modal" class="btn btn-danger" id="notiBtnAtualizar" >Fechar</button></div></div>';


    $('body').append(criarModalNotificao);

    Notification.requestPermission().then(function(result) {

        switch (result) {
            case 'default':
                $('#modalNotificao1').modal('show');
                break;
            case 'denied': //Quando nega
                $('#modalNotificao1').modal('show');
                break;
            case 'granted':
                //window.location.reload();
                break;

        }
    });

    $('#notiBtnAtualizar').click(function() {
        window.location.reload();
    });


    //============================================
    //
    //============================================

    var _EventoSource = new EventSource("_views/event_get_notificacoes.php");

    function timeConverter(UNIX_timestamp) {
        var a = new Date(UNIX_timestamp * 1000);
        var months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var date = a.getDate();
        var hour = a.getHours();
        var min = a.getMinutes();
        var sec = a.getSeconds();
        var time = date + ' de ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec;
        return time;
    }

    //Envento
    _EventoSource.addEventListener("event_notificacao", function(ev) {

        var arr = JSON.parse(ev.data);
        if (arr.total > 0) {
            var out = '<div class="alert" ><b>Respostas dos Alunos não lidas:</b></div><ul>';
            for (var x = 0; x < arr.total; ++x) {
                document.title = 'Admin [' + arr.total + ']';
                out += '<li style="border-bottom:1px solid #ccc;"><a href="ajuda/" title="Clique para entrar.">'
                        + '<time style="font:arial 10px;color:#ccc;">'
                        + timeConverter(arr[x].aju_data_resposta) + '</time><br/>'
                        + arr[x].aju_id + ') ' + arr[x].aju_remetente + '</a><br/><span>' + arr[x].aju_mensagem + '</span></li>';
            }
            out += '<ul>';
            $('#resultadoAjudaRespostaNaoLidas').html(out);
        }
    });

    switch (_EventoSource.readyState) {
        case 0:
            break;
        case 1:
            break;
        case 2:
            _EventoSource = new EventSource("_views/event_get_notificacoes.php");
            break;
    }

});