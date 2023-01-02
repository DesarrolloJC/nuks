<?php

class Table
{
    public $TABLA;
    public $PRKEY;
    public $CONN;
    private $ACT;
    private $CLN;

    public function __construct($DB)
    {
        $this->CONN = $DB;
        $this->ACT = array(
            "!", "\"", "$", "%", "'", "(", ")", "*", "+", ",", "-", ".", "/", ":", "<", "=", ">", "?", "@",
            "[", "\\", "]", "^", "_", "`", "{", "|", "}", "~",
            "¡", "¢", "£", "¤", "¥", "¦", "§", "¨", "©", "ª", "«", "¬", "®", "¯",
            "°", "±", "²", "³", "´", "µ", "¶", "·", "¸", "¹", "º", "»", "¼", "½", "¾", "¿",
            "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
            "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "×", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "Þ", "ß",
            "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
            "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "÷", "ø", "ù", "ú", "û", "ü", "ý", "þ", "ÿ",
            "Œ", "œ", "Š", "š", "Ÿ", "ƒ",
            "–", "—", "‘", "’", "‚", "“", "”", "„", "†", "‡", "•", "…", "‰", "€", "™",
        );
        $this->CLN = array(
            "&#33;", "&#34;", "&#36;", "&#37;", "&#39;", "&#40;", "&#41;", "&#42;", "&#43;", "&#44;", "&#45;", "&#46;", "&#47;", "&#58;", "&#60;", "&#61;", "&#62;", "&#63;", "&#64;",
            "&#91;", "&#92;", "&#93;", "&#94;", "&#95;", "&#96;", "&#123;", "&#124;", "&#125;", "&#126;",
            "&#161;", "&#162;", "&#163;", "&#164;", "&#165;", "&#166;", "&#167;", "&#168;", "&#169;", "&#170;", "&#171;", "&#172;", "&#174;", "&#175;",
            "&#176;", "&#177;", "&#178;", "&#179;", "&#180;", "&#181;", "&#182;", "&#183;", "&#184;", "&#185;", "&#186;", "&#187;", "&#188;", "&#189;", "&#190;", "&#191;",
            "&#192;", "&#193;", "&#194;", "&#195;", "&#196;", "&#197;", "&#198;", "&#199;", "&#200;", "&#201;", "&#202;", "&#203;", "&#204;", "&#205;", "&#206;", "&#207;",
            "&#208;", "&#209;", "&#210;", "&#211;", "&#212;", "&#213;", "&#214;", "&#215;", "&#216;", "&#217;", "&#218;", "&#219;", "&#220;", "&#221;", "&#222;", "&#223;",
            "&#224;", "&#225;", "&#226;", "&#227;", "&#228;", "&#229;", "&#230;", "&#231;", "&#232;", "&#233;", "&#234;", "&#235;", "&#236;", "&#237;", "&#238;", "&#239;",
            "&#240;", "&#241;", "&#242;", "&#243;", "&#244;", "&#245;", "&#246;", "&#247;", "&#248;", "&#249;", "&#250;", "&#251;", "&#252;", "&#253;", "&#254;", "&#255;",
            "&#338;", "&#339;", "&#352;", "&#353;", "&#376;", "&#402;",
            "&#8211;", "&#8212;", "&#8216;", "&#8217;", "&#8218;", "&#8220;", "&#8221;", "&#8222;", "&#8224;", "&#8225;", "&#8226;", "&#8230;", "&#8240;", "&#8364;", "&#8482;",
        );
    }

    /**** Métodos obtener ****/

