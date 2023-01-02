<?php

include_once '../../php/soap.class.php';
require_once '../../php/connection.class.php';
require_once '../../mng-supplier/model/supplier.class.php';

$DB = new DBConnection;

$SUPPLIER = new Supplier($DB);

class Product extends Table
{

    public function __construct($DB)
    {

        parent::__construct($DB);

        $this->DB = $DB;

        $this->TABLA = 'tbl_product';
        $this->PRKEY = 'product_id';
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

    // Search Bar
    public function searchProd($category, $searField)
    {

        if ($category != 0) {
            $sql = "SELECT product_id, name, url, color 
                    FROM tbl_product
                    WHERE category = $category 
				          AND name LIKE '%$searField%' 
				          AND status = 1
				          AND product_depen = 0";
//            return ($sql);
            return $this->CONN->Query($sql);

        } else {
            $sql = "SELECT product_id, name, url, color 
                FROM tbl_product	
				WHERE name LIKE '%$searField%' 
				       AND status = 1
				   AND product_depen = 0
				OR code_product LIKE '%$searField%' 
				       AND status = 1
				        AND product_depen = 0";

//            return ($sql);
            return $this->CONN->Query($sql);

        }


    }

    /* Get all ID's products start*/
    public function ProductMacma($RES)
    { //Obtiene los ID´s de los productos MACMA
        $COD = [];
        try {
            $Nodos = new SimpleXMLElement(base64_decode($RES));
        } catch (Exception $e) {
            return "error " . $e;
        }

        foreach ($Nodos->xpath('//product') as $N) {
            $COD[] .= $N['code_product'];
        }
//        var_dump($COD);
        return $COD;
    }

    public function ProductBlestar($RES)
    { //Obtiene los ID´s de los productos Blestar
        $COD = [];
        $Nodos = new SimpleXMLElement(base64_decode($RES));

        foreach ($Nodos->xpath('//product') as $N) {
            $COD[] .= $N['code_product'];
        }

        return $COD;
    }

    public function ProductG4($RES)
    { //Obtiene los ID´s de los productos Blestar
        //TODO HEERE HANDLE THE FATAL ERROR UNCAUGHT EXCEPTION, STRING COULD NOT BE PARSED AS XML
        $COD = [];
        if (!empty($RES)) {
            $Nodos = new SimpleXMLElement(base64_decode($RES));

            foreach ($Nodos->xpath('//producto') as $N) {
                $COD[] .= $N['codigo_producto'];
            }

            return $COD;

        }

    }

    public function ProductInnovation($RES)
    {
        $COD = [];
        $Nodos = new SimpleXMLElement(base64_decode($RES));

        foreach ($Nodos->xpath('//producto') as $N) {
            $COD[] .= $N['codigo']; //codigo="BE-004"
        }
        return $COD;
    }

    /* Get all ID's products end*/
    public function insertProductMacma($CODE)
    { //Obtiene la infor de los productos
        $API = new ApiRestSoap;
        $_INS = array();
        $RESP = $API->getProductMacma($CODE);
        // var_dump($RESP);
        // var_dump(base64_decode($RESP));
        // die();
        if ($RESP) {
            $PRODUCT = new SimpleXMLElement(base64_decode($RESP));
            foreach ($PRODUCT->xpath('//product') as $N) {
                $_INS['code_product'] = $N['code_product'];
                $_INS['name'] = $N['name'];
                $_INS['description'] = $N['description'];
                //$_INS['category']        = $N['category'];
                $_INS['price'] = $N['unit_price'];
                $_INS['info'] = 'Material: ' . $N['family'] . '<br>';
                $_INS['info'] .= 'Tamaño: ' . $N['size'] . '<br>';
                //$_INS['info']             .= 'Medida de impresi&oacute;n : ' . $N['printing_size'] . '<br>';
                //$_INS['info']             .= 'Medida de caja: ' . $N['box_size'] . '<br>';
                //$_INS['info']             .= 'Peso de caja: ' . $N['box_weight'] . '<br>';
                //$_INS['info']             .= 'Paquete de: ' . $N['packing'];
                $_INS['status'] = '1';
            }

            foreach ($PRODUCT->xpath('//product//categories//category') as $N) {
                $_INS['category'] = $N['name'];
            }

            /* foreach ($PRODUCT->xpath('//product//print_types//print') as $N) {
            $_INS['info'] .= 'Impresi&oacute;n: ' . $N->print . '<br>';
            } */

//$COLOR = $this->getCodesProductsMacma($CODE);
            //$CODE_COLORS = implode(',',$COLOR);

//$_INS['color'] = $CODE_COLORS;
            $COLORS = array();
            foreach ($PRODUCT->xpath('//product//colors//color') as $N) {
                array_push($COLORS, $N['color']);
            }
            if (count($COLORS) < 1) {
                $COLORS = 0;
            } else {
                $CODE_COLORS = implode(',', $COLORS);
                $_INS['color'] = $CODE_COLORS;
            }

            /* foreach($PRODUCT->xpath('//product//colors//color') as $N){
            $STOCK .= $N->stock.',';
            } */
            $propCheck = $this->validateProductInfo($_INS);
            if ($propCheck > 1) {
                return 0;
            }
            return $_INS;

        }
    }

    public function insertProductInnovation($CODE)
    {
        $API = new ApiRestSoap;
        $_INS = array();
        if ($API->getProductInnovation($CODE)) {
            $RESP = $API->getProductInnovation($CODE);
            $PRODUCT = new SimpleXMLElement(base64_decode($RESP));
            foreach ($PRODUCT->xpath('//product') as $N) {
                $_INS['code_product'] = $N['codigo']; //
                $_INS['name'] = $N['nombre']; //
                $_INS['description'] = $N['descripcion']; //
                $_INS['img'] = $N['imagen_principal']; //
                $_INS['info'] = 'Material: ' . $N['material'] . ' '; //
                $_INS['info'] .= 'Tamaño de producto: ' . $N['medidas_producto'] . ' '; //
                /* $_INS['info']             .= 'Medida de impresi&oacute;n : ' . $N['area_impresion'] . ' ';
                $_INS['info']             .= 'Medida de caja: ' . $N['medidas_paquete'] . ' ';
                $_INS['info']             .= 'Peso de caja: ' . $N['peso_paquete'] . ' '; */
                //$_INS['info']             .= 'Paquete de: ' . $N['cantidad_por_paquete']; //
                $_INS['status'] = '1';
            }

            foreach ($PRODUCT->xpath('//product//lista_precios//precio') as $N) {
                $_INS['price'] = $N['mi_precio']; //
            }

            foreach ($PRODUCT->xpath('//product//categorias//categoria') as $N) {
                $_INS['category'] = $N['nombre']; //
            }

            $COLORS = array();
            foreach ($PRODUCT->xpath('//product//colores//color') as $N) {
                array_push($COLORS, $N['color']); //
            }
            if (count($COLORS) < 1) {
                $COLORS = 0;
            } else {
                $CODE_COLORS = implode(',', $COLORS);
                $_INS['color'] = $CODE_COLORS;
            }
            var_dump($_INS);
            return $_INS;
        } else {
            echo "Nope";
        }

    }

    public function getImgInnovation($CODE)
    {
        $API = new ApiRestSoap;
        $_INS = array();
        $RESP = $API->getProductInnovation($CODE);
        $PRODUCT = new SimpleXMLElement(base64_decode($RESP));
        foreach ($PRODUCT->xpath('//producto//imagenes_adicionales//imagen') as $N) {
            array_push($_INS, $N['url']);
            //echo $N['url'];
        }
        return $_INS;
    }

    public function getCodesProductsMacma($CODE)
    {
        $API = new ApiRestSoap;
        $RESP = $API->getProductMacma($CODE);
        if ($RESP) {
            $PRODUCT = new SimpleXMLElement(base64_decode($RESP));
            $_INS = array();
            foreach ($PRODUCT->xpath('//product//colors//color') as $N) {
                array_push($_INS, $N['code_color']);
            }
            if (count($_INS) < 1) {
                $_INS = 0;
            }
            return $_INS;
        }
    }

    public function getImgProductG4($CODE)
    {
        $API = new ApiRestSoap;
        $_INS = array();
        $RESP = $API->getProductG4($CODE);
        $PRODUCT = new SimpleXMLElement(base64_decode($RESP));
        //var_dump($PRODUCT);
        //echo "Etro aqui <br>";
        /* foreach ($PRODUCT->xpath('//producto//imagenes//ambientada') as $N) {
        array_push($_INS, $N['url']);
        //echo $N['url'];
        } */
        foreach ($PRODUCT->xpath('//producto//imagenes//adicionales//adicional') as $N) {
            array_push($_INS, $N['url']);
            //echo $N['url'];
        }
        if (count($_INS) < 1) {
            $_INS = 0;
        }
        return $_INS;
    }

    public function insertProductBlestar($CODE)
    { //Obtiene la infor de los productos
        $API = new ApiRestSoap;
        $_INS = array();
        $_COLORS = array();
        $RESP = $API->getProductBlestar($CODE);
        $PRODUCT = new SimpleXMLElement(base64_decode($RESP));
        if ($PRODUCT) {

            foreach ($PRODUCT->xpath('//product') as $N) {
                $_INS['code_product'] = $N['code_product'];

                /*
                $IDPROD = $this->searchProductID('08-'.$_INS['code_product']);
                if(!empty($IDPROD['product_id'])){
                echo 'ID extraido: '.$IDPROD.'<br>';
                $_INS['product_depen'] = $IDPROD['product_id'];
                }
                else{
                $_INS['product_depen'] = 0;
                }
                 */

                $_INS['name'] = $N['model'];
                $_INS['description'] = $N['description'];
                $_INS['category'] = $N['category'];
                $_INS['info'] = 'Tamaño: ' . $N['size'] . '<br>';
                /* $_INS['info']            = 'Ancho de caja: ' . $N['box_size'] . '<br>';
                $_INS['info']            = 'Alto de caja: ' . $N['box_weight'] . '<br>'; */
                //$_INS['info']            = 'Piezas por caja: ' . $N['box_ext_pz'] . '<br>';
                $_INS['status'] = '1';
            }
            foreach ($PRODUCT->xpath('//product//color') as $N) {
                array_push($_COLORS, $N['name_color']);
            }
            if (count($_COLORS) < 1) {
                $_COLORS = 0;
            } else {
                $_INS['color'] = implode(',', $_COLORS);
            }
            // var_dump($_INS);
            $propCheck = $this->validateProductInfo($_INS);
            if ($propCheck > 1) {
                return 0;
            }
        }
        return $_INS;

    }

    public function getCodesProductsBlestar($CODE)
    {
        $API = new ApiRestSoap;
        $_INS = array();
        $RESP = $API->getProductBlestar($CODE);
        $PRODUCT = new SimpleXMLElement(base64_decode($RESP));
        foreach ($PRODUCT->xpath('//product//color') as $N) {
            array_push($_INS, $N['code_color']);
        }
        if (count($_INS) < 1) {
            $_INS = 0;
        }
        return $_INS;
    }

    public function insertProductG4($CODE)
    {
        $API = new ApiRestSoap;
        $_INS = array();
        $_PRICE = array();
        $RESP = $API->getProductG4($CODE);
        $PRODUCT = new SimpleXMLElement(base64_decode($RESP));

        foreach ($PRODUCT->xpath('//producto') as $N) {
            $_INS['code_product'] = $N['codigo_producto'];
            $_INS['name'] = $N['nombre_producto'];
            $_INS['category'] = $N['linea'];
            $_INS['description'] = $N['descripcion'];
            $_INS['info'] = 'Medidas generales: ' . $N['medidas'] . ' ';
            $_INS['info'] .= 'Ancho: ' . $N['medida_ancho'] . ' ';
            $_INS['info'] .= 'Alto: ' . $N['medida_alto'] . ' ';
            $_INS['info'] .= 'Medida de fondo: ' . $N['medida_fondo'] . ' ';
            $_INS['info'] .= 'Material: ' . $N['material'] . ' ';
            /* $_INS['info']            .= 'Impresi&oacute;n: ' . $N['impresion'] . ' ';
            $_INS['info']            .= 'Area de impresi&oacute;n: ' . $N['area_impresion'] . ' '; */
            //$_INS['info']            .= 'Piezas por caja: ' . $N['piezas_por_caja'] . ' ';
            $_INS['color'] = $N['nombre_color'];
            $_INS['status'] = '1';
        }

        foreach ($PRODUCT->xpath('//producto//precios//escala') as $N) {
            array_push($_PRICE, $N['precio']);
        }

        if (count($_PRICE) < 1) {
            $_PRICE = 0;
        } else {
            $_INS['price'] = $_PRICE[0];
        }

        foreach ($PRODUCT->xpath('//producto//imagenes//principal') as $N) {
            $_INS['img'] = $N['url'];
            //echo 'IMAGEN: '.$N['url'];
        }

        return json_decode(json_encode($_INS), true);
    }

    public function insertProduct4Promo()
    { //Esta función retorna todos los productos de la API 4Promo
        $url = 'https://4promotional.net:9090/WsEstrategia/inventario'; //URL del inventario
        //  Iniciamos curl
        $curl = curl_init();
        // Desactivamos verificación SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        // Devuelve respuesta aunque sea falsa
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // Especificamo los MIME-Type que son aceptables para la respuesta.
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        // Establecemos la URL
        curl_setopt($curl, CURLOPT_URL, $url);
        // Ejecutmos curl
        $json = curl_exec($curl);
        // Cerramos curl
        curl_close($curl);
        $RES = json_decode($json, true); //Convertimos los datos obtenidos en Json
        return $RES; //Retornamos todos los productos en codigo Json
    }

    public function getSlider($ID)
    {
        $SQL = "SELECT * FROM tbl_slider_product WHERE prod_id = {$ID} ";
        $SLIDER = $this->CONN->Query($SQL);
        return $SLIDER;
    }

    public function searchProductID($CODE)
    { //Busca si el codigo del producto existe, si existe retornara el id del producto
        $SQL = "SELECT product_id FROM {$this->TABLA} WHERE code_product = \"%s\" ";
        $SQL = sprintf($SQL, $CODE);
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    public function searchProductName($NAME)
    { //Busca si el codigo del producto existe, si existe retornara el id del producto
        $SQL = "SELECT min(product_id) as id FROM tbl_product WHERE name LIKE '%{$NAME}%'";
        $data = $this->CONN->Query($SQL);
        $id = $data[0]['id'];
        return $id;
    }

    public function searchProductNameURL($URLPRODUCT)
    {
        $SQL = "SELECT product_id FROM {$this->TABLA} WHERE product_id = (SELECT min(product_id) FROM {$this->TABLA} WHERE url LIKE '%\"%s\"%')";
        $SQL = sprintf($SQL, $URLPRODUCT);
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    /* Funciones que se mandan a llamar en vista editar producto*/
    public function getCategorias($ID)
    {
        $CATES = array();
        $SQL = "SELECT cat_depen, cat_name, cat_level FROM tbl_category WHERE cat_id = {$ID} ";
        $CATR = $this->CONN->Query($SQL);
        $CAT = $CATR[0];
        if ($CAT['cat_level'] == 0) {
            array_push($CATES, $CAT['cat_name']);
        } else {
            $CATES = $this->getCategorias($CAT['cat_depen']);
            array_push($CATES, $CAT['cat_name']);
        }
        return $CATES;
    }

    public function getCates($ID)
    {
        $SQL = "SELECT cat_id, cat_name FROM tbl_category WHERE cat_depen = {$ID}";
        $CATES = $this->CONN->Query($SQL);
        return $CATES;
    }

    public function getCateByName($name)
    {
        $SQL = "SELECT cat_id FROM tbl_category WHERE cat_name = '$name'";
        $CAT = $this->CONN->Query($SQL);
        return $CAT;
    }

    public function getPadre($P)
    {
        $SQL = "SELECT cat_id, cat_depen FROM tbl_category WHERE cat_id = '{$P}' LIMIT 1 ";
        $CAT = $this->CONN->Query($SQL);
        return $CAT[0];
    }

    /* Funciones que se mandan a llamar en vista editar producto*/

    public function getById($ID)
    {
        $SQL = "SELECT * FROM %s WHERE %s = %d LIMIT 1;";

        $SQL = sprintf($SQL, $this->TABLA, $this->PRKEY, $ID);
        $data = $this->CONN->Query($SQL);
        return $data[0];
    }

    public function ReSatanizarTexto($texto)
    {
        $TXT = trim($texto);
        $TXT = str_replace($this->CLN, $this->ACT, $TXT);
        /*
        for ($i = 0; $i < 0; $i++) {
        if (strpos($texto, $this->CLN[$i])) {
        return $this->CLN[$i];
        }
        }
         */
        $TXT = preg_replace("/[\n|\r|\r\n]+/", "<br>", $TXT);
        /*        var_dump($TXT);
        die(); */

        return $TXT;
    }

    public function getPromoOpcion()
    {

    }

    public function updateCategory($old_cat, $new_cat)
    {

        $SQL = "UPDATE {$this->TABLA} SET category = \"%s\" WHERE category = \"%s\" ;";

        $SQL = sprintf($SQL, $new_cat, $old_cat);
        $data = $this->CONN->Execute($SQL);
        return $data;
    }

    public function updateCodeProduct($id, $new_cod)
    {
        $SQL = "UPDATE {$this->TABLA} SET code_product = \"%s\" WHERE product_id = \"%s\" ;";

        $SQL = sprintf($SQL, $new_cod, $id);
        $data = $this->CONN->Execute($SQL);
        return $data;
    }

    public function updateCode($id, $new_cod)
    {
        $SQL = "UPDATE {$this->TABLA} SET code = \"%s\" WHERE product_id = \"%s\" ;";

        $SQL = sprintf($SQL, $new_cod, $id);
        $data = $this->CONN->Execute($SQL);
        return $data;
    }

    public function updateNamePro($id, $new_name)
    {
        $SQL = "UPDATE {$this->TABLA} SET name = \"%s\" WHERE product_id = \"%s\" ;";

        $SQL = sprintf($SQL, $new_name, $id);
        $data = $this->CONN->Execute($SQL);
        return $data;
    }

    public function updateImgPro($id, $new_img)
    {
        $SQL = "UPDATE {$this->TABLA} SET img = \"%s\" WHERE product_id = \"%s\" ;";

        $SQL = sprintf($SQL, $new_img, $id);
        $data = $this->CONN->Execute($SQL);
        return $data;
    }

    public function updateUrl($id, $new_url)
    {
        $SQL = "UPDATE {$this->TABLA} SET prov_website = \"%s\" WHERE product_id = \"%s\" ;";

        $SQL = sprintf($SQL, $new_url, $id);
        $data = $this->CONN->Execute($SQL);
        return $data;
    }

    public function getLastInsertedProd()
    {
        return $this->getById($this->DB->GetLastInsertID());
    }

    public function validateProductInfo($PROD)
    {
        $keys = array_keys($PROD);
        $emptyPropCounter = 0;
        for ($i = 0; $i < count($PROD); $i++) {
            $prop = $PROD[$keys[$i]];
            if (!isset($prop)) {
                $emptyPropCounter++;
            }
        }
        return $emptyPropCounter;
    }

    public function getByName($NAME)
    {
        $SQL = "SELECT * FROM %s WHERE %s = '$NAME'";

        $SQL = sprintf($SQL, $this->TABLA, 'name');
        $data = $this->CONN->Query($SQL);
//        return $SQL;
        return $data[0];
    }

    public function getAllByName($NAME)
    {
        $SQL = "SELECT * FROM %s WHERE %s = '$NAME'";

        $SQL = sprintf($SQL, $this->TABLA, 'name');
        $data = $this->CONN->Query($SQL);
//        return $SQL;
        return $data;
    }

    public function getAllByCode($CODE)
    {
        $SQL = "SELECT * FROM %s WHERE %s = '$CODE'";

        $SQL = sprintf($SQL, $this->TABLA, 'code_product');
        $data = $this->CONN->Query($SQL);
//        return $SQL;
        return $data;
    }

    public function getAll()
    {
        $SQL = "SELECT * FROM %s WHERE 1";

        $SQL = sprintf($SQL, $this->TABLA);
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    public function getMostClicked()
    {
        $SQL = "SELECT * FROM %s WHERE clicks > 0 ORDER BY clicks DESC, name ASC limit 20000";

        $SQL = sprintf($SQL, $this->TABLA);
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    public function countWhereDifferentSupplier($PROV)
    {
        $SQL = "SELECT COUNT(product_id) from tbl_product WHERE supplier != '{$PROV}'";
        $data = $this->CONN->Query($SQL);
        return $data[0]["COUNT(product_id)"];
    }

    public function countAll()
    {
        $SQL = "SELECT COUNT(*) from tbl_product WHERE 1";
        $data = $this->CONN->Query($SQL);
        return $data[0]["COUNT(*)"];
    }

    public function getDataInnova()
    {
        $API = new ApiRestSoap;
        $RESP = $API->validateConnectionInnova();
        if (strpos($RESP, "Datos de acceso correctos")) {
            $data = $API->getProductsAllInnovation();
            $PRODUCT = new SimpleXMLElement(base64_decode($data));
            return $PRODUCT;
        }
//        return ($RESP);
    }

    public function getByCate($cate)
    {

        $SQL = "SELECT * FROM {$this->TABLA} WHERE category = '$cate'";
        $data = $this->CONN->Query($SQL);
//        return $SQL;
        return $data;
    }

    public function getBySupplier($supplier_id)
    {
        $SQL = "SELECT * FROM tbl_product WHERE supplier = $supplier_id";
        $SQL = "SELECT product_id, code_product, name, url, COUNT(name) FROM tbl_product GROUP BY code_product HAVING COUNT(name) > 1";
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    public function getByProdName($name)
    {
        $SQL = "SELECT * FROM {$this->TABLA} WHERE name = '$name'";
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    public function getByImage($imgFile)
    {
        $SQL = "SELECT * FROM {$this->TABLA} WHERE img = '$imgFile'";
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    public function idkfap()
    {
        $SQL = "SELECT product_id, supplier, code_product, name, COUNT(name) FROM {$this->TABLA} GROUP BY name HAVING COUNT(name) > 1";
        $data = $this->CONN->Query($SQL);
        return $data;
    }

}
