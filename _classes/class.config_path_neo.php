<?php

class Config_Path_Neo
{

    public static $caminho = array(0 => array("./", "../"), 1 => array("../", "../../"));

    public static function functionName()
    {
        $var = pathinfo(getcwd());

        $basename = $var['basename'];

        $i = 0;
        switch ($basename)
        {
            case 'admin':
                $i = 0;
                break;
            default:
                $i = 1;
                break;
        }
        return self::$caminho[$i];
    }

    public static function e_raiz()
    {
        $var = pathinfo(getcwd());

        $basename = $var['basename'];

        return (bool) ($basename == 'administrativo') ? true : false;
    }

}