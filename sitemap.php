<?php
    /*
        GENERADOR DE SITEMAPS SEGÚN ENTRADAS DE LA BBDD
    */

    //Función para agregar una url en el sitemap
    function sitemap_append_url($data, $type = 0){
        $url = urlencode($data['title']);
        $url = strtolower(str_replace('+', '-', $url));
        if ($type == 0){
            return "<url><loc>http://".$_SERVER['HTTP_HOST']."/peliculas/".$url."</loc></url>";
        }
        return "<url><loc>http://".$_SERVER['HTTP_HOST']."/series/".$url."</loc></url>";
    }

    //Obtener la clase para maquetar y crear conexión a BBDD
    require('res/struct.php');
    $conection = db_connect();

    //Comprobar si se intenta acceder al sitemap principal o a una rama.
    if (!isset ($_GET['page'])){

        //Si es el sitemap principal, se hace un conteo de entrardas para determinar la cantidad de ramas a crear

        $sql = "SELECT MAX(id) + (SELECT MAX(id) FROM series) as count FROM movies;";
        $result = mysqli_fetch_array(mysqli_query($conection, $sql))['count'];

        //Se hace una división entrera entre 1000 para determinar cantidad de ramas (Ya que cada rama tendrá un máximo de 1000 entradas)
        $sitemaps_count = intdiv($result, 1000);

        //Se añaden las cabeceras XML para comenzar a maquetar sitemap
        $xml = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        //Bucle para crear las ramas y agregarlas al sitemap
        for ($i = 0; $i <= $sitemaps_count; $i++){
            $xml .= "<sitemap><loc>http://".$_SERVER['HTTP_HOST']."/sitemap_index$i.xml</loc></sitemap>";
        }

        $xml .= "</sitemapindex>";
        
        //Se cambian las cabeceras de la página como contenido XML y se presenta el sitemap con las ramas generadas
        header('Content-type:text/xml;charset:utf8');
        echo $xml;
    
    }else{

        /*
            En caso de ser una rama del sitemap principal, se obtienen las entradas que corresponden siguiendo la
            siguiente expresión:

            id_mínima = número_de_rama * 1000
            
            Ya que cada rama contiene 1000 urls.
        */

        //Se añaden las cabeceras XML para comenzar a maquetar la rama del sitemap
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        //Si es la primera página el límite es 1000 de lo contrario el límite es (id_mínima + 1000)
        $min = 0; if ($_GET['page'] == 0) {  $min = 1; }else{ $min = ($_GET['page'] * 1000) + 1; }
        
        //Se obtienen las películas disponibles para la rama en cuestión
        $cpelis =  mysqli_fetch_array(
            mysqli_query($conection,
                "SELECT COUNT(*) AS pelis FROM movies WHERE id >= $min AND id <= ".($min + 999)
            ) )['pelis'];

        
        //Si las películas son menos de 1000 quiere decir que son las últimas y por lo tanto hay espacio en la rama para ubicar series.
        if ($cpelis < 1000){

            /* 
                El proceso  para seleccionar la ID mínima de las series es diferente ya que se agregar luego de las películas,
                sin embargo, las ID vuelven a contar a partír del número uno, entonces, la expresión es la siguiente:

                id_mínima = (núnero_de_rama * 1000) + películas_en_rama - películas_totales

                de modo que en la primera rama que contenga series la id mínima será 0, en la siguiente 1000 y así sucesivamente
            */

            $_cpelis = mysqli_fetch_array(mysqli_query($conection, "SELECT COUNT(*) AS pelis FROM movies"))['pelis'];
            
            $scount = $min + $cpelis - $_cpelis;
            $slimit = 1000 - $cpelis;

            //Se obtienen las películas y series que caben en la rama
            $qmovies = mysqli_query($conection, "SELECT id, title FROM movies WHERE id >= $min LIMIT ".$cpelis);
            $qseries = mysqli_query($conection, "SELECT id, title FROM series WHERE id >= $scount LIMIT ".$slimit);

            //Se agregan a la rama (Primero las películas que faltan, luego las series)
            while ($fila = mysqli_fetch_array($qmovies)){
                $xml .= sitemap_append_url($fila);
            }

            while ($fila = mysqli_fetch_array($qseries)){
                $xml .= sitemap_append_url($fila, 1);
            }

            $xml .= "</urlset>";

            //Se cambian las cabeceras de la página como contenido XML y se presenta la rama generada.
            header('Content-type:text/xml;charset:utf8');
            echo $xml;

        } else {
            //Si cantidad_de_películas >= 1000 quiere decir que la rama tendrá exclusivamente películas

            //Se obtienen las películas
            $qmovies = "SELECT id, title FROM movies WHERE id >= $min LIMIT 1000";
            $qmovies = mysqli_query($conection, $qmovies);

            //Se agregan a la rama mediante un bucle
            while ($fila = mysqli_fetch_array($qmovies)){
                $xml .= sitemap_append_url($fila);
            }
            $xml .= "</urlset>";

            //Se cambian las cabeceras de la página como contenido XML y se presenta la rama de películas generada.
            header('Content-type:text/xml;charset:utf8');
            echo $xml;

        }

    }
?>