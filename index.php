<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" content="text/html">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Prueba Lleego</title>
</head>
<body>
    <div id="content" class="justify-content-center align-items-center">
        <form method="post" class="form-inline justify-content-center align-items-center" id="formApi">
            <div class="form-group mb-2">
                <label>Introduzca su destino:</label>
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" name="destiny" required/>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Comprobar</button>
        </form>

        <br><br>

        <?php

        if (isset($_POST["destiny"])){
            callApi();
        }

        ?>
    </div>
</body>
</html>

<?php

    function callApi(){

        //Recogemos la variable con el destino
        $destiny = $_POST["destiny"];

        //Declaro la key de la API
        $apiKey = "88358f766e194221b1c940a6c764de26";
        //URL de la api -> city: nombre ciudad, lang: lenguaje respuesta, days: cantidad de dias del resultado
        $apiUrl = "https://api.weatherbit.io/v2.0/forecast/daily?city=".normalize($destiny)."&lang=es&days=7&key=".$apiKey;

        //Iniciamos sesión de curl y definimos opciones basicas
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, $apiUrl);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($cu);

        if ($resp === false){
            echo "Algo ha ido mal: ".curl_error($cu);
        }
        curl_close($cu);

        //Formateamos el resultado y lo dejamos preparado para trabajarlo como objeto
        $objetosTiempo = json_decode($resp, false);

        echo "<h4>La temperatura para los próximos 7 días en ".ucfirst($destiny)." será: </h4>";

        echo "<ul class='list-group list-group-flush'>";

        //Recorremos el objeto data que contiene los datos que necesitamos para este caso
        foreach ($objetosTiempo->data as $tiempo){
            //Pasamos string a formato fecha
            $date = strtotime($tiempo->datetime);

            //Damos formato d/m/y a la fecha y sacamos la temperatura mínima y máxima
            echo "<li class='list-group-item'>";
                echo "Fecha: ".date("d/m/y", $date)." - ".$tiempo->weather->description;
                echo " - Temp. mínima: ".$tiempo->min_temp."ºC | Temperatura máxima: ".$tiempo->max_temp."ºC";
            echo "</li>";
        }

        echo "</ul>";

    }

    //Añado esta función para prevenir la introducción de acentos en la url de la API. Fuente: php.net/strtr
    function normalize ($string) {
        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
        );

        return strtr($string, $table);
    }

?>