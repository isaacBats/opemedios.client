<img src="images/trans.gif" width="1" height="20" alt=""><br>
<table width="880" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td valign="top" class="desarrollo">


            <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="7" alt=""><br>
            <b><font class="titulo-azul">Resultados de la B&uacute;squeda</font></b><br>
            <img src="images/trans.gif" width="1" height="7" alt=""><br>
            <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
            <img src="images/trans.gif" width="1" height="7" alt=""><br>

            <!-- iniciamos a imprimir las noticias -->
<?php
$cont_not = 1;
            foreach($arreglo_noti as $id_not => $idtf) {
				
				echo '<span style="font-size:small">'.$cont_not.'.-</span><br>';
				$cont_not ++ ;
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