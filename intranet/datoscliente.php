<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access.php");
//include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
//include("phpdelegates/db_array.php");
//include("phpdelegates/paginacion.php");
// llamamos las clases a utilizar
include("phpclasses/Empresa.php");
include("phpclasses/Cuenta.php");

//llamamos la clase  Data Access Object
//include("phpdao/OpmDB.php");

//creamos un DAO para obtener los datos de la empresa dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$datos = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$datos->init();
//obtenemos la informacion del usuario y creamos el objeto
 $query = "SELECT * FROM cuenta WHERE id_cuenta = '".$_SESSION['cuenta']."'";
$datos->execute_query("SELECT * FROM cuenta WHERE id_cuenta = '".$scuenta."'");// session_usr se encuentra en los inclides de sesion
$current_user = new Cuenta($datos->get_row_assoc());
$empresa = $current_user->get_id_empresa();
 $query = "SELECT * FROM empresa WHERE id_empresa = '".$current_user->get_id_empresa()."'";
$datos->execute_query($query);
$current_account = new Empresa($datos->get_row_assoc());
 $query = "SELECT * FROM fuente ";
$datos->execute_query($query);
$num_fuente = $datos->num_rows();
 $query = "select * from noticia a, asigna c where a.fecha = CURDATE() and a.id_noticia = c.id_noticia and c.id_empresa = ".$empresa;
$datos->execute_query($query);
$num_hoy = $datos->num_rows();
 $query = "select * from noticia a, asigna c where a.id_noticia = c.id_noticia and c.id_empresa = ".$empresa;
$datos->execute_query($query);
$num_total = $datos->num_rows();
 $query = "select * from noticia a, asigna c where a.id_noticia = c.id_noticia and MONTH(a.fecha) = MONTH(CURDATE()) and c.id_empresa = ".$empresa;
$datos->execute_query($query);
$num_mes = $datos->num_rows();
 $query = "select * from noticia a, noticia_int b, asigna c where a.id_noticia = b.id_noticia and a.id_noticia = c.id_noticia and
MONTH(a.fecha) = MONTH(CURDATE()) and c.id_empresa = ".$empresa;
$datos->execute_query($query);
$num_int = $datos->num_rows();
 $query = "select * from noticia a, noticia_per b, asigna c where a.id_noticia = b.id_noticia and a.id_noticia = c.id_noticia and
MONTH(a.fecha) = MONTH(CURDATE()) and c.id_empresa = ".$empresa;
$datos->execute_query($query);
$num_per = $datos->num_rows();
 $query = "select * from noticia a, noticia_rev b, asigna c where a.id_noticia = b.id_noticia and a.id_noticia = c.id_noticia and
MONTH(a.fecha) = MONTH(CURDATE()) and c.id_empresa = ".$empresa;
$datos->execute_query($query);
$num_rev = $datos->num_rows();
 $query = "select * from noticia a, noticia_rad b, asigna c where a.id_noticia = b.id_noticia and a.id_noticia = c.id_noticia and
MONTH(a.fecha) = MONTH(CURDATE()) and c.id_empresa = ".$empresa;
$datos->execute_query($query);
$num_rad = $datos->num_rows();
 $query = "select * from noticia a, noticia_tel b, asigna c where a.id_noticia = b.id_noticia and a.id_noticia = c.id_noticia and
MONTH(a.fecha) = MONTH(CURDATE()) and c.id_empresa = ".$empresa;
$datos->execute_query($query);
$num_tv = $datos->num_rows();

//en las siguientes paginas no se necesita hacer el query
if($_SERVER["PHP_SELF"] == "/intranet/index.php"
|| $_SERVER["PHP_SELF"] == "/intranet/colu-poli-detalle.php"
|| $_SERVER["PHP_SELF"] == "/intranet/busqueda.php"
|| $_SERVER["PHP_SELF"] == "/intranet/resultados.php"
|| $_SERVER["PHP_SELF"] == "/intranet/resultados_new.php"
|| $_SERVER["PHP_SELF"] == "/intranet/noticia_detalle_electronico.php"
|| $_SERVER["PHP_SELF"] == "/intranet/noticia_detalle_prensa.php"
|| $_SERVER["PHP_SELF"] == "/intranet/noticia_detalle_internet.php"
|| $_SERVER["PHP_SELF"] == "/intranet/reporte.php"
|| $_SERVER["PHP_SELF"] == "/intranet/index_new.php") { 

    // no se hace nada
}
else // hacemos query
{
$query = "select ".$pagina." AS rest from permiso where id_empresa = ".$empresa;
    $datos->execute_query($query);
    $permiso = $datos->get_row_assoc();
    $acceso = 1;
    $acceso  = $permiso['rest'];

    if($acceso ==1 ) {
        header("Location: acceso_denegado.php");
        exit;
    }
}

?>
<div align="center">
<table width="920" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="240">
	<!-- medida de la imagen del logo del cliente 190x100 pix  -->
	<img src="http://sistema.opemedios.com.mx/data/empresas/<?php echo $current_account->get_logo() ?>" alt=" <?php echo $current_account->get_nombre() ?>">
	</td>
	<td width="1" bgcolor="#5a83ae">
	<!-- no meter nada en esta columna -->
	</td>
	<!-- color de fondo del cliente -->
	<td bgcolor="#ffede1" width="679">
	<!-- datos del cliente -->
	<table width="100%" cellspacing="6" cellpadding="6" border="0">
	<tr>
		<td valign="top">
		<font class="titulo-azul">
		<b>Bienvenido: </b><?php echo $current_user->get_nombre_completo() ?><br>
		<b>Empresa: </b><?php echo $current_account->get_nombre() ?><br>
		<img src="images/trans.gif" width="1" height="5" alt="" border="0"><br>
		<font class="desarrollo2">
		<b>Noticias del mes:</b> Televisi&oacute;n: <?php echo $num_tv ?> | Radio: <?php echo $num_rad ?> | Prensa: <?php echo $num_per ?> | Revista: <?php echo $num_rev ?> | Internet: <?php echo $num_int ?> | Fuentes Monitoreadas: <?php echo $num_fuente ?>
		</font>
		
		</td>
		<td align="right" valign="top">
		<font class="desarrollo1">
		<b>Noticias de hoy: <?php echo $num_hoy ?></b><br>
		
		<img src="images/trans.gif" width="1" height="3" alt="" border="0"><br>
		<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
		<img src="images/trans.gif" width="1" height="3" alt="" border="0"><br>
		
		<b>Noticias del mes: <?php echo $num_mes ?></b><br>
		
		<img src="images/trans.gif" width="1" height="3" alt="" border="0"><br>
		<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
		<img src="images/trans.gif" width="1" height="3" alt="" border="0"><br>
		
		<b>Noticias totales: <?php echo $num_total ?></b><br>
		</td>
	</tr>
	
	</table>

	</td>
</tr>
</table></div>

<!-- <img src="images/trans.gif" width="1" height="10" alt="" border="0"><br> -->

