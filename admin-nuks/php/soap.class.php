<?php

class ApiRestSoap
{
    public function __construct()
    {
    }

    public function soapConnection($wsdl)
    { //Función nusoap para conectar al servidor donde recibe como parametro la URL del webservice
        require_once 'nu-soap/lib/nusoap.php';
        $client = new nusoap_client($wsdl, 'wsdl'); //Crea el cliente
        return $client; //Retorna la conexion
    }

    public function getProductsAllMacma()
    { //Función que retorna todos los productos del servidor
        $client = $this->soapConnection("https://macmamexico.mx/query/query.php?wsdl"); //Se manda a llamar a la funcion soapConnection para guardar la conexión
        $err = $client->getError(); //Obtenemos el error si existe
        if ($err) { //Si existe un error se saldra de la función
            exit();
        }

        $params = array('User' => '0176', 'Key' => 'AQS8243'); //Enviamos las credenciales
        $RES = $client->call('SearchAllProducts', $params); //Almacenamos el Json en $RES

        return $RES; //Retornamos el Json
    }

    public function getProductMacma($COD)
    { //Función que retorna un producto con base a su codigo de producto
        $client = $this->soapConnection("https://macmamexico.mx/query/query.php?wsdl");
        $err = $client->getError();
        if ($err) {
            exit();
        }
        $params = array('User' => '0176', 'Key' => 'AQS8243', 'idProduct' => $COD); //Se agregan las credenciales y el id del porducto que se requiere la información
        $RES = $client->call('SearchByCode', $params);

        return $RES; //Retorna en un Json el producto solicitado
    }

    public function getProductsAllG4()
    {
        $client = $this->soapConnection("https://distr.ws.g4mexico.com/index.php?wsdl");
        $err = $client->getError();
        if ($err) { //MOSTRAR ERRORES
            //echo '<h2>Constructor error</h2>' . $err;       //Esta linea imprime el error capturado
            exit();
        }
        //arreglo parámetros para la consulta de un solo producto ,'sku'=>'anf-cav-gob'
        $params = array('user' => 'C3206', 'key' => 'Ven@06');
        $RES = $client->call('getProduct', $params);
        return $RES;
    }

    public function getProductG4($COD)
    {
        $client = $this->soapConnection("https://distr.ws.g4mexico.com/index.php?wsdl");
        $err = $client->getError();
        if ($err) { //MOSTRAR ERRORES
            echo '<h2>Constructor error</h2>' . $err;
            exit();
        }
        //ARREGLO PARAMETROS
        $params = array('user' => 'C3206', 'key' => 'Ven@06', 'sku' => $COD);
        $RES = $client->call('getProduct', $params);

        return $RES;
    }

    public function getProductsAllInnovation($page)
    {
        $client = $this->soapConnection("https://ws.innovation.com.mx/index.php?wsdl");
        /* $err = $client->getError();
         if ($err) { //MUESTRAR ERRORES
             //echo '<h2>Constructor error</h2>' . $err;       //Esta linea imprime el error capturado
             exit();
         }
         $params = array('user_api' => 'pkdajVI6091a83b1c23du2xA', 'api_key' => 'aKi6Dq-8YC6091a83b1c244I174PFuSg03eJd', 'format' => 'XML', 'page' => $PAG);
         $RES = $client->call('Products', $params);
         //echo '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
         //echo '<br/> <pre class="prettyprint">'.htmlentities(base64_decode($RES)).'</pre>';
         return $RES;*/

        $err = $client->getError();
        if ($err) {//MOSTRAR ERRORES
            echo '<h2>Constructor error</h2>' . $err;
            exit();
        }
        $params = array('user_api' => 'pkdajVI6091a83b1c23du2xA', 'api_key' => 'aKi6Dq-8YC6091a83b1c244I174PFuSg03eJd', 'format' => 'JSON');//PARAMETROS
        $response = $client->call('Pages', $params); //MÉTODO PARA OBTENER EL NÚMERO DE PÁGINAS ACTIVAS
        $response = json_decode($response, true);
        if ($response['response'] === true) {
            $pag = 1;
            while ($pag <= $response['pages']) {
                $selected = '';
                if ($pag === intval($page)) {
                    $selected = 'selected';
                }
                $options .= '<option value="' . $pag . '" ' . $selected . '="">' . $pag . '</option>';
                $pag++;
            }
//            echo '<div class="col-md-10"><h1>Todos los productos</h1></div><div class="col-md-2 mt-5"><label for="page">Página:</label><select class="form-control" id="page" name="page">' . $options . '</select></div>';
        }
        $params = array('user_api' => 'pkdajVI6091a83b1c23du2xA', 'api_key' => 'aKi6Dq-8YC6091a83b1c244I174PFuSg03eJd', 'format' => 'JSON', 'page' => $page);//PARAMETROS
        $response = $client->call('Products', $params);//MÉTODO PARA CONSULTAR LOS PRODUCTOS
        $response = json_decode($response, true);
        if ($response['response'] === true) {
            return $response;
            /******* TU CÓDIGO ********/
            /*foreach ($response['data'] as $key => $value) {
                $categories = '';
                foreach ($value['categorias'] as $k => $val) {
                    if ($k == 0) {
                        $categories .= '<span>' . $val['nombre'] . ' </span>';
                    } else {
                        $categories .= ' | <span>' . $val['nombre'] . ' </span>';
                    }
                }
                echo '<div class=" col-lg-3 col-md-4 col-6 mb-5 card-product text-center"><img src="' . $value['imagen_principal'] . '" class="w-100"><h4>' . $value['codigo'] . '</h4><p>' . $categories . '</p><p>' . $value['nombre'] . '</p><p><strong>Desde: </strong> $' . number_format($value['lista_precios'][4]['mi_precio'], 2, '.', ',') . '</p></div>';
            }*/
            /******* TU CÓDIGO ********/
        }
    }

