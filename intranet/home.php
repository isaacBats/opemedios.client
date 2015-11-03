<img src="images/trans.gif" width="1" height="20" alt=""><br>
<table width="880" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="140" valign="top" class="desarrollo">

            <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="7" alt=""><br>
            <b><font class="titulo-azul">Noticias</b> (Hoy)</font><br>
            <img src="images/trans.gif" width="1" height="7" alt=""><br>
            <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="15" alt=""><br>




            <img src="images/bullet-sub.gif" alt="" border="0" align="middle">&nbsp;<a href="index.php?tipo_query=2&valor=1" class="menu">Televisi&oacute;n</a><br>

            <img src="images/trans.gif" width="1" height="7" alt=""><br>


            <img src="images/bullet-sub.gif" alt="" border="0" align="middle">&nbsp;<a href="index.php?tipo_query=2&valor=2" class="menu">Radio</a><br>

            <img src="images/trans.gif" width="1" height="7" alt=""><br>


            <img src="images/bullet-sub.gif" alt="" border="0" align="middle">&nbsp;<a href="index.php?tipo_query=2&valor=3" class="menu">Peri&oacute;dico</a><br>

            <img src="images/trans.gif" width="1" height="7" alt=""><br>


            <img src="images/bullet-sub.gif" alt="" border="0" align="middle">&nbsp;<a href="index.php?tipo_query=2&valor=4" class="menu">Revista</a><br>

            <img src="images/trans.gif" width="1" height="7" alt=""><br>


            <img src="images/bullet-sub.gif" alt="" border="0" align="middle">&nbsp;<a href="index.php?tipo_query=2&valor=5" class="menu">Internet</a><br>


            <img src="images/trans.gif" width="1" height="15" alt=""><br>
            <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="15" alt=""><br>
            <form method="get" name="form_temas" action="index.php">
                <select name="valor" id="id_tema" style="background-color: FCF5F1; font-size: xx-small; width:150px; height:22px;">
                    <option value="0" selected>** Por Tema ** </option>
                    <?php
                    $base->execute_query("SELECT id_tema, nombre FROM tema where id_empresa =".$current_account->get_id());
                    while($row_tema = $base->get_row_assoc()) {
                        if($_GET['valor'] == $row_tema['id_tema'] &&  $_GET['tipo_query'] == 3)
                        {
                            echo '<option value="'.$row_tema['id_tema'].'" selected="selected">'.$row_tema['nombre'].'</option>';
                        }
                        else
                        {
                            echo '<option value="'.$row_tema['id_tema'].'">'.$row_tema['nombre'].'</option>';
                        }
                        
                    }
                    ?>
                </select><br>
                <input  name="tipo_query" type="hidden" value="3">
                <img src="images/trans.gif" width="1" height="5" alt=""><br>
                <div align="center"><input type="image" src="images/b_buscar.gif" alt="buscar por tema" border="0" name ="buscar"/></div>
            </form>
            <img src="images/trans.gif" width="1" height="7" alt=""><br>
            <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="15" alt=""><br>

            <img src="images/bullet-sub.gif" alt="" border="0" align="middle">&nbsp;<a href="busqueda.php" class="menu">B&uacute;squeda avanzada</a><br>

            <img src="images/trans.gif" width="1" height="7" alt=""><br>

        </td>
        <td width="35">&nbsp;</td>
        <td valign="top" class="desarrollo">


            <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="7" alt=""><br>
            <?php
            $arreglotf = array(1=>"Televisión",
                               2=>"Radio",
                               3=>"Periódico",
                               4=>"Revista",
                           5=>"Internet");
            switch($tipoquery)
            {
                case 1:
                    $label = "";
					$label2 = "No se han publicado noticias el día de hoy";
                    break;
                case 2:
                    $label= "(".$arreglotf[$value].")";
					$label2= "No se han publicado noticias de ".$arreglotf[$value]." el día de hoy";
                    break;
                case 3:
                    $base->execute_query("SELECT nombre FROM tema WHERE id_tema =".$value);
                    $rowt = $base->get_row_assoc();
                    $label = "(Tema: ".$rowt['nombre'].")";
					$label2 = " No se han publicado Noticias del Tema ".$rowt['nombre']." el dia de hoy";
                    break;

            }
            ?>
            <b><font class="titulo-azul">Noticias del <?php echo getFecha_actual();?> </font></b><font class="titulo-azul"><?php echo $label;?></font><br>
            <img src="images/trans.gif" width="1" height="7" alt=""><br>
            <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="15" alt=""><br>

            <!-- iniciamos a imprimir las noticias -->
