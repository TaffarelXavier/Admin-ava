<?php

class T_PrintScreen {

    private $conexao = null;

    public function __construct($connection) {
        $connection->query("use " . DB_NAME);
        $this->conexao = $connection;
    }

    /**
     * 
     * @param type $usuario_id
     * @param type $datainicial
     * @param type $datafinal
     * @param type $inicio
     * @param type $final
     * @return type
     */
    public function select($usuario_id, $datainicial, $datafinal, $inicio, $final) {
        try {
            $sql = 'SELECT printscr_id, printscr_data,printscr_usuario_id,printscr_acao,printscr_pagina FROM `printscreen_tbl` AS t1 WHERE t1.printscr_usuario_id LIKE ? AND t1.printscr_data BETWEEN ? AND ? ORDER BY printscr_id DESC LIMIT ?,?';
            $this->conexao->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $sth = $this->conexao->prepare($sql);
            $sth->bindValue(1, $usuario_id, PDO::PARAM_STR);
            $sth->bindParam(2, $datainicial, PDO::PARAM_STR);
            $sth->bindParam(3, $datafinal, PDO::PARAM_STR);
            $sth->bindParam(4, $inicio, PDO::PARAM_INT);
            $sth->bindParam(5, $final, PDO::PARAM_INT);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function select_imagem($id) {
        try {
            $sql = 'SELECT  
printscr_id,
printscr_data,
printscr_imagem,
printscr_usuario_id	
FROM `printscreen_tbl` as t1 WHERE t1.printscr_id = ?';
            $sth = $this->conexao->prepare($sql);
            $sth->bindValue(1, $id, PDO::PARAM_STR);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @return type
     */
    public function contar() {
        try {
            $sql = 'SELECT COUNT(*) FROM `printscreen_tbl`';
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            $total = $sth->fetch();
            return $total[0];
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $imagem_url
     * @param type $usuario_id
     * @param type $acao
     * @param type $pagina
     * @return type
     */
    public function insert($imagem_url, $usuario_id, $acao, $pagina) {
        try {
            $sql = 'INSERT INTO `printscreen_tbl` (`printscr_id`, `printscr_data`, `printscr_imagem`, `printscr_usuario_id`, `printscr_acao`, `printscr_pagina`) VALUES (NULL,"' . date('d/m/Y \à\s H:i:s', time()) . '", ?, ?,?,?);';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $imagem_url, PDO::PARAM_STR);
            $sth->bindParam(2, $usuario_id, PDO::PARAM_INT);
            $sth->bindParam(3, $acao, PDO::PARAM_STR);
            $sth->bindParam(4, $pagina, PDO::PARAM_STR);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
