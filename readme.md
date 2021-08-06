# depelículas.ml
Sitio web para descargar películas y series en formato torrent

## Descripción
Este es un sitio web desarrollado utilizando principalmente PHP como lenguaje de back-end y MySQL como gestor de base de datos, HTML y CSS para maquetar y dar estilos al contenido; XML para el generador del sitemap del sitio y un poco de JavaScript para corregir las imágenes de las películas y series en caso de que los enlaces estuvieran rotos.

La distribución del sitio web es la siguiente:

    /res
        icon.png   --- El ícono del sitio web con transparencia ya que tiene bordes redondeados.
        struct.php --- Archivo que contiene la mayor parte del código php para el funcionamiento general de la web.
        styles.css ---  Los estilos CSS de toda la página web.
    
    .htaccess     --- Archivo de sintaxis Apache para que las URLs del sitio web sean amigables.
    contenido.php --- En este archivo se visualizan las películas y series (con su ficha técnica) además de las tags.
    find.php      --- Este archivo se encarga de la barra de búsqueda del sitio web.
    index.php     --- Es la página principal del sitio web.
    sitemap.php   --- Es el generador de sitemaps del sitio web.

Adicionalmente encontrarán un archivo llamado "movies_series.db" el cual contiene la base de datos (en formato SQLite) de las películas y series utilizadas en este sitio web (a la fecha de publicar el repositorio).

(Cabe mencionar que en el sitio web real el codigo se encuentra en versión minificada para optimización del mismo)
Sitio web: http://depeliculas.ml



Mi portafolio: https://luisjdev.com