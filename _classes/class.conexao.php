<?php

class Conexao
{

    public static $instance = null;

    /**
     * <p>Chama a conexão</p>
     * @return type
     */
    public static function conn()
    {
        try
        {
            if (!isset(self::$instance))
            {
                self::$instance = new PDO("mysql:dbname=" . DB_NAME. ";host=" . DB_HOST,DB_USER,DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
            } return self::$instance;
        } catch (PDOException $e)
        {

            exit('<p style="text-align:center;">A conexão falhou.' . $e->getMessage() . '</p>');
        }
    }

    /**
     * <p>Fecha a conexão</p>
     */
    public static function close_conn()
    {
        $estancia = self::$instance;
        self::$instance = null;
        unset($estancia);
    }

}
