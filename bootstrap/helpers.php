<?php

/*
|--------------------------------------------------------------------------
| Polyfills for mbstring extension
|--------------------------------------------------------------------------
|
| These functions and constants ensure the application doesn't crash (500 Error)
| if the mbstring PHP extension is missing or disabled on the server.
|
*/

// Define constants used by Laravel/mb_* functions
if (!defined('MB_CASE_UPPER'))
    define('MB_CASE_UPPER', 0);
if (!defined('MB_CASE_LOWER'))
    define('MB_CASE_LOWER', 1);
if (!defined('MB_CASE_TITLE'))
    define('MB_CASE_TITLE', 2);

if (!function_exists('mb_internal_encoding')) {
    function mb_internal_encoding($encoding = null)
    {
        return $encoding ? true : 'UTF-8';
    }
}

if (!function_exists('mb_strlen')) {
    function mb_strlen($string, $encoding = null)
    {
        return strlen($string);
    }
}

if (!function_exists('mb_substr')) {
    function mb_substr($string, $start, $length = null, $encoding = null)
    {
        return substr($string, $start, $length);
    }
}
if (!function_exists('mb_strpos')) {
    function mb_strpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        return strpos($haystack, $needle, $offset);
    }
}

if (!function_exists('mb_strtolower')) {
    function mb_strtolower($string, $encoding = null)
    {
        return strtolower($string);
    }
}

if (!function_exists('mb_strtoupper')) {
    function mb_strtoupper($string, $encoding = null)
    {
        return strtoupper($string);
    }
}

/**
 * Polyfill for mb_split if mbstring extension is missing
 */
if (!function_exists('mb_split')) {
    function mb_split($pattern, $string, $limit = -1)
    {
        return preg_split('/' . str_replace('/', '\/', $pattern) . '/u', $string, $limit);
    }
}

/**
 * Polyfill for mb_convert_case
 */
if (!function_exists('mb_convert_case')) {
    function mb_convert_case($string, $mode, $encoding = 'UTF-8')
    {
        if ($mode == MB_CASE_TITLE) {
            return ucwords(strtolower($string));
        } elseif ($mode == MB_CASE_UPPER) {
            return strtoupper($string);
        } else {
            return strtolower($string);
        }
    }
}
