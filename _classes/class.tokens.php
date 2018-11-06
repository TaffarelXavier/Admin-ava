<?php

/**
 * <p>Esta classe é responsável por uma parte da segurança do sistema</p>
 */
class Tokens
{

    /**
     *
     * @var type 
     */
    public static $key = "";

    /**
     *
     * @var type 
     */
    public static $valor = "";

    /**
     * <p>Insere um valor em um imput</p>
     * @param type $session_key
     * @param type $algo
     * @return Array O index 0 é a chave, e o 1 é o seu valor.
     */
    public static function inserir_nova($algo = "fnv164")
    {
        $key = hash($algo, sha1(uniqid(rand(), true)));

        if (!isset($_SESSION["security"]))
        {
            $_SESSION["security"][count($_SESSION["security"]) + 1] = $key;
        } else
        {
            $_SESSION["security"][count($_SESSION["security"]) + 1] = $key;
        }
        self::$key = count($_SESSION["security"]);
        self::$valor = $key;
        return array(self::$key, $key);
    }

    public static function validar_sessao($key, $token)
    {
        if (self::key_exists($key) === false)
        {
            exit('0');
        } else if (self::is_nulo($key, $token) == true)
        {
            exit('0');
        } elseif (self::is_equals($key, $token) == false)
        {
            exit('0');
        } else
        {
            return true;
        }
    }

    /**
     * 
     */
    public static function delete_secur_sessoes()
    {
        for ($index = 0; $index < count($_SESSION["security"]); $index++)
        {
            unset($_SESSION["security"][$index]);
        }
    }

    public static function delete_sessoes($key)
    {
        for ($index = 0; $index < $key; $index++)
        {
            unset($_SESSION["security"][$index]);
        }
    }

    public static function delete_complete_sessao()
    {
        unset($_SESSION["security"]);
    }

    /**
     * 
     */
    public static function get_security()
    {
        return $_SESSION["security"];
    }

    /**
     * 
     */
    public static function sessao_exists($key)
    {
        if (isset($_SESSION["security"][$key]))
        {
            return true;
        } else
        {
            return false;
        }
    }

    /**
     * 
     */
    public static function sessao_in_array($valor)
    {
        return in_array($valor, $_SESSION["security"]);
    }

    /**
     * <p>Muito importante</p>
     * @param type $key
     * @param type $valor True se a chave corresponder ao valor, senão false.
     */
    public static function is_equals($key, $valor)
    {
        if ($_SESSION["security"][$key] == $valor)
        {
            return true;
        } else
        {
            return false;
        }
    }

    /**
     * 
     * @param type $key
     */
    public static function key_exists($key)
    {
        return array_key_exists($key, $_SESSION["security"]);
    }

    /**
     * 
     * @param type $key
     * @param type $valor
     * @return type
     */
    public static function is_nulo($key, $valor)
    {
        return (is_null($key) || is_null($valor) || empty($key) || empty($valor)) ? true : false;
    }

    /**
     * 
     * @param type $data
     * @return boolean
     */
    public static function hash_algos($data = "")
    {
        $var = hash_algos();

        $neo_Bca_338 = ($data == "") ? md5(uniqid(rand(), true)) : $data;

        foreach ($var as $key => $value)
        {
            echo ("<b>" . $value . "</b>" . "=" . hash($value, $neo_Bca_338) . "<br/>");
        }
        return true;
    }

}

/**
 * //Convertendo caracteres especiais para a realidade HTML
  $varLimpa = htmlspecialchars($_POST['infoForm'], ENT_QUOTES,'UTF-8');

  //voltando os caracteres originais
  $varSuja = htmlspecialchars_decode($varLimpa );
 */
