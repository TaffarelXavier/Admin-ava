<!doctype HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Meu IP</title>
        <script src="../js/jquery-1.10.1.min.js"></script>
        <script src="../js/jquery.form.js"></script>
        <style>
            body{
                text-align: center;
                margin-top:50px;
            }
            .main{
                width: 400px;
                border:1px solid #ccc;
                margin:0px auto;
                padding:10px;
                box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.3);
            }
            h1{
                text-align: center;
            }
            label{
                font:18px "century gothic";  
            }
            button{
                width: 80%;
                padding:10px;
                border:1px solid #ccc;
                background:#CBDCCE;
                font:25px "century gothic";
            }
            #resposta{
                text-align: center;
            }
        </style>
        <script>
            var Mensagem = 13;
            var texto = new Array(Mensagem + 1);

            texto[0] = "2x5";
            texto[1] = "2(5+2)";
            texto[2] = "2/2*5";
            texto[3] = "8x8";
            texto[4] = "2+5+3+(2*5)";
            texto[5] = "10-5";
            texto[6] = "10-10";
            texto[7] = "2*9";
            texto[8] = "4x8";
            texto[9] = "9x5";
            texto[10] = "8*3";
            texto[11] = "2*6";
            texto[12] = "3x5";
            texto[13] = "2x4";

            resposta = [10, 14, 5, 64, 20, 5, 0, 18, 32, 45, 24, 12, 15, 8];

            function rndnumber()
            {
                var randscript = -1
                while (randscript < 0 || randscript > Mensagem || isNaN(randscript)) {
                    randscript = parseInt(Math.random() * (Mensagem + 1));
                }
                return randscript;
            }

            quo = rndnumber();

        </script>

    </head>
    <body>
        <?php
        $ip = $_SERVER['REMOTE_ADDR'];
        ?>
        <div class="main">
            <div class="resultado"></div>
            <form enctype="multipart/form-data" action="models/insert.php" method="post">
                <input type="text" id="resposta" autofocus="" /><br/>
                <input name="ip" type="hidden" value="<?php echo $ip ?>" />
                <label class="bold">Para ter acesso, clique em salvar.</label><br/><br/>
                <button class="btn green" id="btnObterAcesso"><i class="icon-save"></i>Acessar</button>
            </form>
            <script>

                quox = "<h3>O resultado de:" + texto[quo] + "</h3>";

                $('.resultado').html(quox);

                $(document).ready(function() {
                    var btn = $('#btnObterAcesso');

                    $("form").submit(function() {
                        if ($('#resposta').val().trim() == '') {
                            $('#resposta').select();
                            alert('Digite uma resposta...');
                            return false;
                        }
                        if (resposta[quo] == $('#resposta').val()) {

                        }
                        else {
                            alert('Resposta incorreta.');
                            window.location.reload();
                            return false;
                        }

                    });

                    $("form").ajaxForm({
                        type: 'post',
                        url: 'models/insert.php',
                        beforeSend: function() {
                            btn.attr('disabled', true).html('Obtendo, aguarde...');
                        },
                        success: function(data) {
                            if (data == '1') {
                                btn.attr('disabled', true).html('Sucesso, aguarde um momento!');
                                window.open(window.location.origin,'_top');
                            }
                            else {
                                alert('Ops, houve um erro.' + data);
                            }
                        }
                    });
                });
            </script>
        </div>
    </body>
</html>
