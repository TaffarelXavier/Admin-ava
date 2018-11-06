$(document).ready(function () {

    if (typeof (EventSource) !== "undefined") {
        var source = new EventSource("_views/visitaas.sse.php");

        source.addEventListener("visitas", function (e) {
            $('.resultado_visitas').html(e.data);
        }, false);

        source.addEventListener("total", function (e) {
            $('.total').text("Total de visitas " + e.data + " Visitas");
        }, false);

    } else {
        document.getElementById("result").innerHTML = "Desculpa, mas seu navegador não suporta o objeto DOM EventSource.";
    }

    if (typeof (EventSource) !== "undefined") {

        var evtSource = new EventSource("_views/tabelas.php");
        evtSource.addEventListener("show_tables", function (e) {
        }, false);

    } else {
        alert("Seu navegador não suporta Sever Source Events");
    }


    $('#formExeculteQuery').ajaxForm({
        url: '_models/execultar_query.php',
        success: function (data) {
            $('.resultadoQuery').html(data);
        },
        complete: function (xhr) {
            data = xhr.responseText;
        }
    });

    $('textarea[name="query"]').keyup(function (ev) {
        if ($(this).val().trim() != '') {
            $('#btnExeultarQuery').removeAttr('disabled');
        }
        else {
            $('#btnExeultarQuery').attr('disabled', true);
        }

    }).keypress(function (ev) {
        if (ev.shiftKey) {

        } else {
            if (ev.keyCode == 13) {
                ev.preventDefault();
                $(this).parent('form').submit();
                return false;
            }
        }

    });

});