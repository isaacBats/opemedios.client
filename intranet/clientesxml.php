<?php 

//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/funciones.php");
include("phpdelegates/funciones2.php");

// llamamos las clases a utilizar
include("phpclasses/SuperNoticia.php");
include("phpclasses/Horario.php");
include("phpclasses/TarifaPrensa.php");
include("phpclasses/Archivo.php");
include("phpclasses/Seccion.php");
//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//iniciamos conexion a BD
$base = new OpmDB(genera_arreglo_BD());
$base->init();




function noticia_xml($dao, $id_notic, $id_tipo_fuente)
 {
	 
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
            
			$new_back[].='<noticia>'."\n";
			
			$new_back[].="\t".'<id_noticia>'.$noticia->getId().'</id_noticia>'."\n";
			$new_back[].="\t".'<encabezado>'.$noticia->getEncabezado().'</encabezado>'."\n";
			$new_back[].="\t".'<sintesis>'.$noticia->getSintesis().'</sintesis>'."\n";
			$new_back[].="\t".'<autor>'.$noticia->getAutor().'</autor>'."\n";
			$new_back[].="\t".'<fecha>'.$noticia->getFecha().'</fecha>'."\n";
			$new_back[].="\t".'<fecha_larga>'.$noticia->getFecha_larga().'</fecha_larga>'."\n";
			$new_back[].="\t".'<comentario>'.$noticia->getComentario().'</comentario>'."\n";
			$new_back[].="\t".'<tipo_fuente>'.utf8_encode($noticia->getTipo_fuente()).'</tipo_fuente>'."\n";
			$new_back[].="\t".'<fuente>'.$noticia->getFuente().'</fuente>'."\n";
			$new_back[].="\t".'<seccion>'.$noticia->getSeccion().'</seccion>'."\n";
			$new_back[].="\t".'<sector>'.$noticia->getSector().'</sector>'."\n";
			$new_back[].="\t".'<tipo_autor>'.$noticia->getTipo_autor().'</tipo_autor>'."\n";
			$new_back[].="\t".'<genero>'.utf8_encode($noticia->getGenero()).'</genero>'."\n";
			$new_back[].="\t".'<tema>'.$noticia->getTema().'</tema>'."\n";
			$new_back[].="\t".'<tendencia>'.$noticia->getTendencia().'</tendencia>'."\n";
			$new_back[].="\t".'<hora>'.$noticia->getHora().'</hora>'."\n";
			$new_back[].="\t".'<canal>'.$noticia->getCanal().'</canal>'."\n";
			$new_back[].="\t".'<estacion>'.$noticia->getEstacion().'</estacion>'."\n";
			$new_back[].="\t".'<duracion>'.$noticia->getDuracion().'</duracion>'."\n";
			$new_back[].="\t".'<costo>'.$noticia->getCosto().'</costo>'."\n";
			$new_back[].="\t".'<pagina>'.$noticia->getPagina().'</pagina>'."\n";
			$new_back[].="\t".'<tipo_pagina>'.$noticia->getTipo_pagina().'</tipo_pagina>'."\n";
			$new_back[].="\t".'<porcentaje_pagina>'.$noticia->getPorcentaje_pagina().'</porcentaje_pagina>'."\n";
			$new_back[].="\t".'<tiraje>'.$noticia->getTiraje().'</tiraje>'."\n";
			$new_back[].="\t".'<url>'.$noticia->getUrl().'</url>'."\n";
			$new_back[].="\t".'<autor>'.$noticia->getAutor().'</autor>'."\n";
			$new_back[].="\t".'<archivo_principal>http://sistema.operamedios.com.mx/data/noticias/television/'.$principal->getNombre_archivo().'</archivo_principal>'."\n";
			$new_back[].="\t".'<archivos_secundarios>'."\n";
			foreach($arreglo_secundarios as $sec)
			{
				$new_back[].="\t\t".'<archivo>http://sistema.operamedios.com.mx/data/noticias/television/'.$sec->getNombre_archivo().'</archivo>'."\n";
			}
			$new_back[].="\t".'</archivos_secundarios>'."\n";
  
			$new_back[].='</noticia>'."\n";

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
            
			$new_back[].='<noticia>'."\n";
			
			$new_back[].="\t".'<id_noticia>'.$noticia->getId().'</id_noticia>'."\n";
			$new_back[].="\t".'<encabezado>'.$noticia->getEncabezado().'</encabezado>'."\n";
			$new_back[].="\t".'<sintesis>'.$noticia->getSintesis().'</sintesis>'."\n";
			$new_back[].="\t".'<autor>'.$noticia->getAutor().'</autor>'."\n";
			$new_back[].="\t".'<fecha>'.$noticia->getFecha().'</fecha>'."\n";
			$new_back[].="\t".'<fecha_larga>'.$noticia->getFecha_larga().'</fecha_larga>'."\n";
			$new_back[].="\t".'<comentario>'.$noticia->getComentario().'</comentario>'."\n";
			$new_back[].="\t".'<tipo_fuente>'.utf8_encode($noticia->getTipo_fuente()).'</tipo_fuente>'."\n";
			$new_back[].="\t".'<fuente>'.$noticia->getFuente().'</fuente>'."\n";
			$new_back[].="\t".'<seccion>'.$noticia->getSeccion().'</seccion>'."\n";
			$new_back[].="\t".'<sector>'.$noticia->getSector().'</sector>'."\n";
			$new_back[].="\t".'<tipo_autor>'.$noticia->getTipo_autor().'</tipo_autor>'."\n";
			$new_back[].="\t".'<genero>'.utf8_encode($noticia->getGenero()).'</genero>'."\n";
			$new_back[].="\t".'<tema>'.$noticia->getTema().'</tema>'."\n";
			$new_back[].="\t".'<tendencia>'.$noticia->getTendencia().'</tendencia>'."\n";
			$new_back[].="\t".'<hora>'.$noticia->getHora().'</hora>'."\n";
			$new_back[].="\t".'<canal>'.$noticia->getCanal().'</canal>'."\n";
			$new_back[].="\t".'<estacion>'.$noticia->getEstacion().'</estacion>'."\n";
			$new_back[].="\t".'<duracion>'.$noticia->getDuracion().'</duracion>'."\n";
			$new_back[].="\t".'<costo>'.$noticia->getCosto().'</costo>'."\n";
			$new_back[].="\t".'<pagina>'.$noticia->getPagina().'</pagina>'."\n";
			$new_back[].="\t".'<tipo_pagina>'.$noticia->getTipo_pagina().'</tipo_pagina>'."\n";
			$new_back[].="\t".'<porcentaje_pagina>'.$noticia->getPorcentaje_pagina().'</porcentaje_pagina>'."\n";
			$new_back[].="\t".'<tiraje>'.$noticia->getTiraje().'</tiraje>'."\n";
			$new_back[].="\t".'<url>'.$noticia->getUrl().'</url>'."\n";
			$new_back[].="\t".'<autor>'.$noticia->getAutor().'</autor>'."\n";
			$new_back[].="\t".'<archivo_principal>http://sistema.operamedios.com.mx/data/noticias/radio/'.$principal->getNombre_archivo().'</archivo_principal>'."\n";
			$new_back[].="\t".'<archivos_secundarios>'."\n";
			foreach($arreglo_secundarios as $sec)
			{
				$new_back[].="\t\t".'<archivo>http://sistema.operamedios.com.mx/data/noticias/radio/'.$sec->getNombre_archivo().'</archivo>'."\n";
			}
			$new_back[].="\t".'</archivos_secundarios>'."\n";
  
			$new_back[].='</noticia>'."\n";

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
            
			$new_back[].='<noticia>'."\n";
			
			$new_back[].="\t".'<id_noticia>'.$noticia->getId().'</id_noticia>'."\n";
			$new_back[].="\t".'<encabezado>'.$noticia->getEncabezado().'</encabezado>'."\n";
			$new_back[].="\t".'<sintesis>'.$noticia->getSintesis().'</sintesis>'."\n";
			$new_back[].="\t".'<autor>'.$noticia->getAutor().'</autor>'."\n";
			$new_back[].="\t".'<fecha>'.$noticia->getFecha().'</fecha>'."\n";
			$new_back[].="\t".'<fecha_larga>'.$noticia->getFecha_larga().'</fecha_larga>'."\n";
			$new_back[].="\t".'<comentario>'.$noticia->getComentario().'</comentario>'."\n";
			$new_back[].="\t".'<tipo_fuente>'.utf8_encode($noticia->getTipo_fuente()).'</tipo_fuente>'."\n";
			$new_back[].="\t".'<fuente>'.$noticia->getFuente().'</fuente>'."\n";
			$new_back[].="\t".'<seccion>'.$noticia->getSeccion().'</seccion>'."\n";
			$new_back[].="\t".'<sector>'.$noticia->getSector().'</sector>'."\n";
			$new_back[].="\t".'<tipo_autor>'.$noticia->getTipo_autor().'</tipo_autor>'."\n";
			$new_back[].="\t".'<genero>'.utf8_encode($noticia->getGenero()).'</genero>'."\n";
			$new_back[].="\t".'<tema>'.$noticia->getTema().'</tema>'."\n";
			$new_back[].="\t".'<tendencia>'.$noticia->getTendencia().'</tendencia>'."\n";
			$new_back[].="\t".'<hora>'.$noticia->getHora().'</hora>'."\n";
			$new_back[].="\t".'<canal>'.$noticia->getCanal().'</canal>'."\n";
			$new_back[].="\t".'<estacion>'.$noticia->getEstacion().'</estacion>'."\n";
			$new_back[].="\t".'<duracion>'.$noticia->getDuracion().'</duracion>'."\n";
			$new_back[].="\t".'<costo>'.$noticia->getCosto().'</costo>'."\n";
			$new_back[].="\t".'<pagina>'.$noticia->getPagina().'</pagina>'."\n";
			$new_back[].="\t".'<tipo_pagina>'.$noticia->getTipo_pagina().'</tipo_pagina>'."\n";
			$new_back[].="\t".'<porcentaje_pagina>'.$noticia->getPorcentaje_pagina().'</porcentaje_pagina>'."\n";
			$new_back[].="\t".'<tiraje>'.$noticia->getTiraje().'</tiraje>'."\n";
			$new_back[].="\t".'<url>'.$noticia->getUrl().'</url>'."\n";
			$new_back[].="\t".'<autor>'.$noticia->getAutor().'</autor>'."\n";
			$new_back[].="\t".'<archivo_principal>http://sistema.operamedios.com.mx/data/noticias/periodico/'.$principal->getNombre_archivo().'</archivo_principal>'."\n";
			$new_back[].="\t".'<archivos_secundarios>'."\n";
			foreach($arreglo_secundarios as $sec)
			{
				$new_back[].="\t\t".'<archivo>http://sistema.operamedios.com.mx/data/noticias/periodico/'.$sec->getNombre_archivo().'</archivo>'."\n";
			}
			$new_back[].="\t".'</archivos_secundarios>'."\n";
  
			$new_back[].='</noticia>'."\n";

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
            
			$new_back[].='<noticia>'."\n";
			
			$new_back[].="\t".'<id_noticia>'.$noticia->getId().'</id_noticia>'."\n";
			$new_back[].="\t".'<encabezado>'.$noticia->getEncabezado().'</encabezado>'."\n";
			$new_back[].="\t".'<sintesis>'.$noticia->getSintesis().'</sintesis>'."\n";
			$new_back[].="\t".'<autor>'.$noticia->getAutor().'</autor>'."\n";
			$new_back[].="\t".'<fecha>'.$noticia->getFecha().'</fecha>'."\n";
			$new_back[].="\t".'<fecha_larga>'.$noticia->getFecha_larga().'</fecha_larga>'."\n";
			$new_back[].="\t".'<comentario>'.$noticia->getComentario().'</comentario>'."\n";
			$new_back[].="\t".'<tipo_fuente>'.$noticia->getTipo_fuente().'</tipo_fuente>'."\n";
			$new_back[].="\t".'<fuente>'.$noticia->getFuente().'</fuente>'."\n";
			$new_back[].="\t".'<seccion>'.$noticia->getSeccion().'</seccion>'."\n";
			$new_back[].="\t".'<sector>'.$noticia->getSector().'</sector>'."\n";
			$new_back[].="\t".'<tipo_autor>'.$noticia->getTipo_autor().'</tipo_autor>'."\n";
			$new_back[].="\t".'<genero>'.utf8_encode($noticia->getGenero()).'</genero>'."\n";
			$new_back[].="\t".'<tema>'.$noticia->getTema().'</tema>'."\n";
			$new_back[].="\t".'<tendencia>'.$noticia->getTendencia().'</tendencia>'."\n";
			$new_back[].="\t".'<hora>'.$noticia->getHora().'</hora>'."\n";
			$new_back[].="\t".'<canal>'.$noticia->getCanal().'</canal>'."\n";
			$new_back[].="\t".'<estacion>'.$noticia->getEstacion().'</estacion>'."\n";
			$new_back[].="\t".'<duracion>'.$noticia->getDuracion().'</duracion>'."\n";
			$new_back[].="\t".'<costo>'.$noticia->getCosto().'</costo>'."\n";
			$new_back[].="\t".'<pagina>'.$noticia->getPagina().'</pagina>'."\n";
			$new_back[].="\t".'<tipo_pagina>'.$noticia->getTipo_pagina().'</tipo_pagina>'."\n";
			$new_back[].="\t".'<porcentaje_pagina>'.$noticia->getPorcentaje_pagina().'</porcentaje_pagina>'."\n";
			$new_back[].="\t".'<tiraje>'.$noticia->getTiraje().'</tiraje>'."\n";
			$new_back[].="\t".'<url>'.$noticia->getUrl().'</url>'."\n";
			$new_back[].="\t".'<autor>'.$noticia->getAutor().'</autor>'."\n";
			$new_back[].="\t".'<archivo_principal>http://sistema.operamedios.com.mx/data/noticias/revista/'.$principal->getNombre_archivo().'</archivo_principal>'."\n";
			$new_back[].="\t".'<archivos_secundarios>'."\n";
			foreach($arreglo_secundarios as $sec)
			{
				$new_back[].="\t\t".'<archivo>http://sistema.operamedios.com.mx/data/noticias/revista/'.$sec->getNombre_archivo().'</archivo>'."\n";
			}
			$new_back[].="\t".'</archivos_secundarios>'."\n";
  
			$new_back[].='</noticia>'."\n";

            $output = join("", $new_back);
        
            break; // end case 4, revista

        case 5: // internet

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
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_int ON (fuente_int.id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_int ON (noticia_int.id_noticia=noticia.id_noticia)
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
            
			$new_back[].='<noticia>'."\n";
			
			$new_back[].="\t".'<id_noticia>'.$noticia->getId().'</id_noticia>'."\n";
			$new_back[].="\t".'<encabezado>'.$noticia->getEncabezado().'</encabezado>'."\n";
			$new_back[].="\t".'<sintesis>'.$noticia->getSintesis().'</sintesis>'."\n";
			$new_back[].="\t".'<autor>'.$noticia->getAutor().'</autor>'."\n";
			$new_back[].="\t".'<fecha>'.$noticia->getFecha().'</fecha>'."\n";
			$new_back[].="\t".'<fecha_larga>'.$noticia->getFecha_larga().'</fecha_larga>'."\n";
			$new_back[].="\t".'<comentario>'.$noticia->getComentario().'</comentario>'."\n";
			$new_back[].="\t".'<tipo_fuente>'.$noticia->getTipo_fuente().'</tipo_fuente>'."\n";
			$new_back[].="\t".'<fuente>'.$noticia->getFuente().'</fuente>'."\n";
			$new_back[].="\t".'<seccion>'.$noticia->getSeccion().'</seccion>'."\n";
			$new_back[].="\t".'<sector>'.$noticia->getSector().'</sector>'."\n";
			$new_back[].="\t".'<tipo_autor>'.$noticia->getTipo_autor().'</tipo_autor>'."\n";
			$new_back[].="\t".'<genero>'.utf8_encode($noticia->getGenero()).'</genero>'."\n";
			$new_back[].="\t".'<tema>'.$noticia->getTema().'</tema>'."\n";
			$new_back[].="\t".'<tendencia>'.$noticia->getTendencia().'</tendencia>'."\n";
			$new_back[].="\t".'<hora>'.$noticia->getHora().'</hora>'."\n";
			$new_back[].="\t".'<canal>'.$noticia->getCanal().'</canal>'."\n";
			$new_back[].="\t".'<estacion>'.$noticia->getEstacion().'</estacion>'."\n";
			$new_back[].="\t".'<duracion>'.$noticia->getDuracion().'</duracion>'."\n";
			$new_back[].="\t".'<costo>'.$noticia->getCosto().'</costo>'."\n";
			$new_back[].="\t".'<pagina>'.$noticia->getPagina().'</pagina>'."\n";
			$new_back[].="\t".'<tipo_pagina>'.$noticia->getTipo_pagina().'</tipo_pagina>'."\n";
			$new_back[].="\t".'<porcentaje_pagina>'.$noticia->getPorcentaje_pagina().'</porcentaje_pagina>'."\n";
			$new_back[].="\t".'<tiraje>'.$noticia->getTiraje().'</tiraje>'."\n";
			$new_back[].="\t".'<url>'.$noticia->getUrl().'</url>'."\n";
			$new_back[].="\t".'<autor>'.$noticia->getAutor().'</autor>'."\n";
			$new_back[].="\t".'<archivo_principal>http://sistema.operamedios.com.mx/data/noticias/internet/'.$principal->getNombre_archivo().'</archivo_principal>'."\n";
			$new_back[].="\t".'<archivos_secundarios>'."\n";
			foreach($arreglo_secundarios as $sec)
			{
				$new_back[].="\t\t".'<archivo>http://sistema.operamedios.com.mx/data/noticias/internet/'.$sec->getNombre_archivo().'</archivo>'."\n";
			}
			$new_back[].="\t".'</archivos_secundarios>'."\n";
  
			$new_back[].='</noticia>'."\n";

            $output = join("", $new_back);

            break; // end case 5, internet

    } // end switch
	 
	 return $output;
	 
 }// end function noticia_xml






if(isset($_GET['clave']) && $_GET['clave'] == 'YmFsdGF6YXI=' )
{
	// la noticia mas reciente

$query_noticias =  "SELECT
                      noticia.id_noticia AS id_noticia,
                      noticia.id_tipo_fuente AS id_tipo_fuente
                    FROM
                     asigna
                     INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                     INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
					 WHERE
                     empresa.id_empresa = 171
					 ORDER BY 
					 fecha DESC, id_noticia DESC
					 LIMIT 30;
					 ";

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

$xml = "";
$xml .= '<?xml version="1.0" encoding="utf-8" ?>' . "\n";
$xml.= '<noticiasOPM>'."\n";
foreach($arreglo_noti as $value => $label)
{
	$xml.= noticia_xml($base, $value, $label);
	//$xml.= $value;
}
$xml.= '</noticiasOPM>'."\n";
header('Content-Type: application/xml; charset=utf-8');
echo $xml;



	
} // end if clave = .........
else
{
	die("Error");
}


  
  
?>