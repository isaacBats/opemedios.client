<?php
//Pagina de busqueda avanzada
//Josue Morado Manriquez
//
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
// llamamos las clases a utilizar

include("phpdao/OpmDB.php");

//iniciamos conexion a BD
$base = new OpmDB(genera_arreglo_BD());
$base->init();

//creamos un arreglo para mostrar el menu tipo de autor
$base->execute_query("SELECT * FROM tipo_autor ORDER BY descripcion");
$arreglo_tipo_autor = array();
while($tipo_autor = $base->get_row_assoc())
{
    $arreglo_tipo_autor[$tipo_autor['id_tipo_autor']] = $tipo_autor["descripcion"];
}

//creamos un arreglo para mostrar el menu sector
$base->execute_query("SELECT id_sector, nombre FROM sector WHERE activo = 1 ORDER BY nombre");
$arreglo_sectores = array();
while($sector = $base->get_row_assoc())
{
    $arreglo_sectores[$sector['id_sector']] = $sector["nombre"];
}

//creamos un arreglo para mostrar el menu genero
$base->execute_query("SELECT * FROM genero ORDER BY descripcion");
$arreglo_generos = array();
while($genero = $base->get_row_assoc())
{
    $arreglo_generos[$genero['id_genero']] = $genero["descripcion"];
}

//creamos un arreglo para mostrar el menu tendencia monitorista
$base->execute_query("SELECT * FROM tendencia");
$arreglo_tendencia = array();
while($tendencia = $base->get_row_assoc())
{
    $arreglo_tendencia[$tendencia['id_tendencia']] = $tendencia["descripcion"];
}

//creamos un arreglo para mostrar el menu tipo_fuente
$base->execute_query("SELECT * FROM tipo_fuente");
$arreglo_tipos_fuente = array();
while($tipo_fuente = $base->get_row_assoc())
{
    $arreglo_tipos_fuente[$tipo_fuente['id_tipo_fuente']] = $tipo_fuente["descripcion"];
}

// el arrgelo de temas esta mas abajo


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>B&uacute;squeda Avanzada de Noticias - Cliente OPEMEDIOS</title>
	<LINK rel=stylesheet href="estilos.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <script type="text/javascript" src="InputCalendar/calendarDateInput.js">

/***********************************************
* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
* Script featured on and available at http://www.dynamicdrive.com
* Keep this notice intact for use.
***********************************************/

</script>

<script type="text/javascript" language="javascript" src="ajax_tipos_fuente_fuentes.js"></script>
<script type="text/javascript" language="javascript" src="ajax_fuentes_secciones_busq.js"></script>

<script type="text/javascript" language="javascript">
function seleccion_tipo_fuente()
	{
		var i = document.getElementById('id_tipo_fuente').selectedIndex;
		var valor = document.getElementById('id_tipo_fuente').options[i].value;
		sndReqCat(valor);
		limpia_secciones();
	}
	function seleccion_fuente()
	{
		var i = document.getElementById('id_fuente').selectedIndex;
		var valor = document.getElementById('id_fuente').options[i].value;
		sndReqCat2(valor);
	}
	function limpia_secciones() {
		// Detect Browser

		id = parseInt('seccion');
		var IE = (document.all) ? 1 : 0;
		var DOM = 0;
		if (parseInt(navigator.appVersion) >=5) {DOM=1};

		// Grab the content from the requested "div" and show it in the "container"
		if (DOM) {
			var viewer = document.getElementById('seccion');
			//alert(viewer.innerHTML );
			viewer.innerHTML = 'Selecciona una Fuente';
		}  else if(IE) {
			document.all['seccion'].innerHTML = 'Selecciona una Fuente';
		}
	}
	
	function muestra_evitar() {
		// Detect Browser

		var IE = (document.all) ? 1 : 0;
		var DOM = 0;
		if (parseInt(navigator.appVersion) >=5) {DOM=1};

		// Grab the content from the requested "div" and show it in the "container"
		if (DOM) {
			var viewer1 = document.getElementById('evitar_label');
			var viewer2 = document.getElementById('evitar_input');
			//alert(viewer.innerHTML );
			viewer1.innerHTML = 'Evitar:';
			viewer2.innerHTML = '<input name="txt_evitar" type="text" style="width:300px; font-size:x-small;" id="txt_evitar" /><br><span style="font-size:x-small;">**Las noticias que contengan estas palabras no se mostrar&aacute;n</span>';
		}  else if(IE) {
			document.all['evitar_label'].innerHTML = 'Evitar:';
			document.all['evitar_input'].innerHTML = '<input name="txt_evitar" type="text" class="textbox1" id="txt_evitar" /><span class="label2">**Las noticias que contengan estas palabras no se mostraran</span>';
		}
	}
	
	function limpia_evitar() {
		// Detect Browser

		var IE = (document.all) ? 1 : 0;
		var DOM = 0;
		if (parseInt(navigator.appVersion) >=5) {DOM=1};

		// Grab the content from the requested "div" and show it in the "container"
		if (DOM) {
			var viewer1 = document.getElementById('evitar_label');
			var viewer2 = document.getElementById('evitar_input');
			//alert(viewer.innerHTML );
			viewer1.innerHTML = '';
			viewer2.innerHTML = '';
		}  else if(IE) {
			document.all['evitar_label'].innerHTML = '';
			document.all['evitar_input'].innerHTML = '';
		}
	}
    
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

// hasta este momento hacemos el arreglo de lso temas por que se necesitan variables de datoscliente.php

