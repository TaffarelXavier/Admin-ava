$(document).ready(function() {

    $('#formLogin').ajaxForm({
        url: '_models/login.php',
        beforeSend: function() {
            $('button[type="submit"]').attr('disabled', true).text("Entrando, aguarde um momento...");
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        success: function(data) {
            if (data === '1') {
                window.location.reload();
            }
            else if (data == 'Insira corretamente o captcha.') {
                alert(data);
                $('button[type="submit"]').attr('disabled', false).text("Entrar");
                change_captcha();
            }
            else {
                $('button[type="submit"]').attr('disabled', false).text("Entrar");
                alert('Não foi possível fazer o login.' + data);
                change_captcha();
            }
        }
    });

    function change_captcha() {
        document.getElementById('captcha').src = '_views/captcha.php?rnd=' + Math.random();
    }

    $('#atualizarCaptcha').click(function() {
        $(this).addClass('disabled');
        change_captcha();
        $(this).removeClass('disabled');
    });

    change_captcha();
});