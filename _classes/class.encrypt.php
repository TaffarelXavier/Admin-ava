<?php

class Encrypt
{

    private $conexao = null;

    public function __construct($connection)
    {
        $this->conexao = $connection;
    }

    public function criptografar($texto)
    {
        $td = mcrypt_module_open('rijndael-256', '', 'ofb', '');

        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);

        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $iv, $texto, MCRYPT_MODE_ECB, $iv);

        return array('string' => $texto, 'key' => base64_encode($iv), 'crypt' => base64_encode($encrypted));
    }

    public function decriptografar($key, $crypt)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, base64_decode($key), base64_decode($crypt), MCRYPT_MODE_ECB));
    }

    public function gravar_no_banco()
    {
        $arr = $this->criptografar('chkdskP!@#$123');
        var_dump($arr);
        try
        {
            $data = time();
            $valor = '';
            $sth = $this->conexao->prepare('INSERT INTO `sistema` (`sistema_id`, `sistema_hash`, `sistema_date`, `sistema_key`) VALUES (NULL,?,?,?)');
            $sth->bindParam(1, $arr['crypt'], PDO::PARAM_STR);
            $sth->bindParam(2, $data, PDO::PARAM_STR);
            $sth->bindParam(3, $valor, PDO::PARAM_STR);
            $sth->execute();
            return $sth->rowCount();
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    public function verificar($key)
    {
        try
        {
            $sql = 'SELECT * FROM `sistema` ORDER BY sistema_id ASC';
            $sth = $this->conexao->prepare($sql);
            $sth->execute();
            $r = $sth->fetch(PDO::FETCH_ASSOC);
            return $this->decriptografar($key, $r['sistema_hash']);
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

    public function atualizar_senha($hash)
    {
        try
        {
            $sql = 'UPDATE `sistema` SET `sistema_key` = ? WHERE `sistema`.`sistema_id` = 1';
            $sth = $this->conexao->prepare($sql);
            $sth->bindParam(1, $hash, PDO::PARAM_STR);
            $sth->execute();
            return $sth;
        } catch (Exception $exc)
        {
            echo $exc->getMessage();
        }
    }

}
