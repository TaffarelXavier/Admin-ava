<?php

/*

 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TX_Desconectar_Alunos{

    private $conexao = null;

    public function __construct($connection) {
            $connection->query('use ' . DB_NAME . ';');
        $this->conexao = $connection;
    }

    public function select() {
        try {
            $sql = '';
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

   /**
    * 
    * @param type $user_id
    * @param type $mensagem
    * @param type $data
    * @return type
    */
    public function tx_inserir($user_id, $mensagem,$data) {
        try {
            $sql = 'INSERT INTO `tx_alunos_desconectar` (`tx_desc_id`, `tx_desc_aluno_id`, `tx_desc_mensagem`, `tx_desc_aluno_visto`, `tx_desc_data_do_envio`) VALUES (NULL,?,?,"nao",?);';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $user_id, PDO::PARAM_INT);
            $sth->bindParam(2, $mensagem, PDO::PARAM_STR);
            $sth->bindParam(3, $data, PDO::PARAM_STR);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
