<?php

$uri = $_SERVER['REQUEST_URI'];

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

require 'index.php';