    public function getProductInnovation($COD)
    {
        $client = $this->soapConnection("https://ws.innovation.com.mx/index.php?wsdl");
        $err = $client->getError();
        if ($err) { //MUESTRAR ERRORES
            //echo '<h2>Constructor error</h2>' . $err;       //Esta linea imprime el error capturado
            exit();
        }
        $params = array('user_api' => 'pkdajVI6091a83b1c23du2xA', 'api_key' => 'aKi6Dq-8YC6091a83b1c244I174PFuSg03eJd', 'format' => 'XML', "code_product" => "$COD");
        $RES = $client->call('Stock', $params);
        //var_dump($RES);
        return $RES;
    }

    public function getProductsAllBlestar()
    {
        $Client = $this->soapConnection("https://blestar.net/WebServiceBlestar/server.php?wsdl");
        $err = $Client->getError();
        if ($err) {
            exit();
        }
        $params = array('Token' => 'GKB4E40e7292ac638c698');
        $RES = $Client->call('SearchAllProducts', $params);
        // var_dump($RES);
        return $RES;
    }

    public function getProductBlestar($COD)
    {
        $Client = $this->soapConnection("https://blestar.net/WebServiceBlestar/server.php?wsdl");

        $err = $Client->getError();
        if ($err) {
            exit();
        }
        $params = array('Token' => 'GKB4E40e7292ac638c698', 'idProduct' => $COD);
        $RES = $Client->call('SearchByCode', $params);
        return $RES;

    }

    public function getProductsAllDobleVela()
    {
        $client = $this->soapConnection("http://srv-datos.dyndns.info/doblevela/service.asmx?wsdl");
        $err = $client->getError();
        if ($err) { //MUESTRAR ERRORES
            //echo '<h2>Constructor error</h2>' . $err;       //Esta linea imprime el error capturado
            exit();
        }
        $params = array('Key' => 't5jRODOUUIqvGsiusc5o+w==');
        $RES = $client->call('GetExistenciaAll', $params);
        //echo '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
        //echo '<br/> <pre class="prettyprint">'.htmlentities(base64_decode($RES)).'</pre>';
        return $RES;
    }

    public function getProductDobleVela($COD)
    {
        $Client = $this->soapConnection("http://srv-datos.dyndns.info/doblevela/service.asmx?wsdll");

        $err = $Client->getError();
        if ($err) {
            exit();
        }
        $params = array('Key' => 't5jRODOUUIqvGsiusc5o+w==', 'codigo' => $COD); //GetExistencia
        $RES = $Client->call('GetExistencia', $params);
        return $RES;
    }

    public function validateConnectionInnova()
    {

        $wsdl = "https://ws.innovation.com.mx/index.php?wsdl";
        $client = $this->soapConnection($wsdl);
        $err = $client->getError();
        if ($err) {//MOSTRAR ERRORES
            echo '<h2>Constructor error</h2>' . $err;
            exit();
        }
        // Especificar de respuesta XML en arreglo de parametros
        $params = array('user_api' => 'pkdajVI6091a83b1c23du2xA', 'api_key' => 'aKi6Dq-8YC6091a83b1c244I174PFuSg03eJd', 'format' => 'JSON');
        //Médodo Validate
        $response = $client->call('Validate', $params);
        /****** TU CÓDIGO AQUÍ ******/
        //mostrar el resultado

        return $response;

    }

    public function getActivePagesInnove()
    {
        $wsdl = "https://ws.innovation.com.mx/index.php?wsdl";
        $client = $this->soapConnection($wsdl);
        $err = $client->getError();
        if ($err) {//MOSTRAR ERRORES
            echo '<h2>Constructor error</h2>' . $err;
            exit();
        }
        // Formato de respuesta XML
        $params = array('user_api' => 'pkdajVI6091a83b1c23du2xA', 'api_key' => 'aKi6Dq-8YC6091a83b1c244I174PFuSg03eJd', 'format' => 'JSON');//parametros
        //Método
        $response = $client->call('Pages', $params);
        /****** TU CÓDIGO AQUÍ ******/
        // mostrar el resultado
        return $response;
    }

}
