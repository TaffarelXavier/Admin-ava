<?php

class TX_String {

    /**
     * 
     * @param type $str
     * @param type $length
     * @param string $st
     * @return boolean
     */
    public static function replaceNome($str, $length = 2, $list_black = array(' da ', ' de ', ' das ', ' dos ', ' e ', ' DO ', ' do '), $st = '') {

        $exp = preg_split('/\s+/', trim($str));

        $string = str_ireplace($list_black, ' ', implode(' ', $exp));

        $count_word = str_word_count($string);

        switch ($count_word) {
            case 0:
                return false;
            case 1:
                return $string;
            case 2:
                return $string;
            default:
                $ex = explode(' ', $string);

                $length_word = (count($ex));

                if ($length > $length_word) {
                    $length = $length_word;
                }
                
                if ($length == 'TODO' || $length == 0 || $length == '' || $length == '0') {
                    for ($i = 0; $i < $length_word; ++$i) {
                        $st .=' ' . $ex[$i];
                    }
                } else {
                    for ($i = 0; $i < $length; ++$i) {
                        $st .=' ' . $ex[$i];
                    }
                }
                return trim($st);
        }
    }

}