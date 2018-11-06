<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include '../../autoload.php';

    $array = array('aluno', 'professor', 'administrador');

    $haystack = val_input::sani_string('tipo');

    $usuario_id = trim(val_input::sani_string('usuario'));

    $datainicial = val_input::sani_string('data_inicial');

    $datafinal = val_input::sani_string('data_final');

    if (in_array($haystack, $array)) {

        switch ($haystack) {
            case 'aluno':

                $t = new T_PrintScreen($connection);
              
                $var = $t->select("%".$usuario_id."%", $datainicial, $datafinal);
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Data</th>
                            <th>Usuário</th>
                            <th>Ação</th>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($r = $var->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td><?php echo $r['printscr_id']; ?></td>
                                <td><?php echo $r['printscr_data']; ?></td>
                                <td><?php echo $r['printscr_usuario_id']; ?></td>
                                <td><?php echo $r['printscr_acao']; ?></td>
                                <td><?php echo $r['printscr_pagina']; ?></td>
                                <td><a href="image.php?id=<?php echo $r['printscr_id']; ?>" class="btn" target="_blank">Abrir Imagem</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                break;
            case 'professor':

                break;
            case 'administrador':

                break;
        }
    }
}
