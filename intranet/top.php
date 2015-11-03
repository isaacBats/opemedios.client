

	
	<LINK rel=stylesheet href="estilos.css">
	
	<script languaje="javascript">
<!--
var fecha = new Date();
var nomdia;
switch(fecha.getDay()){ 
 case 1: nomdia = "Lunes"; break;
 case 2: nomdia = "Martes"; break;
 case 3: nomdia = "Miercoles"; break;
 case 4: nomdia = "Jueves"; break;
 case 5: nomdia = "Viernes"; break;
 case 6: nomdia = "Sabado"; break;
 case 7: nomdia = "Domingo"; break;
}
var nommes;
switch(fecha.getMonth() + 1)
{
 case 1: nommes = "enero"; break;
 case 2: nommes = "febrero"; break;
 case 3: nommes = "marzo"; break;
 case 4: nommes = "abril"; break;
 case 5: nommes = "mayo"; break;
 case 6: nommes = "junio"; break;
 case 7: nommes = "julio"; break;
 case 8: nommes = "agosto"; break;
 case 9: nommes = "septiembre"; break;
 case 10: nommes = "octubre"; break;
 case 11: nommes = "noviembre"; break;
 case 12: nommes = "diciembre"; break;
}
//-->
</script>
<table width="948" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="314" align="left" valign="top"><br></td>
	<td align="right" valign="top">
	<img src="images/trans.gif" width="1" height="7" alt="" border="0"><br>
	<font class="fecha">
<img src="images/bullet-sub.gif" align="middle">  Hoy es: <script>
document.write( " " + fecha.getDate() + " de " + nommes + " de " + fecha.getYear());
</script> &nbsp; <img src="images/bullet-sub.gif" align="middle"> <span id="liveclock" style="position:relative;left:0;top:0;">
</span>

<script language="JavaScript">
<!-- Mas trucos y scripts en http://www.javascript.com.mx -->
 <!--

function show5(){
if (!document.layers&&!document.all&&!document.getElementById)
return

 var Digital=new Date()
 var hours=Digital.getHours()
 var minutes=Digital.getMinutes()
 var seconds=Digital.getSeconds()

var dn="PM"
if (hours<12)
dn="AM"
if (hours>12)
hours=hours-12
if (hours==0)
hours=12

 if (minutes<=9)
 minutes="0"+minutes
 if (seconds<=9)
 seconds="0"+seconds
//change font size here to your desire
myclock="<font class='fecha'>"+hours+":"+minutes+":"
 +seconds+" "+dn+"</font>"
if (document.layers){
document.layers.liveclock.document.write(myclock)
document.layers.liveclock.document.close()
}
else if (document.all)
liveclock.innerHTML=myclock
else if (document.getElementById)
document.getElementById("liveclock").innerHTML=myclock
setTimeout("show5()",1000)
 }


window.onload=show5
 //-->
 </script><br>
	</td>
</tr>
</table>

<img src="images/top.gif" width="948" height="15" alt=""><br>

