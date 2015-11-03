<?php
//Pagina principal de sistema de noticias
//Josué Morado Manríquez
//
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/funciones.php");
include("phpdelegates/funciones2.php");

// llamamos las clases a utilizar
include("phpclasses/SuperNoticia.php");
include("phpclasses/Horario.php");
include("phpclasses/TarifaPrensa.php");
include("phpclasses/Archivo.php");
include("phpclasses/Seccion.php");
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
    
    <script type="text/javascript" src="InputCalendar/calendarDateInput.js">

/***********************************************
* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
* Script featured on and available at http://www.dynamicdrive.com
* Keep this notice intact for use.
***********************************************/

</script>
</head>

<body bgcolor="#d5dfef" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" class="fondo">
<div align="center">
<img src="images/trans.gif" width="1" height="25" alt=""><br>
<?php include 'top.php'; ?>
<table width="948" border="0" cellspacing="0" cellpadding="0" background="images/fondo-desa.png">
<tr>
	<td align="center">
<?php include 'datoscliente.php'; ?>
<?php include 'menu.php'; ?>
<?php 


//armamos el query para obtener las noticias a mostrar
//hacemos un switch para armar el WHERE dependiendo de la situacion,
// solo se obtendran un arreglo con las id de las noticias y el tipo de fuente q son,
//posteriormente se obtendra la informacion de cada una de ellas

// la noticia mas reciente

$query_noticias =  "SELECT
                      noticia.id_noticia AS id_noticia,
                      noticia.id_tipo_fuente AS id_tipo_fuente
                    FROM
                     asigna
                     INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                     INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
					 WHERE
                     empresa.id_empresa = ".$current_account->get_id()."
					 ORDER BY 
					 fecha DESC, id_noticia DESC
					 LIMIT 1;
					 ";

//ejecutamos query y hacemos el arreglo de id_noticia y id_tipo_fuente
$base->execute_query($query_noticias);

if($base->num_rows() == 0)
{
	$num_noticias1 = 0;
}
else
{
	$num_noticias1 = $base->num_rows();
}

$arreglo_noti1 = array();
while($row = $base->get_row_assoc())
{
    $arreglo_noti1[$row['id_noticia']]=$row['id_tipo_fuente'];
}

// las 14 noticias siguientes

$query_noticias =  "SELECT
                      noticia.id_noticia AS id_noticia,
                      noticia.id_tipo_fuente AS id_tipo_fuente
                    FROM
                     asigna
                     INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                     INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
					 WHERE
                     empresa.id_empresa = ".$current_account->get_id()."
					 ORDER BY 
					 fecha DESC, id_noticia DESC
					 LIMIT 1, 10;
					 ";

//ejecutamos query y hacemos el arreglo de id_noticia y id_tipo_fuente
$base->execute_query($query_noticias);

if($base->num_rows() == 0)
{
	$num_noticias2 = 0;
}
else
{
	$num_noticias2 = $base->num_rows();
}

$arreglo_noti2 = array();
while($row = $base->get_row_assoc())
{
    $arreglo_noti2[$row['id_noticia']]=$row['id_tipo_fuente'];
}


// las 25 noticias siguientes

$query_noticias =  "SELECT
                      noticia.id_noticia AS id_noticia,
                      noticia.id_tipo_fuente AS id_tipo_fuente
                    FROM
                     asigna
                     INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                     INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
					 WHERE
                     empresa.id_empresa = ".$current_account->get_id()."
					 ORDER BY 
					 fecha DESC, id_noticia DESC
					 LIMIT 11, 10;
					 ";

//ejecutamos query y hacemos el arreglo de id_noticia y id_tipo_fuente
//$base->execute_query($query_noticias);

/*if($base->num_rows() == 0){
	$num_noticias3 = 0;
}
else{
	$num_noticias3 = $base->num_rows();
}

$arreglo_noti3 = array();
while($row = $base->get_row_assoc()){
    $arreglo_noti3[$row['id_noticia']]=$row['id_tipo_fuente'];
}*/


?>
<?php include 'home_new.php'; ?>

	</td>
</tr>

</table><?php include 'pie.php'; ?></div>


</body>
</html><br><br>

<?php 
$base->close();
?>
