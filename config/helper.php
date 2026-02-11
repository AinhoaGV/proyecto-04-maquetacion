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