<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/paginacion.php");
// llamamos las clases a utilizar
include("phpclasses/ColumnaPolitica.php");
include("phpclasses/Usuario.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//creamos un DAO para obtener los datos de la empresa dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
// mandamos a llamar el codigo donde se obtienen los clientes  ya con paginacion
//declaramos las variables de paginacion

//---------------------------------------------------------------------
$query = "SELECT a.fecha,a.titulo,a.autor, a.imagen_jpg imagen,a.id_columna_politica id,b.nombre fuente,
a.contenido, a.archivo_pdf archivo
FROM columna_politica a, fuente b
where a.id_fuente = b.id_fuente and a.id_columna_politica = ".$_GET['id'];
$base->execute_query($query);
$col = $base->get_row_assoc();
$pagina = 'col_pol';
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Columnas Politicas - OPEMEDIOS</title>
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
<?php include 'datoscliente.php'; ?>
<?php include 'menu.php'; ?>

<img src="images/trans.gif" width="1" height="20" alt=""><br>
<table width="880" cellspacing="0" cellpadding="0" border="0">
<tr>
	
	<td valign="top" class="desarrollo">
	

<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
<img src="images/trans.gif" width="1" height="7" alt=""><br>
<b><font class="titulo-azul">Columnas Políticas</font></b><br>
<img src="images/trans.gif" width="1" height="7" alt=""><br>
<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
<img src="images/trans.gif" width="1" height="15" alt=""><br>
	
<!-- inicia tabla de noticia -->
<div align="center">
<table width="100%" border="0" cellspacing="4" cellpadding="4">
<tr>
	<td width="120" valign="top" class="desarrollo1">
	<a target="_blank" href="<?php echo 'image.php?pagina='.$pagina.'&id='.$col['imagen'];?>"><img src="http://sistema.opemedios.com.mx/data/thumbs/<?php echo $col['imagen'] ?>_pp.jpg" alt="" width="120" height="140" border="0"></a><br>
	<img src="images/trans.gif" width="1" height="10" alt=""><br>
	<a href="http://sistema.opemedios.com.mx/data/col_pol/pdf/<?php echo $col['archivo']  ?>">Descargar Documento</a>	</td>
	<td valign="top" class="desarrollo">
	<b><?php echo utf8_encode($col['titulo']) ?><br>
	<?php echo utf8_encode($col['autor']) ?><br>
	<?php echo utf8_encode($col['fuente']) ?></b><br><br>
	
<div align="justify"><?php echo utf8_encode($col['contenido']) ?> </div><br><br>




	</td>

	</tr>
	



</table></div>

	<!-- termina tabla de noticia -->


	



	

	  
	  
	</td>
	</tr>
	</table>
	<img src="images/trans.gif" width="1" height="30" alt=""><br>
	</td>
</tr>

</table>

	</td>
</tr>

</table><?php include 'pie.php'; ?></div>


</body>
</html><br><br>
