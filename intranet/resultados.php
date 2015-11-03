<?php
//Resultados de la busqueda avanzada
//Josué Morado Manríquez
//
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/funciones.php");
// llamamos las clases a utilizar
include("phpclasses/SuperNoticia.php");
include("phpclasses/Horario.php");
include("phpclasses/TarifaPrensa.php");
include("phpclasses/Seccion.php");
//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//iniciamos conexion a BD
$base = new OpmDB(genera_arreglo_BD());
$base->init();

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType)
    {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
            break;
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "palabrasclave":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "frasecompleta":
            $theValue = ($theValue != '') ? '"' . $theValue . '"' : 'NULL';
            break;
        case "textolimpio":
            $theValue = ($theValue != '') ?  $theValue  : 'NULL';
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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>
        <title>Resultados de Búsqueda - Sistema OPEMEDIOS</title>
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


                        if(isset($_GET['busqueda'])&& $_GET['busqueda'] == true) {
                            if($_GET['accion'] == 2) // si se va a graficar, manda a otra pagina (en construccion)
                            {
                            // header a graficas
                            }

                            if($_GET['accion'] == 1) // Mostramos los resultados
                            {

                                $string = "";
                                $matchSELECT = "";
                                $matchWHERE = "";
                                $order="";

                                if($_GET['txt_buscar']!= "")// si esta lleno
                                {
                                    if($_GET['tipo_busqueda'] == 2)// Palabras Clave
                                    {
                                        $string .= "'";
                                        $string_evita = "";

                                        $string .= sprintf("%s",GetSQLValueString($_GET['txt_buscar'], "textolimpio"));

                                        if($_GET['txt_evitar']!="")//  contiene algun valor
                                        {

                                            $split = split(" ",$_GET['txt_evitar']);
                                            foreach ($split as $array => $value) {
                                                $string_evita .= ' -'.GetSQLValueString($value, "textolimpio").' ';
                                            }
                                            $string_evita = substr($string_evita,0,(strLen($string_evita)-1)); // borra el ultimo espacio

                                        }

                                        $string .= $string_evita;
                                        $string .= "'";

                                        $matchSELECT = sprintf(",((1.5 * (MATCH(noticia.encabezado) AGAINST(%s IN BOOLEAN MODE))) +
                                          (0.9 * (MATCH(noticia.sintesis) AGAINST(%s IN BOOLEAN MODE))) +
                                          (0.5 * (MATCH(noticia.autor) AGAINST(%s IN BOOLEAN MODE)))) AS Relevance",
                                            $string,
                                            $string,
                                            $string);

                                        $matchWHERE = sprintf("MATCH(noticia.`encabezado`,noticia.`sintesis`,noticia.`autor`)AGAINST(%s IN BOOLEAN MODE) AND",$string);

                                        $order = "ORDER BY Relevance DESC, id_noticia DESC";

                                    }

                                    if($_GET['tipo_busqueda'] == 1)//frase completa
                                    {
                                        $string = sprintf("'%s'",GetSQLValueString($_GET['txt_buscar'], "frasecompleta"));//el string lleva comilla doble dentro de la comilla simple

                                        $matchSELECT = sprintf(",((1.5 * (MATCH(noticia.encabezado) AGAINST(%s IN BOOLEAN MODE))) +
                                   (0.9 * (MATCH(noticia.sintesis) AGAINST(%s IN BOOLEAN MODE))) +
                                   (0.5 * (MATCH(noticia.autor) AGAINST(%s IN BOOLEAN MODE)))) AS Relevance",
                                            $string,
                                            $string,
                                            $string);

                                        $matchWHERE = sprintf("MATCH(noticia.`encabezado`,noticia.`sintesis`,noticia.`autor`)AGAINST(%s IN BOOLEAN MODE) AND",$string);

                                        $order = "ORDER BY Relevance DESC, fecha DESC, id_noticia DESC";

                                    }

                                } // end if si esta lleno
                                else {
                                    $order = "ORDER BY fecha DESC, id_noticia DESC";
                                }

                                // ahora armamos el query

                                $query = array();

                                $query[] .= "SELECT
                                                noticia.id_noticia AS id_noticia,
                                                noticia.id_tipo_fuente AS id_tipo_fuente,
												noticia.fecha AS fecha";
                                $query[] .= $matchSELECT;

                                $query[] .= 'FROM
                                                 asigna
                                                 INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                                                 INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)';
                                $query[] .= 'WHERE';

                                $query[] .= $matchWHERE;

                                
                                $fecha1 = date("Y-m-d",mktime(0,0,0,$_GET['fecha1_Month_ID'],$_GET['fecha1_Day_ID'],$_GET['fecha1_Year_ID']));
                                $fecha2 = date("Y-m-d",mktime(0,0,0,$_GET['fecha2_Month_ID'],$_GET['fecha2_Day_ID'],$_GET['fecha2_Year_ID']));
                                $query[] .= "(noticia.fecha BETWEEN '".$fecha1."' AND '".$fecha2."')";

                                $sector = $_GET['id_sector'];
                                $genero = $_GET['id_genero'];
                                $tipoautor = $_GET['id_tipo_autor'];
                                $tendencia = $_GET['id_tendencia'];
                                $tipofuente = $_GET['id_tipo_fuente'];
                                $tema = $_GET['id_tema'];

                                $query[] .= " AND empresa.id_empresa = ".$current_account->get_id(); // solo las noticias del cliente

                                if($sector != 0) {$query[] .= 'AND noticia.id_sector = '.$sector ;}
                                if($genero != 0) {$query[] .= 'AND noticia.id_genero = '.$genero ;}
                                if($tipoautor != 0) {$query[] .= 'AND noticia.id_tipo_autor = '.$tipoautor ;}
                                if($tendencia != 0) {$query[] .= 'AND asigna.id_tendencia = '.$tendencia ;}
                                if($tema != 0) {$query[] .= 'AND asigna.id_tema = '.$tema ;}

                                if($tipofuente != 0) {$query[] .= 'AND noticia.id_tipo_fuente = '.$tipofuente ;}
                                if(isset($_GET['id_fuente']) && $_GET['id_fuente']!= 0) {$query[] .= 'AND noticia.id_fuente = '.$_GET['id_fuente'] ;}
                                if(isset($_GET['id_seccion']) && $_GET['id_seccion']!= 0) {$query[] .= 'AND noticia.id_seccion = '.$_GET['id_seccion'] ;}

                                $query[] .= $order;

                                

                                $query_entero = join(" ", $query);

                                //ejecutamos query y hacemos el arreglo de id_noticia y id_tipo_fuente
                                $base->execute_query($query_entero);

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



                            } // end if accion == 1

                        }// end if busqueda = true
						
					//	die($query_entero);

                        ?> 


                        <?php include 'home_resultados.php'; ?>

                    </td>
                </tr>

            </table><?php include 'pie.php'; ?></div>


    </body>
</html><br><br>

<?php 
$base->close();
?>
