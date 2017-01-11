<?php

namespace Mindy\Helper;

/**
 * Class Params
 * @package Mindy\Helper
 */
class Params 
{
    /**
     * @var array
     */
    public static $params = [];

    /**
     * @param array $params
     */
    public static function setParams(array $params = [])
    {
        self::$params = $params;
    }

    /**
     * @return array
     */
    public static function getParams()
    {
        return self::$params;
    }

    /**
     * @param $paths
     * @return array
     */
    public static function collect($paths)
    {
        $params = [];
        foreach($paths as $path) {
            if(!is_dir($path)) {
                continue;
            }

            $files = glob($path . '/*/config/params.php');
            if (is_array($files)) {
                foreach ($files as $file) {
                    $tmp = include_once($file);
                    if (is_array($tmp) && !empty($tmp)) {
                        $module = str_replace($path . '/', '', $file);
                        $module = str_replace('/config/params.php', '', $module);
                        $params[$module] = $tmp;
                    }
                }
            }
        }
        return self::$params = $params;
    }

    /**
     * Возвращает пользовательский параметр приложения
     * @param string $key Ключ параметра или ключи вложенных параметров через точку
     * Например, 'Media.Foto.thumbsize' преобразуется в ['Media']['Foto']['thumbsize']
     * @param mixed $defaultValue Значение, возвращаемое в случае отсутствия ключа
     *
     * @return mixed
     */
    public static function get($key, $defaultValue = null)
    {
        return self::getKeyFromAlias($key, self::$params, $defaultValue);
    }

    /**
     * Возвращает значения ключа в заданном массиве
     * @param string $key Ключ или ключи точку
     * Например, 'Media.Foto.thumbsize' преобразуется в ['Media']['Foto']['thumbsize']
     * @param array $array Массив значений
     * @param mixed $defaultValue Значение, возвращаемое в случае отсутствия ключа
     *
     * @return mixed
     */
    public static function getKeyFromAlias($key, $array, $defaultValue = null)
    {
        if (strpos($key, '.') === false) {
            return (isset($array[$key])) ? $array[$key] : $defaultValue;
        }

        $keys = explode('.', $key);

        if (!isset($array[$keys[0]])) {
            return $defaultValue;
        }

        $value = $array[$keys[0]];
        unset($keys[0]);

        foreach ($keys as $k) {
            if(!is_array($value)) {
                return $defaultValue;
            }
            if (!isset($value[$k]) && !array_key_exists($k, $value)) {
                return $defaultValue;
            }
            $value = $value[$k];
        }

        return $value;
    }
}
