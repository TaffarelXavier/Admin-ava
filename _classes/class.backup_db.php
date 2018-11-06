<?php

/**
 * 
 * Use this class to do a backup of your database 
 * @author Raul Souza Silva (raul.3k@gmail.com) 
 * @category Database 
 * @copyright No one. You can copy, edit, do anything you want. If you change anything to better, please let me know. 
 * 
 */
Class Backup_DB {

    /**
     * 
     * The host you will connect 
     * @var String 
     */
    private $host;

    /**
     * 
     * The database you will use to connect 
     * @var String 
     */
    private $dbName;

    /**
     * 
     * Array with the tables of the database 
     * @var Array 
     */
    private $tables = array();

    /**
     * 
     * Hold the connection 
     * @var ObjectConnection 
     */
    private $handler;

    /**
     * 
     * Array to hold the errors 
     * @var Array 
     */
    private $error = array();

    /**
     * 
     * The result string. String with all queries 
     * @var String 
     */
    private $final;

    /**
     * 
     * The result string. String with all queries 
     * @var String 
     */
    const CRLN = "\r\n";

    /**
     * 
     * @param type $connection
     */
    public function __construct($connection, $dbname) {

        try {
            $this->handler = $connection;
        } catch (PDOException $e) {
            $this->handler = null;
            $this->error[] = $e->getMessage();
            return false;
        }

        $this->dbName = $dbname;

        $this->final = "DROP DATABASE IF EXISTS `" . $this->dbName . "`;" . self::CRLN . 'CREATE DATABASE `' . $this->dbName . "`;" . self::CRLN . "USE `" . $this->dbName . "`;" . self::CRLN . self::CRLN;

        if ($this->host == 'localhost') {
            // We have a little issue in unix systems when you set the host as localhost 
            $this->host = '127.0.0.1';
        }

        $this->getTables();
        $this->generate();
    }

    /**
     * 
     * Call this function to get the database backup 
     * @example DBBackup::backup(); 
     */
    public function backup() {
        //return $this->final; 
        if (count($this->error) > 0) {
            return array('error' => true, 'msg' => $this->error);
        }
        return array('error' => false, 'msg' => $this->final);
    }

    /**
     * 
     * Generate backup string 
     * @uses Private use 
     */
    private function generate() {
        foreach ($this->tables as $tbl) {
            //$this->final .= '--CREATING TABLE ' . $tbl['name'] . "\n";
            $this->final .= $tbl['create'] . ";\n\n";
            //$this->final .= '--INSERTING DATA INTO ' . $tbl['name'] . "\n";
            $this->final .= $tbl['data'] . "\n\n\n";
        }
        $this->final .= '-- THE END' . "\n\n";
    }

    /**
     * 
     * Get the list of tables 
     * @uses Private use 
     */
    private function getTables() {
        try {
            $stmt = $this->handler->query('SHOW TABLES');
            $tbs = $stmt->fetchAll();
            $i = 0;
            foreach ($tbs as $table) {
                $this->tables[$i]['name'] = $table[0];
                $this->tables[$i]['create'] = $this->getColumns($table[0]);
                $this->tables[$i]['data'] = $this->getData($table[0]);
                $i++;
            }
            unset($stmt);
            unset($tbs);
            unset($i);
            return true;
        } catch (PDOException $e) {
            $this->handler = null;
            $this->error[] = $e->getMessage();
            return false;
        }
    }

    /**
     * 
     * Get the list of Columns 
     * @uses Private use 
     */
    private function getColumns($tableName) {
        try {
            $stmt = $this->handler->query('SHOW CREATE TABLE ' . $tableName);
            $q = $stmt->fetchAll();
            //$q[0][1] = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $q[0][1]);
            return $q[0][1];
        } catch (PDOException $e) {
            $this->handler = null;
            $this->error[] = $e->getMessage();
            return false;
        }
    }

    /**
     * 
     * Get the insert data of tables 
     * @uses Private use 
     */
    private function getData($tableName) {
        try {
            $stmt = $this->handler->query('SELECT * FROM ' . $tableName);
            $q = $stmt->fetchAll(PDO::FETCH_NUM);
            $data = '';
            foreach ($q as $pieces) {
                foreach ($pieces as &$value) {
                    $value = htmlentities(addslashes($value));
                }
                $data .= 'INSERT INTO `' . $tableName . '` VALUES (\'' . implode('\',\'', $pieces) . '\');' . "\n";
            }
            return $data;
        } catch (PDOException $e) {
            $this->handler = null;
            $this->error[] = $e->getMessage();
            return false;
        }
    }

    /**
     * <p>O arquivo onde será salvo o backup</p>
     * @param string $filename
     * <p>Exemplo</p>
     * $db = new DBBackup();
     * $file = dirname(getcwd());
     * $db->export($file . DIRECTORY_SEPARATOR . "taffarel.sql");
     */
    public function export($filename) {
        $array = $this->backup();
        $data = null;
        foreach ($array as $key => $value) {
            $data = $value;
        }
        file_put_contents($filename, html_entity_decode($data));
    }

    /**
     * <p>O arquivo onde será salvo o backup</p>
     * @param string $sql
     */
    public function import($sql) {
        try {
            return $this->handler->exec($sql);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
