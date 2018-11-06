$(document).ready(function () {

    $('#selectDestinatario').change(function () {

        var destinatario = $('.destinatário');

        var textoInfo = $('.texto-info');

        var selDestis = $('.selDestis');

        if ($(this).prop('selectedIndex') == 1) {
            $('.mostrar-select select option').remove();
            textoInfo.text('Carregando...');
            $.post("_views/select_tag.php", {nivel: 1}, function (data) {
                $('.mostrar-select select').append(data);
                textoInfo.text('');
            });
            destinatario.show();
            selDestis.text("Aos Administradores");
        }
        else if ($(this).prop('selectedIndex') == 2) {
            $('.mostrar-select select option').remove();
            textoInfo.text('Carregando...');
            $.post("_views/select_tag.php", {nivel: 2}, function (data) {
                $('.mostrar-select select').append(data);
                textoInfo.text('');
            });
            destinatario.show();
            selDestis.text("Aos Professores");
        }
        else if ($(this).prop('selectedIndex') == 3) {
            $('.mostrar-select select option').remove();
            textoInfo.text('Carregando...');
            $.post("_views/select_tag.php", {nivel: 3}, function (data) {
                $('.mostrar-select select').append(data);
                textoInfo.text('');
            });
            destinatario.show();
            selDestis.text("Às Turmas");
        }
    });

    $('.mostrar-select')._neoPluginSelect();

    var options = {
        url: '_models/ins_suporte.php',
        beforeSend: function () {
        },
        uploadProgress: function (event, position, total, percentComplete) {
        },
        success: function (responseText) {
            if (responseText == '1') {
                alert('Suporte inserido com sucesso!');
                window.location.reload();
            }
            else {
                alert('Não foi possível inserir este suporte.\n Erro:' + responseText);
            }
        }
    };

    $("#formEnviarNovoComunicado").ajaxForm(options);

    //$('#myTab  [href="#profile"]').tab('show'); // Select first tab

    /**
     * Fecha o suporte
     */
    $('.fechar-suporte').click(function () {
        var suporteId = $(this).attr('data-suporte-id');
        var btn = $(this);
        if (confirm('Deseja realmente fechar este suporte?')) {
            $.post("_models/upd_suporte.php", {suporte_id: suporteId, status: 'fechar'}, function (data) {
                btn.text('Fechar');
                if (data == '1') {
                    btn.text('Abrir').addClass('btn-success');
                }
            });
        }
        else {

        }
    });
    /**
     * Abri o suporte
     */
    $('.abrir-suporte').click(function () {
        var suporteId = $(this).attr('data-suporte-id');
        var btn = $(this);
        if (confirm('Deseja realmente fechar este suporte?')) {
            $.post("_models/upd_suporte.php", {suporte_id: suporteId, status: 'abrir'}, function (data) {
                btn.text('Abrir');
                if (data == '1') {
                    btn.text('Fechar').removeClass('btn-success').addClass('btn-warning');
                }
            });
        }
        else {

        }
    });
    
    
    $('.editar_mensgem').click(function () {
        var dataSource = $(this).attr('data-source');
        var mensagem = $(this).attr('data-mensagem');
        var obj = JSON.parse(dataSource);
        $('#' + obj.suporte_tipo + '-alter').prop('checked', true);
        $('#mensagemEdicao').val(mensagem);
        $('#remetenteEdicao').val(obj.suporte_remetente);
        $('#suporteId').val(obj.suporte_id);
        $('#suporteOrdem').val(obj.suporte_ordem);
    });


    $('#mensagemEdicao').keydown(function (ev) {
        if (ev.ctrlKey && ev.keyCode == 83) {
            $(this).parent('form').submit();
            ev.preventDefault();
            return false;
        }
    });

    $(this).keydown(function (ev) {
        if (ev.ctrlKey && ev.keyCode == 83) {
            return false;
        }
    });

    $('#mensagem,#ordem').keydown(function (ev) {
        if (ev.ctrlKey && ev.keyCode == 83) {

            if ($('#ordem').val().trim() == '') {
                $('#ordem').select();
                $('#get_error_group').show();
                $('#get_error').show('').html('Escolha a ordem').delay(2000).hide('', function () {
                    $('#get_error_group').hide('');
                });
                ev.preventDefault();
                return false;
            }
            else if ($('#mensagem').val().trim() == '') {
                $('#mensagem').select();
                $('#get_error_group').show();
                $('#get_error').show('').html('Digite alguma mensagem.').delay(2000).hide('', function () {
                    $('#get_error_group').hide('');
                });
                return false;
            }
            else if ($('#selectDestinatario').val().trim() == '') {
                $('#selectDestinatario').select();
                $('#get_error_group').show();
                $('#get_error').show('').html('Selecione o destinatário').delay(2000).hide('', function () {
                    $('#get_error_group').hide('');
                });
                return false;
            }

            $('#formEnviarNovoComunicado').submit();
            ev.preventDefault();
            return false;

        }
    });

    var options = {
        url: '_models/alterar_mensagem.php',
        beforeSend: function () {
        },
        uploadProgress: function (event, position, total, percentComplete) {
        },
        success: function (responseText) {
            if (responseText == '1') {
                alert('Dados alterados com sucesso!');
            }
            else if (responseText == '0') {
                alert('Nenhum dado foi salvo.');
            }
            else {
                alert(responseText);
            }
        }
    };

    $("#formUpdateSuporte").ajaxForm(options);

    $('.desconectar').click(function () {
        if (confirm('Deseja realmente sair?')) {
            $.post('../_models/sair.php', function (data) {
                if (data === '1') {
                    window.location.reload();
                }
                else {
                    alert(data);
                }
            });
        }
    });

    $('#insertModal').click(function () {
        $('#mensagem').val('[title]\n{{ content}}');
    });
    $('#insertAlert').click(function () {
        var string = $('#mensagem').val();
        $('#mensagem').val(string.replace(/(\[|\]|\{\{|\}\})/gmi, ''));
    });
    $('#insertText').click(function () {
        var string = $('#mensagem').val();
        $('#mensagem').val(string.replace(/(\[|\]|\{\{|\}\})/gmi, ''));
    });

    $('#btnExcluirSuporte').click(function () {
        if (confirm('Deseja realmente excluir permanemente este comuniado?')) {
            $.post("../_models/excluir_comunicado.php", {suporte_id: $('#suporteId').val()}, function (data) {
                if (data > 0) {
                    alert('Comunicado excluído com sucesso!');
                    window.location.reload();
                }
                else {
                    alert(data);
                }
            });
        }
    }
    );
});