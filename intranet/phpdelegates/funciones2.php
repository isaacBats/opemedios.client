<?php
      
  include_once("conf/db_conf.php");
  /**
   * Funcion con el cual 
   * @return \PDO
   */
  function getPDO(){

    $conn = new OpmDBConf();
    
    return new \PDO("mysql:host={$conn->get_databaseURL()};dbname={$conn->get_databaseName()}", $conn->get_databaseUName(), $conn->get_databasePWord());
  }


/*
 * Funciones que muestran las noticias
 */
 
 function muestra_noticia_grande($dao, $id_notic, $id_tipo_fuente)
 {
   
    $pdo = getPDO();
	 //dependiendo del tipo de fuente se genera la tabla

    switch($id_tipo_fuente) {
        case 1:

        
            // armamos el query de la noticia de TV
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
						  fuente.logo AS logo_fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia,
                          noticia_tel.hora AS hora,
                          noticia_tel.duracion AS duracion,
						  noticia_tel.costo AS costo,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          fuente_tel.canal AS canal

                        FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_tel ON (fuente_tel.id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_tel ON (noticia_tel.id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)

                         WHERE
                         noticia.id_noticia = ".$id_notic;

            $dao->execute_query($query);
            $noticia = new SuperNoticia($dao->get_row_assoc());

            //costo-beneficio
			
			if($noticia->getCosto() == "")
			{
				$c_b = "N/D";
			}
			else
			{
				$c_b = $noticia->getCosto(); 
				$_SESSION['suma_costo']+= $noticia->getCosto();
			}
			
			//hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

                        if($dao->num_rows() == 0) {
                            $isprincipal = 0;
                        }
                        else {
                            $isprincipal = 1;
                            $principal = new Archivo($dao->get_row_assoc());
                        }


                        //hacemos consulta para obtener los archivos secundarios  de la noticia
                        //por cada archivo que obtengamos generamos un objeto Archivo y lo metemos a un arreglo
                        $arreglo_secundarios = array();
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 0;");

                        if($dao->num_rows() == 0) {
                            $issecundarios = 0;
                        }
                        else {
                            
                            $issecundarios = $dao->num_rows();
                            while($row_archivo = $dao->get_row_assoc()) {
                                $archivo = new Archivo($row_archivo);
                                $arreglo_secundarios[$archivo->getId()]=$archivo;
                            }
                        }
	
           
            // generamos la salida:
            $new_back = array();
            $new_back[].= '<!-- tabla noticia grande television -->
							<table border="0">
								<tr>
									<td width="10"></td>
									<td width="380" valign="top">';
									
			if($isprincipal == 1)
			{
				$new_back[].='
							<div align="center">
							
							<object id="mediaplayer" classid="clsid:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#version=5,1,52,701" standby="loading microsoft windows media player components..." type="application/x-oleobject" width="350" height="250">
							<param name="filename" value="http://sistema.opemedios.com.mx/data/noticias/television/'.$principal->getNombre_archivo().'">
								 <param name="animationatstart" value="true">
								 <param name="transparentatstart" value="true">
								 <param name="autostart" value="false">
								 <param name="showcontrols" value="true">
								 <param name="ShowStatusBar" value="true">
								 <param name="windowlessvideo" value="false">
								 <embed src="http://sistema.opemedios.com.mx/data/noticias/television/'.$principal->getNombre_archivo().'" autostart="0" showcontrols="true" showstatusbar="1" bgcolor="white" width="320" height="310">
							</object>
							
							<br><br></div>
			';	
			}
			
			if($issecundarios > 0)
			{
				$new_back[].='<div class="label_red">Archivos Relacionados:</div>
                    <div>';
					foreach($arreglo_secundarios as $secundario)
					{
						$new_back[].='&nbsp;&nbsp;&loz;&nbsp;<a class="label1" href="http://sistema.opemedios.com.mx/data/noticias/television/'.$secundario->getNombre_archivo().'" target="_blank">'.$secundario->getNombre().'</a><br />';
					}
				$new_back[].='</div>';
			}
			
			$new_back[].='
				</td>
                <td width="500" valign="top">
                <div>
                <a class="titulo_grande" href="noticia_detalle_electronico.php?id_noticia='.$noticia->getId().'&id_tipo_fuente=1">'.utf8_encode($noticia->getEncabezado()).'</a>
                </div>
                <div>
<table border="0" align="center" width="100%">
                    	<tr>
                        <td width="25"></td>
                        <td width="60%" valign="top">
                        	<div class="fecha1">'.$noticia->getFecha_larga().'  --  '.$noticia->getHora().'<br>
                          Autor: '.utf8_encode($noticia->getAutor()).' ('.$noticia->getTipo_autor().')<br>
                          Canal: '.$noticia->getCanal().'<br><br>
                          <span class="label_red">Tema: </span><span class="label2">'.$noticia->getTema().'</span><br /><br />
                          <span class="label_red">Costo Beneficio: </span><span class="label2"><b>$ '.$noticia->getCosto().'</b></span><br />
                          </div>
                        </td>
                        <td width="40%" valign="top" align="right"><img width="130" height="65" src="http://sistema.opemedios.com.mx/data/fuentes/'.$noticia->getLogo_fuente().'" /></td>
</tr>
                    </table><br />
                    <div class="label2" align="justify">'.utf8_encode($noticia->getSintesis()).'</div><br />
                   <table border="0">
                   <tr>
                   	<td width="200" valign="top">
                        <div class="label_red">
                       Fuente: <font class="label2">'.utf8_encode($noticia->getFuente()).'</font><br>
                       Sección: <font class="label2">'.utf8_encode($noticia->getSeccion()).'</font><br><br>
					   Duración: <font class="label2">'.$noticia->getDuracion().'</font>
                   		</div>
                    </td>
                    <td width="200" valign="top">
                    	<div class="label_red">
                       Género: <font class="label2">'.utf8_encode($noticia->getGenero()).'</font><br>
                       Sector: <font class="label2">'.utf8_encode($noticia->getSector()).'</font><br><br>
                       Tendencia: <font class="label2">'.$noticia->getTendencia().'</font>
                   		</div>
                    </td>
                   </tr>
                   </table>
                </div>
                </td>
                <td width="10"></td>
            </tr>
        </table>
        <br>
        <hr width="100%" size="1" noshade="noshade" color="#000066">
        <!-- FIN tabla noticia grande medio electronico -->
			';
            

            $output = join("", $new_back);

            break;  // end case 1, television

        case 2: // radio

        // armamos el query de la noticia de radio
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
						  fuente.logo AS logo_fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia,
                          noticia_rad.hora AS hora,
                          noticia_rad.duracion AS duracion,
						  noticia_rad.costo AS costo,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          fuente_rad.estacion AS estacion

                        FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_rad ON (fuente_rad.id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_rad ON (noticia_rad.id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)

                         WHERE
                         noticia.id_noticia = ".$id_notic;

            $dao->execute_query($query);
            $noticia = new SuperNoticia($dao->get_row_assoc());

            //costo-beneficio
			
			if($noticia->getCosto() == "")
			{
				$c_b = "N/D";
			}
			else
			{
				$c_b = $noticia->getCosto(); 
				$_SESSION['suma_costo']+= $noticia->getCosto();
			}
			
			//hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

                        if($dao->num_rows() == 0) {
                            $isprincipal = 0;
                        }
                        else {
                            $isprincipal = 1;
                            $principal = new Archivo($dao->get_row_assoc());
                        }


                        //hacemos consulta para obtener los archivos secundarios  de la noticia
                        //por cada archivo que obtengamos generamos un objeto Archivo y lo metemos a un arreglo
                        $arreglo_secundarios = array();
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 0;");

                        if($dao->num_rows() == 0) {
                            $issecundarios = 0;
                        }
                        else {
                            
                            $issecundarios = $dao->num_rows();
                            while($row_archivo = $dao->get_row_assoc()) {
                                $archivo = new Archivo($row_archivo);
                                $arreglo_secundarios[$archivo->getId()]=$archivo;
                            }
                        }
	
           
            // generamos la salida:
            $new_back = array();
            $new_back[].= '<!-- tabla noticia grande television -->
							<table border="0">
								<tr>
									<td width="10"></td>
									<td width="380" valign="top">';
									
			if($isprincipal == 1)
			{
				$new_back[].='
							<div align="center">
							
							<object id="mediaplayer" classid="clsid:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#version=5,1,52,701" standby="loading microsoft windows media player components..." type="application/x-oleobject" width="350" height="250">
							<param name="filename" value="http://sistema.opemedios.com.mx/data/noticias/radio/'.$principal->getNombre_archivo().'">
								 <param name="animationatstart" value="true">
								 <param name="transparentatstart" value="true">
								 <param name="autostart" value="false">
								 <param name="showcontrols" value="true">
								 <param name="ShowStatusBar" value="true">
								 <param name="windowlessvideo" value="false">
								 <embed src="http://sistema.opemedios.com.mx/data/noticias/radio/'.$principal->getNombre_archivo().'" autostart="0" showcontrols="true" showstatusbar="1" bgcolor="white" width="320" height="310">
							</object>
							
							<br><br></div>
			';	
			}
			
			if($issecundarios > 0)
			{
				$new_back[].='<div class="label_red">Archivos Relacionados:</div>
                    <div>';
					foreach($arreglo_secundarios as $secundario)
					{
						$new_back[].='&nbsp;&nbsp;&loz;&nbsp;<a class="label1" href="http://sistema.opemedios.com.mx/data/noticias/radio/'.$secundario->getNombre_archivo().'" target="_blank">'.$secundario->getNombre().'</a><br />';
					}
				$new_back[].='</div>';
			}
			
			$new_back[].='
				</td>
                <td width="500" valign="top">
                <div>
                <a class="titulo_grande" href="noticia_detalle_electronico.php?id_noticia='.$noticia->getId().'&id_tipo_fuente=2">'.utf8_encode($noticia->getEncabezado()).'</a>
                </div>
                <div>
<table border="0" align="center" width="100%">
                    	<tr>
                        <td width="25"></td>
                        <td width="60%" valign="top">
                        	<div class="fecha1">'.$noticia->getFecha_larga().'  --  '.$noticia->getHora().'<br>
                          Autor: '.utf8_encode($noticia->getAutor()).' ('.$noticia->getTipo_autor().')<br>
                          Estación: '.$noticia->getEstacion().'<br><br>
                          <span class="label_red">Tema: </span><span class="label2">'.$noticia->getTema().'</span><br /><br />
                          <span class="label_red">Costo Beneficio: </span><span class="label2"><b>$ '.$noticia->getCosto().'</b></span><br />
                          </div>
                        </td>
                        <td width="40%" valign="top" align="right"><img width="130" height="65" src="http://sistema.opemedios.com.mx/data/fuentes/'.$noticia->getLogo_fuente().'" /></td>
</tr>
                    </table><br />
                    <div class="label2" align="justify">'.utf8_encode($noticia->getSintesis()).'</div><br />
                   <table border="0">
                   <tr>
                   	<td width="200" valign="top">
                        <div class="label_red">
                       Fuente: <font class="label2">'.utf8_encode($noticia->getFuente()).'</font><br>
                       Sección: <font class="label2">'.utf8_encode($noticia->getSeccion()).'</font><br><br>
					   Duración: <font class="label2">'.$noticia->getDuracion().'</font>
                   		</div>
                    </td>
                    <td width="200" valign="top">
                    	<div class="label_red">
                       Género: <font class="label2">'.utf8_encode($noticia->getGenero()).'</font><br>
                       Sector: <font class="label2">'.utf8_encode($noticia->getSector()).'</font><br><br>
                       Tendencia: <font class="label2">'.$noticia->getTendencia().'</font>
                   		</div>
                    </td>
                   </tr>
                   </table>
                </div>
                </td>
                <td width="10"></td>
            </tr>
        </table>
        <br>
        <hr width="100%" size="1" noshade="noshade" color="#000066">
        <!-- FIN tabla noticia grande medio electronico -->
			';

            $output = join("", $new_back);

            break;  // end case 2, radio

        case 3: // periodico

        //hacemos consulta para la noticia de periodico
            $query = "SELECT
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
						  fuente.logo AS logo_fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          noticia_per.pagina AS pagina,
                          noticia_per.id_tipo_pagina AS id_tipo_pagina,
                          noticia_per.porcentaje_pagina AS porcentaje_pagina,
                          tipo_pagina.descripcion AS tipo_pagina,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          fuente_per.tiraje AS tiraje,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia
                    FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_per ON (fuente_per.id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_per ON (noticia_per.id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)
                         INNER JOIN tipo_pagina ON (tipo_pagina.id_tipo_pagina=noticia_per.id_tipo_pagina)

                         WHERE
                         noticia.id_noticia = ".$id_notic;


            $dao->execute_query($query);
            $noticia = new SuperNoticia($dao->get_row_assoc());

            // buscamos el costo/beneficio
			
			
			//metemos las tarifas en un arreglo
			$arreglo_tarifas = array();
			$tarifas = 0;
			
			//si hay una tarifa con el tamaño exacto de la nota creamos solo una  con el precio establecido
			$dao->execute_query("SELECT * FROM cuesta_prensa
								  WHERE
									  id_fuente = ".$noticia->getId_fuente()."
								  AND id_seccion = ".$noticia->getId_seccion()."
								  AND id_tipo_pagina = ".$noticia->getId_tipo_pagina().";");
			
			if($dao->num_rows()>0)
			{
				$tarifas = 1;
			
				while($row_tarifa = $dao->get_row_assoc())
				{
					$tarifa = new TarifaPrensa($row_tarifa);
					$dao->execute_query2("SELECT * FROM seccion WHERE id_seccion=".$row_tarifa['id_seccion']." LIMIT 1");
					$seccion = new Seccion($dao->get_row_assoc2());
					$tarifa->set_seccion($seccion);
					$precio_noticia = $tarifa->get_precio() * ($noticia->getPorcentaje_pagina()/100);
					$tarifa->setPrecio_noticia($precio_noticia);
					$arreglo_tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_seccion()->get_id()."_".$tarifa->get_id_tipo_pagina()]=$tarifa;
				}
				$c_b = $precio_noticia;
				$_SESSION['suma_costo'] += $precio_noticia;
			}
			
			else // si no hubo una con el tamaño exacto ya no se hace nada
			{
				$tarifas = 0;
				$c_b = "N/D";
			}
			
			//hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

                        if($dao->num_rows() == 0) {
                            $isprincipal = 0;
                        }
                        else {
                            $isprincipal = 1;
                            $principal = new Archivo($dao->get_row_assoc());
                        }
						
						//hacemos consulta para obtener los datos del archivo CONTENEDOR y creamos objeto Archivo para asignarlo a la noticia
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 2 LIMIT 1;");

                        if($dao->num_rows() == 0) {
                            $iscontenedor = 0;
                        }
                        else {
                            $iscontenedor = 1;
                            $contenedor = new Archivo($dao->get_row_assoc());
                        }


                        //hacemos consulta para obtener los archivos secundarios  de la noticia
                        //por cada archivo que obtengamos generamos un objeto Archivo y lo metemos a un arreglo
                        $arreglo_secundarios = array();
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 0;");

                        if($dao->num_rows() == 0) {
                            $issecundarios = 0;
                        }
                        else {
                            
                            $issecundarios = $dao->num_rows();
                            while($row_archivo = $dao->get_row_assoc()) {
                                $archivo = new Archivo($row_archivo);
                                $arreglo_secundarios[$archivo->getId()]=$archivo;
                            }
                        }
			
			// generamos la salida:
            $new_back = array();
            $new_back[].= '<!-- tabla noticia grande periodico -->
							<table border="0">
								<tr>
									<td width="10"></td>
									<td width="380" valign="top">';
									
			if($isprincipal == 1)
			{
				$new_back[].='
							<div align="center"><img src="http://sistema.opemedios.com.mx/data/noticias/periodico/thumbs/'.$principal->getNombre_archivo().'_tn.jpg" border="0" /><br><br></div>
			';	
			}
			if($iscontenedor == 1)
			{
				$new_back[].='&nbsp;&nbsp;&loz;&nbsp;<a class="label1" href="http://sistema.opemedios.com.mx/data/noticias/periodico/'.$contenedor->getNombre_archivo().'" target="_blank">Archivo Contenedor</a><br /><br />';
			}
			
			if($issecundarios > 0)
			{
				$new_back[].='<div class="label_red">Archivos Relacionados:</div>
                    <div>';
					foreach($arreglo_secundarios as $secundario)
					{
						$new_back[].='&nbsp;&nbsp;&loz;&nbsp;<a class="label1" href="http://sistema.opemedios.com.mx/data/noticias/periodico/'.$secundario->getNombre_archivo().'" target="_blank">'.$secundario->getNombre().'</a><br />';
					}
				$new_back[].='</div>';
			}
			
			$new_back[].='
				</td>
                <td width="500" valign="top">
                <div>
                <a class="titulo_grande" href="noticia_detalle_prensa.php?id_noticia='.$noticia->getId().'&id_tipo_fuente=3">'.utf8_encode($noticia->getEncabezado()).'</a>
                </div>
                <div>
<table border="0" align="center" width="100%">
                    	<tr>
                        <td width="25"></td>
                        <td width="60%" valign="top">
                        	<div class="fecha1">'.$noticia->getFecha_larga().'<br>
                          Autor: '.utf8_encode($noticia->getAutor()).' ('.$noticia->getTipo_autor().')<br>
                          Página: '.$noticia->getPagina().' ('.$noticia->getTipo_pagina().')<br><br>
                          <span class="label_red">Tema: </span><span class="label2">'.$noticia->getTema().'</span><br /><br />
                          <span class="label_red">Costo Beneficio: </span><span class="label2"><b>$ '.$c_b.'</b></span><br />
                          </div>
                        </td>
                        <td width="40%" valign="top" align="right"><img width="130" height="65" src="http://sistema.opemedios.com.mx/data/fuentes/'.$noticia->getLogo_fuente().'" /></td>
</tr>
                    </table><br />
                    <div class="label2" align="justify">'.utf8_encode($noticia->getSintesis()).'</div><br />
                   <table border="0">
                   <tr>
                   	<td width="200" valign="top">
                        <div class="label_red">
                       Fuente: <font class="label2">'.utf8_encode($noticia->getFuente()).'</font><br>
                       Sección: <font class="label2">'.utf8_encode($noticia->getSeccion()).'</font><br><br>
                   		</div>
                    </td>
                    <td width="200" valign="top">
                    	<div class="label_red">
                       Género: <font class="label2">'.utf8_encode($noticia->getGenero()).'</font><br>
                       Sector: <font class="label2">'.utf8_encode($noticia->getSector()).'</font><br><br>
                       Tendencia: <font class="label2">'.$noticia->getTendencia().'</font>
                   		</div>
                    </td>
                   </tr>
                   </table>
                </div>
                </td>
                <td width="10"></td>
            </tr>
        </table>
        <br>
        <hr width="100%" size="1" noshade="noshade" color="#000066">
        <!-- FIN tabla noticia grande periodico -->
			';

          

            $output = join("", $new_back);


            break; // end case 3, periodico

        case 4: // revista
		
		
		//hacemos consulta para la noticia de periodico
            $query = "SELECT
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
						  fuente.logo AS logo_fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          noticia_rev.pagina AS pagina,
                          noticia_rev.id_tipo_pagina AS id_tipo_pagina,
                          noticia_rev.porcentaje_pagina AS porcentaje_pagina,
                          tipo_pagina.descripcion AS tipo_pagina,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          fuente_rev.tiraje AS tiraje,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia
                    FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_rev ON (fuente_rev.id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_rev ON (noticia_rev.id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)
                         INNER JOIN tipo_pagina ON (tipo_pagina.id_tipo_pagina=noticia_rev.id_tipo_pagina)

                         WHERE
                         noticia.id_noticia = ".$id_notic;


            $dao->execute_query($query);
            $noticia = new SuperNoticia($dao->get_row_assoc());

            // buscamos el costo/beneficio
			
			
			//metemos las tarifas en un arreglo
			$arreglo_tarifas = array();
			$tarifas = 0;
			
			//si hay una tarifa con el tamaño exacto de la nota creamos solo una  con el precio establecido
			$dao->execute_query("SELECT * FROM cuesta_prensa
								  WHERE
									  id_fuente = ".$noticia->getId_fuente()."
								  AND id_seccion = ".$noticia->getId_seccion()."
								  AND id_tipo_pagina = ".$noticia->getId_tipo_pagina().";");
			
			if($dao->num_rows()>0)
			{
				$tarifas = 1;
			
				while($row_tarifa = $dao->get_row_assoc())
				{
					$tarifa = new TarifaPrensa($row_tarifa);
					$dao->execute_query2("SELECT * FROM seccion WHERE id_seccion=".$row_tarifa['id_seccion']." LIMIT 1");
					$seccion = new Seccion($dao->get_row_assoc2());
					$tarifa->set_seccion($seccion);
					$precio_noticia = $tarifa->get_precio() * ($noticia->getPorcentaje_pagina()/100);
					$tarifa->setPrecio_noticia($precio_noticia);
					$arreglo_tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_seccion()->get_id()."_".$tarifa->get_id_tipo_pagina()]=$tarifa;
				}
				$c_b = $precio_noticia;
				$_SESSION['suma_costo'] += $precio_noticia;
			}
			
			else // si no hubo una con el tamaño exacto ya no se hace nada
			{
				$tarifas = 0;
				$c_b = "N/D";
			}
			
			//hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

                        if($dao->num_rows() == 0) {
                            $isprincipal = 0;
                        }
                        else {
                            $isprincipal = 1;
                            $principal = new Archivo($dao->get_row_assoc());
                        }
						
						//hacemos consulta para obtener los datos del archivo CONTENEDOR y creamos objeto Archivo para asignarlo a la noticia
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 2 LIMIT 1;");

                        if($dao->num_rows() == 0) {
                            $iscontenedor = 0;
                        }
                        else {
                            $iscontenedor = 1;
                            $contenedor = new Archivo($dao->get_row_assoc());
                        }


                        //hacemos consulta para obtener los archivos secundarios  de la noticia
                        //por cada archivo que obtengamos generamos un objeto Archivo y lo metemos a un arreglo
                        $arreglo_secundarios = array();
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 0;");

                        if($dao->num_rows() == 0) {
                            $issecundarios = 0;
                        }
                        else {
                            
                            $issecundarios = $dao->num_rows();
                            while($row_archivo = $dao->get_row_assoc()) {
                                $archivo = new Archivo($row_archivo);
                                $arreglo_secundarios[$archivo->getId()]=$archivo;
                            }
                        }
			
			// generamos la salida:
            $new_back = array();
            $new_back[].= '<!-- tabla noticia grande revista -->
							<table border="0">
								<tr>
									<td width="10"></td>
									<td width="380" valign="top">';
									
			if($isprincipal == 1)
			{
				$new_back[].='
							<div align="center"><img src="http://sistema.opemedios.com.mx/data/noticias/revista/thumbs/'.$principal->getNombre_archivo().'_tn.jpg" border="0" /><br><br></div>
			';	
			}
			if($iscontenedor == 1)
			{
				$new_back[].='&nbsp;&nbsp;&loz;&nbsp;<a class="label1" href="http://sistema.opemedios.com.mx/data/noticias/revista/'.$contenedor->getNombre_archivo().'" target="_blank">Archivo Contenedor</a><br /><br />';
			}
			
			if($issecundarios > 0)
			{
				$new_back[].='<div class="label_red">Archivos Relacionados:</div>
                    <div>';
					foreach($arreglo_secundarios as $secundario)
					{
						$new_back[].='&nbsp;&nbsp;&loz;&nbsp;<a class="label1" href="http://sistema.opemedios.com.mx/data/noticias/revista/'.$secundario->getNombre_archivo().'" target="_blank">'.$secundario->getNombre().'</a><br />';
					}
				$new_back[].='</div>';
			}
			
			$new_back[].='
				</td>
                <td width="500" valign="top">
                <div>
                <a class="titulo_grande" href="noticia_detalle_prensa.php?id_noticia='.$noticia->getId().'&id_tipo_fuente=4">'.utf8_encode($noticia->getEncabezado()).'</a>
                </div>
                <div>
<table border="0" align="center" width="100%">
                    	<tr>
                        <td width="25"></td>
                        <td width="60%" valign="top">
                        	<div class="fecha1">'.$noticia->getFecha_larga().'<br>
                          Autor: '.utf8_encode($noticia->getAutor()).' ('.$noticia->getTipo_autor().')<br>
                          Página: '.$noticia->getPagina().' ('.$noticia->getTipo_pagina().')<br><br>
                          <span class="label_red">Tema: </span><span class="label2">'.$noticia->getTema().'</span><br /><br />
                          <span class="label_red">Costo Beneficio: </span><span class="label2"><b>$ '.$c_b.'</b></span><br />
                          </div>
                        </td>
                        <td width="40%" valign="top" align="right"><img width="130" height="65" src="http://sistema.opemedios.com.mx/data/fuentes/'.$noticia->getLogo_fuente().'" /></td>
</tr>
                    </table><br />
                    <div class="label2" align="justify">'.utf8_encode($noticia->getSintesis()).'</div><br />
                   <table border="0">
                   <tr>
                   	<td width="200" valign="top">
                        <div class="label_red">
                       Fuente: <font class="label2">'.utf8_encode($noticia->getFuente()).'</font><br>
                       Sección: <font class="label2">'.utf8_encode($noticia->getSeccion()).'</font><br><br>
                   		</div>
                    </td>
                    <td width="200" valign="top">
                    	<div class="label_red">
                       Género: <font class="label2">'.utf8_encode($noticia->getGenero()).'</font><br>
                       Sector: <font class="label2">'.utf8_encode($noticia->getSector()).'</font><br><br>
                       Tendencia: <font class="label2">'.$noticia->getTendencia().'</font>
                   		</div>
                    </td>
                   </tr>
                   </table>
                </div>
                </td>
                <td width="10"></td>
            </tr>
        </table>
        <br>
        <hr width="100%" size="1" noshade="noshade" color="#000066">
        <!-- FIN tabla noticia grande periodico -->
			';


            $output = join("", $new_back);

        
            break; // end case 4, revista

        case 5: // internet


          $new = $pdo->query("SELECT * FROM noticia_int WHERE id_noticia = $id_notic")->fetch(\PDO::FETCH_ASSOC);
          $sector = (!$new['is_social']) ? ' sector.nombre AS sector, ' : '';
          $isector = (!$new['is_social']) ? ' INNER JOIN sector ON (sector.id_sector=noticia.id_sector) ' : '';

        //hacemos consulta para la noticia de internet
            $query = "SELECT
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
						              fuente.logo AS logo_fuente,
                          seccion.nombre AS seccion, ".
                          $sector .
                          " tipo_fuente.descripcion AS tipo_fuente,
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
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia) " .
                         $isector .
                         " INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)

                         WHERE
                         noticia.id_noticia = ".$id_notic;


            $dao->execute_query($query);
            $noticia = new SuperNoticia($dao->get_row_assoc());
            
            // no existe costo/beneficio, procedemos a generar output

            $c_b = "N/D";
			
			//hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

                        if($dao->num_rows() == 0) {
                            $isprincipal = 0;
                        }
                        else {
                            $isprincipal = 1;
                            $principal = new Archivo($dao->get_row_assoc());
                        }
						
						
                        //hacemos consulta para obtener los archivos secundarios  de la noticia
                        //por cada archivo que obtengamos generamos un objeto Archivo y lo metemos a un arreglo
                        $arreglo_secundarios = array();
                        $dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 0;");

                        if($dao->num_rows() == 0) {
                            $issecundarios = 0;
                        }
                        else {
                            
                            $issecundarios = $dao->num_rows();
                            while($row_archivo = $dao->get_row_assoc()) {
                                $archivo = new Archivo($row_archivo);
                                $arreglo_secundarios[$archivo->getId()]=$archivo;
                            }
                        }
			

           // generamos la salida:
            $new_back = array();
            $new_back[].= '<!-- tabla noticia grande internet -->
							<table border="0">
								<tr>
									<td width="10"></td>
									<td width="380" valign="top">';
			$new_back[].='<div class="label_red">Archivos Relacionados:</div>
                    <div>';
			if($isprincipal == 1)
			{
				$new_back[].='
							&nbsp;&nbsp;&loz;&nbsp;<a class="label1" href="http://sistema.opemedios.com.mx/data/noticias/internet/'.$principal->getNombre_archivo().'" target="_blank">'.$principal->getNombre().'</a><br />
			';	
			}
						
			if($issecundarios > 0)
			{
					foreach($arreglo_secundarios as $secundario)
					{
						$new_back[].='&nbsp;&nbsp;&loz;&nbsp;<a class="label1" href="http://sistema.opemedios.com.mx/data/noticias/internet/'.$secundario->getNombre_archivo().'" target="_blank">'.$secundario->getNombre().'</a><br />';
					}
				
			}
			$new_back[].='</div>';
			$new_back[].='
				</td>
                <td width="500" valign="top">
                <div>
                <a class="titulo_grande" href="noticia_detalle_internet.php?id_noticia='.$noticia->getId().'&id_tipo_fuente=5'.(!$new['is_social']) ? '' : '&red=red'.'">'.utf8_encode($noticia->getEncabezado()).'</a>
                </div>
                <div>
<table border="0" align="center" width="100%">
                    	<tr>
                        <td width="25"></td>
                        <td width="60%" valign="top">
                        	<div class="fecha1">'.$noticia->getFecha_larga().'<br>
                          Autor: '.utf8_encode($noticia->getAutor()).' ('.$noticia->getTipo_autor().')<br>
						  URL: <a target="_blank" href="'.$noticia->getUrl().'">Ver Noticia</a><br><br>
                          <span class="label_red">Tema: </span><span class="label2">'.$noticia->getTema().'</span><br /><br />
                          <span class="label_red">Costo Beneficio: </span><span class="label2"><b>$ '.$c_b.'</b></span><br />
                          </div>
                        </td>
                        <td width="40%" valign="top" align="right"><img width="130" height="65" src="http://sistema.opemedios.com.mx/data/fuentes/'.$noticia->getLogo_fuente().'" /></td>
</tr>
                    </table><br />
                    <div class="label2" align="justify">'.utf8_encode($noticia->getSintesis()).'</div><br />
                   <table border="0">
                   <tr>
                   	<td width="200" valign="top">
                        <div class="label_red">
                       Fuente: <font class="label2">'.utf8_encode($noticia->getFuente()).'</font><br>
                       Sección: <font class="label2">'.utf8_encode($noticia->getSeccion()).'</font><br><br>
                   		</div>
                    </td>
                    <td width="200" valign="top">
                    	<div class="label_red">
                       Género: <font class="label2">'.utf8_encode($noticia->getGenero()).'</font><br>
                       Sector: <font class="label2">'.utf8_encode($noticia->getSector()).'</font><br><br>
                       Tendencia: <font class="label2">'.$noticia->getTendencia().'</font>
                   		</div>
                    </td>
                   </tr>
                   </table>
                </div>
                </td>
                <td width="10"></td>
            </tr>
        </table>
        <br>
        <hr width="100%" size="1" noshade="noshade" color="#000066">
        <!-- FIN tabla noticia grande internet -->
			';

            $output = join("", $new_back);

            break; // end case 5, internet

    } // end switch
	 
	 return $output;
	 
 }// end function muestra noticia grande

?>
