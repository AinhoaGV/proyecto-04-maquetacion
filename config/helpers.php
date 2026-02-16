<?php
//recibir el valor del parámetro y comproba si es vacio o no, y devolver true si est vacío o y falde en caso contrario
function comprobarVacio($param1){
    if(empty($param1)){
        return true;
    }else{
        return false;
    }
}
// Una función para comparar si es mayor que un valor y menor que otro valor y devolver false si no cumple esa condición y true si la cumple
function comprobarCaracteres($campo, $min, $max){
    $caracteres = strlen($campo);
    if($caracteres<$min || $caracteres>$max){
        return true;
    }else{
        return false;
    }
}

// función para comprobar si la estructura del correo recibido es acorde a la expresión regulasr. En el caso de que sea diferente devolveremos false y si es correcta devolvemos true.
function comprobarEmail($email){
    $patron = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    return preg_match($patron, $email);
}

function enviarRespuestaAsincrona($mensaje, $fallo, $campo){

    // creación de array asociativo
    $arrayRespuesta = array(
        "mensaje" => $mensaje,
        "fallo" => $fallo,
        "campo" => $campo
    );

    // crear un json del array
    $jsonDelArray = json_encode($arrayRespuesta);

    //devolvemos el json al cliente
    echo $jsonDelArray;
    die;
}

// Indica si estamos en modo desarrollo (APP_ENV=dev).
function vite_is_dev(){
    $env = strtolower($_ENV['APP_ENV'] ?? getenv('APP_ENV') ?? '');
    return in_array($env, ['dev', 'development', 'local'], true);
}

// Devuelve el prefijo base del proyecto segun la URL actual.
// Ej: /proyecto04/index.php -> /proyecto04
function vite_base_path(){
    $script = $_SERVER['SCRIPT_NAME'] ?? '/';
    $dir = str_replace('\\', '/', dirname($script));
    return rtrim($dir, '/');
}

// Devuelve URL publica de assets estaticos (public/ en dev, dist/ en prod).
function vite_public_url($path, $manifestPath = null){
    $root = dirname(__DIR__);
    $manifestPath = $manifestPath ?? ($root . '/dist/manifest.json');
    $basePath = vite_base_path();
    $useDist = is_readable($manifestPath) && !vite_is_dev();
    $prefix = $useDist ? ($basePath . '/dist/') : ($basePath . '/');
    $path = ltrim($path, '/');
    return $prefix . $path;
}

// Carga los assets compilados por Vite usando manifest.json (produccion).
// Si no existe el manifest, usa /src/main.js (desarrollo con Vite).
function vite_assets($entry = 'src/main.js', $assetBase = null, $manifestPath = null){
    $root = dirname(__DIR__);
    $manifestPath = $manifestPath ?? ($root . '/dist/manifest.json');
    $assetBase = $assetBase ?? (vite_base_path() . '/dist/');

    if (vite_is_dev()) {
        // En dev cargamos directamente la entrada de Vite para HMR
        $entryPath = '/' . ltrim($entry, '/');
        echo '<script type="module" src="' . $entryPath . '"></script>' . PHP_EOL;
        return;
    }

    if (is_readable($manifestPath)) {
        $json = file_get_contents($manifestPath);
        $manifest = json_decode($json, true);

        if (is_array($manifest) && isset($manifest[$entry])) {
            $item = $manifest[$entry];

            if (!empty($item['css']) && is_array($item['css'])) {
                foreach ($item['css'] as $cssFile) {
                    echo '<link rel="stylesheet" href="' . $assetBase . $cssFile . '">' . PHP_EOL;
                }
            }

            if (!empty($item['file'])) {
                echo '<script type="module" src="' . $assetBase . $item['file'] . '"></script>' . PHP_EOL;
            }
        }
        return;
    }

    // Fallback si no hay manifest (no deberia pasar en produccion)
    $entryPath = ltrim($entry, '/');
    echo '<script type="module" src="/' . $entryPath . '"></script>' . PHP_EOL;
}