<?php

//include_once '../../php/soap.class.php';
//require_once '../../php/connection.class.php';

class Category extends Table
{
    private $ACT;
    private $CLN;

    public function __construct($DB)
    {
        parent::__construct($DB);
        $this->TABLA = 'tbl_category';
        $this->PRKEY = 'cat_id';
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

    public function getAllCats()
    {
        $SQL = "SELECT * FROM {$this->TABLA} WHERE 1";
        $RES = $this->CONN->Query($SQL);
        return $RES;
    }

    public function getCategory($CAT)
    {
//        $CAT = 0;
//        if (!empty($match)) {
//            $allCats = $this->getAllCats();
//            $match = $this->levenshteinDiff($match, $allCats);
//            var_dump($match);
//        }

        $SQL = "SELECT * FROM tbl_category WHERE cat_name = \"%s\" ";
        $SQL = sprintf($SQL, $CAT);
//        echo $SQL;
        $data = $this->CONN->Query($SQL);
//        var_dump($SQL);
//        die();
        return $data;
    }

    public function getLikeCategory($CAT)
    {
//        $CAT = 0;
//        if (!empty($match)) {
//            $allCats = $this->getAllCats();
//            $match = $this->levenshteinDiff($match, $allCats);
//            var_dump($match);
//        }

        $SQL = "SELECT * FROM tbl_category WHERE cat_name LIKE '%{$this->SanitizarTexto($CAT)}%'";
//        $SQL = sprintf($SQL, $CAT);
//        echo $SQL;
        $data = $this->CONN->Query($SQL);
//        var_dump($CAT);
//        var_dump($SQL);
//        die();
        return $data;
    }

    public function getCategoryId($CAT)
    {
        $SQL = "SELECT cat_id FROM tbl_category WHERE cat_name = \"%s\" ";
        $SQL = sprintf($SQL, $CAT);
        $data = $this->CONN->Query($SQL);

        return $data;
    }

    public function getCategoryIdByUrl($CAT)
    {
        $SQL = "SELECT cat_id FROM tbl_category WHERE cat_url = \"%s\" ";
        $SQL = sprintf($SQL, $CAT);
        $data = $this->CONN->Query($SQL);

        return $data;
    }

    public function CheckChilds($ID)
    {
        $CHILDS = 0;
        $SQL = "SELECT COUNT(*) AS CHILDS FROM tbl_category WHERE cat_depen = $ID";
        $HIJOS = $this->CONN->Query($SQL);
        $SQLP = "SELECT COUNT(*) AS CHILDS FROM tbl_product WHERE category = $ID";
        $HIJOSP = $this->CONN->Query($SQL);
        $CHILDS = $HIJOS[0]['CHILDS'] + $HIJOSP[0]['CHILDS'];
        return $CHILDS;
    }

    public function RecorrerLugarUP($INDICE, $ORIGEN, $DEPENDE)
    {
        $SQL = "UPDATE {$this->TABLA} SET cat_order = cat_order+1 WHERE cat_order >= {$INDICE}  AND cat_order <= {$ORIGEN} AND cat_depen = {$DEPENDE}";
        $RES = $this->CONN->Execute($SQL);
        return $RES;
    }

    public function RecorrerLugarDOWN($INDICE, $ORIGEN, $DEPENDE)
    {
        $SQL = "UPDATE {$this->TABLA} SET cat_order = cat_order-1 WHERE cat_order <= {$INDICE} AND cat_order >= {$ORIGEN} AND cat_depen = {$DEPENDE}";
        $RES = $this->CONN->Execute($SQL);
        return $RES;
    }

    public function RecorrerDelete($INDICE, $DEPENDE)
    {
        $SQL = "UPDATE {$this->TABLA} SET cat_order = cat_order-1 WHERE cat_order > '$INDICE' AND cat_depen = '$DEPENDE'";
        // $RES = $this->CONN->Execute($SQL);
        return $SQL;
    }

    public function getNextPosition($DEPENDE)
    {
        $SQL = "SELECT cat_order FROM {$this->TABLA} WHERE cat_depen = {$DEPENDE} ORDER BY cat_order DESC LIMIT 1";
        //var_dump($SQL);
        $data = $this->CONN->Query($SQL);
        if ($data == '' || $data == null || $data == null) {
            $NEXT = 1;
        } else {
            $NEXT = $data[0]['cat_order'] + 1;
        }
        return $NEXT;
    }

    public function ReSatanizarTexto($texto)
    {
        $TXT = trim($texto);
        $TXT = str_replace($this->CLN, $this->ACT, $TXT);
        $TXT = preg_replace("/[\n|\r|\r\n]+/", "<br>", $TXT);

        return $TXT;
    }

    public function getProducts($ID)
    {
        $SQL = "SELECT * FROM tbl_product WHERE category = '$ID'";
        $RES = $this->CONN->Query($SQL);
        return $RES;
    }

    public function getDependency($ID)
    {
        $SQL = "SELECT cat_depen FROM tbl_category WHERE cat_id = \"%s\" ";
        $SQL = sprintf($SQL, $ID);
//        echo $SQL;
        $data = $this->CONN->Query($SQL);
        return $data;

    }

    public function CheckParent($ID)
    {
        $SQL = "SELECT * FROM tbl_category WHERE cat_id = \"%s\" ";
        $SQL = sprintf($SQL, $ID);
//        echo $SQL;
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    public function removeCat($ID)
    {
        $SQL = "DELETE FROM {$this->TABLA} WHERE cat_id = $ID";
        $RES = $this->CONN->Query($SQL);
        return $RES;
    }

    public function getDups()
    {
        $SQL = " SELECT cat_id, cat_name, COUNT(cat_name) FROM tbl_category GROUP BY cat_name HAVING COUNT(cat_name) > 1";
        $RES = $this->CONN->Query($SQL);
        return $RES;
    }

    function levenshteinDiff($input, $array)
    {

        // no se ha encontrado la distancia más corta, aun
        $shortest = -1;

        // bucle a través de las palabras para encontrar la más cercana
        foreach ($array as $word) {
//            var_dump($word);

            // calcula la distancia entre la palabra de entrada
            // y la palabra actual
            $lev = levenshtein($input, $word["cat_name"]);

            // verifica por una coincidencia exacta
            if ($lev == 0) {

                // la palabra más cercana es esta (coincidencia exacta)
                $closest = $word["cat_name"];
                $shortest = 0;

                // salir del bucle, se ha encontrado una coincidencia exacta
                break;
            }

            // si esta distancia es menor que la siguiente distancia
            // más corta o si una siguiente palabra más corta aun no se ha encontrado
            if ($lev <= $shortest || $shortest < 0) {
                // establece la coincidencia más cercana y la distancia más corta
                $closest = $word["cat_name"];
                $shortest = $lev;
            }
        }

        return $closest;
    }

    public function smolCats()
    {
        $SQL = "select * from tbl_category where length(cat_name) < 3";
        $RES = $this->CONN->Query($SQL);
        return $RES;
    }

    public function invalidCats()
    {
        $RES = $this->getAllCats();

//        var_dump($this->CLN[58]);
//        var_dump($this->ACT[58]);
        $notallowed = array_slice($this->CLN, 0, 59);
        var_dump(array_slice($this->CLN, 0, 59));
        var_dump(array_slice($this->ACT, 0, 59));
        foreach ($notallowed as $item) {
            foreach ($RES as $RE) {
                if (strpos($RE["cat_name"], $item)) {
                    var_dump($RE["cat_name"]);
                    var_dump($item);
                } else {
//                    var_dump(strpos($RE["cat_name"], $item));
//                    var_dump($RE["cat_name"]);
//                    var_dump($item);
                }
            }
        }


//        return $RES;
    }

    public function getByName($name)
    {
        $SQL = "SELECT {$this->PRKEY} FROM {$this->TABLA} WHERE cat_name = '{$name}'";
        $data = $this->CONN->Query($SQL);
        $id = $data[0];
        return $id;
    }

}
