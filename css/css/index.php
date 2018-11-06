<?php
session_start();
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../bootstrap.min.css"/>
        <link rel="stylesheet" href="../bootstrap-responsive.min.css"/>
        <link rel="stylesheet" href="../style.man.css"/>
        <script src="../../js/jquery-1.10.1.min.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../js/jquery.form.js"></script>
    </head>
    <title></title>
    <body>
        <div class="container">
            <div class="row-fluid">
                <a href="#myModal" role="button" class="btn" data-toggle="modal">Executar modal de demo</a>
            </div>
        </div>
        <div id="myModal" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3></h3>
            </div>
            <div class="modal-body text-center">
                <form action="models/run.php" id="formDesativarSite" method="POST">
                    <input type="radio" value="manutencao" name='tipo' />
                    <input type="radio" value="desativar" name='tipo' />
                    <input type="password" name="password" />
                </form>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Fechar</a>
                <button class="btn btn-danger" form="formDesativarSite" > <span class="icon-trash"></span>Salvar</button>
            </div>
        </div>
        <script>

            //Valida uma string como JSON
            function IsJsonString(str) {
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return true;
            }

            $(document).ready(function () {
                $('#formDesativarSite').ajaxForm({
                    success: function (string) {

                        if (IsJsonString(string)) {

                            var obj = JSON.parse(string);

                            if (obj.tipo == 'criar') {
                                if (obj.valor == '1') {
                                    alert('Arquivo criado com sucesso!');
                                }
                                else {
                                    alert('Não foi possível criar o arquivo.');
                                }
                                return false;
                            }

                            if (obj.tipo == 'excluir') {
                                if (obj.valor == '1') {
                                    alert('Excluído com sucesso!');
                                }
                                if (obj.valor == '0') {
                                    alert('Não foi possível excluir o arquivo.');
                                }
                                return false;
                            }
                        } else {
                            alert(string);
                        }

                    }
                });
            });
        </script>
    </body>
</html>
