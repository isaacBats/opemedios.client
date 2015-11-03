<?php 
// hacemos el arreglo de lso temas por que se necesitan variables de datoscliente.php

//creamos un arreglo para mostrar los temas del cliente
$base->execute_query("SELECT id_tema, nombre FROM tema where id_empresa =".$current_account->get_id());
$arreglo_temas = array();
while($tema = $base->get_row_assoc())
{
    $arreglo_temas[$tema['id_tema']] = $tema["nombre"];
}
//creamos un arreglo para mostrar el menu tipo_fuente
$base->execute_query("SELECT * FROM tipo_fuente");
$arreglo_tipos_fuente = array();
while($tipo_fuente = $base->get_row_assoc())
{
    $arreglo_tipos_fuente[$tipo_fuente['id_tipo_fuente']] = $tipo_fuente["descripcion"];
}
?>
<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
<img src="images/trans.gif" width="1" height="5" alt=""><br>
<form  name="form1" action="resultados_new.php" method="get">
<table width="100%" border="0">
	<tr>
    	<td width="10"></td>
        <td width="50" valign="top"><font class="label1"><b>Buscar</b></font></td>
        <td width="165" class="label1"> Entre:<script>DateInput('fecha1', true, 'DD-MON-YYYY')</script></td>
        <td class="label1" width="165">y:<script>DateInput('fecha2', true, 'DD-MON-YYYY')</script></td>
        <td class="label1" width="210">Tema:<br /><select name="id_tema" id="id_tema" style="width:200px; font-size:x-small;">
                      <option value="0">**Todos los Temas**</option>
                      <?php
						foreach ($arreglo_temas as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
						}
						?>
                      </select></td>
      <td class="label1" width="230">Tipo de Fuente:<br /><select name="id_tipo_fuente" id="id_tipo_fuente" style="width:200px; font-size:x-small;">
                      <option value="0">**Todos los Tipos de Fuente**</option>
                      <?php
						foreach ($arreglo_tipos_fuente as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.utf8_encode($label).'</option>';
						}
						?>
          </select></td>
        <td class="label1" width="60" valign="middle"><input type="image" src="images/btn_buscar1.gif" alt="buscar" border="0" name ="buscar"/></td>
        <td><span class="label1">
          <input name="busqueda" type="hidden" id="busqueda" value="true" />
          <input name="accion" type="hidden" id="busqueda" value="1" />
        </span></td>
    </tr>
	<tr height="5">
	  <td></td>
	  <td colspan="2" valign="top" class="label1"><a href="busqueda.php" class="label1">Búsqueda Avanzada</a></td>
	  <td class="label1">&nbsp;</td>
	  <td class="label1">&nbsp;</td>
	  <td class="label1">&nbsp;</td>
	  <td class="label1" valign="middle">&nbsp;</td>
	  <td></td>
    </tr>
</table>
</form>
<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
<img src="images/trans.gif" width="1" height="5" alt=""><br>
<table width="900" cellspacing="0" cellpadding="3" border="0">
    <tr>
        <td valign="top">
        <!-- tabla noticia grande medio electronico 
        <table border="0">
        	<tr>
            	<td width="10"></td>
                <td width="380" valign="top">
                	<div align="center"><embed src="http://sistema.operamedios.com.mx/data/noticias/television/ID14783_783_c3.wmv" width="350" align="middle" border="2" height="250"><br><br></div>
                    <div class="label_red">Archivos Relacionados:</div>
                    <div>&nbsp;&nbsp;&loz;&nbsp;<a class="label1" href="ejemplo.jpg">Archivo1.doc</a><br />
                    	 &nbsp;&nbsp;&loz;&nbsp;<a class="label1" href="ejemplo.jpg">Archivo2.xls</a><br />
                    </div>
                </td>
                <td width="500" valign="top">
                <div>
                <a class="titulo_grande" href="ejemplo.php">ANGELINA JOLIE VISTE A SU HIJA DE HOMBRE SEGUN TELE</a>
                </div>
                <div>
<table border="0" align="center" width="100%">
                    	<tr>
                        <td width="25"></td>
                        <td width="60%" valign="top">
                        	<div class="fecha1">30 de Septiembre, 2010  --  20:00hrs<br>
                          Autor: Josué Morado (Reportero)<br>
                          Canal: 28<br><br>
                          <span class="label_red">Tema: </span><span class="label2">Tema de Ejemplo</span><br /><br />
                          <span class="label_red">Costo Beneficio: </span><span class="label2"><b>$ 1200</b></span><br />
                          </div>
                        </td>
                        <td width="40%" valign="top" align="right"><img width="130" height="65" src="" /></td>
</tr>
                    </table><br />
                    <div class="label2" align="justify"> blab eqrgewrg wergwerg rg we wg we qgqrw egfqegre qg ergwerg wergeqrwg wergwer gw werg werg erwgwerg erwgerwg wergwer gwerg erwg werg wergewr gewgerw ger weg egwe gewgewgeg ege ge eregeg ewrg blab eqrgewrg wergwerg rg we wg we qgqrw egfqegre qg ergwerg wergeqrwg wergwer gw werg werg erwgwerg erwgerwg wergwer gwerg erwg werg wergewr gewgerw ger weg egwe gewgewgeg ege ge eregeg ewrg blab eqrgewrg wergwerg rg we wg we qgqrw egfqegre qg ergwerg wergeqrwg wergwer gw werg werg erwgwerg erwgerwg wergwer gwerg erwg werg wergewr gewgerw ger weg egwe gewgewgeg ege ge eregeg ewrg 
                   </div><br />
                   <table border="0">
                   <tr>
                   	<td width="200" valign="top">
                        <div class="label_red">
                       Fuente: <font class="label2">Fuente de Ejemplo</font><br>
                       Sección: <font class="label2">Seccion de Ejemplo</font>
                   		</div>
                    </td>
                    <td width="200" valign="top">
                    	<div class="label_red">
                       Género: <font class="label2">Genero de Ejemplo</font><br>
                       Sector: <font class="label2">Sector de Ejemplo</font><br><br>
                       Tendencia: <font class="label2">Positiva</font>
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
         FIN tabla noticia grande medio electronico -->
          
            <?php
			$i=1;
			if($num_noticias > 0)
			{
				foreach($arreglo_noti as $id_not => $idtf)
				{
					//echo $id_not." -> ".$idtf."<br><br>";
					echo '<font class="label_red">'.$i.' .- </font><br>';
					echo muestra_noticia_grande($base,$id_not,$idtf);
					$i++;
				}
			}
			else
			{
				echo '<font class="label_red">No hay noticias con los parametros estabecidos</font>';
			}
			
			
			
?>
          
       </td>
    </tr>
</table>

<img src="images/trans.gif" width="1" height="30" alt=""><br>
</td>
</tr>

</table>