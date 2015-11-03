<?php
$from = "From: <prueba@opemedios.com.mx> ";
$to = "oscar..leon@iccred.com, oscar..leon@itdmx.com, oleongochar@hotmail.com, oleongocha@gmail.com";
$subject = "Prueba de correo";
$body = "Prueba de correo desde opemedios.com";

if (mail($to,$subject,$body,$from)) {
echo "MAIL 001 - OK enviado a diferentes correos";
} else {
echo "MAIL FAILED";
}
?>