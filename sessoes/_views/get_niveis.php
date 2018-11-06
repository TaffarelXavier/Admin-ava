<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../autoload.php';

    $ses = new Sessao_Usuarios($connection);

    $nivel = val_input::sani_string('nivel');

    echo '<h2>', ucfirst($nivel), '</h2>';

    $f1 = $ses->get_niveis($nivel);
    ?>
    <table class="table" id="table1">
        <thead>
            <tr>
                <th>Id:</th>
                <th>Nome:</th>
                <th>Data:</th>
                <th>Ip:</th>
                <th>Navegador:</th>
                <th>Usuário ID:</th>
                <th>Nível:</th>
                <th>Método:</th>
                <th>Plataforma:</th>
            </tr>
        </thead>
        <?php
        while ($r = $f1->fetch(PDO::FETCH_ASSOC)) {
            $d = 'Não Encontrado.';
            if ($r['sessao_nivel'] == 'administrativo') {
                $d = $ses->_sql('SELECT prof_nome FROM `professores` AS t1 WHERE t1.prof_cpf = ?', $r['sessao_usuario_id']);
            } else if ($r['sessao_nivel'] == 'aluno') {
                $d = $ses->_sql('SELECT alu_nome FROM `alunos` AS t1 WHERE t1.alu_id  = ?', $r['sessao_usuario_id']);
            } else {
                $d = $ses->_sql('SELECT prof_nome FROM `professores` AS t1 WHERE t1.prof_cpf = ?', $r['sessao_usuario_id']);
            }
            ?>
            <tr>
                <td><?php echo $r['sessao_id']; ?></td>
                <td><?php echo $d[0]; ?></td>
                <td><?php echo $r['sessao_data']; ?></td>
                <td><?php echo $r['sessao_ip']; ?></td>
                <td><?php echo $r['sessao_navegador']; ?></td>
                <td><?php echo $r['sessao_usuario_id']; ?></td>
                <td><?php echo $r['sessao_nivel']; ?></td>
                <td><?php echo $r['sessao_metodo']; ?></td>
                <td><?php echo $r['sessao_plataforma']; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <script>
        $(document).ready(function() {

            $('#table1').dataTable({
                "oLanguage": {
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Próximo",
                        "sFirst": "Primeira página"
                    },
                    "sZeroRecords": "Nenhum dado encontrado",
                    "sSearch": "Pesquisar",
                    "sEmptyTable": "Nenhum dado foi encontrado na tabela",
                    "sInfo": "Mostrando (_START_ até _END_) de _TOTAL_ registros."
                },
                "aaSorting": [
                    [0, 'desc']
                ]
            });

            /**
             *             "aoColumnDefs": [
             {
             'bSortable': true,
             'aTargets': [1]
             }
             ],**/
        });
    </script>
    <?php
}