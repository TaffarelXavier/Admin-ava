<?php
if (!defined('_LOGADO_')) {
    header('location: ../');
    exit();
}
try {
    $contar = $connection->prepare("SELECT count(*) FROM `$tablename`");

    $contar->execute();

    $total = $contar->fetch();
} catch (Exception $exc) {
    if ($exc->getCode() == '42S02') {
        header('location: ./');
    }
    echo $exc->getCode();
}
?>
<h2 style="color:#ccc" id="<?php echo $tablename; ?>"><span><?php echo $tablename; ?></span>[<?php echo ($total[0]); ?>] registros.</h2>
<div class="row-fluid">
    <div class="span12">

        <button class='btn btn-info pull-left' id="btnAtualizar" style="border-radius: 0;"
                value="UPDATE `<?php echo $tablename; ?>` SET colname = value option;">Update  </button>

        <button class='btn btn-danger pull-left' id="btnDelete" style="border-radius: 0;" 
                value="DELETE FROM `<?php echo $tablename; ?>` WHERE ;">Delete</button>

        <button class='btn btn-success pull-left' id="btnSelect" style="border-radius: 0;" 
                value="SELECT * FROM `<?php echo $tablename; ?>` WHERE 1;">Select</button>

        <?php
        $sth3 = $connection->prepare("describe $tablename");
        $sth3->execute();
        $sql = "(";
        $ii = 0;
        while ($lin = $sth3->fetch(PDO::FETCH_ASSOC)) {
            $sql .= "`" . $lin['Field'] . "`,";
        }
        $p_sql = substr($sql, 0, strlen($sql) - 1) . ')';
        ?>

        <button class='btn btn-info pull-left' id="btnInsert" style="border-radius: 0;" 
                value="INSERT INTO `<?php echo $tablename . "` " . $p_sql; ?> VALUES <?php echo str_ireplace('`', '\'', $p_sql); ?>;">Insert</button>

        <button class='btn btn-warning' type="button" style="border-radius: 0;float:left;" 
                value="TRUNCATE `<?php echo $tablename; ?>`;" id="btnTruncar">Truncar</button>

        <button class='btn btn-danger' value="DROP TABLE `<?php echo $tablename; ?>`;" id="btnDropTable"
                style="border-radius: 0;float:left;" >Drop</button>

        <button class='btn btn-info pull-left' id="btnAlterChange" style="border-radius: 0;"
                value="ALTER TABLE `<?php echo $tablename; ?>` CHANGE `nome_anterior` `novo_nome` INT(11) NOT NULL AUTO_INCREMENT;">Alter Change </button>

        <button class='btn btn-danger pull-left' id="btnAlterDrop" style="border-radius: 0;" 
                value="ALTER TABLE `<?php echo $tablename; ?>` DROP `coluna_a_ser_excluída`;">ALTER DROP</button>

        <button class='btn btn-success pull-left' id="btnAlterAdd" style="border-radius: 0;" 
                value="ALTER TABLE `<?php echo $tablename; ?>` ADD `nome_da_coluna` VARCHAR(124) NOT NULL ;">ALTER ADD</button>
        <div class="clearfix"></div><!--FIM-->
        <!--Salvar Nova Query--->
        <br/>
        <div class="row-fluid">
            <div class="span4">
                <?php
                $TT_Query = new T_Query($connection);
                $ftrf1 = $TT_Query->select($dbname, $tablename);
                if ($ftrf1->fetch() != false) {
                    ?>
                    <label style="text-align: center;background:rgba(0,0,0,0.2);"><b>Querys:</b></label>
                    <?php
                }
                ?>
                <ul style="max-height: 80px;overflow: auto;padding:0;margin:0;">
                    <?php
                    $ftrf2 = $TT_Query->select($dbname, $tablename);
                    while ($lin245 = $ftrf2->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <li style="padding:1px 3px;margin:0;"><a class="tquerys" data-query="<?php echo $lin245['que_queryname']; ?>"
                                                                 style="cursor:pointer;"
                                                                 ><?php echo $lin245['que_titulo']; ?></a></li>
                            <?php
                        }
                        ?>
                </ul>
            </div>
            <div class="span8">
                <form enctype="multipart/form-data" action="" id="formInsertQuery" method="post">
                    <textarea name="query" rows="3" class="span12" style="max-width: 100%;" id="queryEditorID"
                              required="" placeholder="Query"></textarea><br/>
                    <input type="text" name="titulo" style="float:left;" required="" id="queryTituloID" placeholder="Título da Query" />
                    <button class="btn green" style="float:left;">Salvar</button>
                    <button class="btn green" style="float:left;" type="button" id="atualizarQueryEditor"><i class="icon-refresh"></i></button>
                    <input type="hidden" readonly="" name="dbname" value="<?php echo $dbname; ?>" class="" />
                    <input type="hidden" readonly="" name="tablename" value="<?php echo $tablename; ?>" class="" />
                </form>
            </div>
        </div>

        <form method="post" id='formExeculteSql'>
            <label><b>Execultar SQL:</b></label>
            <input type="hidden" name="tablename" value='<?php echo $tablename; ?>' />
            <input type="hidden" name='dbname' value='<?php echo $dbname; ?>' />
            <textarea rows="5" name='sql' class='span12' style="max-width: 100%;"><?php echo 'SELECT * FROM `' . $tablename . '`;' ?></textarea>
            <div class="clearfix"></div>
            <button class='btn btn-info' type="submit">Executar</button>
        </form>   

    </div>
