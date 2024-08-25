<?php

    /*
    Clase de la configuracion de la base de datos
    */

    class BaseDeDatos{
        
        /*
        Funcion para conectar la base de datos al proyecto
        */

        public static function connect() {
            /*Instanciar la clase, por parametros se pasan el nombre del servidor, el usuario, la clae y el nombre de la base de datos*/
            $db = new mysqli('localhost', 'root', '', 'mercadoJuegos');
            /*Indicar el tipamiento de datos*/
            $db -> query("SET NAMES 'utf8'");
            /*Retonar el resultado*/
            return $db;
        }
        
    }

?>