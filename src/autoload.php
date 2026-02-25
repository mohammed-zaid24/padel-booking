<?php

spl_autoload_register(function (string $class) {

    // We only autoload classes that start with "App\"
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/'; // this is the src/ folder

    // If the class does not start with App\, we ignore it
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    // Remove "App\" from the start
    $relativeClass = substr($class, strlen($prefix));

    // Convert namespace separators "\" into folder separators "/"
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    // If the file exists, load it
    if (file_exists($file)) {
        require_once $file;
    }
});
