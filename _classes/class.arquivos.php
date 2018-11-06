<?php

/*


 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Arquivos {

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
     * @param type $file_name
     * @param type $file_md5
     * @param type $ext
     * @param type $data
     * @param type $file_type
     * @return type
     */
    public function inserir($file_name, $file_md5, $ext, $data, $file_type) {
        try {
            $sql = 'INSERT INTO `arquivos_sistema` (`arquivo_id`, '
                    . '`arquivo_nome`, `arquivo_md5`, `arquivo_ext`,'
                    . ' `arquivo_data`,`arquivo_type`) VALUES (NULL,?,?,?,?,?);';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $file_name, PDO::PARAM_INT);
            $sth->bindParam(2, $file_md5, PDO::PARAM_STR);
            $sth->bindParam(3, $ext, PDO::PARAM_STR);
            $sth->bindParam(4, $data, PDO::PARAM_STR);
            $sth->bindParam(5, $file_type, PDO::PARAM_STR);
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
    public function get_grupos() {
        try {
            $sql = 'SELECT * FROM `arquivos_sistema` AS t1 GROUP BY t1.arquivo_ext';
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $ext
     * @return type
     */
    public function get_files($ext) {
        try {
            $sql = 'SELECT * FROM `arquivos_sistema` AS t1 WHERE t1.arquivo_ext = ?';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $ext, PDO::PARAM_STR);
            $sth->execute();
            return $sth;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
