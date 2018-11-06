<?php
include '../../autoload.php';
$array = array();
if ($handle = opendir(SYS_DOC_ROOT . 'admin/backup')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..' && $file != 'models' && $file != 'views') {
            $array[] = $file;
            arsort($array);
        }
    }
    closedir($handle);
}
?>  
<table class='table'>
    <thead>
        <tr>
            <th>Tamanho em bytes e Nome</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php

        function formatBytes($size, $precision = 2) {
            $base = log($size, 1024);
            $suffixes = array('', 'KB', 'MB', 'GB', 'TB');
            return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
        }

        foreach ($array as $key => $value) {
            $file = _DOMINIO_ . 'backup/' . $value;
            ?>
            <tr>
                <td><?php echo formatBytes(filesize(SYS_DOC_ROOT . 'admin/backup/' . $value), 3) . ' <b>' . $value, '</b>'; ?></td>
                <td>
                    <div class="btn-group pull-right">
                        <a href="#" class="pull-left btn-mini btn btn-danger excluir_arquivo"
                           data-arquivo='<?php echo $value; ?>'>
                            <i class="icon-trash"></i></a>
                        <a href="<?php echo $file; ?>" download='<?php echo $value; ?>'
                           class="pull-left btn-mini btn btn-info" data-toggle="modal"  >
                            <i class="icon-download-alt"></i></a>
                        <a href="#" data-file="<?php echo $value; ?>" class="pull-left btn-mini btn btn-success up-refresh" data-toggle="modal"  >
                            <i class="icon-refresh"></i></a>
                    </div>
                </td> 
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('.excluir_arquivo').click(function() {
            if (confirm('Deseja realmente excluir este arquivo de backup?')) {
                var dataArquivo = $(this).attr('data-arquivo');
                $.post('backup/models/excluir_arquivo_backup.php', {
                    arquivo: dataArquivo
                }, function(data) {
                    if (data == '1') {
                        alert('Arquivo excluído com sucesso!');
                        $.get('backup/views/arquivos_de_backups.php', function(data) {
                            $('.carregarArquivosDeBackup').html(data);
                        });
                    }
                    else {
                        alert('Arquivo excluído com sucesso!');
                    }
                });
            }
        });
        $('.up-refresh').click(function() {
             $('#textareaSql').val('Importando...');
            var fileName = $(this).attr('data-file');
             $.post('backup/models/up-import.php',{
                 filename:fileName
             },function(data){
               $('#textareaSql').val(data);
             });
            
        });
    });
</script>