<?php
// Код автозагрузки классов и модулей
spl_autoload_register(function($class_name) {return autoload($class_name, __DIR__);});

function autoload($class_name, $base_path) {

    $class_path = $base_path . '\\' . strtolower($class_name) . ".php";
    
    if (file_exists($class_path)) {
        require_once $class_path;
    }

}
