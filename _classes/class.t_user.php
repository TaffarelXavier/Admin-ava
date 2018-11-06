<?php

/*


 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class T_User {

    private $conexao = null;

    public function __construct($connection) {
        $connection->query("use " . DB_NAME);
        $this->conexao = $connection;
    }

    public function get_privilegios($userid) {
        try {
            $sql = 'SELECT * FROM `user` AS t1 WHERE t1.user_id= ?';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $userid, PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetchObject();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function deletar() {
        
    }

    public function atualiazar() {
        
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

    /**
     * 
     * @param type $horario_id
     * @return type
     */
    public function delete_horario($horario_id) {
        try {
            $sql = '';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $horario_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
