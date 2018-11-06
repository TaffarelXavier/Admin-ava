<?php

class Criptografia
{
    /**
     * 
     * @param type $texto
     * @return Array Indexs: string= A string literal; key= a chave; crypt=o texto criptografado
     */
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
}
