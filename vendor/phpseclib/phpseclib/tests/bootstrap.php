<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * Bootstrapping File for phpseclib Test Suite
 *
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
date_default_timezone_set('UTC');
$loader_path = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($loader_path)) {
    echo 'Dependencies must be installed using composer:

';
    echo 'php composer.phar install

';
    echo 'See http://getcomposer.org for help with installing composer
';
    die(1);
}
$loader = (include $loader_path);
$loader->add('', __DIR__);