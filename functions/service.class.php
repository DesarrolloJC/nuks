<?php
include_once './admin-art/php/connection.class.php';

class services extends DBConnection
{
    private $__ACT;
    private $__CLN;

    public function __construct()
    {
        parent::__construct();
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

    public function getSeo()
    {
        $SQL = "SELECT * FROM tbl_seo WHERE 1 LIMIT 1";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getSlider()
    {
        $SQL = "SELECT * FROM tbl_slider WHERE 1";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getSliderProduct($ID)
    {

        $SQL = "SELECT * FROM tbl_slider_product WHERE prod_id = {$ID}";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getProductsMacma()
    {
        $SQL = "SELECT * FROM tbl_macma WHERE 1";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getCategories($dep)
    { //getMenu(0);
        $location_local = "plantilla/";

        switch ($_SERVER["HTTP_HOST"]) {
            case "localhost":
                $URLBASE = "http://localhost/" . $location_local; //Desarrollo
                break;
            case "127.0.0.1":
                $URLBASE = "http://127.0.0.1/" . $location_local; //Desarrollo
                break;
            case "artpromos.com.mx":
                $URLBASE = "https://artpromos.com.mx/"; //Produccion
                break;
        }
        $menu = '';
        for ($dep; $dep < 1; $dep++) {
            $SQL = "SELECT * FROM tbl_category WHERE cat_depen = $dep ORDER BY cat_name ASC";
            $RES = $this->Query($SQL);
            foreach ($RES as $C) {
                // var_dump($RES);

                $menu .= '<li>';
                $subCat = $this->subCat($C['cat_id']);

                if (count($subCat) > 0) { //Si existen subcategorias
                    //Imprime categoria principal que tiene subcategoria
                    $menu .= '<a href="' . $URLBASE . 'categoria/' . $C['cat_url'] . '" style="display: inline-block; width: 90%;">' . $C['cat_name'] . '</a><div data-toggle="collapse" href="#catagory-widget' . $C['cat_id'] . '" role="button" aria-expanded="false"  class="collapsed sub-cat"><i class="fas fa-angle-down" style="font-size:20px; cursor: pointer;"></i></div>';
                    foreach ($subCat as $sc) {
                        $subCat2 = $this->subCat($sc['cat_id']);
                        if (count($subCat2) > 0) {
                            // echo "ENTRé"; //Si existen sub-subcategorias
                            $menu .= '<ul class="catagory-submenu collapse" id="catagory-widget' . $C['cat_id'] . '"><li>';
                            $menu .= '<a href="' . $URLBASE . 'categoria/' . $sc['cat_url'] . '" style="display: inline-block; width: 90%;">' . $sc['cat_name'] . '</a>';
                            $menu .= '<div data-toggle="collapse" href="#catagory-widget' . $sc['cat_id'] . '-1" role="button" aria-expanded="false"  class="collapsed sub-cat"><i class="fas fa-angle-down" style="font-size:20px; cursor: pointer;"></i></div>';
                            foreach ($subCat2 as $ssc) {

                                //Imprime la Subcategoria

                                $menu .= '<ul class="catagory-submenu collapse" id="catagory-widget' . $sc['cat_id'] . '-1"><li>';
                                $menu .= '<a href="' . $URLBASE . 'categoria/' . $ssc['cat_url'] . '">' . $ssc['cat_name'] . '</a></li></ul></li>';
                            }
                            $menu .= "</ul>";
                        } else {
                            //Si no existen sub-subcategorias
                            $menu .= '<ul class="catagory-submenu collapse" id="catagory-widget' . $C['cat_id'] . '"><li><a href="' . $URLBASE . 'categoria/' . $sc['cat_url'] . '">' . ucfirst(strtolower($sc['cat_name'])) . '</a></li></ul></li>';
                            // $menu .= '';
                        }
                    }
                } else { //Si no existen subcategorias
                    $menu .= '<a href="' . $URLBASE . 'categoria/' . $C['cat_url'] . '" role="button" >' . ucfirst(strtolower($C['cat_name'])) . '</a></li>';
                }
                $menu .= '</li>';
            }
        }
        // echo $menu;

        return $menu;
    }

    public function subCat($ID)
    {
        $SQL = "SELECT * FROM tbl_category WHERE cat_depen = $ID";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getCategoriesSelect()
    {
        $SQL = "SELECT * FROM tbl_category WHERE cat_depen = 0";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getCategoriesURL($URL)
    {
        $SQL = "SELECT cat_id, cat_name FROM tbl_category WHERE cat_url = '{$URL}' LIMIT 1";
        $RES = $this->Query($SQL);
        return $RES[0];
    }

    public function getPrintingMethods()
    {
        $SQL = "SELECT * FROM tbl_decoration WHERE 1";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getProductCat($ID)
    {
        //$SQL = "SELECT * FROM tbl_product WHERE category = '{$ID}'";
        $SQL = "SELECT p.product_id,p.code,p.code_product,p.name,p.description,p.img,p.url,p.color,p.info,p.price_general,p.price_client,p.price_distributor_level_one,p.price_distributor_level_two,p.price_distributor_level_three, p.status, p.supplier, c.cat_name, c.cat_url
              FROM tbl_product p
              INNER JOIN tbl_category c ON p.category = c.cat_id
              WHERE p.product_depen = 0 AND p.status = 1 AND p.category = '{$ID}'
              ORDER BY p.name ASC
              LIMIT 0, 24;";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getNumProduct()
    {
        $SQL = "SELECT COUNT(*) AS total FROM tbl_product WHERE product_depen = 0 AND status = 1";
        $RES = $this->Query($SQL);
        return $RES[0];
    }

    public function getNumProductByCat($cat)
    {
        $SQL = "SELECT COUNT(*) AS total FROM tbl_product WHERE product_depen = 0 AND status = 1 AND category = $cat";
        $RES = $this->Query($SQL);
        return $RES[0];
    }

    public function pagination()
    {
        $SQL = "SELECT p.product_id,p.code,p.code_product,p.name,p.description,p.img,p.url,p.color,p.info,p.price_general,p.price_client,p.price_distributor_level_one,p.price_distributor_level_two,p.price_distributor_level_three, p.status, p.supplier, c.cat_name, c.cat_url
              FROM tbl_product p
              INNER JOIN tbl_category c ON p.category = c.cat_id
              WHERE p.product_depen = 0 AND p.status = 1
              ORDER BY p.name ASC
              LIMIT 0, 24;";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getProduct($PROD_URL)
    {
        $SQL = "SELECT p.product_id,p.code,p.code_product,p.supplier,p.name,p.description,p.img,p.url,p.color,p.info,p.price_general,p.price_client,p.price_distributor_level_one,p.price_distributor_level_two,p.price_distributor_level_three, p.category as cat_id, c.cat_name as category, cat_url, p.prov_website as prov_website, p.supplier,p.clicks
              FROM tbl_product p
              INNER JOIN tbl_category c ON p.category = c.cat_id
              WHERE p.url = '$PROD_URL'";
        // var_dump($SQL);
        // die();
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getProductsSimillar($NAME, $ID)
    {

        $SQL = "SELECT * FROM tbl_product WHERE name LIKE '%$NAME%' AND product_id <> $ID LIMIT 10 ";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getProductsSimillarCat($CAT)
    {
        $SQL = "SELECT * FROM tbl_product WHERE category LIKE '%$CAT%' LIMIT 10";
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function getProductImagesForBlestar($PROD_URL)
    {
        $SQL = "SELECT p.img
              FROM tbl_product p
              WHERE p.url = '$PROD_URL'limit 1";
        // var_dump($SQL);
        // die();
        $RES = $this->Query($SQL);
        return $RES;
    }

    public function removeProdsFromSup($sup)
    {
        $SQL = "SELECT *
              FROM tbl_product p
              INNER JOIN tbl_category c ON p.category = c.cat_id
              WHERE p.supplier = '$sup' limit 1";
        $RES = $this->Query($SQL);

        for ($i = 0; $i < count($RES); $i++) {
//            var_dump("../img/product/" . $RES[$i]["img"]);
            $file = "../img/product/" . $RES[$i]["img"];
            unset($file);

        }
        die();
        return $RES;
    }

}
