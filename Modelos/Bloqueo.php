<?php

    /*
    Clase modelo de administrador
    */

    class Bloqueo{

        private $id;
        private $activo;
        private $idBloqueador;
        private $idBloqueado;
        private $motivo;
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
        Funcion getter de motivo
        */

        public function getMotivo(){
            /*Retornar el resultado*/
            return $this->motivo;
        }

        /*
        Funcion setter de motivo
        */

        public function setMotivo($motivo){
            /*Llamar parametro*/
            $this->motivo = $motivo;
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
        Funcion getter de id bloqueador
        */

        public function getIdBloqueador(){
            /*Retornar el resultado*/
            return $this->idBloqueador;
        }

        /*
        Funcion setter de id bloqueador
        */

        public function setIdBloqueador($idBloqueador){
            /*Llamar parametro*/
            $this->idBloqueador = $idBloqueador;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de id bloqueado
        */

        public function getIdBloqueado(){
            /*Retornar el resultado*/
            return $this->idBloqueado;
        }

        /*
        Funcion setter de id bloqueado
        */

        public function setIdBloqueado($idBloqueado){
            /*Llamar parametro*/
            $this->idBloqueado = $idBloqueado;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para guardar el bloqueo
        */

        public function guardar(){
            /*Construir la consulta*/
            $consulta = "INSERT INTO bloqueos VALUES(NULL, {$this -> getActivo()}, {$this -> getIdBloqueador()}, 
                {$this -> getIdBloqueado()}, '{$this -> getMotivo()}', '{$this -> getFechaHora()}')";
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
        Funcion para obtener la lista de bloqueos por parte del usuario
        */

        public function obtenerBloqueosPorUsuario(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT u.id AS 'idUsuarioBloqueado', u.nombre AS 'nombreBloqueado', u.apellido AS 'apellidoBloqueado', b.motivo AS 'motivoBloqueo', b.fechaHora AS 'fechaBloqueo'
                FROM Bloqueos b
                INNER JOIN Usuarios u ON u.id = b.idBloqueado
                WHERE b.idBloqueador = {$this -> getIdBloqueador()} AND b.activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para eliminar un bloqueo
        */

        public function eliminar(){
            /*Construir la consulta*/
            $consulta = "UPDATE bloqueos SET activo = '{$this -> getActivo()}' WHERE idBloqueador = {$this -> getIdBloqueador()} AND idBloqueado = {$this -> getIdBloqueado()}";
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
        Funcion para obtener el ultimo bloqueo registrado
        */
    
        public function ultimo(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT id FROM bloqueos ORDER BY id DESC LIMIT 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $ultimo = $resultado -> fetch_object();
            /*Devolver el resultado*/
            $ultimoBloqueo = $ultimo -> id;
            /*Retornar el resultado*/
            return $ultimoBloqueo;
        }

        /*
        Funcion para obtener todos los bloqueos registrados
        */
    
        public function obtenerListaBloqueos(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT ubo.nombre AS 'nombreBloqueado', ubo.apellido AS 'apellidosBloqueado', ubr.nombre AS 'nombreBloqueador', ubr.apellido AS 'apellidosBloqueador', b.motivo AS 'motivoBloqueo', b.fechaHora AS 'fechaBloqueo'
                FROM Bloqueos b
                INNER JOIN Usuarios ubr ON ubr.id = b.idBloqueador
                INNER JOIN Usuarios ubo ON ubo.id = b.idBloqueado
                WHERE b.activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }
        
    }

?>