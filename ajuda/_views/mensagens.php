<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include '../../autoload.php';

    $Ajuda = new Ajuda($connection);

    $ajuda_id = val_input::sani_string('ajuda_id');

    $d = $Ajuda->get_mensagens_por_id($ajuda_id);
    ?>

    <table class="table">
        <thead>
            <tr>
                <th style="text-align: center !important;">Id:</th>
                <th style="text-align: center !important;">Ajuda ID:</th>
                <th style="text-align: center !important;">Mensagem:</th>
                <th style="text-align: center !important;">Remetente:</th>
                <th style="text-align: center !important;">Data do Envio:</th>
                <th style="text-align: center !important;">ID do Usuário:</th>
                <th style="text-align: center !important;">Status:</th>
            </tr>
        </thead>
        <?php
        while ($r = $d->fetch(PDO::FETCH_ASSOC)) {

            $arr_tipos = array('white', 'normal');

            if ($r['aju_visto'] == 'nao') {
                $arr_tipos = array('#ccc', 'bold');
            }
            ?>
            <tbody>
                <tr class="mensagem-users" data-html='<?php echo stripcslashes($r['aju_mensagem']); ?>'
                    usuario-id="<?php echo $r['aju_usuario']; ?>" data-visto="<?php echo $r['aju_visto']; ?>" 
                    data-ajuda-id="<?php echo $ajuda_id; ?>" id="<?php echo $r['aju_id']; ?>" 
                    style="background:<?php echo $arr_tipos[0]; ?>;padding:5px;padding-left:10px;margin-bottom: 2px;
                    cursor:pointer;font-weight: <?php echo $arr_tipos[1]; ?>;" >
                    <td><?php echo $r['aju_id']; ?></td>
                    <td><?php echo $r['aju_ajuda_id']; ?></td>
                    <td><span id="texto-<?php echo $r['aju_id']; ?>"><?php echo $r['aju_mensagem']; ?></span></td>
                    <td><?php echo $r['aju_remetente']; ?></td>
                    <td><?php echo date('d/m/Y \à\s H:i', $r['aju_data_resposta']); ?></td>
                    <td><?php echo $r['aju_usuario']; ?></td>
                    <td><?php echo $r['aju_status']; ?></td>
                </tr>
            </tbody>
            <?php
        }
        ?>
    </table>
    <hr style="border:0;border-top:1px solid #ccc; " />
    <br/>
    <script>

        $('#atualizarLista').click(function() {
            var btn = $(this);

            btn.attr('disabled', true).html('Atualizando...');

            $.post('_views/mensagens.php', {
                ajuda_id: dadosParaAtualizar.ajuda_id
            }, function(data) {
                $('.get_mensagens').html(data);
                btn.attr('disabled', false).html('Atualizar');
            });
        });

        $('.mensagem-users').click(function() {

            var aju_id = $(this).attr('id');

            var ajudaID = $(this).attr('data-ajuda-id');

            var visto = $(this).attr('data-visto');

            var usuarioID = $(this).attr('usuario-id');

            var getTexto = $(this).attr('data-html');

            if (usuarioID == 0) {

            }

            $('#form_editar_aju_id').val(aju_id);

            $('#form_editar_texto').val(getTexto);

            if (visto == 'nao') {
                $.post('_models/marcar_como_lida.php', {
                    ajuda_id: ajudaID,
                    ajud_id: aju_id,
                    lido: 'sim'
                }, function(data) {
                    if (data == '1') {
                        $('#' + aju_id).css({background: 'white', fontWeight: 'normal'});
                    }
                    else if (data == '0') {

                    }
                    else {
                        alert('Ops, houve um erro. Código: ' + data);
                    }
                });
            }
            else {
                $('#modalEditar').modal('show');
            }
        });
        $('#mensagem').focus();
    </script>
    <?php
}

