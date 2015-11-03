<?php
//Pagina principal de sistema de noticias
//Josué Morado Manríquez
//
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//iniciamos conexion a BD
$base = new OpmDB(genera_arreglo_BD());
$base->init();

$base->execute_query("SELECT id_fuente FROM fuente WHERE id_tipo_fuente = 2");

$arreglo = array();

while($row = $base->get_row_assoc())
{
	$arreglo[].= $row['id_fuente'];
}

foreach($arreglo as $fuente)
{
	$base->execute_query("INSERT INTO seccion (nombre, descripcion, activo, id_fuente) VALUES ('GENERAL', 'Seccion GENERAL', 1, ".$fuente.")");
}
echo "script ejecutado";
$base->close;
?>


