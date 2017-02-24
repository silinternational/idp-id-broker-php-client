<?php

// Composer autoloading
$composerAutoloaderFile = __DIR__ . '/../vendor/autoload.php';
if (file_exists($composerAutoloaderFile)) {
    $loader = include_once $composerAutoloaderFile;
} else {
    die('Unable to find Composer autoloader file.');
}
