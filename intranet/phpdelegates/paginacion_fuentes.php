<?php
/* 
 * Codigo para manejar paginacion de resultados de un query
 */


if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


$currentPage = $_SERVER["PHP_SELF"];

$maxRows = 30;
$pageNum = 0;
if (isset($_GET['pageNum'])) {
  $pageNum = $_GET['pageNum'];
}
$startRow = $pageNum * $maxRows;

$parametro = "-1";

if (isset($_GET['txt_buscar'])) {
  $parametro = $_GET['txt_buscar'];
}

$query_fuentes = sprintf("SELECT * FROM fuente WHERE (nombre LIKE %s OR empresa LIKE %s ) ORDER BY nombre", GetSQLValueString("%".$parametro."%", "text"),GetSQLValueString("%".$parametro."%", "text"));
$query_limit_fuentes = sprintf("%s LIMIT %d, %d", $query_fuentes, $startRow, $maxRows);

$base->execute_query($query_limit_fuentes);

//ahora metemos el resultado en un arreglo de objetos empresa
if($base->num_rows()>0)
{
    $arreglo_fuentes = array();
    while($row_fuentes = $base->get_row_assoc())
    {
        $fuente = new Fuente($row_fuentes);
        $arreglo_fuentes[$fuente->get_id()] = $fuente;
        //echo $arreglo_empresas[$empresa->get_id()]->get_nombre();  // para ver el resultado
    }
}



if (isset($_GET['totalRows']))
{
  $totalRows = $_GET['totalRows'];
}
else
{
    $base->execute_query($query_fuentes);
    $totalRows = $base->num_rows();
}

$totalPages = ceil($totalRows/$maxRows)-1;

$queryString = "";
if (!empty($_SERVER['QUERY_STRING']))
{
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param)
    {
        if (stristr($param, "pageNum") == false &&
            stristr($param, "totalRows") == false)
        {
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) {
        $queryString = "&" . htmlentities(implode("&", $newParams));
    }
}
$queryString = sprintf("&totalRows=%d%s", $totalRows, $queryString);

?>
