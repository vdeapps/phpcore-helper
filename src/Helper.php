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
    public static function formatted($numValue, $deci = 0, $sep = ' ', $dec_point = ',')
    {
        if ($numValue == '') {
            return '';
        }
        
        return number_format($numValue, $deci, $dec_point, $sep);
    }
    
    /**
     * Retourne une valeur aléatoire
     *
     * @param int   $min     borne minimum
     * @param int   $max     borne maximum
     *
     * @param array $tbCheck Contient les valeurs déjà sorties
     *
     * @return bool|int FALSE=>Erreur, sinon la valeur
     * @throws \Exception
     */
    public static function rand($min = 0, $max = 9999, &$tbCheck = [])
    {
        $val = rand($min, $max);
        
        $iter = 0;
        $maxiter = $max - $min;
        while (in_array($val, $tbCheck)) {
            if ($iter == $maxiter) {
                throw new \Exception("Nb rand max deja atteint", 5);
                
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
    public static function base64Encode($str, $stripEgal = true)
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
    public static function base64Decode($str)
    {
        return base64_decode(str_pad(strtr($str, '-_', '+/'), strlen($str) % 4, '=', STR_PAD_RIGHT));
    }
    
    /**
     * Compare 2 tableaux de valeurs ou 2 valeurs
     *
     * @param mixed|array $arr1      La variable sera transformée en tableau
     * @param mixed|array $arr2      La variable sera transformée en tableau
     * @param string      $operateur default(IN), NOTIN, EQ
     *
     * @return boolean
     */
    public static function compareValues($arr1 = [], $arr2 = [], $operateur = 'IN')
    {
        if (!is_array($arr1)) {
            $arr1 = [$arr1];
        }
        if (!is_array($arr2)) {
            $arr2 = [$arr2];
        }
        
        if (!is_array($arr1) || !is_array($arr2)) {
            return false;
        }
        
        switch (strtoupper($operateur)) {
            case 'IN':
                $result = (count(array_intersect($arr1, $arr2)) !== 0);
                break;
            
            case 'NOTIN':
                $result = (count(array_diff($arr1, $arr2)) !== 0);
                break;
            
            case 'EQ':
                $result = (count(array_intersect($arr1, $arr2)) == count($arr2));
                break;
            
            default:
                $result = false;
                break;
        }
        
        return $result;
    }
}
