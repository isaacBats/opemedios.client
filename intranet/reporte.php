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
//creamos un arreglo para mostrar el menu sector
//$base->execute_query("SELECT id_sector, nombre FROM sector WHERE activo = 1 ORDER BY nombre");
//$arreglo_sectores = array();
//while($sector = $base->get_row_assoc()){
//    $arreglo_sectores[$sector['id_sector']] = $sector["nombre"];
//}

//creamos un arreglo para mostrar el menu genero
//$base->execute_query("SELECT * FROM genero ORDER BY descripcion");
//$arreglo_generos = array();
//while($genero = $base->get_row_assoc()){
//    $arreglo_generos[$genero['id_genero']] = $genero["descripcion"];
//}

//creamos un arreglo para mostrar el menu tendencia 
$base->execute_query("SELECT * FROM tendencia");
$arreglo_tendencia = array();
while($tendencia = $base->get_row_assoc()){
    $arreglo_tendencia[$tendencia['id_tendencia']] = $tendencia["descripcion"];
}

//creamos un arreglo para mostrar el menu tipo_fuente
$base->execute_query("SELECT * FROM tipo_fuente");
$arreglo_tipos_fuente = array();
while($tipo_fuente = $base->get_row_assoc()){
    $arreglo_tipos_fuente[$tipo_fuente['id_tipo_fuente']] = $tipo_fuente["descripcion"];
}

//obtenemos la informacion del usuario y creamos el objeto
$datos->execute_query("SELECT * FROM cuenta WHERE id_cuenta = '".$scuenta."'");// session_usr se encuentra en los inclides de sesion
$current_user = new Cuenta($datos->get_row_assoc());
$id_empresa = $current_user->get_id_empresa();

$query1 = "SELECT id_tema, nombre FROM tema where id_empresa = ".$id_empresa . " order by nombre";
$base->execute_query($query1);
$arreglo_temas = array();
while($tema = $base->get_row_assoc()){
    $arreglo_temas[$tema['id_tema']] = $tema["nombre"];
}
$query = "SELECT * FROM empresa WHERE id_empresa = ".$id_empresa;
$base->execute_query($query);
$empresa = $base->get_row_assoc();
$type_usr = 'Cliente';
?>

<img src="images/trans.gif" width="1" height="20" alt=""><br>
<table width="890" cellspacing="0" cellpadding="0" border="0">
<tr>
	
	<td valign="top" class="desarrollo">

<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
<img src="images/trans.gif" width="1" height="7" alt=""><br>
<b><font class="titulo-azul">&nbsp;&nbsp;&nbsp;Reporte de Noticias</font></b>

	<table width="880" border="0" align="center" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
                <td width="880" height="350" valign="top" background="images/images/BackGround_02bg.jpg">
					<table width="880" border="0">                        
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3"><hr /></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1">Seleccione los Parámetros del Reporte</td>
						</tr>
                    </table>
              <form id="form2" name="form2" method="GET" action="crear_reporte_xls_intranet.php">
                <table width="880" border="0">
                  <tr>
                    <td width="47">&nbsp;</td>
                    <td width="94">&nbsp;</td>
                    <td width="123" class="label3"><div align="right"><?php echo $type_usr; ?>
                      <input name="busqueda" type="hidden" id="busqueda" value="true" />
                    </div></td>
                    <td width="718"><?php echo $empresa['nombre'];echo $usuario['nombre']; ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Entre:</div></td>
                    <td><script>DateInput("fecha1", true, "YYYY-MM-DD") </script></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Y:</div></td>
                    <td>
					<script>DateInput("fecha2", true, "YYYY-MM-DD") </script>
						<input name="row_id" type="hidden" value="<?php echo $id_empresa; ?>" />
						<input name="type" type="hidden" value="cliente" />
						<input name="id_sector" type="hidden" value="0"/>
						<input name="id_genero" type="hidden" value="0"/>
						<input name="id_tipo_autor" type="hidden" value="0"/>
						
						
					</td>
                  </tr>
                  <?php if($type_usr == 'Cliente'){ ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Tema:</div></td>
                    <td><select name="id_tema" class="combo3" id="id_tema">
                      <option value="0">**Todos los Temas**</option>
                      <?php
						foreach ($arreglo_temas as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
						}
                      ?>
                    </select></td>
                  </tr><?php } ?>                 
                                    
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Tendencia:</div></td>
                    <td><label>
                      <select name="id_tendencia_monitorista" class="combo3" id="id_tendencia_monitorista">
                      <option value="0">**Todas las Tendencias**</option>
                      <?php
							foreach ($arreglo_tendencia as $value => $label)
							{
								echo '<option value="'.$value.'"';  echo'>'.utf8_encode($label).'</option>';
							}
							?>
                    </select>
                    </label></td>
                  </tr>                 
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Tipo de Fuente:</div></td>
                    <td><label>
                      <select name="id_tipo_fuente" class="combo3" id="id_tipo_fuente" onchange="seleccion_tipo_fuente()">
                      <option value="0">**Todos los Tipos de Fuente**</option>
                      <?php
							foreach ($arreglo_tipos_fuente as $value => $label)
							{
								echo '<option value="'.$value.'"';  echo'>'.utf8_encode($label).'</option>';
							}
							?>
                    </select>
                    </label></td>
                  </tr>                  
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right"></div></td>
                    <td><div align="center">
                      <label>
                      <input type="submit" name="buscar2" id="buscar2" value="Generar Reporte" />
                      </label>
                    </div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right"></div></td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                  </form>
              </td>
            </tr>
    </table>


<?php include 'pie.php'; ?></div>
</body>
</html><br><br>

<?php 
$base->close();
?>
