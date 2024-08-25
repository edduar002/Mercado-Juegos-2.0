<?php

    /*
    Clase modelo de consola
    */

    class Consola{

        private $id;
        private $activo;
        private $nombre;
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
        Funcion getter de nombre
        */

        public function getNombre(){
            /*Retornar el resultado*/
            return $this->nombre;
        }

        /*
        Funcion setter de nombre
        */

        public function setNombre($nombre){
            /*Llamar parametro*/
            $this->nombre = $nombre;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para realizar el registro de la consola en la base de datos
        */

        public function guardar(){
            /*Construir la consulta*/
            $consulta = "INSERT INTO consolas VALUES(NULL, {$this -> getActivo()}, '{$this -> getNombre()}')";
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
        Funcion para listar todas las consolas activas
        */

        public function listar(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM consolas WHERE activo = 1 ORDER BY nombre ASC";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para obtener la consola buscada, si existe
        */

        public function buscar(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * 
                FROM consolas
                WHERE nombre LIKE '%{$this -> getNombre()}%' AND activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para listar todas las consolas inactivas
        */

        public function listarInactivas(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM consolas WHERE activo = 0";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para obtener una consola
        */

        public function obtenerUna(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM consolas WHERE id = {$this -> getId()} AND activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $consola = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $consola -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para eliminar la consola
        */

        public function eliminar(){
            /*Construir la consulta*/
            $consulta = "UPDATE consolas SET activo = '{$this -> getActivo()}' WHERE id = {$this -> getId()}";
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
        Funcion para actualizar la consola
        */

        public function actualizar(){
            /*Construir la consulta*/
            $consulta = "UPDATE consolas SET nombre = '{$this -> getNombre()}' 
                WHERE id = {$this -> getId()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $actualizado = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $bandera = false;
            /*Comprobar si la consulta fue exitosa y el total de columnas afectadas se altero llamando la ejecucion de la consulta*/
            if($actualizado && mysqli_affected_rows($this -> db) > 0){
                /*Cambiar el estado de la variable bandera*/
                $bandera = true;
            }
            /*Retornar el resultado*/
            return $bandera;
        }

        /*
        Funcion para comprobar si ya se ha creado la consola con anterioridad
        */

        public function comprobarConsolaUnica($nombre){
            /*Construir la consulta*/
            $consulta = "SELECT activo FROM consolas WHERE nombre = '{$this -> getNombre()}' AND nombre != '{$nombre}'";
            /*Llamar la funcion que ejecuta la consulta*/
            $consola = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $consola -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para recuperar la consola eliminada
        */

        public function recuperarConsola(){
            /*Comprobar si se quiere recuperar atravez del id*/
            if($this -> getId() != null){
                /*Construir la consulta*/
                $consulta = "UPDATE consolas SET activo = 1 WHERE id = {$this -> getId()}";
            /*Comprobar si se quiere recuperar atravez del nombre*/
            }elseif($this -> getNombre() != null){
                /*Construir la consulta*/
                $consulta = "UPDATE consolas SET activo = 1 WHERE nombre = '{$this -> getNombre()}'";
            }
            /*Llamar la funcion que ejecuta la consulta*/
            $recuperado = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $bandera = false;
            /*Comprobar si la consulta fue exitosa*/
            if($recuperado){
                /*Cambiar el estado de la variable bandera*/
                $bandera = true;
            }
            /*Retornar el resultado*/
            return $bandera;
        }

    }

?>