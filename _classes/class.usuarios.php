<?php

class Usuarios
{

    private $conexao = null;

    public function __construct($connection)
    {
        $this->conexao = $connection;
    }

    public function select($admin_id)
    {
        try
        {
            $sql = 'SELECT admin_id,admin_username,admin_nivel FROM `admin` as t1 WHERE t1.admin_id =?';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $admin_id, PDO::PARAM_INT);
            $sth->execute();
            return $sth->fetch();
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    public function verificar_se_usuario_existe($username)
    {
        try
        {
            $sql = 'SELECT COUNT(admin_id) FROM `admin` AS t1 WHERE t1.admin_username = ?';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $username, PDO::PARAM_STR);
            $sth->execute();
            $total = $sth->fetch(PDO::FETCH_ASSOC);
            return (int) $total['COUNT(admin_id)'];
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    /**
     *  <p>Criar uma senha para um novo usuário. Esta senha é considerada muito forte e muito difícil de decifrá-la.</p>
     * @param type $username
     * @param type $userpass
     * @return type
     */
    public function criar_novo_usuario($username, $userpass, $admin_nivel)
    {

        try
        {
//Define o tamanho máximo de caracteres para o usuário
            if (strlen($username) > 15)
            {
                exit("Erro na criação de usuário.Cod:1");
            } else if (!is_string($userpass))
            {
                exit("Erro na criação de usuário.Cod:2");
            }
            $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
            $hash = password_hash($userpass, PASSWORD_BCRYPT, array("cost" => 14, "salt" => $salt));
            $sth = $this->conexao->prepare("INSERT INTO `admin` (`admin_id`, `admin_username`, `admin_password`,`admin_nivel`)VALUES (NULL,?,?,?)");
            $sth->bindParam(1, $username, PDO::PARAM_STR);
            $sth->bindParam(2, $hash, PDO::PARAM_STR);
            $sth->bindParam(3, $admin_nivel, PDO::PARAM_STR);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

}
