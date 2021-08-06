<?php

    //Carga las clase para maquetar la web
    require_once('res/struct.php');

    if (isset($_GET['keys'])){

        //Obtiene las series o películas cuyos títulos correspondan con los términos de busqueda y los muestra como tarjetas.
        $keys = $_GET['keys'];
        $cm = mysqli_fetch_array(mysqli_query( db_connect(), "SELECT COUNT(*) AS conteo FROM movies WHERE title LIKE '%".$keys."%'" ));
        $cs = mysqli_fetch_array(mysqli_query( db_connect(), "SELECT COUNT(*) AS conteo FROM series WHERE title LIKE '%".$keys."%'" ));


        web_struct::get_head('Buscar contenido - Depelículas');
        web_struct::get_nav();
        web_struct::get_header('Buscar contenido');

        echo '<center><article class="main-content">';
        web_struct::get_category('Películas que coinciden con "'.$keys.'" ('.$cm['conteo'].' Resultados)');
        web_struct::get_movies_group('SELECT * FROM movies WHERE title LIKE "%'.$keys.'%" OR content LIKE "'.$keys.'" ORDER BY id DESC', '/peliculas/');
        web_struct::get_category('Series que coinciden con "'.$keys.'" ('.$cs['conteo'].' Resultados)');
        web_struct::get_movies_group('SELECT * FROM series WHERE title LIKE "%'.$keys.'%" OR content LIKE "'.$keys.'" ORDER BY id DESC', '/series/');
        echo '</article></center>';
        web_struct::get_footer();

    } else {
        //En caso de que no hayan sido introducidos términos de búsqueda se regresará a la página principal
        header('LOCATION: index');
    }


?>