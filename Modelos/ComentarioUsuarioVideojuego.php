<?php

    /*
    Clase modelo de comentario usuario videojuego
    */

    class ComentarioUsuarioVideojuego{

        private $id;
        private $activo;
        private $idUsuario;
        private $idVideojuego;
        private $contenido;
        private $fechaHora;
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
        Funcion getter de activo
        */

        public function getActivo(){
            /*Retornar el resultado*/
            return $this->activo;
        }

        /*
        Funcion setter de activo
        */
    
        public function setActivo($activo){
            /*Llamar parametro*/
            $this->activo = $activo;
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
        Funcion setter de id usuario
        */

        public function setIdUsuario($idUsuario){
            /*Llamar parametro*/
            $this->idUsuario = $idUsuario;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de id videojuego
        */

        public function getIdVideojuego(){
            /*Retornar el resultado*/
            return $this->idVideojuego;
        }

        /*
        Funcion setter de id videojuego
        */

        public function setIdVideojuego($idVideojuego){
            /*Llamar parametro*/
            $this->idVideojuego = $idVideojuego;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de fecha hora
        */

        public function getFechaHora(){
            /*Retornar el resultado*/
            return $this->fechaHora;
        }

        /*
        Funcion setter de fecha hora
        */

        public function setFechaHora($fechaHora){
            /*Llamar parametro*/
            $this->fechaHora = $fechaHora;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de contenido
        */

        public function getContenido(){
            /*Retornar el resultado*/
            return $this->contenido;
        }

        /*
        Funcion setter de contenido
        */

       public function setContenido($contenido){
            /*Llamar parametro*/
            $this->contenido = $contenido;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para guardar la relacion entre videojuego y comentario en la base de datos
        */

        public function guardar(){
            /*Llamar la funcion para encriptar el contenido del comentario*/
            $contenidoEncriptado = Ayudas::encriptarContenido($this -> getContenido());
            /*Construir la consulta*/
            $consulta = "INSERT INTO comentariousuariovideojuego VALUES(NULL, {$this -> getActivo()}, {$this -> getIdUsuario()}, {$this -> getIdVideojuego()}, '{$contenidoEncriptado}', '{$this -> getFechaHora()}')";
            /*Llamar la funcion que ejecuta la consulta*/
            $registro = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $resultado = false;
            /*Comprobar si la consulta fue exitosa*/
            if($registro){
                /*Cambiar el estado de la variable bandera*/
                $resultado = true;
            }
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener los comentarios de cada videojuego
        */

        public function obtenerComentariosDeVideojuego(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT c.contenido AS 'contenidoComentario', u.nombre AS 'nombreComentador', u.foto AS 'fotoComentador', c.fechaHora AS 'fechaCreacionComentario', c.id AS 'idComentario', u.id AS 'usuarioComentador'
                FROM ComentarioUsuarioVideojuego c
                INNER JOIN Usuarios u ON u.id = c.idUsuario
                WHERE c.idVideojuego = {$this -> getIdVideojuego()} AND c.activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para eliminar un comentario
        */

        public function eliminar(){
            /*Construir la consulta*/
            $consulta = "UPDATE ComentarioUsuarioVideojuego SET activo = '{$this -> getActivo()}' WHERE id = {$this -> getId()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $eliminado = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $bandera = false;
            /*Comprobar si la consulta fue exitosa*/
            if($eliminado){
                /*Cambiar el estado de la variable bandera*/
                $bandera = true;
            }
            /*Retornar el resultado*/
            return $bandera;
        }
        
    }

?>