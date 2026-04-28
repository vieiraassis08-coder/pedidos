<?php

spl_autoload_register(function (string $className): void {
    $baseDir = __DIR__;
    $relativeClass = ltrim($className, '\\');
    $file = $baseDir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
