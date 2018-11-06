<?php

/*


 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Alunos {

    private $conexao = null;

    public function __construct($connection) {
        $connection->query('use ' . DB_NAME . ';');
        $this->conexao = $connection;
    }

    public function get_todos() {
        try {
            $sth = $this->conexao->prepare('SELECT * FROM `alunos`');
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * <p></p>
     * @param type $turma_id
     * @return array A turma e seu ID com index: 'nome' e 'id'.
     */
    public function get_turma_por_id_aluno($turma_id) {
        try {
            $sql = "SELECT turma_id,turma_nome FROM `turmas` AS t1 WHERE t1.turma_id = ?";
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $turma_id, PDO::PARAM_INT);
            $sth->execute();
            $dados = $sth->fetch(PDO::FETCH_ASSOC);
            return array('nome' => $dados['turma_nome'], 'id' => $dados['turma_id']);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $user_id
     * @param type $discplina_id
     * @param type $file_md5
     * @param type $real_file_name
     * @return type
     */
    public function inserir($user_id, $discplina_id, $file_md5, $real_file_name) {
        try {
            $sql = '';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $user_id, PDO::PARAM_INT);
            $sth->bindParam(2, $discplina_id, PDO::PARAM_STR);
            $sth->bindParam(3, $file_md5, PDO::PARAM_STR);
            $sth->bindParam(4, $real_file_name, PDO::PARAM_STR);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
