<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sessao_Usuarios {

    private $conexao = null;

    public function __construct($connection) {
        $connection->query("use " . DB_NAME);
        $this->conexao = $connection;
    }

    /**
     * 
     * @return type
     */
    public function get_grupos_navegador() {
        try {
            $sql = 'SELECT sessao_navegador FROM `sessao_login` AS t1 GROUP BY t1.sessao_navegador';
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function get_grupos_nivel() {
        try {
            $sql = 'SELECT sessao_nivel FROM `sessao_login` AS t1 GROUP BY t1.sessao_nivel';
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }
    /**
     * 
     * @param type $navegador
     * @return type
     */
    public function get_navegador($navegador) {
        try {
            $sql = 'SELECT * FROM `sessao_login` as t1 WHERE t1.sessao_navegador = ? ORDER BY t1.sessao_id DESC ';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $navegador, PDO::PARAM_STR);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }
    /**
     * 
     * @param type $brawser
     * @return type
     */
    public function count_por_browser($brawser) {
        try {
            $sql = "SELECT COUNT(*) FROM `sessao_login` AS t1 WHERE t1.sessao_navegador = ?";
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $brawser, PDO::PARAM_INT);
            $sth->execute();
            $total = $sth->fetch();
            return $total[0];
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
            }
    public function get_niveis($nivel) {
        try {
            $sql = 'SELECT * FROM `sessao_login` as t1 WHERE t1.sessao_nivel = ? ORDER BY t1.sessao_id DESC ';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $nivel, PDO::PARAM_STR);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }
    /**
     * 
     * @param type $sql
     * @param type $id
     * @return type
     */
    public function _sql($sql, $id) {
        try {
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $id, PDO::PARAM_STR);
            $sth->execute();
            return $sth->fetch();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
