<?php
    
    //Información de BBDD para conexión
    $db = [
        "server" => "localhost",
        "user" => "root",
        "pass" => "",
        "database" => "movies_test_db"
    ];

    //Retorna conexión con BBDD
    function db_connect(){
        global $db;
        return mysqli_connect($db['server'], $db['user'], $db['pass'], $db['database']);
    }

    //Clase para generalizar la estructura de la web
    class web_struct{
        
        //Obtener el Head HTML
        public static function get_head($title = 'depeliculas - Descarga peliculas y series gratis en español formato torrent', $pre = ''){
            echo '<head>
                <meta charset="UTF-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
                <meta name="description" content="En depeliculas podrás encontrar miles de peliculas de diversos idiomas y géneros para descargar en formato torrent"/>
                <meta name="keywords" content="descarga de peliculas, peliculas torrent descargar, similares elitetorrent, peliculas torrent subtituladas"/>
                <title>'.$title.'</title>
                <script src="https://pro.fontawesome.com/releases/v5.10.0/js/all.js" Defer></script>
                <link rel="stylesheet" href="'.$pre.'res/styles.css"/>
                <link rel="icon" href="'.$pre.'res/icon.png"/>
            </head>';
        }

        //Obtener la barra de navegación HTML (nav)
        public static function get_nav($pre = '/'){
            echo '<nav>
                <a href="'.$pre.'index"><button><span class="fas fa-home" title="Inicio"></button></a>
                <form action="'.$pre.'find" autocomplete="off" method="get">
                    <button type="submit"><span class="fa fa-search" title="buscar"></span></button> 
                    <input type="text" name="keys"/>
                </form>
            </nav>';
        }

        //Obtener la cabecera del cuerpo de la web (header)
        public static function get_header($text = "Peliculas y series en español formato torrent"){
            echo '<header>
                <div class="header-text">
                    <h2 class="main-subtitle">'.$text.'</h2>
                    <span>(Podrás filtrar por categorías al final de la página)</span>
                </div>
            </header>';
        }

        //Obtener barra clasificadora de categorías (pj. "Películas en Castellano")
        public static function get_category($text){
            echo '<span class="split-category">'.$text.'</span>';
        }


        //Obtener el contenido de la entrada (película o serie) según el título
        public static function get_content($title, $type = 0){
            if ($type == 0){
                return mysqli_fetch_array( mysqli_query(db_connect(), "SELECT * FROM movies WHERE title='$title'") );
            }else{
                return mysqli_fetch_array( mysqli_query(db_connect(), "SELECT * FROM series WHERE title='$title'") );
            }
        }

        //Obtener el pie de página HTML de la web con las categorías
        public static function get_footer($pre = '/'){
            echo '<footer class="tags">
                <p>depeliculas.ml &copy; 2021</p>
                <ul>
                    <h1>Categorías</h1>
                    <li><a href="'.$pre.'series/">SERIES</a></li>
                    <li><a href="'.$pre.'peliculas/">PELÍCULAS</a></li>
                    <li><a href="'.$pre.'drama/">DRAMA</a></li>
                    <li><a href="'.$pre.'comedia/">COMEDIA</a></li>
                    <li><a href="'.$pre.'misterio/">MISTERIO</a></li>
                    <li><a href="'.$pre.'crimen/">CRIMEN</a></li>
                    <li><a href="'.$pre.'accion/">ACCIÓN</a></li>
                    <li><a href="'.$pre.'suspenso/">SUSPENSO</a></li>
                    <li><a href="'.$pre.'aventura/">ACTION & ADVENTURE</a></li>
                    <li><a href="'.$pre.'scifi-y-fantasy/">SCI-FI & FANTASY</a></li>
                    <li><a href="'.$pre.'romance/">ROMANCE</a></li>
                    <li><a href="'.$pre.'terror/">TERROR</a></li>
                    <li><a href="'.$pre.'familia/">FAMILIA</a></li>
                    <li><a href="'.$pre.'documentales-y-tv/">DOCUMENTALES Y TV</a></li>
                    <li><a href="'.$pre.'historia/">HISTORIA</a></li>
                    <li><a href="'.$pre.'guerra/">GUERRA</a></li>
                    <li><a href="'.$pre.'musica/">MUSICA</a></li>
                    <li><a href="'.$pre.'western/">WESTERN</a></li>
                    <li><a href="'.$pre.'guerra-y-politica/">GUERRA Y POLÍTICA</a></li>
                    <li><a href="'.$pre.'thriller/">THRILLER</a></li>
                    <li><a href="'.$pre.'biografia/">BIOGRAFÍA</a></li>
                </ul>
            </footer>';
        }

        //Obtener un lista de tarjetas (Películas o Series) según la consulta SQL por parámetro
        public static function get_movies_group($query, $slug = ""){

            $result = mysqli_query(db_connect(), $query);            
            $_result = "<ul class='movies'>";

            while ($fila = mysqli_fetch_array($result)){
                $url = urlencode($fila['title']);
                $url = strtolower(str_replace('+', '-', $url));
                $title = $fila['title'];

                if (strlen($title) > 20){ $title = substr($title, 0, 17)."..."; }

                $_result .= '<li class="movie"><a href="'.$slug.$url.'" target="_blank" title="'.$fila['title'].'">
                    <img loading="lazy" src="'.$fila['thumb'].'" onerror="this.src=\'https://www.elitetorrent.fm/wp-includes/images/media/default.png\'" alt="Portada de '.$fila['title'].'" class="thumb"/>'.$title.'</a></li>';
            }

            $_result .= "</ul>";
            echo $_result;

        }

        //Generar la página de la película o serie en función del título pasado por URL
        public static function get_movie_content($title, $type = 0){

            $content = web_struct::get_content($title, $type);
            if (!isset($content) ) { die ( header('HTTP/1.1: 404 Not Found') ); }

            web_struct::get_head($content['title']." Descargar por torrent", '../../');
            web_struct::get_nav('../../');
            web_struct::get_header($content['title']." Descargar por torrent");

            echo '<center>
                <article class="movie-descr">
                <div class="ficha-pelicula">
                    <div class="l-info">
                        <img src="'.$content['thumb'].'" onerror="this.src=\'https://www.elitetorrent.fm/wp-includes/images/media/default.png\'"/>
                        <a href="'.$content['download'].'" target="blank"><button>DESCARGAR</button></a>
                    </div>
                <div class="info">';

            web_struct::get_category('Información técnica');

            echo '<ul>';

            //Obtener los detalles técnicos de la película o serie si están disponibles.

            if ($content['fecha'] != "DESCONOCIDO") { echo"<li>Fecha: ".$content['fecha']."</li>"; } 
            if ($content['size'] != "NONE") { echo'<li>Tamaño: '.$content['size'].'</li>'; } 
            if ($content['genre'] != "NONE") { echo'<li>Género: '.$content['genre'].'</li>'; }
            if ($content['language'] != "NONE") { echo'<li>Idioma: '.$content['language'].'</li>'; } 
            if ($content['format'] != "NONE") { echo'<li>Formato: '.$content['format'].'</li>'; }
            if ($content['quality'] != "NONE") { echo'<li>Cálidad: '.$content['quality'].'</li>'; }

            web_struct::get_category('Detalles'); echo '<p>'.$content['content'].'</p></div></div></article></center>';
            web_struct::get_footer();

            

        }

    }

?>