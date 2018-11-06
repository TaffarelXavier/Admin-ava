<?php

class T_Crypt {

    /**
     * <p>Função para criptografar uma string</p>
     * @param type $decrypted A string para ser criptografada
     * @param type $salt O Salt
     * @return Array Retorna um array ou falso. Chaves: key=Chave Privada, msg=mensagem criptografada
     */
    public static function encrypt($decrypted, $salt = '!kQm*fF3pXe1Kbm%9Taffarel1,23(') {
        
        $publicKey = md5(uniqid(rand(), true));

        $key = hash('SHA256', $salt . $publicKey, true);

        srand();

        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);

        if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) {
            return false;
        }

        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));

        return array('key' => $publicKey, 'msg' => $iv_base64 . $encrypted);
        
    }

    /**
     * <p>Função para descriptografar uma string</p>
     * @param type $decrypted A string para ser criptografada
     * @param type $salt O Salt
     * @return boolean
     */
    public static function decrypt($encrypted, $chavepublica, $salt = '!kQm*fF3pXe1Kbm%9Taffarel1,23(') {
        $key = hash('SHA256', $salt . $chavepublica, true);
        $iv = base64_decode(substr($encrypted, 0, 22) . '==');
        $encrypted = substr($encrypted, 22);
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
        $hash = substr($decrypted, -32);
        $decrypted = substr($decrypted, 0, -32);
        if (md5($decrypted) != $hash) {
            return array(false,hash('SHA256', md5(uniqid(rand(), true)), true));
        }
        return $decrypted;
    }

}
