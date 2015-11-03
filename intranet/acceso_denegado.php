<?php
//Pagina principal de sistema de noticias
//Josué Morado Manríquez
//
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/funciones.php");
// llamamos las clases a utilizar
include("phpclasses/SuperNoticia.php");
include("phpclasses/Horario.php");
include("phpclasses/TarifaPrensa.php");
//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//iniciamos conexion a BD
$base = new OpmDB(genera_arreglo_BD());
$base->init();



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Sistema de Noticias OPEMEDIOS</title>
	<LINK rel=stylesheet href="estilos.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body bgcolor="#d5dfef" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class="fondo">
<div align="center">
<img src="images/trans.gif" width="1" height="25" alt=""><br>
<?php include 'top.php'; ?>
<table width="948" border="0" cellspacing="0" cellpadding="0" background="images/fondo-desa.png">
<tr>
	<td align="center">
<?php //include 'datoscliente.php'; ?>
<?php include 'menu.php'; ?>

<br><br><br><br><br><br>
<font class="desarrollo-rojo">Actualmente no tiene acceso a esta característica. Favor de contactar a su ejecutivo de cuenta para activarla</font>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	</td>
</tr>

</table><?php include 'pie.php'; ?></div>


</body>
</html><br><br>

<?php 
$base->close();
?>
