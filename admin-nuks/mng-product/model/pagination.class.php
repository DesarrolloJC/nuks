<?php

class Product extends Table
{

    public function __construct($DB)
    {
        parent::__construct($DB);
        $this->TABLA = 'tbl_product';
        $this->PRKEY = 'product_id';
    }

    public function productsResPagination($LIM)
    {
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

        $SQL = "SELECT p.product_id,p.code,p.code_product,p.name,p.description,p.img,p.url,p.color,p.info,p.price_general,p.price_client,p.price_distributor_level_one,p.price_distributor_level_two,p.price_distributor_level_three, p.status, p.supplier, c.cat_name
                FROM tbl_product p
                INNER JOIN tbl_category c ON p.category = c.cat_id
                WHERE p.product_depen = 0 AND p.status = 1
                ORDER BY p.name ASC
                LIMIT $LIM, 24;";
        $RES = $this->CONN->Query($SQL);
        $html = '';
        foreach ($RES as $P) {

            $ch = curl_init($URLBASE . "img/product/" . $P['img']);

            curl_setopt($ch, CURLOPT_NOBODY, true);
            $data = curl_exec($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode == "200") {
                $IMAGEN = $URLBASE . 'img/product/' . $P['img'];
                $IMGSTYLE = '';
            } else {
                $IMAGEN = $URLBASE . 'img/relleno-art.webp"';
                $IMGSTYLE = '';
            }


            $html .= '<div class="col-sm-6 col-xl-3">';
            $html .= '<div class="product-item">';
            $html .= '<div class="product-thumb">';
            $html .= '<a href="' . $URLBASE . 'producto/' . $P['url'] . '"><img src="' . $IMAGEN . '" alt="' . $P['name'] . '" style ="' . $IMGSTYLE . '" ></a>';
            $html .= '</div>';
            $html .= '<div class="product-content">';
            $html .= '<a href="' . $URLBASE . "categoria/" . $P[17] . '" class="cata">' . $P['cat_name'] . '</a>';
            $html .= '<h6><a href="' . $URLBASE . 'producto/' . $P['url'] . '" class="product-title">' . $P['name'] . '</a></h6>';
            $html .= '<p class="quantity text-truncate"><a href="' . $URLBASE . 'producto/' . $P['url'] . '">' . $P['description'] . '</a></p>';
            $html .= '</div>';
            $html .= '</div>';
            /* $html .= '<div class="product-item">
            <div class="product-thumb">
            <a href="' . $URLBASE . 'product/' . $P['url'] . '"><img src="' . $IMAGEN . '" alt="' . $P['name'] . '" style ="' . $IMGSTYLE . '" ></a>
            </div>
            <div class="product-content">
            <a href="' . $URLBASE . 'categoria/' . $P[17] . '" class="cata">' . $P['cat_name'] . '</a>
            <h6 style="text-transform: uppercase;"><a style="font-weight: 600;"
            href="' . $URLBASE . 'producto/' . $P['url'] . '"
            class="product-title">' . $P['name'] . '</a>
            </h6>
            <p class="quantity text-truncate"><a href="' . $URLBASE . 'producto/' . $P['url'] . '">' . $P['description'] . '</a>
            </p>
            <div class="d-flex justify-content-between align-items-center">
            <div>' . ($P['color'] != '') ? $P['color'] : "" . ' </div>
            </div>
            </div>
            </div>
            '; */
            $html .= '</div>';
        }
        echo $html;
    }

    public function productsResPaginationCat($LIM, $CAT)
    {
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

        $SQL = "SELECT p.product_id,p.code,p.code_product,p.name,p.description,p.img,p.url,p.color,p.info,p.price_general,p.price_client,p.price_distributor_level_one,p.price_distributor_level_two,p.price_distributor_level_three, p.status, p.supplier, c.cat_name
                FROM tbl_product p
                INNER JOIN tbl_category c ON p.category = c.cat_id
                WHERE p.product_depen = 0 AND p.status = 1 AND p.category = '$CAT'
                ORDER BY p.name
                LIMIT $LIM, 24;";
        try {
//            var_dump($SQL);
//            die();

            $RES = $this->CONN->Query($SQL);
        } catch (Exception $e) {
            var_dump($SQL);
            var_dump($e);
            die();
        }
        $html = '';
        foreach ($RES as $P) {
            $ch = curl_init($URLBASE . "img/product/" . $P['img']);

            curl_setopt($ch, CURLOPT_NOBODY, true);
            $data = curl_exec($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode == "200") {
                $IMAGEN = $URLBASE . 'img/product/' . $P['img'];
                $IMGSTYLE = '';
            } else {
                $IMAGEN = $URLBASE . 'img/relleno-art.webp"';
                $IMGSTYLE = '';
            }

            $html .= '<div class="col-sm-6 col-xl-3">';
            $html .= '<div class="product-item">';
            $html .= '<div class="product-thumb">';
            $html .= '<a href="' . $URLBASE . 'producto/' . $P['url'] . '"><img src="' . $IMAGEN . '" alt="' . $P['name'] . '" style ="' . $IMGSTYLE . '" ></a>';
            $html .= '</div>';
            $html .= '<div class="product-content">';
            $html .= '<a href="' . $URLBASE . "categoria/" . $P[17] . '" class="cata">' . $P['cat_name'] . '</a>';
            $html .= '<h6><a href="' . $URLBASE . 'producto/' . $P['url'] . '" class="product-title">' . $P['name'] . '</a></h6>';
            $html .= '<p class="quantity text-truncate"><a href="' . $URLBASE . 'producto/' . $P['url'] . '">' . $P['description'] . '</a></p>';
            $html .= '</div>';
            $html .= '</div>';
            /* $html .= '<div class="product-item">
            <div class="product-thumb">
            <a href="' . $URLBASE . 'product/' . $P['url'] . '"><img src="' . $IMAGEN . '" alt="' . $P['name'] . '" style ="' . $IMGSTYLE . '" ></a>
            </div>
            <div class="product-content">
            <a href="' . $URLBASE . 'categoria/' . $P[17] . '" class="cata">' . $P['cat_name'] . '</a>
            <h6 style="text-transform: uppercase;"><a style="font-weight: 600;"
            href="' . $URLBASE . 'producto/' . $P['url'] . '"
            class="product-title">' . $P['name'] . '</a>
            </h6>
            <p class="quantity text-truncate"><a href="' . $URLBASE . 'producto/' . $P['url'] . '">' . $P['description'] . '</a>
            </p>
            <div class="d-flex justify-content-between align-items-center">
            <div>' . ($P['color'] != '') ? $P['color'] : "" . ' </div>
            </div>
            </div>
            </div>
            '; */
            $html .= '</div>';
        }
        echo $html;
    }

}
