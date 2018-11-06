/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {

    var array = [];

    $('.emoji').click(function() {

        var _thisClass = $(this).attr('class');

        var _dataC = $(this).attr('data-c');

        var ar = _thisClass.split(/\s+/gmi);

        $(".text-focus").remove();

        var emoji = '<img class="emoji ' + ar[1] + '" alt="' + ar[1] + '" data-c="' + _dataC + '"  />';

        array['emoji'] = emoji;

        $('#editor').append(emoji).select();
        $('#mensagem').val($('#editor').html());
    });

    $('#editor').keyup(function() {
        $('#mensagem').val($(this).html());
    });

    $(this).keyup(function(ev) {
        if (ev.keyCode == 13) {
            $('#editor').append(array['emoji']).select();
            $('#mensagem').val($('#editor').html());
        }

    }).keydown(function(ev) {
        if (ev.keyCode == 13) {
            $('#editor').append(array['emoji']).select();
            $('#mensagem').val($('#editor').html());
        }
    });

    $('#editor').keyup(function(ev) {
        var y = $(this).height();
        if (ev.keyCode == 13) {
            if ($(this).height() < 82) {
                $(this).height((y + 10) + "px");
            }
        }
    });


    if (this.addEventListener) {
        // IE9, Chrome, Safari, Opera
        this.addEventListener("mousewheel", MouseWheelHandler, false);
        // Firefox
        this.addEventListener("DOMMouseScroll", MouseWheelHandler, false);
    } else {
        this.attachEvent("onmousewheel", MouseWheelHandler);
    }

    var d = 0;

    function MouseWheelHandler(e) {

        var e = window.event || e; // old IE support

        var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));

        if (delta == '1') {
            ++d;
            //$('#myTab li:eq(' + d + ') a').tab('show');
        }
        else {
            --d;
            //$('#myTab li:eq(' + d + ') a').tab('show');
        }

        return false;
    }


});