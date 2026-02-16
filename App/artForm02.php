<?php 
require_once '../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

//funciones externas
include_once("../config/helpers.php");

// aquí voy a gestionar lo que reciba del formulario
$nombre = $_POST["nombre"];
$telefono = $_POST["telefono"];
$email = $_POST["email"];
$mensaje = $_POST["mensaje"];
// 1 recibir los datos del formulario a través de POST y los value en nuevas variables que usaré aquí
// Comprobación de términos
if(!isset($_POST["terminos"]) || comprobarVacio($_POST["terminos"])){
    enviarRespuestaAsincrona("Debes aceptar las condiciones de envío", true, "terminos");
    die;
}

// Comprobación de Captcha
$respUser = $_POST["respUser"];
$respSystem = $_POST["respSystem"];
// Vacio
if(!isset($respUser)){
    enviarRespuestaAsincrona("Debes rellenar el captcha", true, "captcha");
    die;
}
// No coinciden
if($respUser != $respSystem){
    enviarRespuestaAsincrona("El captcha no es correcto", true, "captcha");
    die;
}
// 2 comprobar que los datos son correctos
$ip = $_SERVER['REMOTE_ADDR']; // guardo la IP del usuario que envía el formulario
$fecha = date('Y-m-d H:i:s'); // guardo la fecha y hora del envío del formulario

//Si nombre viene vacio
if(comprobarVacio($nombre)){
    enviarRespuestaAsincrona("No puedes dejar el nombre vacío", true, "nombre");
    die;
}
// Si nombre es menor de 3 o mayor de 40
if(comprobarCaracteres($nombre, 3, 40)){
    enviarRespuestaAsincrona("El nombre debe tener entre 3 y 40 caracteres", true, "nombre");
    die;
}

// Si teléfono viene vacio
if(comprobarVacio($telefono)){
    enviarRespuestaAsincrona("Debes rellenar el teléfono", true, "telefono");
    die;
}

// Si el email viene vacio
if(comprobarVacio($email)){
    enviarRespuestaAsincrona("Debes rellenar el email", true, "email");
    die;
}

//Expresión regular para comprobar formato email
if (!comprobarEmail($email)) {
    enviarRespuestaAsincrona("El email no tiene un formato válido", true, "email");
    die;
}

// SI el mensaje viene vacio
if(comprobarVacio($mensaje)){
    enviarRespuestaAsincrona("No puedes dejar el mensaje vacío", true, "mensaje");
    die;
}
// Si nombre es menor de 4 o mayor de 200
if(comprobarCaracteres($mensaje, 5, 200)){
    enviarRespuestaAsincrona("El mensaje debe tener entre 5 y 200 caracteres", true, "mensaje");
    die;    
}

// 3 Enviar emails
$urlWeb = "http://localhost:3000";
$correoEmisor = $_ENV["EMAIL_WEB"];
$nombreEmisor = "Web Panadería";
$correoDestinatario = $_ENV["EMAIL_ADMIN"];
$nombreDestinatario = "Admin de la web";
$asunto = "Has recibido una nueva consulta en la web de $nombre";

$html = file_get_contents('./templates/artForm01.html');

// TODO: cambiar variables y template y duplicar para usuario

$vars = [
    '{urlWeb}' => $urlWeb,
    '{asunto}' => $asunto,
    '{titulo}' => "Has recibido un correo pidiendo información de $nombre",
    '{explicacion}' => "A continuación, te mostramos los datos de la persona interesada:",
    '{nombre}' => $nombre,
    '{telefono}' => $telefono,
    '{email}' => $email,
    '{mensaje}' => $mensaje,
    '{responder}' => "Procura responder dentro del plazo de 2 días",
    '{fecha}' => $fecha
];
$cuerpo = str_replace(array_keys($vars), array_values($vars), $html);

include('./envioPhpMailer.php');

// 4 guardar los datos en una base de datos
// configuramos la conexión en $con
$con = mysqli_connect($_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASS"], $_ENV["DB_NAME"]);
//si la conexión es false sacamos error
if($con === false){
    error_log("Error de conexión a la base de datos: " . mysqli_connect_error());
}else{
    // si la conexión es correcta, continuamos
    $con->set_charset("utf8mb4");
    $sql = "INSERT INTO consultas (nombre, telefono, email, mensaje, ip, fecha) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    // ejecutamos el inser del registro en la tabla consultas de la db con prepare
    if($stmt===false){
        error_log("Error al preparar la consulta: " . mysqli_error($con));
    }else{
        //inserción definitiva en la DB
        mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $telefono, $email, $mensaje, $ip, $fecha);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    //salimos
    mysqli_close($con);
}

// 5 enviar correos de aviso: a la empresa y al propio usuario
$urlWeb = "http://localhost:3000";
$correoEmisor = $_ENV["EMAIL_WEB"];
$nombreEmisor = "Web Panadería";
$correoDestinatario = $email;
$nombreDestinatario = $nombre;
$asunto = "Gracias por contactar con nosotros, $nombre";

$html = file_get_contents('./templates/artForm01.html');

$vars = [
    '{urlWeb}' => $urlWeb,
    '{asunto}' => $asunto,
    '{titulo}'   => "Hemos recibido tu consulta, $nombre",
    '{explicacion}' => "A continuación, te mostramos los datos que nos has facilitado:",
    '{nombre}' => $nombre,
    '{telefono}' => $telefono,
    '{email}' => $email,
    '{mensaje}' => $mensaje,
    '{responder}' => "Te responderemos dentro del plazo de 2 días",
    '{fecha}' => $fecha
];
$cuerpo = str_replace(array_keys($vars), array_values($vars), $html);

include('./envioPhpMailer.php');

// 6  redirigir a la página de index para mostrar un mensaje de envío ok en vez de el formulario
enviarRespuestaAsincrona("El formulario se ha enviado correctamente, ".$nombre, false, "");
die;

