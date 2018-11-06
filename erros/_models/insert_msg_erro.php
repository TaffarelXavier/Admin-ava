<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include '../../autoload.php';

    $sup = new Suporte($connection);

    $suporte_id = val_input::sani_number_int('suporte_id');

    $status = val_input::sani_string('status');

    $enviar_arquivo = val_input::sani_string('enviar_arquivo');

    $pagina_do_erro = val_input::sani_string('pagina_do_erro');

    $erro_mensagem = val_input::sani_string('mensagem');

    $file_name = $file_type = $file_md5 = '';

    if ($enviar_arquivo == 'on') {

        $Upload = new Upload();

        $Upload->cemvs_max_size_file = 5242880;

        $Upload->cemvs_min_size_file = 1;

        $Upload->tipos_permitidos = array(
            "application/pdf", "application/force-download",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "image/jpeg", "text/plain", "image/png");

        $Upload->iniciar("file", "../../arquivos/");
        $file_md5 = $Upload->file_in_md5;
        $file_type = $Upload->get_type;
        $file_name = $Upload->real_file_name;
    } else {
        $file_type = $file_type = $file_md5 = '';
    }

    $Erros = new Erros($connection);

    if ($Erros->inserir($pagina_do_erro, $erro_mensagem, time(), $file_type, $file_name, $file_md5) > 0) {

        $emailenviar = "taffarel_deus@hotmail.com";
        $destino = "taffarel_deus@hotmail.com";
        $assunto = "Contato pelo Site";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Taffarel Xavier <taffarel_deus@hotmail.com>';

        $enviaremail = mail($destino, $assunto, $erro_mensagem, $headers);

        echo '1';
        
    } else {
        echo '0';
    }
}

