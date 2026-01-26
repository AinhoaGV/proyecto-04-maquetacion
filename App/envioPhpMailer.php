<?php

// Este código lo tengo que configurar para que haga el envío desde una cuenta de correo oficial del host.
// Como este proyecto está colgado en el dominio webda.eus, el correo que se use para enviar correos desde esta web, a través del phpMailer, debe ser de ese mismo dominio. Ejemplo: no-reply@webda.eus
// En este archivo configuraré usuario, el server y la contraseña de ese correo.
  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
  
require '../vendor/autoload.php'; /* modificar */
  
$mail = new PHPMailer(true);
  
try {
    $mail->SMTPDebug = 0;                                       
    $mail->isSMTP();
    $mail->Host= $_ENV['HOST']; /* modificar */                
    $mail->SMTPAuth   = true;                        
    $mail->Username = $_ENV['USERNAME']; /* modificar */              
    $mail->Password = $_ENV['PASS']; /* modificar */                       
    $mail->SMTPSecure = 'ssl';                              
    $mail->Port = 465;  
  
    $mail->setFrom($correoEmisor, $nombreEmisor); //varibles
    $mail->addAddress($correoDestinatario, $nombreDestinatario); //varibles
    /* $mail->addCC('info@webda.eus');//copia
    $mail->addBCC('info@webda.eus');//copia oculta */
       
    $mail->isHTML(true);
    $mail->CharSet = PHPMailer::CHARSET_UTF8;                    
    $mail->Subject = $asunto; //varibles
    $mail->Body = $cuerpo; //variables
    /* $mail->AddEmbeddedImage('../imags-web/mundo2.png', 'reflogotipo'); */
    $mail->AltBody = 'Body in plain text for non-HTML mail clients';

    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        //echo $aviso; //SACAMOS AVISO EN LA WEB DEL ENVÍO DEL CORREO
    }
    
} catch (Exception $e) {
    echo "El mensaje no se ha enviado. Mailer Error: {$mail->ErrorInfo}";
}
  
?>