<?php
//Pagina principal de sistema de noticias
//Josué Morado Manríquez
//
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/funciones.php");
// llamamos las clases a utilizar
include("phpclasses/SuperNoticia.php");
include("phpclasses/Horario.php");
include("phpclasses/TarifaPrensa.php");
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

//se obtiene informacion de que noticias se obtendran
$tipoquery =""; // 1:diario  2:tipofuente  3:tema
$value="";

if(!isset($_GET['tipo_query']))
{
    $tipoquery = 1;
    $value = 0;
}
else
{
    $tipoquery = $_GET['tipo_query'];
    $value = $_GET['valor'];
}

//armamos el query para obtener las noticias a mostrar
//hacemos un switch para armar el WHERE dependiendo de la situacion,
// solo se obtendran un arreglo con las id de las noticias y el tipo de fuente q son,
//posteriormente se obtendra la informacion de cada una de ellas
$query_noticias =  "SELECT
                      noticia.id_noticia AS id_noticia,
                      noticia.id_tipo_fuente AS id_tipo_fuente
                    FROM
                     asigna
                     INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                     INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)";
$whereclause="";
switch($tipoquery)
{
    case 1: // noticias del dia
        $whereclause = "WHERE
                            empresa.id_empresa = ".$current_account->get_id()."
                        AND
                            noticia.fecha = CURDATE()";
        break;

    case 2: // por tipo de fuente
        $whereclause = "WHERE
                            empresa.id_empresa = ".$current_account->get_id()."
                        AND
                            noticia.fecha = CURDATE()
                        AND
                            noticia.id_tipo_fuente =".$value;
        break;

    case 3: // por tema
        $whereclause = " WHERE
                            empresa.id_empresa = ".$current_account->get_id()."
                        AND
                            noticia.fecha = CURDATE()
                        AND
                            asigna.id_tema =".$value;
        break;

}

$query_noticias .= $whereclause;
$query_noticias .= " ORDER BY fecha DESC, id_noticia DESC;";

//ejecutamos query y hacemos el arreglo de id_noticia y id_tipo_fuente
$base->execute_query($query_noticias);

if($base->num_rows() == 0)
{
	$num_noticias = 0;
}
else
{
	$num_noticias = $base->num_rows();
}

$arreglo_noti = array();
while($row = $base->get_row_assoc())
{
    $arreglo_noti[$row['id_noticia']]=$row['id_tipo_fuente'];
}

// acumulador de suma de costo de noticias, se usa variable de sesion para ir sumando desde las funciones de mostrar noticia
//al inicio del script se inicializa a cero
$_SESSION['suma_costo'] = 0;
// para alternar colores
$row_color = 1;

?>


<?php include 'home.php'; ?>

	</td>
</tr>

</table><?php include 'pie.php'; ?></div>


</body>
</html><br><br>

<?php 
$base->close();
?>