//creamos un arreglo para mostrar los temas del cliente
$base->execute_query("SELECT id_tema, nombre FROM tema where id_empresa =".$current_account->get_id());
$arreglo_temas = array();
while($tema = $base->get_row_assoc())
{
    $arreglo_temas[$tema['id_tema']] = $tema["nombre"];
}

?>

<img src="images/trans.gif" width="1" height="20" alt=""><br>
<table width="880" cellspacing="0" cellpadding="0" border="0">
<tr>
	
	<td valign="top" class="desarrollo">
	

<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
<img src="images/trans.gif" width="1" height="7" alt=""><br>
<b><font class="titulo-azul">Búsqueda Avanzada</font></b><br>
<img src="images/trans.gif" width="1" height="7" alt=""><br>
<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
<img src="images/trans.gif" width="1" height="15" alt=""><br>
	
<!-- inicia tabla de noticia -->
<form id="form2" name="form2" method="get" action="resultados.php">
<div align="center"><table width="100%" border="0" cellspacing="4" cellpadding="4">
<tr bgcolor="#FFEDE1">
	<td width="25%" align="right" class="desarrollo"><input name="busqueda" type="hidden" id="busqueda" value="true" /></td>
	<td width="75%" class="desarrollo"><p>
                      <label>
                        <input name="tipo_busqueda" type="radio" id="buscar_0" value="1" checked="checked" onClick="limpia_evitar()" />
                        Frase Completa</label> 
                      <label>
                        <input name="tipo_busqueda" type="radio" id="buscar_1" value="2" onClick="muestra_evitar()" />
                        Palabras Clave</label>
                      <br />
                    </p></td>
</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Buscar:</td>
	<td class="desarrollo">	  <input type="text" name="txt_buscar" id="txt_buscar" style="font-size:x-small ; width:300px; height:22px;">	</td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right"><div id="evitar_label" ></div></td>
	<td class="desarrollo"><div id="evitar_input"></div></td>

</tr>

<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">&nbsp;</td>
	<td class="desarrollo">&nbsp;</td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Entre:</td>
	<td class="desarrollo"><script>DateInput('fecha1', true, 'DD-MON-YYYY')</script></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Y:</td>
	<td class="desarrollo"><script>DateInput('fecha2', true, 'DD-MON-YYYY')</script></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">&nbsp;</td>
	<td class="desarrollo"><br></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Tema:</td>
	<td class="desarrollo"><select name="id_tema" id="id_tema" style="width:300px; font-size:x-small;">
                      <option value="0">**Todos los Temas**</option>
                      <?php
						foreach ($arreglo_temas as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
						}
						?>
                      </select></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Sector:</td>
	<td class="desarrollo"><select name="id_sector" id="id_sector" style="width:300px; font-size:x-small;">
                      <option value="0">**Todos los Sectores**</option>
                      <?php
						foreach ($arreglo_sectores as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
						}
						?>
                      </select>
                      </td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Género:</td>
	<td class="desarrollo">
    <select name="id_genero" id="id_genero" style="width:300px; font-size:x-small;">
                      <option value="0">**Todos los Generos**</option>
                      <?php
						foreach ($arreglo_generos as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.utf8_encode($label).'</option>';
						}
						?>
                      </select>
    </td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Tipo de Autor:</td>
	<td class="desarrollo"><select name="id_tipo_autor" id="id_tipo_autor" style="width:300px; font-size:x-small;">
                      <option value="0">**Todos los Tipos de Autor**</option>
                      <?php
						foreach ($arreglo_tipo_autor as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
						}
						?>
                      </select></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Tendencia:</td>
	<td class="desarrollo"><select name="id_tendencia" id="id_tendencia" style="width:300px; font-size:x-small;">
                      <option value="0">**Todas las Tendencias**</option>
                      <?php
						foreach ($arreglo_tendencia as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
						}
						?>
                      </select></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">&nbsp;</td>
	<td class="desarrollo"><br></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Tipo de Fuente:</td>
	<td class="desarrollo"><select name="id_tipo_fuente" id="id_tipo_fuente" onChange="seleccion_tipo_fuente()" style="width:300px; font-size:x-small;">
                      <option value="0">**Todos los Tipos de Fuente**</option>
                      <?php
							foreach ($arreglo_tipos_fuente as $value => $label)
							{
								echo '<option value="'.$value.'"';  echo'>'.utf8_encode($label).'</option>';
							}
							?>
                    </select></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Fuente:</td>
	<td class="desarrollo"><div id="fuente" style="font-size:x-small">Selecciona un Tipo de Fuente</div></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Sección:</td>
	<td class="desarrollo"><div id="seccion" style="font-size:x-small">Selecciona una Fuente</div></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">&nbsp;</td>
	<td class="desarrollo"><br></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo" align="right">Acción:</td>
	<td class="desarrollo"><p>
                      <label>
                        <input name="accion" type="radio" id="accion_0" value="1" checked="checked"/>
                        Mostrar Resultados de B&uacute;squeda</label> 
                      <label>
                        <input name="accion" type="radio" id="accion_1" value="2"/ disabled>
                        Graficar Resultados (En Construcción)</label>
                      <br />
                    </p></td>

</tr>
<tr bgcolor="#FFEDE1">
	<td class="desarrollo"></td>
	<td class="desarrollo"><div align="right"><input type="submit" value="Ejecutar B&uacute;squeda &gt;&gt;"></div></td>

</tr>
</table></div>
</form>
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


<?php echo $base->close(); ?>