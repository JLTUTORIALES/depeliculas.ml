<?php require_once('res/struct.php'); /* Obtener clase para maquetar web */ ?>
<!DOCTYPE html>
<html lang="es">
<?php web_struct::get_head(); ?>
<body>
    <?php web_struct::get_nav(); web_struct::get_header(); ?>
    <center><article class="main-content">
        <?php

            //Obtiene algunas categorías determinadas para presentar contenido en la página principal

            web_struct::get_category('ÚLTIMOS ESTRENOS EN ESPAÑOL LATINO');
            web_struct::get_movies_group('SELECT * FROM movies WHERE language="Español Latino" ORDER BY id DESC LIMIT 12', '/peliculas/');
            web_struct::get_category('ÚLTIMOS ESTRENOS EN CASTELLANO', 'pelicula');
            web_struct::get_movies_group('SELECT * FROM movies WHERE language="Castellano" ORDER BY id DESC LIMIT 12', '/peliculas/');
            web_struct::get_category('ÚLTIMAS SERIES EN DEPELICULAS');
            web_struct::get_movies_group('SELECT * FROM series ORDER BY id DESC LIMIT 12', '/series/');
        
        ?>
    </article></center>
    <?php web_struct::get_footer(); ?>
</body>
</html>