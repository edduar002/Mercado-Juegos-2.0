<?php

    class VideojuegoCategoria{

        private $id;
        private $activo;
        private $idVideojuego;
        private $idCategoria;
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
        Funcion getter de id categoria
        */

        public function getIdCategoria(){
            /*Retornar el resultado*/
            return $this->idCategoria;
        }

        /*
        Funcion setter de id categoria
        */

        public function setIdCategoria($idCategoria){
            /*Llamar parametro*/
            $this->idCategoria = $idCategoria;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para guardar la relacion entre videojuego y categoria en la base de datos
        */

        public function guardar(){
            /*Recorrer lista de categorias*/
            foreach($this -> getIdCategoria() as $categorias){
                /*Construir consulta*/
                $consulta = "INSERT INTO videojuegocategoria VALUES(NULL, {$this -> getIdVideojuego()}, {$categorias})";
                /*Llamar la funcion que ejecuta la consulta*/
                $registro = $this -> db -> query($consulta);
            }
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

    }

?>