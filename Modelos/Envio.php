<?php

    /*
    Clase modelo de envio
    */

    class Envio{

        private $id;
        private $activo;
        private $idUsuario;
        private $departamento;
        private $municipio;
        private $codigoPostal;
        private $barrio;
        private $direccion;
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
        Funcion getter de departamento
        */

        public function getDepartamento(){
            /*Retornar el resultado*/
            return $this->departamento;
        }

        /*
        Funcion setter de departamento
        */

        public function setDepartamento($departamento){
            /*Llamar parametro*/
            $this->departamento = $departamento;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de municipio
        */

        public function getMunicipio(){
            /*Retornar el resultado*/
            return $this->municipio;
        }

        /*
        Funcion setter de municipio
        */

        public function setMunicipio($municipio){
            /*Llamar parametro*/
            $this->municipio = $municipio;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de codigo postal
        */

        public function getCodigoPostal(){
            /*Retornar el resultado*/
            return $this->codigoPostal;
        }

        /*
        Funcion setter de codigo postal
        */

        public function setCodigoPostal($codigoPostal){
            /*Llamar parametro*/
            $this->codigoPostal = $codigoPostal;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de barrio
        */

        public function getBarrio(){
            /*Retornar el resultado*/
            return $this->barrio;
        }

        /*
        Funcion setter de barrio
        */

        public function setBarrio($barrio){
            /*Llamar parametro*/
            $this->barrio = $barrio;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de direccion
        */

        public function getDireccion(){
            /*Retornar el resultado*/
            return $this->direccion;
        }

        /*
        Funcion setter de direccion
        */

        public function setDireccion($direccion){
            /*Llamar parametro*/
            $this->direccion = $direccion;
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
        Funcion para realizar el registro de la transaccion en la base de datos
        */

        public function guardar(){
            /*Construir la consulta*/
            $consulta = "INSERT INTO envios VALUES(NULL, {$this -> getActivo()}, {$this -> getIdUsuario()}, '{$this -> getDepartamento()}', '{$this -> getMunicipio()}', '{$this -> getCodigoPostal()}', '{$this -> getBarrio()}', 
                '{$this -> getDireccion()}')";
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
        Funcion para eliminar el envio
        */

        public function eliminar(){
            /*Construir la consulta*/
            $consulta = "UPDATE envios SET activo = '{$this -> getActivo()}' WHERE id = {$this -> getId()}";
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
        Funcion para actualizar el envio
        */
    
        public function actualizar(){
            /*Construir la consulta*/
            $consulta = "UPDATE envios SET departamento = '{$this -> getDepartamento()}', municipio = '{$this -> getMunicipio()}', codigoPostal = '{$this -> getCodigoPostal()}', barrio = '{$this -> getBarrio()}', direccion = '{$this -> getDireccion()}' 
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
        Funcion para obtener un envio
        */

        public function obtenerUno(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM envios WHERE id = {$this -> getId()} AND activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $uso = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $uso -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }

    }

?>