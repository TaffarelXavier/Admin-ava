<?php

class Admin{

    private $conexao = null;

    public function __construct($connection) {
        $this->conexao = $connection;
    }

    public function select() {
        
    }

    public function deletar() {
        
    }

    public function atualiazar() {
        
    }
    
    public function inserir() {
        try {
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}