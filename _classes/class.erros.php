<?php

class Erros
{

    private $conexao = null;

    public function __construct($connection)
    {
        //$connection->query('use ' . DB_NAME . ';');
        $this->conexao = $connection;
    }

    public function select()
    {
        
    }

    public function deletar()
    {
        
    }
    
    /**
     * 
     * @param type $status_nome
     * @param type $erro_id
     * @return type
     */
    public function atualiazar($status_nome, $erro_id)
    {
        try
        {
            $sql = 'UPDATE `tbl_erros` SET `erro_status` =  ? WHERE `tbl_erros`.`erro_id` = ?';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $status_nome, PDO::PARAM_STR);
            $sth->bindParam(2, $erro_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    /**
     * 
     * @param type $pagina_do_erro
     * @param type $mensagem
     * @param type $data
     * @param type $file_type
     * @param type $file_name
     * @param type $file_md5
     * @return type
     */
    public function inserir($pagina_do_erro, $mensagem, $data, $file_type, $file_name, $file_md5)
    {
        try
        {

            $sql = 'INSERT INTO `tbl_erros` (`erro_id`, `erro_pagina_do_erro`, `erro_mensagem`, `erro_data`, `erro_file_type`, `erro_file_name`, `erro_file_md5`) VALUES (NULL,?,?,?,?,?,?);';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $pagina_do_erro, PDO::PARAM_STR);
            $sth->bindParam(2, $mensagem, PDO::PARAM_STR);
            $sth->bindParam(3, $data, PDO::PARAM_STR);
            $sth->bindParam(4, $file_type, PDO::PARAM_STR);
            $sth->bindParam(5, $file_name, PDO::PARAM_STR);
            $sth->bindParam(6, $file_md5, PDO::PARAM_STR);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

}
