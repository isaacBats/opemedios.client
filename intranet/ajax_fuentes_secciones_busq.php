<?php
/**
 * Obtiene las secciones de una tienda
 */
$fuente	= $_REQUEST['f'];

include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");

//creamos un DAO
//recibe como parametro el resultado de la funcion
$base_ajax = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base_ajax->init();

if($fuente == 0)
{
   // $base_ajax->execute_query("SELECT `id_seccion`,`nombre` FROM `seccion` WHERE `activo` = 1;");
}
else
{
    $base_ajax->execute_query("SELECT `id_seccion`,`nombre` FROM `seccion` WHERE `id_fuente` = ".$fuente." ;");	// Consultamos todas las secciones del formato dado
}

$resp = array();
while ($fila = $base_ajax->get_row())	// $fila[0] = id_seccion  $fila[1] = nombre
{
    $resp[]= $fila;
}

if(count($resp)<=0)
{
    $output = "$fuente|<span class=\"label2\"><strong>No hay secciones en esa Fuente</strong></span>";
    echo $output;
    return true;
}
else
{
    $salto="<br /><br />";

    $new_back = array();

    $new_back[] .= '<select name="id_seccion" id="id_seccion" style="width:300px; font-size:x-small;">';
	$new_back[] .= '<option value="0">**Todas las Secciones**</option>';
    foreach($resp as $sub)
    {
        $new_back[] .= '<option value="'.$sub[0].'" '.$add.'>'.$sub[1].'</option>';
    }
    $new_back[] .= '</select>';

    $allnewback = join("", $new_back);

    // ========================

    //name of the div id to be updated | the html that needs to be changed
    $output = "$fuente|$allnewback";
    echo $output;
    return true;
}

$base_ajax->close();
?>
