<?php

    /*
    Clase modelo de usuario chat
    */

    class UsuarioChat{

        private $id;
        private $idRemitente;
        private $idDestinatario;
        private $idChat;
        private $mensaje;
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
        Funcion getter de id remitente
        */

        public function getIdRemitente(){
            /*Retornar el resultado*/
            return $this->idRemitente;
        }

        /*
        Funcion setter de id remitente
        */

        public function setIdRemitente($idRemitente){
            /*Llamar parametro*/
            $this->idRemitente = $idRemitente;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de id destinatario
        */

        public function getIdDestinatario(){
            /*Retornar el resultado*/
            return $this->idDestinatario;
        }

        /*
        Funcion setter de id destinatario
        */

        public function setIdDestinatario($idDestinatario){
            /*Llamar parametro*/
            $this->idDestinatario = $idDestinatario;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de id chat
        */

        public function getIdChat(){
            /*Retornar el resultado*/
            return $this->idChat;
        }

        /*
        Funcion setter de id chat
        */

        public function setIdChat($idChat){
            /*Llamar parametro*/
            $this->idChat = $idChat;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de mensaje
        */

        public function getMensaje(){
            /*Retornar el resultado*/
            return $this->mensaje;
        }

        /*
        Funcion setter de mensaje
        */

        public function setMensaje($mensaje){
            /*Llamar parametro*/
            $this->mensaje = $mensaje;
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
        Funcion para guardar el usuario chat en la base de datos
        */

        public function guardar(){
            /*Construir la consulta*/
            $consulta = "INSERT INTO usuariochat VALUES (NULL, ";
            /*Comprobar si se quiere guardar un mensaje*/ 
            if($this -> getMensaje() == null){
                /*Construir la consulta*/
                $consulta .= "NULL, ";
            /*De lo contrario*/    
            }else{
                /*Llamar la funcion para encriptar el contenido del mensaje*/
                $mensajeEncriptado = Ayudas::encriptarContenido($this -> getMensaje());
                /*Construir la consulta*/
                $consulta .= "'{$mensajeEncriptado}', ";
            } 
            /*Construir la consulta*/
            $consulta .= "{$this -> getIdRemitente()}, 
                {$this -> getIdDestinatario()}, {$this -> getIdChat()}, '{$this -> getFechaHora()}')";
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
        Funcion para obtener todos los mensajes
        */

        public function obtenerMensajes(){
            /*Construir la consulta*/
            $consulta = "(SELECT DISTINCT * FROM usuariochat WHERE idDestinatario = {$this -> getIdDestinatario()} AND idRemitente = {$this -> getIdRemitente()} UNION
                SELECT DISTINCT * FROM usuariochat WHERE idDestinatario = {$this -> getIdRemitente()} AND idRemitente = {$this -> getIdDestinatario()}) ORDER BY id ASC";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener los chats propios de cada usuario
        */

        public function obtenerChats(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT id AS 'idUsuarioChat', nombre AS 'nombreChat', foto AS 'fotoChat', apellido AS 'apellidoChat' 
                FROM usuarios WHERE id IN 
                (SELECT idDestinatario FROM usuarioChat WHERE idRemitente = {$this->getIdRemitente()} 
                UNION 
                SELECT idRemitente FROM usuarioChat WHERE idDestinatario = {$this->getIdDestinatario()})";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener el identificador del chat
        */

        public function obtenerIdentificadorPropioDeChat(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT idChat from usuariochat WHERE idRemitente = {$this -> getIdRemitente()} AND idDestinatario = {$this -> getIdDestinatario()} OR
                idRemitente = {$this -> getIdDestinatario()} AND idDestinatario = {$this -> getIdRemitente()}"; 
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Obtener el resultado*/            
            $idenficador = $resultado -> fetch_object();
            /*Devolver resultado*/
            $ultimoChat = $idenficador -> idChat;
            /*Retornar el resultado*/
            return $ultimoChat;
        }
        
    }

?>