</div>
<div class="row-fluid">
    <div class="span12" style="max-width: 100%;overflow: auto;max-height: 400px;">
        <div class="span6 resultado"></div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12" style="max-width: 100%;overflow: auto;">
        <?php
        echo '<label><b>Describe table:</b></label>';
        echo '<table class="table table-hover table-striped"  style="text-align: left;font-size:14px;padding:0px;">';
        echo "<tr><th></th>"
        . "<th>Nome</th>"
        . "<th>Tipo</th>"
        . "<th class='hidden-phone'>Null</th>"
        . "<th>Primay Key</th>"
        . "<th class='hidden-phone'>Default</th>"
        . "<th class='hidden-phone'>Extra</th>"
        . "</tr>";
        $sth2 = $connection->prepare("describe $tablename");
        $sth2->execute();
        $x = 0;
        while ($lin = $sth2->fetch(PDO::FETCH_ASSOC)) {
            ++$x;
            $change = "ALTER TABLE `$tablename` CHANGE `" . $lin['Field'] . "` `NOVO_NOME_AQUI` varchar(11);";
            $drop = "ALTER TABLE `$tablename` DROP `" . $lin['Field'] . "`;";
            echo '<tr>'
            . '<td><a class="pull-left btn-mini btn btn-danger coluna_drop" data-source-drop="' . $drop . '" ><i class="icon-trash"></i></a>'
            . '<a class="pull-left btn-mini btn btn-info coluna_change"  data-source-change="' . $change . '" ><i class="icon-refresh"></i></a></td>'
            . '<td>' . $x . '-' . $lin['Field'] . '</td>'
            . '<td>' . $lin['Type'] . '</td>'
            . '<td class="hidden-phone">' . $lin['Null'] . '</td>'
            . '<td>' . $lin['Key'] . '</td>'
            . '<td class="hidden-phone">' . $lin['Default'] . '</td>'
            . '<td class="hidden-phone">' . $lin['Extra'] . '</td>'
            . '</tr>';
        }
        echo "</table><br/>";
        ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        var editor = $('textarea[name="sql"]');

        $('.coluna_drop').click(function () {
            var _drop = $(this).attr('data-source-drop');
            editor.val(_drop);
        });
        $('.coluna_change').click(function () {
            var _drop = $(this).attr('data-source-change');
            editor.val(_drop);
        });

        $("#formInsertQuery").ajaxForm({
            type: 'post',
            url: '_models/insert_query.php',
            beforeSend: function () {
            },
            success: function (data) {
                if (data == '1') {
                    $('#queryEditorID').val('Inserido com sucesso!');
                } else {
                    alert('Ops, houve um erro.' + data);
                }
            }
        });


        $('.tquerys').click(function () {
            editor.val($(this).attr('data-query'));
        });


        $('#atualizarQueryEditor').click(function () {
            $('#queryEditorID').val(editor.val());
            $('#queryTituloID').focus();
        });

        /**
         * Sintaxe update table
         */
        $('#btnAtualizar').click(function () {
            editor.val($(this).val());
        });

        /**
         * Sintaxe Delete table
         */
        $('#btnDelete').click(function () {
            editor.val($(this).val());
        });

        /**
         * Drop table
         */
        $('#btnDropTable').click(function () {
            editor.val($(this).val());
        });

        /**
         * Sintaxe Select stantament
         */
        $('#btnSelect').click(function () {
            editor.val($(this).val());
        });

        /**
         * Sintaxe truncate
         */
        $('#btnTruncar').click(function () {
            editor.val($(this).val());
        });

        /**
         * Sintaxe Insert Into
         */
        $('#btnInsert').click(function () {
            editor.val($(this).val());
        });

        $('#btnAlterChange').click(function () {
            editor.val($(this).val());
        });

        $('#btnAlterDrop').click(function () {
            editor.val($(this).val());
        });

        $('#btnAlterAdd').click(function () {
            editor.val($(this).val());
        });



        $('#formTruncarTabela').submit(function () {
            if (!confirm('Deseja realmente truncar esta tabela?')) {
                return false;
            }
        });
        $('#formTruncarTabela').ajaxForm({
            url: '_models/truncar_tabela.php',
            success: function (data) {
                if (data == '1') {
                    window.location.reload();
                }
            }
        });

        $('#formExeculteSql').submit(function () {
            //            if (!confirm('Deseja realmente truncar esta tabela?')) {
            //                return false;
//            }
        });


        $('#formExeculteSql').ajaxForm({
            beforeSend: function () {
                $('button[type="submit"]').text('Execultando, aguarde...');
            },
            target: ".resultado",
            url: '_models/execultar_sql.php',
            success: function (data) {
                $('.resultado_pesquisa').show();
                $('button[type="submit"]').text('Execultar');
            }
        });


        $('#formDropTabela').submit(function () {
            if (!confirm('Deseja realmente excluir esta tabela?')) {
                return false;
            }
        });

        $('#formDropTabela').ajaxForm({
            url: '_models/excluir_table.php',
            success: function (data) {
                if (data == '0') {
                    window.location.href = './';
                }
            }
        });

    });
</script>