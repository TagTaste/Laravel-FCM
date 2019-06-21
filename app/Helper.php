<?php

namespace App;

class Helper
{

    public static function convertToCamelCase($array) {
        $finalArray = array();
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                if (strpos($key, "_")) {
                    $key = lcfirst(str_replace("_", "", ucwords($key, "_"))); //let's convert key into camelCase
                    $finalArray[$key] = $value;
                } else {
                    $finalArray[$key] = $value;
                }
            } else {
                if (strpos($key, "_")) {
                    $key = lcfirst(str_replace("_", "", ucwords($key, "_")));
                    $finalArray[$key] = Helper::convertToCamelCase($value);
                } else {
                    $finalArray[$key] = Helper::convertToCamelCase($value);
                }
            }
        }
        return $finalArray;
    }

    /**
     * Helper to convert under_score type array's keys to camelCase type array's keys
     * @param   array   $array          array to convert
     * @return  array   camelCase array
     */
    public static function camel_case_keys($array) {
        $camelCaseArray = array();
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                if (strpos($key, "_")) {
                    //let's convert key into camelCase
                    $key = lcfirst(str_replace("_", "", ucwords($key, "_"))); 
                    $camelCaseArray[$key] = $value;
                } else {
                    $camelCaseArray[$key] = $value;
                }
            } else {
                if (strpos($key, "_")) {
                    $key = lcfirst(str_replace("_", "", ucwords($key, "_")));
                    $camelCaseArray[$key] = Helper::camel_case_keys($value);
                } else {
                    $camelCaseArray[$key] = Helper::camel_case_keys($value);
                }
            }
        }
        return $camelCaseArray;
    }

    /**
     * Convert camelCase type array's keys to under_score+lowercase type array's keys
     * @param   array   $array          array to convert
     * @param   array   $arrayHolder    parent array holder for recursive array
     * @return  array   under_score array
     */
    public static function under_score_keys($array, $arrayHolder = array()) {
        $underscoreArray = !empty($arrayHolder) ? $arrayHolder : array();
        foreach ($array as $key => $val) {
            $newKey = preg_replace('/[A-Z]/', '_$0', $key);
            $newKey = strtolower($newKey);
            $newKey = ltrim($newKey, '_');
            if (!is_array($val)) {
                $underscoreArray[$newKey] = $val;
            } else {
                $underscoreArray[$newKey] = $this->under_score_keys($val, $underscoreArray[$newKey]);
            }
        }
        return $underscoreArray;
    }   
}