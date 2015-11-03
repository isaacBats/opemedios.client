<?php
//Pagina que muestra una noticia de medio electronico
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
include("phpclasses/Archivo.php");
include("phpclasses/Ubicacion.php");
//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//iniciamos conexion a BD
$base = new OpmDB(genera_arreglo_BD());
$base->init();

$tabla_tipo = "";
$carpeta_tipo ="";
$label_tipo = "";

if($_GET['id_tipo_fuente'] == 3) {
    $tabla_tipo = "per";
    $carpeta_tipo="periodico";
    $label_tipo = "Periódico";
}
if($_GET['id_tipo_fuente'] == 4) {
    $tabla_tipo = "rev";
    $carpeta_tipo="revista";
    $label_tipo = "Revista";
}

$arr_color_ub = array(0=>"#E7EDF6", 1=>"#A60000");

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

                        //                        $base->execute_query("SELECT * FROM asigna WHERE id_noticia=".$_GET['id_noticia']."&id_empresa=".$current_account->get_id());
                        //                        if($base->num_rows() < 1) {
                        //                            header("Location: acceso_denegado.php");
                        //                            exit();
                        //                        }


                        // armamos el query de la noticia de medio impreso
                        $query ="   SELECT
                          noticia.id_noticia AS id_noticia,
                          noticia.encabezado AS encabezado,
                          noticia.sintesis AS sintesis,
                          noticia.autor AS autor,
                          noticia.fecha AS fecha,
                          noticia.comentario AS comentario,
                          noticia.id_tipo_fuente AS id_tipo_fuente,
                          noticia.id_fuente AS id_fuente,
                          noticia.id_seccion AS id_seccion,
                          noticia.id_sector AS id_sector,
                          noticia.id_tipo_autor AS id_tipo_autor,
                          noticia.id_genero AS id_genero,
                          fuente.nombre AS fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          noticia_".$tabla_tipo.".pagina AS pagina,
                          noticia_".$tabla_tipo.".id_tipo_pagina AS id_tipo_pagina,
                          noticia_".$tabla_tipo.".porcentaje_pagina AS porcentaje_pagina,
                          tipo_pagina.descripcion AS tipo_pagina,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          fuente_".$tabla_tipo.".tiraje AS tiraje,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia
                    FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_".$tabla_tipo." ON (fuente_".$tabla_tipo.".id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_".$tabla_tipo." ON (noticia_".$tabla_tipo.".id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)
                         INNER JOIN tipo_pagina ON (tipo_pagina.id_tipo_pagina=noticia_".$tabla_tipo.".id_tipo_pagina)

                         WHERE
                         noticia.id_noticia = ".$_GET['id_noticia'];


                        $base->execute_query($query);
                        $noticia = new SuperNoticia($base->get_row_assoc());

                        // buscamos el costo/beneficio
			
			
						//metemos las tarifas en un arreglo
						$arreglo_tarifas = array();
						$tarifas = 0;
						
						//si hay una tarifa con el tamaño exacto de la nota creamos solo una  con el precio establecido
						$base->execute_query("SELECT * FROM cuesta_prensa
											  WHERE
												  id_fuente = ".$noticia->getId_fuente()."
											  AND id_seccion = ".$noticia->getId_seccion()."
											  AND id_tipo_pagina = ".$noticia->getId_tipo_pagina().";");
						
						if($base->num_rows()>0)
						{
							$tarifas = 1;
						
							while($row_tarifa = $base->get_row_assoc())
							{
								$tarifa = new TarifaPrensa($row_tarifa);
								$base->execute_query2("SELECT * FROM seccion WHERE id_seccion=".$row_tarifa['id_seccion']." LIMIT 1");
								$seccion = new Seccion($base->get_row_assoc2());
								$tarifa->set_seccion($seccion);
								$precio_noticia = $tarifa->get_precio() * ($noticia->getPorcentaje_pagina()/100);
								$tarifa->setPrecio_noticia($precio_noticia);
								$arreglo_tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_seccion()->get_id()."_".$tarifa->get_id_tipo_pagina()]=$tarifa;
							}
							$c_b = $precio_noticia;
						}
						
						else // si no hubo una con el tamaño exacto ya no se hace nada
						{
							$tarifas = 0;
							$precio_noticia = "N/D";
						}


                        //hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                        $base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

                        if($base->num_rows() == 0) {
                            $isprincipal = 0;
							$nombre_archivo_principal;
                        }
                        else {
                            $isprincipal = 1;
                            $principal = new Archivo($base->get_row_assoc());
							$nombre_archivo_principal = $principal->getNombre_archivo();
                        }
						
						//hacemos consulta para obtener los datos del archivo CONTENEDOR y creamos objeto Archivo para asignarlo a la noticia
                        $base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 2 LIMIT 1;");

                        if($base->num_rows() == 0) {
                            $iscontenedor = 0;
                        }
                        else {
                            $iscontenedor = 1;
                            $contenedor = new Archivo($base->get_row_assoc());
                        }


                        //hacemos consulta para obtener los archivos secundarios  de la noticia
                        //por cada archivo que obtengamos generamos un objeto Archivo y lo metemos a un arreglo
                        $arreglo_secundarios = array();
                        $base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 0;");

                        if($base->num_rows() == 0) {
                            $issecundarios = 0;
                        }
                        else {
                            
                            $issecundarios = $base->num_rows();
                            while($row_archivo = $base->get_row_assoc()) {
                                $archivo = new Archivo($row_archivo);
                                $arreglo_secundarios[$archivo->getId()]=$archivo;
                            }
                        }
						
						// ya por último obtenemos la ubicacion
						
						$base->execute_query("SELECT * FROM ubicacion WHERE id_noticia = ".$noticia->getId()." LIMIT 1;");
						$ubicacion = new Ubicacion($base->get_row_assoc());

                        ?>

                        <img src="images/trans.gif" width="1" height="20" alt=""><br>
                        <table width="880" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                               
                                <td valign="top" class="desarrollo">


                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                    <img src="images/trans.gif" width="1" height="7" alt=""><br>
                                    <strong><?php echo $noticia->getEncabezado(); ?></strong><br>
                                    <?php echo $label_tipo; ?><br>
                                    <img src="images/trans.gif" width="1" height="7" alt=""><br>
                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                    <img src="images/trans.gif" width="1" height="15" alt=""><br>

                                    <!-- inicia tabla de noticia -->
                                    <div align="center"><table width="100%" border="0" cellspacing="4" cellpadding="4">
                                            <tr>
                                                <td colspan="2">
                                                    <table width="100%" cellspacing="2" cellpadding="2">
                                                        <tr bgcolor="#FFEDE1">
                                                            <td class="desarrollo1" align="center"><b>Clave:</b> <?php echo $noticia->getId(); ?></td>
                                                            <td class="desarrollo1" align="center"><b>Fuente:</b> <?php echo $noticia->getFuente(); ?></td>
                                                            <td class="desarrollo1" align="center"><b>Tiraje:</b> <?php echo $noticia->getTiraje(); ?></td>
                                                            <td class="desarrollo1" align="center"><b>Fecha:</b> <?php echo $noticia->getFecha_larga(); ?> </td>
                                                            <td class="desarrollo1" align="center"><b>P&aacute;gina:</b> <?php echo $noticia->getPagina(); ?></td>
                                                            <td class="desarrollo1" align="center"><b>Tamaño:</b> <?php echo $noticia->getPorcentaje_pagina(); ?>. %</td>

                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="25%" align="right" class="desarrollo"><b>Costo / Beneficio:</b></td>
                                                <td class="desarrollo"><strong>$ <?php echo $c_b; ?></strong></td>

                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                                    <div align="center">
                                                      <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                        <tr>
                                                          <td width="50%"><div align="center"><img src="http://sistema.operamedios.com.mx/data/noticias/<?php echo $carpeta_tipo."/thumbs/".$nombre_archivo_principal."_tn.jpg" ?>"><br>
                                                              <a target="_blank" href="<?php echo "http://sistema.operamedios.com.mx/data/noticias/".$carpeta_tipo."/".$nombre_archivo_principal; ?>">Descarga Aqui</a></div></td>
                                                          <td align="center" width="50%">
                                                          	<!-- empienza tabla ubicación -->Ubicación:
                                                          	<table width="90" cellspacing="2" cellpadding="2" border="0">
                                                                <tr>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getUno()]; ?>"><font face="Tahoma" size="1"></td>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getDos()]; ?>"><font face="Tahoma" size="1"></td>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getTres()]; ?>"><font face="Tahoma" size="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getCuatro()]; ?>"><font face="Tahoma" size="1"></td>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getCinco()]; ?>"><font face="Tahoma" size="1"></td>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getSeis()]; ?>"><font face="Tahoma" size="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getSiete()]; ?>"><font face="Tahoma" size="1"></td>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getOcho()]; ?>">&nbsp;</td>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getNueve()]; ?>">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getDiez()]; ?>"><font face="Tahoma" size="1"></td>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getOnce()]; ?>"><font face="Tahoma" size="1"></td>
                                                                    <td width="40" height="40" align="center" bgcolor="<?php echo $arr_color_ub[$ubicacion->getDoce()]; ?>"><font face="Tahoma" size="1"></td>
                                                                </tr>
                                                            </table>
                                                            <!-- termina tabla ubicación -->
                                                          
                                                          </td>
                                                        </tr>
                                                      </table>
                                                  <br>
                                                    </div>
                                                <br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                                    <img src="images/trans.gif" width="1" height="15" alt=""><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top" class="desarrollo"><b>S&iacute;ntesis:</b></td>
                                                <td class="desarrollo"><?php echo $noticia->getSintesis(); ?><br></td>

                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="desarrollo" align="right"><b>Autor:</b></td>
                                                <td class="desarrollo"><?php echo $noticia->getAutor(); ?><br></td>

                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="desarrollo" align="right"><b>Tipo de autor:</b></td>
                                                <td class="desarrollo"><?php echo $noticia->getTipo_autor(); ?></td>

                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top" class="desarrollo"><b>Archivos Relacionados:</b></td>
                                                <td class="desarrollo">

                                                    <?php
                                                   if($iscontenedor == 1)
                                                   {
                                                       echo '<a target="_blank" href="http://sistema.operamedios.com.mx/data/noticias/'.$carpeta_tipo.'/'.$contenedor->getNombre_archivo().'">Página Contenedora</a><br>';
                                                         echo'<img src="images/trans.gif" width="1" height="5" alt=""><br>';
                                                   }
                                                    foreach($arreglo_secundarios as $sec) {
                                                        echo '<a target="_blank" href="http://sistema.operamedios.com.mx/data/noticias/'.$carpeta_tipo.'/'.$sec->getNombre_archivo().'">'.$sec->getNombre().'</a><br>';
                                                         echo'<img src="images/trans.gif" width="1" height="5" alt=""><br>';
                                                        
                                                    }

                                                    ?>

                                                </td>

                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top" class="desarrollo"><b>Comentarios:</b></td>
                                                <td class="desarrollo"><?php echo $noticia->getComentario(); ?><br></td>

                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table width="100%" cellspacing="2" cellpadding="2">
                                                        <tr bgcolor="#FFEDE1">
                                                            <td class="desarrollo1" align="center"><b>Sector:</b> <?php echo $noticia->getSector(); ?></td>
                                                            <td class="desarrollo1" align="center"><b>G&eacute;nero:</b> <?php echo utf8_encode($noticia->getGenero()); ?></td>
                                                            <td class="desarrollo1" align="center"><b>Secci&oacute;n:</b> <?php echo $noticia->getSeccion(); ?></td>
                                                            <td class="desarrollo1" align="center"><b>Tendencia:</b> <?php echo $noticia->getTendencia(); ?></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table></div>

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
