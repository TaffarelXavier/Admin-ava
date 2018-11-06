<?php

//========================================================================================
//                              CONFIGURAÇÃO DO SISTEMA
//========================================================================================
##Se for https
if (isset($_SERVER['HTTPS'])) {

    define('_DOMINIO_', '//' . $_SERVER['SERVER_NAME'] . '/' . 'admin' . '/');
} else {

    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        define('_DOMINIO_', '//' . $_SERVER['SERVER_NAME'] . '/' . 'admin' . '/');
    } else {
        define('_DOMINIO_', '//' . $_SERVER['SERVER_NAME'] . '/' . 'admin' . '/');
    }
}
//Define a base do sistema
define('SYS_BASE_NAME', _DOMINIO_);

//Define a raiz do sistema
define('SYS_DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);

define('SERVER_NOME', $_SERVER['SERVER_NAME']);

define('SERVIDOR_CEMVS', 'cemvs.ltai.com.br');

//Define o caminho da fonte a ser usada
define('FONTE_NAME', '../_helpers/century_gothic.gdf');

//Define o tamanho da String a ser usada
define('TAMANHO', 3);

define('BACKUP_NAME', 'admin');

define('BACKUP_PASTA', SYS_DOC_ROOT . 'admin' . '/' . 'backup' . '/');

define('SYS_LOGO', 'logo.png');

switch (SERVER_NOME) {
    case 'admin.ltai.com.br':
        /** MySQL hostname */
        define('DB_HOST', 'mysql552.umbler.com');
        /** MySQL database username */
        define('DB_USER', 'cemvs_user_2018');
        /** The name of the database for WordPress */
        define('DB_NAME', 'cemvsdb_2018');
        /** MySQL database password */
        define('DB_PASSWORD', 'YBce{h47P]v');
        break;
    case 'admin.local.com.br':
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

if (isset($_SESSION['_sessao'])) {
    define('_LOGADO_', true);
}

//===================================
//         Verificando acesso
//===================================
//O Ip do usuário
$meuip = $_SERVER['REMOTE_ADDR'];

$connection = new PDO('mysql:dbname=' . DB_NAME . ";host=" . DB_HOST, DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

try {
    $sth = $connection->prepare('SELECT admin_ip FROM `admin`');
    $sth->execute();
    $arrIp = array();
    while ($lin = $sth->fetch(PDO::FETCH_ASSOC)) {
        $arrIp[] = $lin['admin_ip'];
    }
    //Se não estiver no meio dos IP do banco de dados.
    if (!in_array($meuip, $arrIp)) {
        exit('<h1 style="text-align:center;">Você não possui acesso a este sistema.</h1>');
    }
} catch (Exception $exc) {
    exit($exc->getMessage());
}