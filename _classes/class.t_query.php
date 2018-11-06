<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*


 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class T_Query {

    private $conexao = null;

    public function __construct($connection) {
        $connection->query("use " . DB_NAME);
        $this->conexao = $connection;
    }

    public function select($dbname,$tblname) {
        try {
            $sth = $this->conexao->prepare('SELECT * FROM `query` AS t1 WHERE t1.que_db_name = ? AND t1.que_table = ?;');
            $sth->bindParam(1, $dbname, PDO::PARAM_STR);
            $sth->bindParam(2, $tblname, PDO::PARAM_STR);
            $sth->execute();
            return $sth;
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
     * @param type $dbname
     * @param type $tablename
     * @param type $query
     * @param type $titulo
     * @return type
     */
    public function inserir($dbname, $tablename, $query, $titulo) {
        try {
            $sth = $this->conexao->prepare('INSERT INTO `query` (`que_id`, `que_db_name`, '
                    . '`que_table`, `que_queryname`,`que_titulo`) VALUES (NULL,?,?,?,?);');
            $sth->bindParam(1, $dbname, PDO::PARAM_STR);
            $sth->bindParam(2, $tablename, PDO::PARAM_STR);
            $sth->bindParam(3, $query, PDO::PARAM_STR);
            $sth->bindParam(4, $titulo, PDO::PARAM_STR);
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
