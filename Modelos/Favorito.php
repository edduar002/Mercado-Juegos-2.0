<?php

    /*
    Clase modelo de favorito
    */

    class Favorito{

        private $id;
        private $idUsuario;
        private $db;

        /*
        Funcion constructor
        */

        public function __construct(){
            /*Llamar conexion a la base de datos*/  
            $this -> db = BaseDeDatos::connect();
        }

        /*
        Funcion getter de id
        */

        public function getId(){
            /*Retornar el resultado*/
            return $this->id;
        }

        /*
        Funcion setter de id
        */

        public function setId($id){
            /*Llamar parametro*/
            $this->id = $id;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de id usuario
        */

        public function getIdUsuario(){
            /*Retornar el resultado*/
            return $this->idUsuario;
        }

        /*
        Funcion setter de id de usuario
        */

        public function setIdUsuario($idUsuario){
            /*Llamar parametro*/
            $this->idUsuario = $idUsuario;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para guardar el favorito en la base de datos
        */

        public function guardar(){
            /*Construir consulta*/
            $consulta = "INSERT INTO favoritos VALUES(NULL, {$this -> getIdUsuario()})";
            /*Llamar la funcion que ejecuta la consulta*/
            $guardado = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $bandera = false;
            /*Comprobar si la consulta fue exitosa*/
            if($guardado){
                /*Cambiar el estado de la variable bandera*/
                $bandera = true;
            }
            /*Retornar el resultado*/
            return $bandera;
        }

        /*
        Funcion para obtener el ultimo favorito registrado
        */

        public function ultimo(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT id FROM favoritos ORDER BY id DESC LIMIT 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $ultimo = $resultado -> fetch_object();
            /*Devolver resultado*/
            $ultimoFavorito = $ultimo -> id;
            /*Retornar el resultado*/
            return $ultimoFavorito;
        }

        /*
        Funcion para listar los videojuegos de favoritos
        */

        public function listar(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT v.id AS 'idVideojuego', v.nombre AS 'nombreVideojuego', v.foto AS 'imagenVideojuego', v.precio AS 'precioVideojuego'
                FROM videojuegofavorito vf
                INNER JOIN favoritos f ON vf.idFavorito = f.id
                INNER JOIN videojuegos v ON v.id = vf.idVideojuego
                WHERE f.idUsuario = {$this -> getIdUsuario()} AND vf.activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para comprobar si un videojuego ya ha sido agregado aL carito
        */

        public function comprobarFavorito($idVideojuego){ 
            /*Construir la consulta*/
            $consulta = "SELECT * FROM favoritos WHERE id IN (SELECT idfavorito FROM videojuegofavorito WHERE idusuario = {$this -> getIdUsuario()} AND idVideojuego = {$idVideojuego} AND activo = 1)";
            /*Llamar la funcion que ejecuta la consulta*/
            $usuario = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $usuario -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }
        
    }

?>