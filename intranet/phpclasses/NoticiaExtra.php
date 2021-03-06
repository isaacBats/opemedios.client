<?php
/**
 * En esta subclase se muestrabn los datos especificos de noticias de periodico, revista e internet
 *
 * @author Josue Morado
 */

class NoticiaExtra extends Noticia
{

    private $pagina;
    private $id_tipo_pagina;
    private $tipo_pagina;
    private $id_tamano_nota;
    private $tamano_nota;
    private $url;
    private $ubicacion;
    private $archivo_pagina;

    function __construct($datos, $tipo) // 3 = periodico, 4=revista, 5=internet
    {
        switch ($tipo)
        {
            case 3:
                parent::__construct($datos);
                $this->pagina = $datos['pagina'];
                $this->id_tipo_pagina = $datos['id_tipo_pagina'];
                $this->tipo_pagina = $datos['tipo_pagina'];
                $this->id_tamano_nota = $datos['id_tamano_nota'];
                $this->tamano_nota = $datos['tamano_nota'];
                $this->url = "www"; // se coloca www para evitar NULL en la stored function
                $this->ubicacion = "";
                $this->archivo_pagina = "";
                break;
            case 4:
                parent::__construct($datos);
                $this->pagina = $datos['pagina'];
                $this->id_tipo_pagina = $datos['id_tipo_pagina'];
                $this->tipo_pagina = $datos['tipo_pagina'];
                $this->id_tamano_nota = $datos['id_tamano_nota'];
                $this->tamano_nota = $datos['tamano_nota'];
                $this->url = "www"; // se coloca www para evitar NULL en la stored function
                $this->ubicacion = "";
                $this->archivo_pagina = "";
                break;
            case 5:
                parent::__construct($datos);
                $this->pagina = "-1";
                $this->id_tipo_pagina = "-1";// se coloca  -1 para evitar null
                $this->tipo_pagina = "";
                $this->id_tamano_nota = "-1";// se coloca -1 para evitar null
                $this->tamano_nota = "";
                $this->url = $datos['url'];
                $this->ubicacion = "";
                $this->archivo_pagina = "";
                break;

            default:
                echo "Error: se especifico un tipo de dato no valido!!";
                break;
        }
    }

    function  __destruct() {
        ;
    }
    
    public function getPagina() {
        return $this->pagina;
    }
        
    public function getId_tipo_pagina() {
        return $this->id_tipo_pagina;
    }
        
    public function getTipo_pagina() {
        return $this->tipo_pagina;
    }
        
    public function getId_tamano_nota() {
        return $this->id_tamano_nota;
    }
        
    public function getTamano_nota() {
        return $this->tamano_nota;
    }
        
    public function getUrl() {
        return $this->url;
    }

    public function setUbicacion(Ubicacion $ubicacion)
    {
        $this->ubicacion = $ubicacion;
    }

    public function setArchivoPagina(Archivo $archivo_pagina)
    {
        $this->archivo_pagina = $archivo_pagina;
    }

    public function getArchivo_pagina()
    {
        return $this->archivo_pagina;
    }
    
    private function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

        switch ($theType)
        {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
    
    public function SQL_NUEVA_NOTICIA()
    {
        $query_nuevo = sprintf("SELECT NUEVA_NOTICIA_EXTRA(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->encabezado,"text"),
            $this->GetSQLValueString($this->sintesis,"text"),
            $this->GetSQLValueString($this->autor,"text"),
            $this->GetSQLValueString($this->fecha, "date"),
            $this->GetSQLValueString($this->comentario, "text"),
            $this->GetSQLValueString($this->id_tipo_fuente,"int"),
            $this->GetSQLValueString($this->id_fuente,"int"),
            $this->GetSQLValueString($this->id_seccion,"int"),
            $this->GetSQLValueString($this->id_sector,"int"),
            $this->GetSQLValueString($this->id_tipo_autor,"int"),
            $this->GetSQLValueString($this->id_genero, "int"),
            $this->GetSQLValueString($this->id_tendencia_monitorista, "int"),
            $this->GetSQLValueString($this->id_usuario, "int"),
            $this->GetSQLValueString($this->pagina, "int"),
            $this->GetSQLValueString($this->id_tipo_pagina, "int"),
            $this->GetSQLValueString($this->id_tamano_nota, "int"),
            $this->GetSQLValueString($this->url, "text")
        );
        return $query_nuevo;
    }

        public function SQL_EDIT_NOTICIA()
    {
        $query_edit = sprintf("SELECT EDIT_NOTICIA_EXTRA(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->id,"int"),
            $this->GetSQLValueString($this->encabezado,"text"),
            $this->GetSQLValueString($this->sintesis,"text"),
            $this->GetSQLValueString($this->autor,"text"),
            $this->GetSQLValueString($this->fecha, "date"),
            $this->GetSQLValueString($this->comentario, "text"),
            $this->GetSQLValueString($this->id_tipo_fuente,"int"),
            $this->GetSQLValueString($this->id_fuente,"int"),
            $this->GetSQLValueString($this->id_seccion,"int"),
            $this->GetSQLValueString($this->id_sector,"int"),
            $this->GetSQLValueString($this->id_tipo_autor,"int"),
            $this->GetSQLValueString($this->id_genero, "int"),
            $this->GetSQLValueString($this->id_tendencia_monitorista, "int"),
            $this->GetSQLValueString($this->pagina, "int"),
            $this->GetSQLValueString($this->id_tipo_pagina, "int"),
            $this->GetSQLValueString($this->id_tamano_nota, "int"),
            $this->GetSQLValueString($this->url, "text")
        );
        return $query_edit;
    }


}//end class
?>
