<?php
//funciones externas
include_once("../config/helpers.php");

require_once '../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

// aquí voy a gestionar lo que reciba del formulario
$nombre = $_POST["nombre"];
$telefono = $_POST["telefono"];
$email = $_POST["email"];
$mensaje = $_POST["mensaje"];
// 1 recibir los datos del formulario a través de POST y los value en nuevas variables que usaré aquí
// Comprobación de términos
if(comprobarVacio($_POST["terminos"])){
    header("location:../index.php?error=aceptar&campo=terminos&nombre=$nombre&telefono=$telefono&email=$email&mensaje=$mensaje#artForm01");
    die;
}else{
    $terminos = $_POST["terminos"];
}
// if(empty($_POST["terminos"])){
//     // como viene vacía, redirijo a la página de contacto
//     // echo "Hay un error pues no ha aceptado las condiciones de privacidad";
//     header('location:../index.php?error=condiciones');
//     die;
// }else{
//     $terminos = $_POST["terminos"];
// }

// Comprobación de Captcha
$respUser = $_POST["respUser"];
$respSystem = $_POST["respSystem"];
// Vacio
if(!isset($respUser)){
    header("location:../index.php?error=vacio&campo=captcha&nombre=$nombre&telefono=$telefono&email=$email&mensaje=$mensaje#artForm01");
    die;
}
// No coinciden
if($respUser != $respSystem){
    header("location:../index.php?error=nocoincide&campo=captcha&nombre=$nombre&telefono=$telefono&email=$email&mensaje=$mensaje#artForm01");
    die;
}
// 2 comprobar que los datos son correctos
$ip = $_SERVER['REMOTE_ADDR']; // guardo la IP del usuario que envía el formulario
$fecha = date('Y-m-d H:i:s'); // guardo la fecha y hora del envío del formulario

//Si nombre viene vacio
if(comprobarVacio($nombre)){
    header('location:../index.php?error=vacio&campo=nombre');
    die;
}
// Si nombre es menor de 3 o mayor de 40
if(comprobarCaracteres($nombre, 3, 40)){
    header("location:../index.php?error=caracteres&campo=nombre&nombre=$nombre&telefono=$telefono&email=$email&mensaje=$mensaje#artForm01");
    die;
}
// $contadorCaracteres = strlen($nombre);
// if($contadorCaracteres<3 || $contadorCaracteres>40){
//     header('location:../index.php?error=nombreCaracteres');
//     die;
// }
// Si teléfono viene vacio
if(comprobarVacio($telefono)){
    header("location:../index.php?error=vacio&campo=telefono&nombre=$nombre&telefono=$telefono&email=$email&mensaje=$mensaje#artForm01");
    die;
}

// Si el email viene vacio
if(comprobarVacio($email)){
    header("location:../index.php?error=vacio&campo=email&nombre=$nombre&telefono=$telefono&email=$email&mensaje=$mensaje#artForm01");
    die;
}

//Expresión regular para comprobar formato email
if (!comprobarEmail($email)) {
    header("location:../index.php?error=formato&campo=email&nombre=$nombre&telefono=$telefono&email=$email&mensaje=$mensaje#artForm01");
    die;
}
// $patron = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
// if (!preg_match($patron, $email)) {
//     header('location:../index.php?error=emailFormato');
//     die;
// }
// if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//     header('location:../index.php?error=emailFormato');
//     die;
// }

// SI el mensaje viene vacio
if(comprobarVacio($mensaje)){
    header("location:../index.php?error=vacio&campo=mensaje&nombre=$nombre&telefono=$telefono&email=$email&mensaje=$mensaje#artForm01");
    die;
}
// Si nombre es menor de 4 o mayor de 200
if(comprobarCaracteres($mensaje, 5, 200)){
    header("location:../index.php?error=caracteres&campo=mensaje&nombre=$nombre&telefono=$telefono&email=$email&mensaje=$mensaje#artForm01");
    die;    
}
// $contadorCaracteres = strlen($mensaje);
// if($contadorCaracteres<5 || $contadorCaracteres>200){
//     header('location:../index.php?error=mensajeCaracteres');
//     die;
// }

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
$nombreURL = urlencode($nombre);
header("location:../index.php?envio=ok&nom=$nombreURL#artForm01");
die;
?>