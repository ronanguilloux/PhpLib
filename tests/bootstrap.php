<?php
spl_autoload_register(function($class) {
    $class = trim(strtolower($class));
    $file = __DIR__ . "/../lib/class.$class.php";
    if (file_exists($file)) {
        require_once $file;
    }
});
?>
