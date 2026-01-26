<?php
//funciones externas
include_once("../config/helper.php");
// aquí voy a gestionar lo que reciba del formulario

// 1 recibir los datos del formulario a través de POST y los value en nuevas variables que usaré aquí
// Comprobación de términos
if(comprobarVacio($_POST["terminos"])){
    header('location:/?error=condiciones');
    die;
}else{
    $terminos = $_POST["terminos"];
}
// if(empty($_POST["terminos"])){
//     // como viene vacía, redirijo a la página de contacto
//     // echo "Hay un error pues no ha aceptado las condiciones de privacidad";
//     header('location:/?error=condiciones');
//     die;
// }else{
//     $terminos = $_POST["terminos"];
// }

// Comprobación de Captcha
$respUser = $_POST["respUser"];
$respSystem = $_POST["respSystem"];
// Vacio
if(!isset($respUser)){
    header('location:/?error=captchaVacio');
    die;
}
// No coinciden
if($respUser != $respSystem){
    header('location:/?error=captchaError');
    die;
}
// 2 comprobar que los datos son correctos
$nombre = $_POST["nombre"];
$telefono = $_POST["telefono"];
$email = $_POST["email"];
$mensaje = $_POST["mensaje"];

//Si nombre viene vacio
if(comprobarVacio($nombre)){
    header('location:/?error=nombreVacio');
    die;
}
// Si nombre es menor de 3 o mayor de 40
if(comprobarCaracteres($nombre, 3, 40)){
    header('location:/?error=nombreCaracteres');
    die;
}
// $contadorCaracteres = strlen($nombre);
// if($contadorCaracteres<3 || $contadorCaracteres>40){
//     header('location:/?error=nombreCaracteres');
//     die;
// }
// Si teléfono viene vacio
if(comprobarVacio($telefono)){
    header('location:/?error=telefonoVacio');
    die;
}

// Si el email viene vacio
if(comprobarVacio($email)){
    header('location:/?error=emailVacio');
    die;
}

//Expresión regular para comprobar formato email
if (!comprobarEmail($email)) {
    header('location:/?error=emailFormato');
    die;
}
// $patron = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
// if (!preg_match($patron, $email)) {
//     header('location:/?error=emailFormato');
//     die;
// }
// if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//     header('location:/?error=emailFormato');
//     die;
// }

// SI el mensaje viene vacio
if(comprobarVacio($mensaje)){
    header('location:/?error=mensajeVacio');
    die;
}
// Si nombre es menor de 4 o mayor de 200
if(comprobarCaracteres($mensaje, 5, 200)){
    header('location:/?error=mensajeCaracteres');
    die;    
}
// $contadorCaracteres = strlen($mensaje);
// if($contadorCaracteres<5 || $contadorCaracteres>200){
//     header('location:/?error=mensajeCaracteres');
//     die;
// }

// 3 Enviar emails

// 4 guardar los datos en una base de datos

// 5 enviar correos de aviso: a la empresa y al propio usuario

// 6  redirigir a la página de gracias
$nombreURL = urlencode($nombre);
header("location:/gracias.php?nom=$nombreURL");
die;
?>