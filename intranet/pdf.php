<?php
include("phpdao/OpmDB.php");
include("phpclasses/PrimeraPlana.php");
include("phpclasses/Cartones.php");
include("phpclasses/Financiero.php");
include("phpdelegates/db_array.php");
require_once("pdf/dompdf_config.inc.php");
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
$body = '<br>';

switch($_POST['action']){
        case "primera_plana":
        $carpeta = "primera_plana";
        break;
        case "portada_financiera":
        $carpeta = "financiero";
        break;
        case "carton":
        $carpeta = "cartones";
        break;
}
foreach ($_POST['row_id'] as $value) {
 $_POST['row_id'];
 $value;
 $query = 'SELECT a.fecha,a.imagen,a.id_'.$_POST['action'].' id,b.nombre fuente
            FROM '.$_POST['action'].' a, fuente b where a.id_fuente = b.id_fuente
            AND id_'.$_POST['action'].' = '.$value;
$base->execute_query($query);
$row_query = $base->get_row_assoc();
$body .= '<img style="width: 75%;" src="http://sistema.opemedios.com.mx/data'.$carpeta.'/'.$row_query['imagen'].'"/><br>';
$body .= $row_query['fuente'].'<br>';
$body .= $row_query['fecha'].'<br>';
$body .= '<br><br><br>';
}
   $html ='
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<style>
body {
  margin: 18pt 18pt 24pt 18pt;
}

* {
  font-family: georgia,serif;
  font-weight: bold;
}

p {
  text-align: justify;
  font-size: 1em;
  margin: 0.5em;
  padding: 10px;
}
.titulo_principal {	font-family: Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bold;
}
.contenido {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9.5px;
	align
; 							white-space: normal;
	text-align: justify;
}
</style>
<script type="text/php">

if ( isset($pdf) ) {

  $font = Font_Metrics::get_font("verdana");;
  $size = 6;
  $color = array(0,0,0);
  $text_height = Font_Metrics::get_font_height($font, $size);

  $foot = $pdf->open_object();

  $w = $pdf->get_width();
  $h = $pdf->get_height();

  // Draw a line along the bottom
  $y = $h - $text_height - 24;
  $pdf->line(16, $y, $w - 16, $y, $color, 0.5);
  $pdf->close_object();
  $pdf->add_object($foot, "all");
  $text = "Page {PAGE_NUM} of {PAGE_COUNT}";

  // Center the text
  $width = Font_Metrics::get_text_width("Page 1 of 2", $font, $size);
  $pdf->page_text($w / 2 - $width / 2, $y, $text, $font, $size, $color);

}
</script>
</head>
<body>
<p align="center">'

.$body.

'</div>

</body>
</html>
';//'.$htmtl.'
      $dompdf = new DOMPDF();
	  //$pdf = $dompdf->open_object();
   // $dompdf ->image('../pdf1/www/images/dompdf_simple.png');
   // void image(string $img_url, string $img_type, float $x, float $y, int $w, int $h)
   // void set_page_count(int $count)
   //http://www.digitaljunkies.ca/dompdf/usage.php

      $dompdf->load_html($html);

	  $dompdf->set_paper("610","792", "landscape");

      $dompdf->render();

      $dompdf->stream("reporte.pdf");

exit;
?>