<?php
			if($num_noticias == 0)
			{
				echo '<br><font color="#FF0000">'.$label2.'</font>';
			}
			
            foreach($arreglo_noti as $id_not => $idtf) {

                echo muestra_noticia($base,$id_not,$idtf,$row_color);
                if($row_color == 1) {$row_color = 2;}else {$row_color = 1;}
            }
            ?>



            <!-- inicia tabla de noticia
            <table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#eaf1ff">
            <tr bgcolor="#ffede1">
                    <td class="desarrollo1">&nbsp; <b>Televisi�n</b> | <b>Tema:</b> Aerol�neas | <b>Fecha:</b> 6 de enero de 2010 | <b>Hora:</b> 17:00 | <b>Canal:</b> 5</td>
                    <td align="right" class="desarrollo1"><b>Clave:</b>1234&nbsp;</td>
                    <td align="right" bgcolor="#c0d2e6" class="desarrollo1"><b>Costo/Beneficio: $40,500</b>&nbsp;</td>
            </tr>
            <tr>
                    <td colspan="3" class="desarrollo">
	<div align="justify" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;">

	<a href="noticia-detalle.php" class="titulo-news"><b>La torre m�s alta del mundo</b></a><br>
                    <img src="images/trans.gif" width="1" height="5" alt=""><br>
	Defiende el secretario de Gobernaci�n, Fernando G�mez Mont, al Presidente; rechaza que el Ejecutivo no escuche al Congreso, como acus� el senador pri�sta Manlio Fabio Beltrones <br></div>
            <div align="right"></div>
	</td>
            </tr>
            <tr>
                    <td colspan="3" class="desarrollo2">
                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="5" alt=""><br>

	&nbsp; <b>Fuente:</b> Ejemplo de Fuente | <b>Autor:</b> Joaqu�n L�pez D.  |  <b>Seccion:</b> Internacionales  | <b>G�nero:</b> Reportaje  |  <b>Tendencia:</b> Positiva  | <b>Sector:</b> Aviaci�n<br>

                    <img src="images/trans.gif" width="1" height="12" alt=""><br>


	 </td>
            </tr>
            </table><br>

            <table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#fcf5f1">
            <tr bgcolor="#ffede1">
                    <td class="desarrollo1">&nbsp; <b>Radio</b> | <b>Tema:</b> Aerol�neas | <b>Fecha:</b> 6 de enero de 2010 | <b>Hora:</b> 17:00 | <b>Canal:</b> 5</td>
                    <td align="right" class="desarrollo1"><b>Clave:</b>1234&nbsp;</td>
                    <td align="right" bgcolor="#c0d2e6" class="desarrollo1"><b>Costo/Beneficio: $40,500</b>&nbsp;</td>
            </tr>
            <tr>
                    <td colspan="3" class="desarrollo">
	<div align="justify" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;">

	<a href="noticia-detalle.php" class="titulo-news"><b>Monreal busca gubernatura de Zacatecas </b> </a><br>
                    <img src="images/trans.gif" width="1" height="5" alt=""><br>
            El presidente municipal de Fresnillo y hermano del senador Ricardo Monreal, dijo que pedir� licencia al cabildo para contender por el Partido del Trabajo en esa entidad <br></div>
	</td>
            </tr>
            <tr>
                    <td colspan="3" class="desarrollo2">
                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="5" alt=""><br>

	&nbsp; <b>Fuente:</b> Ejemplo de Fuente | <b>Autor:</b> Joaqu�n L�pez D.  |  <b>Seccion:</b> Internacionales  | <b>G�nero:</b> Reportaje  |  <b>Tendencia:</b> Positiva  | <b>Sector:</b> Aviaci�n<br>

                    <img src="images/trans.gif" width="1" height="12" alt=""><br>

	 </td>
            </tr>
            </table><br>

            <table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#eaf1ff">
            <tr bgcolor="#ffede1">
                    <td class="desarrollo1">&nbsp; <b>Radio</b> | <b>Tema:</b> Aerol�neas | <b>Fecha:</b> 6 de enero de 2010 | <b>Hora:</b> 17:00 | <b>Canal:</b> 5</td>
                    <td align="right" class="desarrollo1"><b>Clave:</b>1234&nbsp;</td>
                    <td align="right" bgcolor="#c0d2e6" class="desarrollo1"><b>Costo/Beneficio: $40,500</b>&nbsp;</td>
            </tr>
            <tr>
                    <td colspan="3" class="desarrollo">
	<div align="justify" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;">

	<a href="noticia-detalle.php" class="titulo-news"><b>Inicia venta de bases de licitaci�n en telefon�a m�vil</b> </a><br>
                    <img src="images/trans.gif" width="1" height="5" alt=""><br>
            Este mi�rcoles inici� la venta de las Bases de las Licitaciones P�blicas 20 y 21 para el uso, aprovechamiento y explotaci�n de las bandas 1.9 y 1.7 Gigahertz (Ghz) del espectro radioel�ctrico para servicios de telefon�a m�vil y banda ancha. <br></div>
	</td>
            </tr>
            <tr>
                    <td colspan="3" class="desarrollo2">
                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="5" alt=""><br>

	&nbsp; <b>Fuente:</b> Ejemplo de Fuente | <b>Autor:</b> Joaqu�n L�pez D.  |  <b>Seccion:</b> Internacionales  | <b>G�nero:</b> Reportaje  |  <b>Tendencia:</b> Positiva  | <b>Sector:</b> Aviaci�n<br>

                    <img src="images/trans.gif" width="1" height="12" alt=""><br>

	 </td>
            </tr>
            </table><br>


            <table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#fcf5f1">
            <tr bgcolor="#ffede1">
                    <td class="desarrollo1">&nbsp; <b>Radio</b> | <b>Tema:</b> Aerol�neas | <b>Fecha:</b> 6 de enero de 2010 | <b>Hora:</b> 17:00 | <b>Canal:</b> 5</td>
                    <td align="right" class="desarrollo1"><b>Clave:</b>1234&nbsp;</td>
                    <td align="right" bgcolor="#c0d2e6" class="desarrollo1"><b>Costo/Beneficio: $40,500</b>&nbsp;</td>
            </tr>
            <tr>
                    <td colspan="3" class="desarrollo">
	<div align="justify" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;">

	<a href="noticia-detalle.php" class="titulo-news"><b>G�mez Mont pide a SME soluci�n pac�fica</b> </a><br>
                    <img src="images/trans.gif" width="1" height="5" alt=""><br>
            La Secretar�a de Gobernaci�n conmin� al Sindicato Mexicano de Electricistas (SME) a reencontrar una soluci�n por la v�a pac�fica y del di�logo, tras el conflicto generado por la extinci�n de Luz y Fuerza del Centro. <br></div>
	</td>
            </tr>
            <tr>
                    <td colspan="3" class="desarrollo2">
                    <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="5" alt=""><br>

	&nbsp; <b>Fuente:</b> Ejemplo de Fuente | <b>Autor:</b> Joaqu�n L�pez D.  |  <b>Seccion:</b> Internacionales  | <b>G�nero:</b> Reportaje  |  <b>Tendencia:</b> Positiva  | <b>Sector:</b> Aviaci�n<br>

                    <img src="images/trans.gif" width="1" height="12" alt=""><br>

	 </td>
            </tr>
            </table><br>



            -->

            <img src="images/trans.gif" width="1" height="20" alt=""><br>
            <font class="desarrollo1"><div align="right">Total Costo/Beneficio: <strong>$ <?php echo $_SESSION['suma_costo'];?></strong></div></font><br>

        </td>
    </tr>
</table>
<img src="images/trans.gif" width="1" height="30" alt=""><br>
</td>
</tr>

</table>