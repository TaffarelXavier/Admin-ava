<?php
if (!defined('_LOGADO_')) {
    exit('0');
}
?>
<div class="row-fluid">
    <h1>Área de Backup</h1>
</div>
<div class="row-fluid">
    <div class="span6">
        <form method="post" id="formExportDatabase">
            <legend>Exportar</legend>
            <input type="hidden" name="fazerBackup" value="true" />
            <input type="hidden" name="dbname" value="<?php echo $dbname; ?>" />
            <button type="submit" class="btn btn-success">Gerar Arquivo de SQL</button>
            <a href="./" class="btn  btn-danger">Cancelar</a>
            <div class="progress" style="height:6px;top:2px;position: relative;">
                <div class="bar" style="width: 0%;"></div>
            </div>
        </form>
        <div class="percent"></div>
        <div class="status"></div>
        <div class="carregarArquivosDeBackup" style="max-height: 300px;overflow: auto;"></div>    
        <div class="clearfix"></div>
    </div>
    <div class="span6">
        <form method="post" id="formImportDatabase" >
            <legend>Importar</legend>
            <input type="hidden" name="fazerBackup" value="true" />
            <input type="file" class="btn" name="filename" accept=".sql" />
        </form>
        <form method="post" id="formExecInDatabase">
            <input name='dbname' type='hidden' value="<?php echo $dbname; ?>" />
            <button class='btn btn-info pull-left'  type='submit'><i class="icon-ok-circle"></i> Importar</button>
            <div class="status2 pull-left"></div>
            <textarea name="sql" id="textareaSql" class="span12" rows="10" required=""></textarea> 
        </form>
    </div>
</div>
<hr style="border:0;border-top:1px solid #ccc" />
<script>
    $(document).ready(function() {
        
        var carregarArquivosBackup = function() {
            $.get('backup/views/arquivos_de_backups.php', function(data) {
                $('.carregarArquivosDeBackup').html(data);
            });
        };

        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('.status');
        var options = {
            url: 'backup/models/export.php',
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
            success: function(responseText) {
                var percentVal = '100%';
                bar.width(percentVal);
                status.html(responseText);
                carregarArquivosBackup();
                percent.html('<span>Backup realizado com sucesso!' + responseText + '</span>');
            }
        };

        $("#formExportDatabase").ajaxForm(options);

        var options = {
            url: 'backup/models/import.php',
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
            success: function(responseText) {
                $('#textareaSql').val(responseText);
            },
            complete: function() {

            }
        };

        $("#formImportDatabase").ajaxForm(options);

        $("input[type='file']").change(function() {
            if (this.value != '') {
                $('#formImportDatabase').submit();
            }
        });


        var status2 = $('.status2');
        
        var options = {
            url: 'backup/models/exec.php',
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
            success: function(responseText) {
                var percentVal = '100%';
                bar.width(percentVal);
                percent.html(percentVal);
                status2.html(responseText);
                $("#formExecInDatabase").find('button').attr('disabled', false).html('<i class="icon-ok-circle"></i>Importar');
            }
        };
        
        $("#formExecInDatabase").submit(function() {
            $(this).find('button').attr('disabled', true).html('<i class="icon-ok-circle"></i>Importando...');
        }).ajaxForm(options);

        //Inicialização
        carregarArquivosBackup();
    });
</script>
<?php
