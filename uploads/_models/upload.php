<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include '../../autoload.php';

    $Upload = new Upload();

    $Arquivos = new Arquivos($connection);

    /**
     * Definindo valores padrões
     */
    $Upload->cemvs_max_size_file = 1073741824;

    $Upload->cemvs_min_size_file = 1;

    $Upload->tipos_permitidos = array(
        "application/pdf", "application/force-download",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "image/jpeg", "text/plain", "image/png",'video/mp4','audio/x-ms-wma','audio/ogg',
        'video/ogg');

    $upload = $Upload->iniciar('file', '../../arquivos/');
    
    if ($upload == true) {
        echo '<span class="text-success">Operação Realizada com sucesso!</span>';
        $Arquivos->inserir($Upload->real_file_name, $Upload->file_in_md5, $Upload->ext, time(), $Upload->get_type);
        ?>
        <pre>
            <?php
            print_r($Upload);
            ?>
        </pre>
        <?php
    } else {
        ?>
        <pre>
            <?php
            print_r($upload);
            ?>
        </pre>
        <?php
    }
}

