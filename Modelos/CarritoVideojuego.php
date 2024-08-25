<?php

    /*
    Clase modelo de carrito videojuego
    */

    class CarritoVideojuego{

        private $id;
        private $activo;
        private $idVideojuego;
        private $idCarrito;
        private $unidades;
        private $precio;
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
        Funcion getter de id carrito
        */

        public function getIdCarrito(){
            /*Retornar el resultado*/
            return $this->idCarrito;
        }

        /*
        Funcion setter de id carrito
        */

        public function setIdCarrito($idCarrito){
            /*Llamar parametro*/
            $this->idCarrito = $idCarrito;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de precio
        */

        public function getPrecio(){
            /*Retornar el resultado*/
            return $this->precio;
        }

        /*
        Funcion setter de precio
        */

        public function setPrecio($precio){
            /*Llamar parametro*/
            $this->precio = $precio;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de unidades
        */

        public function getUnidades(){
            /*Retornar el resultado*/
            return $this->unidades;
        }

        /*
        Funcion setter de unidades
        */

        public function setUnidades($unidades){
            /*Llamar parametro*/
            $this->unidades = $unidades;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para guardar la relacion entre videojuego y carrito en la base de datos
        */

        public function guardar(){
            /*Construir la consulta*/
            $consulta = "INSERT INTO carritovideojuego VALUES(NULL, {$this -> getActivo()}, 
                {$this -> getIdVideojuego()}, {$this -> getIdCarrito()}, {$this -> getUnidades()}, 
                {$this -> getPrecio()})";
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
        Funcion para eliminar un videojuego del carrito
        */

        public function eliminarVideojuego($idUsuario){
            /*Construir la consulta*/
            $consulta = "UPDATE carritovideojuego
                SET activo = '{$this -> getActivo()}' WHERE idCarrito IN (SELECT id FROM carritos WHERE idUsuario = $idUsuario)
                AND idVideojuego = {$this -> getIdVideojuego()}";
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

        /*
        Funcion para disminuir unidades del carrito
        */

        public function disminuirUnidades($idUsuario){
            /*Construir la consulta*/
            $consulta = "UPDATE carritovideojuego
                SET unidades = unidades - 1 WHERE idCarrito IN (SELECT id FROM carritos WHERE idUsuario = $idUsuario)
                AND idVideojuego = {$this -> getIdVideojuego()}";
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

        /*
        Funcion para aumentar unidades del carrito
        */

        public function aumentarUnidades($idUsuario){
            /*Construir la consulta*/
            $consulta = "UPDATE carritovideojuego
                SET unidades = unidades + 1 WHERE idCarrito IN (SELECT id FROM carritos WHERE idUsuario = $idUsuario)
                AND idVideojuego = {$this -> getIdVideojuego()}";
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