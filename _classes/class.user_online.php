<?php

/*


 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User_Online {

    private $conexao = null;

    public function __construct($connection) {
        $connection->query('use ' . DB_NAME . ';');
        $this->conexao = $connection;
    }

    public function get_users_online() {
        try {
            //120=dois minutos
            $time = time() - 60;
            $sql = 'SELECT chat_user_online_id,alu_id,alu_nome,chat_data,chat_device FROM `chat_user_online` AS '
                    . 't1 JOIN alunos AS t2 ON t1.chat_user_id = t2.alu_id WHERE t1.chat_user_nivel ="aluno"'
                    . ' AND t1.chat_data > ' . $time . ' ORDER BY t1.chat_data DESC, t2.alu_nome ASC';
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function get_foto_perfil($aluno_id) {
        try {
            $sql = "SELECT alu_foto_imagem FROM `alunos_foto` AS t1 WHERE t1.alu_aluno_id = ? AND t1.alu_ativa= 'ativa' ORDER BY t1.alu_foto_id DESC";
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $aluno_id, PDO::PARAM_INT);
            $sth->execute();
            $fto = $sth->fetch(PDO::FETCH_ASSOC);
            return $fto['alu_foto_imagem'];
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
