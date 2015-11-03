<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/paginacion.php");
// llamamos las clases a utilizar
include("phpclasses/ColumnaFinanciera.php");
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

$url = $_SERVER['PHP_SELF']."?" .$_SERVER['QUERY_STRING'];
$url = str_replace("&pagina=".$_GET['pagina'],"",$url);
$limite = paginacion_init($_GET["pagina"],$registros);
$date = $_GET['date'];
if(!isset($date)){
 $date = "CURDATE()";
 $thisdate = "'".date("Y-m-d" ,time())."'";
}else{
   $thisdate = "'".$date."'";
   $date = "'".$date."'";
   }
//-----------------------------------------------------------------------
$query = "SELECT a.fecha,a.titulo,a.autor, a.imagen_jpg imagen,a.id_columna_financiera id,b.nombre fuente
FROM columna_financiera a, fuente b
where a.id_fuente = b.id_fuente and a.fecha = ".$date;
$base->execute_query($query);
$columna = 1;
$pagina = 'col_fin';
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>OPEMEDIOS</title>
	<LINK rel=stylesheet href="estilos.css">
    <script type="text/javascript" src="InputCalendar/calendarDateInput.js"></script>
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
<b><font class="titulo-azul">Columnas Financieras</font></b><br>
<?php if($acceso == 3){
echo '<form name="form1" method="get" action="colu-fina.php">
  <table width="200" border="0">
    <tr>';
 echo  "<td><script>DateInput('date', true, 'YYYY-MM-DD',".$thisdate.")</script></td>
      <td><label>";
 echo'       <input type="submit" name="buscar" id="buscar" value="buscar">
      </label></td>
    </tr>
  </table>
</form>';} ?>
<img src="images/trans.gif" width="1" height="7" alt=""><br>
<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
<img src="images/trans.gif" width="1" height="15" alt=""><br>
	
<!-- inicia tabla de noticia -->
<div align="center">
</div>

	<!-- termina tabla de noticia -->
        <table border="1" align="center" cellpadding="0" cellspacing="0">
<?php

                                    while($row_query = $base->get_row_assoc())
                                    {

                                    if($columna == 1) {
                                            echo "<tr>";
                                        }
                                        echo'	<td width="60">
                                                <a href="image.php?pagina='.$pagina.'&id='.$row_query['imagen'].'"><img src="http://sistema.operamedios.com.mx/data/thumbs/'.$row_query['imagen'].'_pp.jpg" alt="" width="60" height="70" border="0"></a><br>
                                                </td>
                                                <td valign="top" class="desarrollo">
                                                <b><a href="colu-fina-detalle.php?id='.$row_query['id'].'" class="desarrollo">'.$row_query['titulo'].'</a></b><br>
                                                '.$row_query['autor'].'<br>
                                                '.$row_query['fuente'].'<br>
                                                </td>';
                                        $columna ++;
                                        if($columna == 6) {
                                            echo"</tr>";
                                            $columna = 1;
                                        }
                                    }
                                    if($columna != 6 && $columna != 1) {
                                        echo"</tr>";
                                    }
                                    ?>
              </table>
    <div align="center">
                                    <?php echo paginacion($url,$limite[1],$num_row,$registros);
                                        $base->free_result();
                                        $base->close();?>
    </div>
	</tr>
  </table>
	</td>
	</tr>
	</table>
<?php include 'pie.php'; ?></div>
</body>
</html><br><br>
