<?php

/**
 * <p>Esta classe é responsável por uma parte da segurança do sistema</p>
 */
class Tokens_Dois {

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
    public static function inserir_nova($algo = "fnv164") {
        $key = hash($algo, sha1(uniqid(rand(), true)));
        if (!isset($_SESSION["security"])) {
            $_SESSION["security"][1] = $key;
        } else {
            $_SESSION["security"][count($_SESSION["security"]) + 1] = $key;
        }
        self::$key = count($_SESSION["security"]);
        self::$valor = $key;
        return array(self::$key, $key);
    }

    /**
     * <p>Insere um diretamente sem necessidade de inserir as tags inputs diretamente</p>
     * @param type $tipo
     * @param type $keyid
     * @param type $tokenid
     */
    public static function inserir($tipo = 'hidden', $keyid = 's_Taffa_KEY', $tokenid = 's_Taffa_TOKEN') {
        $array = self::inserir_nova('whirlpool');
        ?>
        <input type='<?php echo $tipo; ?>' name='key' value='<?php echo $array[0]; ?>' id='<?php echo $keyid; ?>' class='sTaffa_Security_Key' />
        <input type='<?php echo $tipo; ?>' name='token' value='<?php echo $array[1]; ?>' id='<?php echo $tokenid; ?>' class='sTaffa_Security_Token'  />
        <?php
    }

    /**
     * 
     * @param type $key
     * @param type $token
     * @return boolean Retorna true em caso se sucesso.
     */
    public static function validar_sessao($key, $token) {
        if (self::key_exists($key) === false) {
            return '0';
        } else if (self::is_nulo($key, $token) == true) {
            return '0';
        } elseif (self::is_equals($key, $token) == false) {
            return '0';
        } else {
            return true;
        }
    }

    /**
     * <p>Verifica os dados com duas variáveis: key, e token</p>
     * @return type
     */
    public static function isValido() {
        $key = val_input::sani_string('key');

        $token = val_input::sani_string('token');

        return Tokens_Dois::validar_sessao($key, $token);
    }

    /**
     * 
     */
    public static function delete_secur_sessoes() {
        for ($index = 0; $index < count($_SESSION["security"]); $index++) {
            unset($_SESSION["security"][$index]);
        }
    }

    public static function delete_sessoes($key) {
        for ($index = 0; $index < $key; $index++) {
            unset($_SESSION["security"][$index]);
        }
    }

    public static function delete_complete_sessao() {
        unset($_SESSION["security"]);
    }

    /**
     * 
     */
    public static function get_security() {
        return $_SESSION["security"];
    }

    /**
     * 
     */
    public static function sessao_exists($key) {
        if (isset($_SESSION["security"][$key])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     */
    public static function sessao_in_array($valor) {
        return in_array($valor, $_SESSION["security"]);
    }

    /**
     * <p>Muito importante</p>
     * @param type $key
     * @param type $valor True se a chave corresponder ao valor, senão false.
     */
    public static function is_equals($key, $valor) {
        if ($_SESSION["security"][$key] == $valor) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $key
     */
    public static function key_exists($key) {
        return array_key_exists($key, $_SESSION["security"]);
    }

    /**
     * 
     * @param type $key
     * @param type $valor
     * @return type
     */
    public static function is_nulo($key, $valor) {
        return (is_null($key) || is_null($valor) || empty($key) || empty($valor)) ? true : false;
    }

    /**
     * 
     * @param type $data
     * @return boolean
     */
    public static function hash_algos($data = "") {
        $var = hash_algos();

        $neo_Bca_338 = ($data == "") ? md5(uniqid(rand(), true)) : $data;

        foreach ($var as $key => $value) {
            echo ("<b>" . $value . "</b>" . "=" . hash($value, $neo_Bca_338) . "<br/>");
        }
        return true;
    }

}
