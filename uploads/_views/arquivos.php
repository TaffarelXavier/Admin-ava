<div style="height: auto;" class="accordion in collapse" id="accordion1">


    <?php
    $Arq = new Arquivos($connection);

    $f1Te = $Arq->get_grupos();

    $i = 0;

    while ($linhas = $f1Te->fetch(PDO::FETCH_ASSOC)) {

        ++$i;
        $in = '  ';
        if ($i == 1) {
            $in = ' in ';
        }
        ?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" href="#col_<?php echo $i; ?>">
                    <i class="icon-upload"></i>
                    <b>Arquivos - <?php echo strtoupper($linhas['arquivo_ext']); ?></b>
                </a>
            </div>
            <div id="col_<?php echo $i; ?>" class="accordion-body collapse <?php echo $in; ?>">
                <div class="accordion-inner">
                    <?php
                    $f1578 = $Arq->get_files($linhas['arquivo_ext']);
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id:</th>
                                <th>Nome do Arquivo:</th>
                                <th>Md5:</th>
                                <th>Data</th>
                                <th>Type:</th>
                            </tr> 
                        </thead>
                        <tbody>

                            <?php
                            while ($lin = $f1578->fetch(PDO::FETCH_ASSOC)) {
                                $url = '//'.$_SERVER['SERVER_NAME'] . '/files.uploaded/'.$lin['arquivo_md5'];
                                ?>
                                <tr>
                                    <td><a href="<?php echo $url; ?>" target="_blank"><?php echo $lin['arquivo_id']; ?></a></td>
                                    <td><a href="<?php echo $url; ?>" target="_blank"><?php echo $lin['arquivo_nome']; ?></a></td>
                                    <td><a href="<?php echo $url; ?>" target="_blank"><?php echo $lin['arquivo_md5']; ?></a></td>
                                    <td><a href="<?php echo $url; ?>" target="_blank"><?php echo $lin['arquivo_data']; ?></a></td>
                                    <td><a href="<?php echo $url; ?>" target="_blank"><?php echo $lin['arquivo_type']; ?></a></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>