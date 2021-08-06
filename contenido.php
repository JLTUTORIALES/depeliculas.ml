<?php

    //Obtener la clase para maquetar la web
    require_once('res/struct.php');

    //Determinar si es película o serie mediante GET

    if (isset($_GET['peliculas'])){

        //Comprobar si es la tag /peliculas/ o es una película en concreto
        
        if ($_GET['peliculas'] != ""){

            //Si es una película en concreto, obtiene la página generada y la muestra al usuario

            $title = urldecode(str_replace('-', '+', $_GET['peliculas']));
            web_struct::get_movie_content($title);

        } else{

            //Si es la tag /peliculas/ muestra una cantidad determinada de las películas más recientes

            web_struct::get_head('Películas - DEPELICULAS', '../../');
            web_struct::get_header('Películas');
            web_struct::get_nav('../../');
            echo '<center><article class="main-content">';
            web_struct::get_category('Películas más recientes');
            web_struct::get_movies_group('SELECT * FROM movies ORDER BY id DESC LIMIT 36');
            echo '</article></center>';
            web_struct::get_footer('../../');

        }

    } else if (isset($_GET['series'])){

        //Comprobar si es la tag /series/ o es una serie en concreto

        if ($_GET['series'] != ""){

            //Si es una serie en concreto, obtiene la página generada y la muestra al usuario

            $title = urldecode(str_replace('-', '+', $_GET['series']));
            web_struct::get_movie_content($title, 1);

        }else{

            //Si es la tag /series/ muestra una cantidad determinada de las series más recientes

            web_struct::get_head('Series - DEPELICULAS', '../../');
            web_struct::get_header('Series');
            web_struct::get_nav('../../');
            echo '<center><article class="main-content">';
            web_struct::get_category('Series más recientes');
            web_struct::get_movies_group('SELECT * FROM series ORDER BY id DESC LIMIT 36');
            echo '</article></center>';
            web_struct::get_footer('../../');

        }

    }else{

        //Si no es película ni serie, se verifica con una lísta de TAGS a qué corresponde

        $tags_dict = [
            "drama" => ["Drama", "'%drama%'"],
            "comedia" => ["Comedia", "'%comedia%'"],
            "misterio" => ["Misterio", "'%misterio%'"],
            "crimen" => ["Crimen", "'%crimen%'"],
            "accion" => ["Acción", "'%accion%' OR genre LIKE '%action%' "],
            "suspenso" => ["Suspenso", "'%suspens%'"],
            "aventura" => ["Aventura", "'adventure' OR genre LIKE '%aventura%'"],
            "scifi-y-fantasy" => ["Ciencia Ficción y Fantasía", "'%fantas%' OR genre LIKE '%sci-fi%' OR genre LIKE '%ciencia ficción%'"],
            "romance" => ["Romance", "'%romance%'"],
            "terror" => ["Terror", "'%terror%'"],
            "familia" => ["Familia", "'%familia%'"],
            "documentales" => ["Documentales y TV", "'%docu%' OR genre LIKE '%tv%'"],
            "historia" => ["Historia", "'%historia%'"],
            "guerra" => ["Guerra", "'%guerra%'"],
            "musica" => ["Música", "'%música%'"],
            "western" => ["Western", "'%western%'"],
            "guerra-y-politica" => ["Guerra y Política", "'%guerrra%' OR genre LIKE '%historia%'"],
            "thriller" => ["Triller", "'%thriller%'"],
            "biografia" => ["Biografía", "'%biografía%'"]
        ];

        //Si es una de las tags en lísta se presenta contenido relacionado, de lo contrario, error 404

        if (isset($_GET['mode'])){

            $result = $tags_dict[$_GET['mode']];

            web_struct::get_head($result[0].' - DEPELICULAS', '../../');
            web_struct::get_header('Lo más reciente en '.$result[0]);
            web_struct::get_nav('../../');
            echo '<center><article class="main-content">';
            web_struct::get_category(strtoupper($result[0])." EN PELÍCULAS");
            web_struct::get_movies_group('SELECT * FROM movies WHERE genre LIKE '.$result[1].' ORDER BY id DESC LIMIT 18', '../../peliculas/');
            web_struct::get_category(strtoupper($result[0])." EN SERIES");
            web_struct::get_movies_group('SELECT * FROM series WHERE genre LIKE '.$result[1].' ORDER BY id DESC LIMIT 18', '../../series/');
            echo '</article></center>';
            web_struct::get_footer('../../');
        } else {
            header('HTTP/1.1: 404 Not Found');
        }
    }
?>