<?php

if (!function_exists('array_flip_numkeys')) {
    /**
     * @param array $array
     * @param mixed $default
     * @return array
     */
    function array_flip_numkeys(array $array, $default = null)
    {
        $out = [];
        foreach ($array as $key => $value) is_int($key)
            ? $out[$value] = $default
            : $out[$key] = $value;
        return $out;
    }
}
