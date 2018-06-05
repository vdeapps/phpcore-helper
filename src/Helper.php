<?php
/**
 * Copyright (c) vdeApps 2018
 */

/**
 * Class de test retournant true / false
 * @author vdeapps
 * @version
 */

namespace vdeApps\phpCore;

/**
 * @category awc
 *
 * @uses     awcRegistry
 *
 */
class Helper
{
    
    /**
     * Debug formatté
     *
     * @example pre("Title: ", $tbArray, object, ...)
     *
     * @param mixed
     *
     * @return string
     */
    public static function pre()
    {
        $nbArgs = func_num_args();
        
        $content = '';
        for ($i = 0; $i < $nbArgs; $i++) {
            $mixed = func_get_arg($i);
            
            $content .= print_r($mixed, 1);
        }
        
        if (php_sapi_name() == 'cli') {
            $output = $content;
        } else {
            $output = '<pre class="debug">' . $content . '</pre>';
        }
        
        return $output;
    }
    
    /**
     * Retourne un nombre formaté
     *
     * @param        $numValue
     * @param int    $deci
     * @param string $sep
     *
     * @return string
     */
    public static function formatted($numValue, $deci = 0, $sep = ' ')
    {
        if ($numValue == '') {
            return '';
        }
        
        return number_format($numValue, $deci, ',', $sep);
    }
    
    /**
     * Retourne une valeur aléatoire
     *
     * @param array $tbCheck Contient les valeurs déjà sorties
     * @param int   $min     borne minimum
     * @param int   $max     borne maximum
     *
     * @return bool|int FALSE=>Erreur, sinon la valeur
     */
    public static function rand($min = 0, $max = 9999, &$tbCheck = [])
    {
        $val = rand($min, $max);
        
        $iter = 0;
        $maxiter = $max - $min;
        while (in_array($val, $tbCheck)) {
            if ($iter == $maxiter) {
                return false;
            }
            $val = rand($min, $max);
            $iter++;
        }
        $tbCheck[] = $val;
        
        return $val;
    }
    
    /**
     * Retourne une chaine en camelCase avec lcfirst|ucfirst
     *
     * @param string $str
     * @param string $firstChar lcfirst|ucfirst default(lcfirst)
     *
     * @return string
     */
    public static function camelCase($str, $firstChar = 'lcfirst')
    {
        $str = str_replace(['-', '_', '.'], ' ', $str);
        $str = mb_convert_case($str, MB_CASE_TITLE);
        $str = str_replace(' ', '', $str); //ucwords('ghjkiol|ghjklo', "|");
        
        if (!function_exists($firstChar)) {
            $firstChar = 'lcfirst';
        }
        $str = call_user_func($firstChar, $str);
        
        return $str;
    }
    
    /**
     * base64_decode sans les == de fin
     *
     * @param      $str
     * @param bool $stripEgal
     *
     * @return string
     */
    public static function base64_encode($str, $stripEgal = true)
    {
        $str64 = base64_encode($str);
        if ($stripEgal) {
            return rtrim(strtr($str64, '+/', '-_'), '=');
        }
        
        return $str64;
    }
    
    /**
     * base64_decode
     *
     * @param $str
     *
     * @return bool|string
     */
    public static function base64_decode($str)
    {
        return base64_decode(str_pad(strtr($str, '-_', '+/'), strlen($str) % 4, '=', STR_PAD_RIGHT));
    }
}
