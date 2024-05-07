<?php 
function my_autoloader($class) {
    // Convertimos los '\' en el sistema de archivos en '/'
    $class = str_replace(['\\',basename(dirname(__FILE__)).'\\'], [DIRECTORY_SEPARATOR,''], $class);

    // Directorio base de tu proyecto
    $base_dir = __DIR__ . DIRECTORY_SEPARATOR;

    // Ruta completa del archivo de la clase
    $file = $base_dir . $class . '.php';
   
    // Si el archivo existe, lo incluimos
    if (file_exists($file)) {
        require $file;
    }
}

// Registramos la función de autocarga
spl_autoload_register('my_autoloader');