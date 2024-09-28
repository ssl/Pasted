<?php

/**
 * Simple shorter function to escape strings
 *
 * @param string $value
 * @return string
 */
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}

/**
 * Shorthen a number to a string
 *
 * @param string $value
 * @return string
 */
function short($id) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $base = strlen($chars);
    $shortUrl = '';

    while ($id > 0) {
        $remainder = $id % $base;
        $shortUrl = $chars[$remainder] . $shortUrl;
        $id = floor($id / $base);
    }

    return $shortUrl;
}