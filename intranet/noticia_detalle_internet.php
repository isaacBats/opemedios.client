<?php
//Pagina que muestra una noticia de medio electronico
//Josué Morado Manríquez
//
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/funciones.php");
// llamamos las clases a utilizar
include("phpclasses/SuperNoticia.php");
include("phpclasses/Archivo.php");
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
                          noticia_int.url AS url,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia
                    FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN noticia_int ON (noticia_int.id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)

                         WHERE
                         noticia.id_noticia = ".$_GET['id_noticia'];

                         print_r($query); exit;
                        $base->execute_query($query);
                        $noticia = new SuperNoticia($base->get_row_assoc());

                        //hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                        $base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

                        if($base->num_rows() == 0) {
                            $isprincipal = 0;
                        }
                        else {
                            $isprincipal = 1;
                            $principal = new Archivo($base->get_row_assoc());
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
						
                        ?>

                        <img src="images/trans.gif" width="1" height="20" alt=""><br>
                        <table width="880" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                
                                <td valign="top" class="desarrollo">


                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                    <img src="images/trans.gif" width="1" height="7" alt=""><br>
                                    <strong><?php echo utf8_encode($noticia->getEncabezado()); ?></strong><br>
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
                                                            <td class="desarrollo1" align="center"><b>Fecha:</b> <?php echo $noticia->getFecha_larga(); ?></td>
                                                            <td class="desarrollo1" align="center"><b>Fuente:</b> <?php echo $noticia->getFuente(); ?> </td>
                                                            <td align="center" class="desarrollo1"><strong>Sección: </strong> <?php echo $noticia->getSeccion(); ?></td>
                                                      </tr>
                                                    </table>                                              </td>
                                            </tr>
                                            <tr>
                                                <td width="25%" align="right" class="desarrollo"><b>URL:</b></td>
                                                <td class="desarrollo"><a href="<?php echo $noticia->getUrl(); ?>" target="_blank">Ir a URL</a></td>
                                      </tr>
                                            <tr>
                                                <td colspan="2"><br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br></td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top" class="desarrollo"><b>S&iacute;ntesis:</b></td>
                                                <td class="desarrollo"><?php echo utf8_encode($noticia->getSintesis()); ?></td>
                                      </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="desarrollo" align="right"><b>Autor:</b></td>
                                                <td class="desarrollo"><?php echo utf8_encode($noticia->getAutor()); ?></td>
                                      </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="desarrollo" align="right"><b>Tipo de autor:</b></td>
                                                <td class="desarrollo"><?php echo $noticia->getTipo_autor(); ?></td>
                                      </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top" class="desarrollo"><b>Archivos :</b></td>
                                                <td class="desarrollo">
                                                 <?php
                                                   if($isprincipal > 0)
                                                   {
                                                       echo '<a target="_blank" href="http://sistema.opemedios.com.mx/data/noticias/internet/'.$principal->getNombre_archivo().'">'.$principal->getNombre().'</a><br>';
                                                         echo'<img src="images/trans.gif" width="1" height="5" alt=""><br>';
                                                   }
                                                   foreach($arreglo_secundarios as $sec) {
                                                        echo '<a target="_blank" href="http://sistema.opemedios.com.mx/data/noticias/internet/'.$sec->getNombre_archivo().'">'.$sec->getNombre().'</a><br>';
                                                         echo'<img src="images/trans.gif" width="1" height="5" alt=""><br>';
                                                        
                                                    }

                                                    ?>
                                                
                                                </td>
                                      </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>
                                                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                                    <img src="images/trans.gif" width="1" height="1" alt=""><br>                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top" class="desarrollo"><b>Comentarios:</b></td>
                                                <td class="desarrollo"><?php echo $noticia->getComentario(); ?></td>
                                      </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table width="100%" cellspacing="2" cellpadding="2">
                                                        <tr bgcolor="#FFEDE1">
                                                            <td class="desarrollo1" align="center"><b>Sector:</b> <?php echo $noticia->getSector(); ?></td>
                                                            <td class="desarrollo1" align="center"><b>G&eacute;nero:</b> <?php echo utf8_encode($noticia->getGenero()); ?></td>
                                                            <td class="desarrollo1" align="center"><b>Tendencia:</b> <?php echo $noticia->getTendencia(); ?></td>
                                                        </tr>
                                                    </table>                                                </td>
                                            </tr>
                                        </table>
                                  </div>

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

</table>
<?php include 'pie.php'; ?></div>


</body>
</html><br><br>
