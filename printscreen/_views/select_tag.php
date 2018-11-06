<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    include '../../autoload.php';

    $Suporte = new Suporte($connection);
    
    if (val_input::val_int('nivel') === false)
    {
        exit('0');
    }

    if (val_input::val_int('nivel') != false && val_input::val_int('nivel') == 1)
    {
        $fetc1 = $Suporte->select('SELECT prof_id,prof_nome FROM `professores` AS t1 JOIN niveis_adminitrativos'
                . ' AS t2 ON t1.prof_id = t2.niv_adm_professor_id GROUP BY t1.prof_id');

        while ($linhas = $fetc1->fetch(PDO::FETCH_ASSOC))
        {
            echo '<option value="', $linhas['prof_id'], '">', $linhas['prof_nome'], ' </option>';
        }
    }
    
    if (val_input::val_int('nivel') != false && val_input::val_int('nivel') == 2)
    {
        $fetc1 = $Suporte->select('SELECT prof_id,prof_nome FROM `professores` AS t1 GROUP BY t1.prof_id');

        while ($linhas = $fetc1->fetch(PDO::FETCH_ASSOC))
        {
            echo '<option value="', $linhas['prof_id'], '">', $linhas['prof_nome'], ' </option>';
        }
    }
    
    if (val_input::val_int('nivel') != false && val_input::val_int('nivel') == 3)
    {
        $fetc1 = $Suporte->select('SELECT alu_turma FROM `alunos` AS t1 JOIN turmas AS t2 ON t1.alu_turma = t2.turma_nome GROUP BY t1.alu_turma');

        while ($linhas = $fetc1->fetch(PDO::FETCH_ASSOC))
        {
            echo '<option value="', $linhas['alu_turma'], '">', $linhas['alu_turma'], ' </option>';
        }
    }
}

                       