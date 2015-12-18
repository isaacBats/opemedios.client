<?php
$from = "From: <prueba@opemedios.com.mx> ";
$to = "klonate@gmail.com, daniel@carlos-villicana-davila.com.mx, prueba@opemedios.com.mx";
$subject = "Prueba de correo";
$body = "Prueba de correo desde opemedios.com";

if (mail($to,$subject,$body,$from)) {
echo "MAIL 001 - OK enviado a diferentes correos";
} else {
echo "MAIL FAILED";
}
?>