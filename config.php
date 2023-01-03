<?php

$location_local = "nukspvc/";

switch ($_SERVER["HTTP_HOST"]) {
    case "localhost":
        $URLBASE = "http://localhost/" . $location_local; //Desarrollo
        break;
    case "127.0.0.1":
        $URLBASE = "http://127.0.0.1/" . $location_local; //Desarrollo
        break;
    case "artpromos.com.mx":
        $URLBASE = "https://nukspvc.com/"; //Produccion
        break;
}


require_once './function/services.class.php';
$TITLE = "";
$URL = (isset($_GET['url'])) ? $_GET['url'] : "home";
$INTERNA = (file_exists("$URL.php")) ? "./$URL.php" : "./404.php";
// echo $INTERNA;

$SERV = new services;

try {

//    setcookie("baseurl", $URLBASE, time() + (86400 * 30), "/", $_SERVER["HTTP_HOST"], true);
    echo '<script>localStorage.setItem("urlbase", "' . $URLBASE . '")</script>';
    $NUM = $SERV->getNumProduct();

    $PAG = ceil($NUM['total'] / 24);
    echo '<script>sessionStorage.setItem("paginas", ' . $PAG . ')</script>';
//    setcookie("paginas", $PAG, time() + (86400 * 30), "/", $_SERVER["HTTP_HOST"], true); // 86400 = 1 day

} catch (Exception $exception) {
    var_dump($exception);
    die();
}


/* SE OBTIENE EL SEO DE LA PAGINA */
$DESCRIPTION = '';
$KEYWORDS = '';
$SEO = $SERV->getSeo();
$DESCRIPTION = $SEO[0]['seo_description'];
$KEYWORDS = $SEO[0]['seo_keywords'];

/* SE OBTIENE EL SEO DE LA PAGINA */

switch ($URL) {
    case 'home':
        $TITLE = 'Inicio -  Nuks Artistic PVC';
        break;
    case 'contacto':
        $DESCRIPTION = 'Contacta con uno de nustros asesores, resuelve tus dudas y recibe tu cotización de forma facil y rapida y al mejor costo.';
        $TITLE = 'Contacto - Nukspvc';
        break;
    case 'producto':
        $TITLE = 'Producto';
        break;
    case 'categoria':
        $TITLE = 'Categoria';
        break;
    /* case 'metodos-de-impresion':
        $DESCRIPTION = 'Conoce las tecnicas de impresión utilizadas en los productos y cotiza tus productos con la tecnica de impresión que más te guste.';
        $TITLE = 'Metodos de impresión';
        break; */
    case 'sobre-nosotros':
        $DESCRIPTION = 'Nuks Artistic PVC ha llegado al mercado  con productos de PVC personalizados: la forma más efectiva de promocionar su marca';
        $TITLE = 'Sobre Nosotros';
        break;
    case 'carrito':
        $DESCRIPTION = 'Cotiza tus articulos de forma facil, rapida y segura, solo agrega los productos a tu carrito de cotización y un asesor se contactara contigo.';
        $TITLE = 'Carrito de articulos';
        break;
    case 'iniciar-sesion':
        $DESCRIPTION = 'Inicia sesion en  Nuks Artistic PVC y conoce más sobre nuestros decuentos en todos los productos de la tienda, solo con tu usuario y contraseña';
        $TITLE = 'Inicio de Sesi&oacute;n';
        break;
    case 'registro':
        $DESCRIPTION = 'Registrate ahora y conoce nuestra amplia variedad de productos promocionales a un mejor precio, rapido y sencillo, comienza ahora.';
        $TITLE = 'Registro - Nuks Artistic PVC';
        break;
    case 'aviso-de-privacidad':
        $DESCRIPTION = 'Nuks Artistic PVC es responsable de cumplir con la Ley Federal de Protección de Datos Personales en Posesión de los Particulares';
        $TITLE = 'Aviso de privacidad';
        break;
    default:
    $DESCRIPTION = 'Contamos con un programa de afiliación que te permite obtener beneficios como Distribuidor de Nuks Artistic PVC salte de la rutina de promoción clásica';
        $TITLE = 'Pagina principal';
        break;
}

if (file_exists('./function/' . $INTERNA)) {
    require_once './function/' . $INTERNA;
}
require_once './function/general.php';
