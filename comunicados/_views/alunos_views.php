<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../autoload.php';

    $sup = new Suporte($connection);

    $suporteID = val_input::sani_number_int('suporteID');

    $f = $sup->get_alunos_visualizados($suporteID);
    ?>
    <table class="table" id="tableViewAlunos">
        <thead>
            <tr>
                <th>View ID:</th>
                <th>Aluno ID:</th>
                <th>Aluno Nome:</th>
                <th>Data da Visualização:</th>
            </tr> 
        </thead>
        <tbody>
            <?php
            while ($lin = $f->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><?php echo $lin['sup_view_id']; ?></td>
                    <td><?php echo $lin['alu_id']; ?></td>
                    <td><?php echo $lin['alu_nome']; ?></td>
                    <td><?php echo date('d/m/Y \à\s H:i:s', $lin['sup_view_data']); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <script>
        $(document).ready(function() {
            $('#tableViewAlunos').dataTable({
                "aLengthMenu": [
                    [10, 25, 50, -1],
                    [5, 15, 20, "All"]
                ],
                "iDisplayLength": 5,
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
        });
    </script>
    <?php
}

