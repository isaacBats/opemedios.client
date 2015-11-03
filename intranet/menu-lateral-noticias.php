<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
<img src="images/trans.gif" width="1" height="7" alt=""><br>
<b><font class="titulo-azul">Noticias</font></b><br>
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
        $base->execute_query2("SELECT id_tema, nombre FROM tema where id_empresa =".$current_account->get_id());
        while($row_tema = $base->get_row_assoc2()) {
            if($_GET['valor'] == $row_tema['id_tema'] &&  $_GET['tipo_query'] == 3) {
                echo '<option value="'.$row_tema['id_tema'].'" selected="selected">'.$row_tema['nombre'].'</option>';
            }
            else {
                echo '<option value="'.$row_tema['id_tema'].'">'.$row_tema['nombre'].'</option>';
            }

        }
		$base->free_result2();
        ?>
    </select><br>
    <input  name="tipo_query" type="hidden" value="3">
    <img src="images/trans.gif" width="1" height="5" alt=""><br>
    <div align="center"><input type="image" src="images/b_buscar.gif" alt="buscar por tema" border="0" name ="buscar"/></div>
</form>
<img src="images/trans.gif" width="1" height="7" alt=""><br>
<img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
<img src="images/trans.gif" width="1" height="15" alt=""><br>

<img src="images/bullet-sub.gif" alt="" border="0" align="middle">&nbsp;<a href="busqueda.php" class="menu">B&uacute;squeda Avanzada</a><br>

<img src="images/trans.gif" width="1" height="7" alt=""><br>