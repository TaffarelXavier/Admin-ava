<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../autoload.php';

    $Alunos = new Alunos($connection);

    $f = $Alunos->get_todos();
    ?>
    <input type="checkbox" id="selecionarTodosAlunos" />
    <label for="selecionarTodosAlunos" class="bold" id="labelSelecionAll">Selecionar tudo.</label>
    <form enctype="multipart/form-data" id="formDesconectarAluno" method="post">
        <label for="" class="bold"><b>Mensagem:</b></label>
        <textarea name="mensagem" class="span12" required=""></textarea>
        <label for="" class="bold"><b>Usuários:</b></label>
        <textarea id="getAlunos" name="alunosIds" class="span12" rows="5" readonly="" required=""></textarea>
        <button class="btn btn-success">Salvar</button>
    </form>
    <table class="table" id="tableDescAluno" style="text-align: left;">
        <thead>
            <tr>
                <th>
                </th>
                <th>Id:</th>
                <th>Nome:</th>
                <th>Turma:</th>
            </tr> 
        </thead>
        <tbody>
            <?php
            $i = 0;
            $arrayId = array();
            while ($reg = $f->fetch(PDO::FETCH_ASSOC)) {
                ++$i;
                $t = $Alunos->get_turma_por_id_aluno($reg['alu_turma']);
                $arrayId[] = $reg['alu_id'];
                ?>
                <tr>
                    <td><input type="checkbox" class="inputDesconectar" id="<?php echo $reg['alu_id']; ?>" />
                        <label for="<?php echo $reg['alu_id']; ?>" id="label-des-<?php echo $reg['alu_id']; ?>"
                               class="bold desconectarAluno text-error">Off</label>
                    </td>
                    <td style="text-align: left;"><?php echo $reg['alu_id']; ?></td>
                    <td style="text-align: left;"id="get-aluno-<?php echo $reg['alu_id']; ?>"><?php echo $reg['alu_nome']; ?></td>
                    <td style="text-align: left;"><?php echo $t['nome']; ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <script>
        var arrAlunos = [];

        function removeValueFromArray(array, value) {

            index = array.indexOf(value);

            if (index != -1) {
                array.splice(index, 1);
            }
            return array;
        }


        var todosIds = '<?php echo json_encode($arrayId); ?>';

        var obj = JSON.parse(todosIds);

        $('#selecionarTodosAlunos').click(function() {
            if (this.checked) {
                $('#labelSelecionAll').text('Remover seleção.');
                $('#getAlunos').val(obj.join(","));
            }
            else {
                $('#labelSelecionAll').text('Selecionar tudo.');
                $('#getAlunos').val('');
            }
        });
        $('.inputDesconectar').change(function() {

            var l = $('#label-des-' + this.id);

            if (l.text() == 'Off') {

                arrAlunos.push(this.id);

                l.text('On').removeClass('text-error').addClass('text-success');

                $('#getAlunos').val(arrAlunos.join(','));
            }
            else {
                l.text('Off').removeClass('text-success').addClass('text-error');

                $('#getAlunos').val(removeValueFromArray(arrAlunos, this.id).join(','));
            }
        });


    //
    //
    //

        $("#formDesconectarAluno").ajaxForm({
            type: 'post',
            url: '_models/desconectar_aluno.php',
            beforeSend: function() {
            },
            success: function(data) {
                if (data == '1') {
                    alert('Operação realizada com sucesso!');
                }
                else if (data == '0') {
                    alert('Nenhum dado foi inserirdo.');
                }
                else {
                    alert('Ops ouve um erro: ' + data);
                }
            }
        });

        $('#tableDescAluno').dataTable({
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
                [1, 'asc']
            ]
        });
    </script>
    <?php
}