    public function getAll()
    {
        $SQL = "SELECT * FROM %s ";

        $SQL = sprintf($SQL, $this->TABLA);
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    public function getById($ID)
    {
        $SQL = "SELECT * FROM %s WHERE %s = %d LIMIT 1;";

        $SQL = sprintf($SQL, $this->TABLA, $this->PRKEY, $ID);
        $data = $this->CONN->Query($SQL);
        return $data[0];
    }


    public function getTotalRegistros()
    {
        $SQL = "SELECT COUNT(*) AS total FROM %s ";
        $SQL = sprintf($SQL, $this->TABLA);
        $data = $this->CONN->Query($SQL);
        return $data[0]['total'];
    }

    public function getNumFiltrados($JOIN, $WHERE)
    {
        $SQL = "SELECT COUNT(*) AS total FROM %s %s WHERE %s";
        $SQL = sprintf($SQL, $this->TABLA, $JOIN, $WHERE);

        $data = $this->CONN->Query($SQL);
        return $data[0]['total'];
    }

    public function getPaginaFiltrados($JOIN, $WHERE, $ORDER, $LIMIT)
    {
        $SQL = "SELECT * FROM %s %s WHERE %s ORDER BY %s LIMIT %s";
        $SQL = sprintf($SQL, $this->TABLA, $JOIN, $WHERE, $ORDER, $LIMIT);
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    public function getDuplicatedProducts($thing)
    {
        $SQL = "SELECT * FROM %s WHERE %s = %d LIMIT 1;";

        $SQL = sprintf($SQL, $this->TABLA, $this->PRKEY, $ID);
        $data = $this->CONN->Query($SQL);
        return $data[0];
    }

    /**** Métodos manipular datos inicio ****/

    public function insert($array, $flag = 1)
    {
        if ($flag) {
            $campos = '';
            $datos = '';
            foreach ($array as $nombre => $valor) {
                $campos .= " $nombre,";
                $datos .= " '$valor',";
            }
            $this->getDuplicatedProducts($campos);
            $campos = preg_replace('/(,{1})$/', '', $campos);
            $datos = preg_replace('/(,{1})$/', '', $datos);
            $SQL = "INSERT INTO %s (%s) VALUES (%s)";

            $SQL = sprintf($SQL, $this->TABLA, $campos, $datos);
            $bool = $this->CONN->Execute($SQL);
            return $bool;
        }
    }

    public function update($array, $ID)
    {
        $valores = '';
        foreach ($array as $nombre => $valor) {
            $valores .= " $nombre = '$valor',";
        }
        $valores = preg_replace('/(,{1})$/', '', $valores);
        $SQL = "UPDATE %s SET %s WHERE %s = %d";

        $SQL = sprintf($SQL, $this->TABLA, $valores, $this->PRKEY, $ID);
        $bool = $this->CONN->Execute($SQL);
        return $bool;
        // return $SQLU;
    }

    public function delete($ID)
    {
        $SQL = "DELETE FROM %s WHERE %s = %d;";

        $SQL = sprintf($SQL, $this->TABLA, $this->PRKEY, $ID);
        $bool = $this->CONN->Execute($SQL);
        return $bool;
    }
    /**** Métodos manipular datos fin ****/

    /**** Funciones especiales inicio ****/

    public function SanitizarTexto($texto)
    {
        $TXT = trim($texto);
        $TXT = str_replace($this->ACT, $this->CLN, $TXT);
        $TXT = preg_replace("/[\n|\r|\r\n]+/", "<br>", $TXT);
        return $TXT;
    }

    public function makeNombre($texto)
    {
        $T = explode(".", $texto);
        array_pop($T);
        $texto = implode('.', $T);
        return $texto;
    }

    public function makeURL($texto)
    {
        $chr = "/[^a-zA-Z0-9-]+/";
        $acl = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', ' ');
        $vcl = array('a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N', '-');
        $url = trim($texto);
        $url = str_replace($acl, $vcl, $url);
        $url = preg_replace($chr, '', $url);
        $url = preg_replace("/-+/", '-', $url);
        $url = strtolower($url);
        return $url;
    }

    public function getExtension($nombre)
    {
        $nom = explode('.', $nombre);
        $l = count($nom) - 1;
        return "." . $nom[$l];
    }

    public function makeURLFILE($NFILE)
    {
        $N = $this->makeNombre($NFILE);
        $NOM = $this->makeURL($N);
        $EXT = $this->getExtension($NFILE);
        $FILENAME = $NOM . $EXT;
        return $FILENAME;
    }

    public function ReSatanizarTexto($texto)
    {
        $TXT = trim($texto);
        $TXT = str_replace($this->CLN, $this->ACT, $TXT);
        $TXT = preg_replace("/[\n|\r|\r\n]+/", "<br>", $TXT);
        return $TXT;
    }

    /**** FUNCIONES DE ARCHIVOS ****/

    public function getFileErrorMSG($ERROR)
    {
        $MSG = "";
        switch ($ERROR) {
            case 0:
                $MSG = "El archivo se ha subido correctamente";
                break;
            case 1:
                $MSG = "El archivo es demasiado grande para subirlo al servidor.";
                break;
            case 2:
                $MSG = "El archivo es damasiado grande para procesarlo.";
                break;
            case 3:
                $MSG = "El archivo no se ha subido completamente.";
                break;
            case 4:
                $MSG = "No se ha subido ningún archivo";
                break;
            case 6:
                $MSG = "Se ha perdido la ubicación temporal del archivo";
                break;
            case 7:
                $MSG = "No se pudo escribir su archivo en el servidor";
                break;
            case 8:
                $MSG = "Un módulo de php ha detenido la carga del archivo";
                break;
            default:
                $MSG = "Error de imagen desconocido";
                break;
        }
        return $MSG;
    }

    /**** FUNCIONES DE ARCHIVOS ****/

    /**** FUNCIONES DE FECHA HORA ****/

    public function FechaEspanol($DATE)
    {
        $TIME = strtotime($DATE);
        $FECHA = strftime("%A %d de %B de %Y ", $TIME);
        return ucfirst($FECHA);
    }

    public function FechaHoraEspanol($DATE)
    {
        $TIME = strtotime($DATE);
        $FECHA = strftime("%A %d de %B de %Y, %i:%M ", $TIME);
        return ucfirst($FECHA);
    }

    public function DiaEspanol($DATE)
    {
        $TIME = strtotime($DATE);
        $FECHA = strftime("%A %d", $TIME);
        return ucfirst($FECHA);
    }

    public function Hora($DATE)
    {
        $TIME = strtotime($DATE);
        $FECHA = strftime("%I:%M ", $TIME);
        $FECHA .= ($TIME % 86400) < 43200 ? 'am' : 'pm';
        return ucfirst($FECHA);
    }

    /**** FUNCIONES DE FECHA HORA ****/

    public function idkfa()
    {
        $SQL = "SELECT cat_id, cat_name, cat_url, COUNT(cat_name) FROM tbl_category GROUP BY cat_name HAVING COUNT(cat_name) > 1";
        $data = $this->CONN->Query($SQL);
        return $data;
    }
}
