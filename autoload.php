<?php

spl_autoload_register(function ($className) {
    $baseDirectory = __DIR__ . '/app/';

    $directories = array(
        'App\\Controllers' => 'Controllers',
        'App\\Models' => 'Models',
        'App\\Routing' => 'Routing',
        'Core' => '../Core',
        'Config' => '../Config'
    );

    foreach ($directories as $namespace => $directory) {

        if (strpos($className, $namespace . '\\') === 0) {

            $relativeClass = substr($className, strlen($namespace . '\\'));
            $filePath = $baseDirectory . $directory . '/' . str_replace('\\', '/', $relativeClass) . '.php';
            
            if (file_exists($filePath)) {
                require_once $filePath;
                return;
            }
        }
    }
});
