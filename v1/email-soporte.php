<?php
require '../config/cors.php';
require '../vendor/autoload.php';

$input = json_decode(file_get_contents('php://input'), true);

$nombre= $input['nombre'];
$email = $input['email'];
$asunto = $input['asunto'];
$mensaje = $input['mensaje'];

//
$to = "soportepatrimonio@gmail.com";
$subject="envío desde php de $email";
$body=$mensaje;
$headers = "From: $email";

if (mail($to, $subject, $body, $headers)) {
    echo "Correo electrónico enviado correctamente";
} else {
    echo "Error al enviar el correo electrónico";
}
?>
