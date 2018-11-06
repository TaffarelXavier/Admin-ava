<div class="row-fluid">
    <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab">Início</a></li>
        <li><a href="#profile" data-toggle="tab">Arquivos Enviados</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="home">
            <div class="row-fluid">
                <!--UPLOAD DE ARQUIVO-->
                <form enctype="multipart/form-data" action="_models/upload.php" id="formUpload" method="post">
                    <label class="control-label" for="inputPassword">Arquivo:</label>
                    <div class="uploadFiles"></div>
                    <div class="progress" style="text-align: center;">
                        <div class="bar"></div>
                        <div class="percent" style="position: absolute;margin:0px auto;left:1%;right: 1%;">0%</div>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" style="padding: 10px 5px;">
                        <i class="icon-upload"></i> Enviar</button>

                    <div id="status"></div>
                </form>
            </div>
        </div>
        <!--INÍCIO INCLUIR NOVO COMUNICADO-->
        <div class="tab-pane fade" id="profile">
            <?php
            include '_views/arquivos.php';
            ?>
        </div>
        <!--FIM INCLUIR NOVO COMUNICADO-->
    </div>
</div>
<script>
    $(document).ready(function() {

        $('.uploadFiles')._neoPluginUpload({
            accept: "",
            tiposPermitidos: ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf', 'video/mp4',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/force-download', 'audio/x-ms-wma', 'audio/ogg','video/ogg'],
            tamanhoPermitido: 1073741824 //2MB
        });

        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');

        $('#formUpload').ajaxForm({
            beforeSend: function() {
                status.empty();
                var percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            success: function() {
                var percentVal = '100%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            complete: function(xhr) {
                status.html(xhr.responseText);
                percent.html('Concluído!');
                bar.width(0);
            }
        });


    });
</script>