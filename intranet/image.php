<?php
#header('Content-type: image/jpeg');
#header('Content-Disposition: filename="http://sistema.opemedios.com.mx/data/'.$_GET['pagina'].'/'.$_GET['id'].'"');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Imagen</title>
</head>
<body>
<img src="http://sistema.opemedios.com.mx/data/<?php echo $_GET['pagina']?>/<?php echo $_GET['id']?>">
</body>
</html>