<?php

    /*
    Clase modelo de carrito
    */

    class Carrito{

        private $id;
        private $activo;
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
        Funcion setter de id de usuario
        */

        public function setIdUsuario($idUsuario){
            /*Llamar parametro*/
            $this->idUsuario = $idUsuario;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para guardar el carrito en la base de datos
        */

        public function guardar(){
            /*Construir consulta*/
            $consulta = "INSERT INTO carritos VALUES(NULL, {$this -> getActivo()}, {$this -> getIdUsuario()})";
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
            $consulta = "SELECT DISTINCT id FROM carritos ORDER BY id DESC LIMIT 1";
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
        Funcion para eliminar el carrito
        */

        public function eliminarCarrito(){
            /*Construir la consulta*/
            $consulta = "UPDATE carritos
                SET activo = '{$this -> getActivo()}' WHERE idUsuario = {$this -> getIdUsuario()}";
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
        Funcion para listar los videojuegos del carrito
        */

        public function listar(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT v.id AS 'idVideojuegoCarrito', v.nombre AS 'nombreVideojuegoCarrito', v.foto AS 'imagenVideojuegoCarrito', v.precio AS 'precioVideojuegoCarrito', cv.unidades AS 'unidadesCarrito', v.stock AS 'stockVideojuego'
                FROM CarritoVideojuego cv
                INNER JOIN carritos c ON cv.idCarrito = c.id
                INNER JOIN videojuegos v ON v.id = cv.idVideojuego
                WHERE c.idUsuario = {$this -> getIdUsuario()} AND cv.activo = 1 AND c.activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Array para almacenar la información del carrito*/
            $informacionCarrito = array();
            /*Establecer una variable total*/
            $total = 0;
            /*Mientras hayan videojuegos en el carrito disponibles para recorrer*/
            while ($fila = $resultado->fetch_object()) {
                /*Comprobar si no existe la informacion del carrito*/
                if(!isset($informacionCarrito['carrito'])) {
                    /*Crear array con informacion del carrito*/
                        $informacionCarrito['carrito'] = array(
                        /*Inicializar un array para almacenar los videojuegos del carrito*/
                        'videojuegos' => array()
                    );
                }
                /*Almacenar la información del videojuego en el array de carrito y videojuego*/
                $informacionCarrito['carrito']['videojuegos'][] = array(
                    'idVideojuegoCarrito' => $fila->idVideojuegoCarrito,
                    'nombreVideojuegoCarrito' => $fila->nombreVideojuegoCarrito,
                    'imagenVideojuegoCarrito' => $fila->imagenVideojuegoCarrito,
                    'precioVideojuegoCarrito' => $fila->precioVideojuegoCarrito,
                    'stockVideojuego' => $fila->stockVideojuego,
                    'unidadesCarrito' => $fila->unidadesCarrito
                );
                /*Obtener precios, unidades y el total del carrito*/
                $precios = $fila -> precioVideojuegoCarrito;
                $unidades = $fila -> unidadesCarrito;
                $total += $precios * $unidades;
                /*Almacenar la información del total del carrito en el array de carrito*/
                $informacionCarrito['totalCarrito'] = array(
                    'totalCarrito' => $total
                );
            }
            /*Retornar el resultado*/
            return $informacionCarrito;
        }

        /*
        Funcion para comprobar si un videojuego ya ha sido agregado a la sección favoritos
        */

        public function comprobarCarrito($idVideojuego){ 
            /*Construir la consulta*/
            $consulta = "SELECT * FROM carritos WHERE activo = 1 AND id IN (SELECT idCarrito FROM carritovideojuego WHERE idusuario = {$this -> getIdUsuario()} AND idVideojuego = {$idVideojuego} AND activo = 1)";
            /*Llamar la funcion que ejecuta la consulta*/
            $usuario = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $usuario -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }
 
    }

?>
