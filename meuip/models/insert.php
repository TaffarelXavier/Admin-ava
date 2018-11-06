<?php

define('SERVER_NOME', $_SERVER['SERVER_NAME']);

define('_SERVER_', 'cemvs.ltai.com.br');

switch (SERVER_NOME) {
    case 'admin.ltai.com.br': /*WEB*/
        /** MySQL hostname */
        define('DB_HOST', 'mysql552.umbler.com');
        /** MySQL database username */
        define('DB_USER', 'cemvs_user_2018');
        /** The name of the database for WordPress */
        define('DB_NAME', 'cemvsdb_2018');
        /** MySQL database password */
        define('DB_PASSWORD', 'YBce{h47P]v');
        break;
    case 'admin.local.com.br': /*LOCAL*/
        /** MySQL hostname */
        define('DB_HOST', 'localhost');
        /** MySQL database username */
        define('DB_USER', 'root');
        /** The name of the database for WordPress */
        define('DB_NAME', 'cemvsdb_2018');
        /** MySQL database password */
        define('DB_PASSWORD', 'chkdsk');
        break;
}

$conn = new PDO("mysql:dbname=" . DB_NAME . ";host=" . DB_HOST, DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conn->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);

$ip = filter_input(INPUT_POST, 'ip', FILTER_SANITIZE_STRING);

try {
    $sql = "INSERT INTO `admin` (`admin_id`, `admin_username`, `admin_password`, `admin_nivel`, `admin_data`, `admin_ip`) VALUES (NULL, 'Taffarel', '', 'medio', '" . date('d/m/Y H:i:s', time()) . "', ?);";
    $sth = $conn->prepare($sql);
    $sth->bindParam(1, $ip, PDO::PARAM_INT);
    $sth->execute();
    echo ($sth->rowCount() > 0) ? '1' : '0';
} catch (Exception $exc) {
    echo $exc->getMessage();
}

unset($conn);
