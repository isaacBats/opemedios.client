<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include("phpdelegates/db_array.php");
//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");
include("phpclasses/Cuenta.php");

$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
//$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
// $account = new Cuenta($base->get_row_assoc());

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($base, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  $theValue = $base->getConnection()->real_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "long":
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
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "id_tipo_usuario";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "../index.shtml?mensaje=denegado";
  $MM_redirecttoReferrer = false;

  $LoginRS__query=sprintf("SELECT id_cuenta, username, password, activo FROM cuenta WHERE username=%s AND password=%s and activo = 1",  // Se le añadio el campo activo
  GetSQLValueString($base, $loginUsername, "text"), GetSQLValueString($base, $password, "text"));

  $base->execute_query($LoginRS__query);
  $row_query = $base->get_row_assoc();
  $loginFoundUser = $base->num_rows();
  if ($loginFoundUser) {

    $loginStrGroup  = 100;
	$activo  = $row_query['activo'];
    //$cuenta  = $row_query['id_cuenta'];

    //declare two session variables and assign them
   echo $_SESSION['MM_Username'] = $loginUsername;
   echo $_SESSION['MM_UserGroup'] = $loginStrGroup;
   echo	$_SESSION['MM_Activo'] = $activo;
   echo $_SESSION['cuenta'] = $row_query['id_cuenta'];
   
	if($activo == 1)
	{
		if (isset($_SESSION['PrevUrl']) && false) {
		  $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
		}
		header("Location: " . $MM_redirectLoginSuccess );
		}
		 else {
		header("Location: ". $MM_redirectLoginFailed );
		}
	}
	else
	{
		header("Location: ". $MM_redirectLoginFailed );
	}
}
?>
