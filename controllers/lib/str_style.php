<?php

function toSnakeCase($str) {
    $str = str_replace(' ', '_', $str);
    $str = strtolower($str);
    return $str;
}

function toCamelCase($str) {
    $str = str_replace(['_', ' '], ' ', $str);
    $str = ucwords($str);
    $str = lcfirst($str);
    $str = str_replace(' ', '', $str);
    return $str;
}

function toCapitalizedCase($str) {
    $str = strtolower($str);
    $str = ucwords($str);
    return $str;
}
