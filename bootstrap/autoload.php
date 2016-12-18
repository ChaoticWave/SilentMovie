<?php
define('LARAVEL_START', microtime(true));

//  Register The Composer Auto Loader
require __DIR__ . '/../vendor/autoload.php';

//  Include The Compiled Class File
if (is_readable($compiledPath = __DIR__ . '/cache/compiled.php')) {
    /** @noinspection PhpIncludeInspection */
    require $compiledPath;
}
