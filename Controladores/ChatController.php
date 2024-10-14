<?php

    /*Incluir el objeto de usuario chat*/
    require_once 'Modelos/UsuarioChat.php';

    /*
    Clase controlador de chat
    */

    class ChatController{

        /*
        Funcion para listar todos los chats del usuario
        */

        public function chats(){
            /*instanciar el objeto*/
            $usuarioChat = new UsuarioChat();
            /*Crear el objeto*/
            $usuarioChat -> setIdRemitente($_SESSION['loginexitoso'] -> id);
            $usuarioChat -> setIdDestinatario($_SESSION['loginexitoso'] -> id);
            /*Traer lista de chats*/
            $listadoChats = $usuarioChat -> obtenerChats();
            /*Retornar lista*/
            return $listadoChats;
        }

        /*
        Funcion para guardar un mensaje en la base de datos
        */

        public function guardarMensajes(){
            /*Instanciar mensaje*/
            $usuarioChat = new UsuarioChat();
            /*Contruir objeto*/
            $usuarioChat -> setIdRemitente($_SESSION['loginexitoso'] -> id);
            $usuarioChat -> setIdDestinatario($_SESSION['mensajechat']);
            $usuarioChat -> setIdChat($this -> obtenerIdChat());
            $usuarioChat -> setMensaje($_POST['mensaje']);
            $usuarioChat -> setFechaHora(date('Y-m-d H:i:s'));
            /*Guardar mensaje en la base de datos*/
            $enviado = $usuarioChat -> guardar();
            /*Retornar el resultado*/
            return $enviado;
        }

        /*
        Funcion para enviar un mensaje
        */

        public function enviarMensaje(){
            /*Obtener chat al cual se enviara el mensaje*/
            $chat = $_SESSION['mensajechat'];
            /*Llamar la funcion que trae la lista de mensajes enviados*/
            $listadoMensajesEnviados = $this -> mensajes();
            /*Llamar la funcion que trae la lista de chats*/
            $listadoChats = $this -> chats();
            /*Comprobar si se ha traido con exito las listas de chats y mensajes enviados*/
            if($listadoMensajesEnviados && $listadoChats){
                /*Guardar el mensaje en la base de datos*/
                $guardado = $this -> guardarMensajes();
                /*Comprobar si el mensaje ha sido guardado*/
                if($guardado){
                    /*Redirigir*/
                    header("Location:"."http://localhost/Mercado-Juegos/?controller=ChatController&action=verMensajes&idContacto=$chat");
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("mensajeenviadoerror", "El mensaje no ha sido enviado con exito", '?controller=ChatController&action=verMensajes&idContacto=$chat');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para cargar la ventana del chat y sus respectivos chats
        */

        public function chatear(){
            /*Traer la lista de chats*/
            $listadoChats = $this -> chats();
            /*Comprobar si la lista de chats ha sido traida con exito*/
            if($listadoChats){
                /*Incluir la vista*/
                require_once "Vistas/Chat/Chat.html";
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para listar todos los mensajes recibidos
        */

        public function mensajes(){
            /*instanciar el objeto*/
            $usuarioChat = new UsuarioChat();
            /*Crear el objeto*/
            $usuarioChat -> setIdRemitente($_SESSION['loginexitoso'] -> id);
            $usuarioChat -> setIdDestinatario($_SESSION['mensajechat']);
            /*Traer lista de mensajes*/
            $listadoMensajes = $usuarioChat -> obtenerMensajes();
            /*Retornar el resultado*/
            return $listadoMensajes;
        }

        /*
        Funcion para ver los mensajes
        */

        public function verMensajes(){
            /*Crear sesion del chat con el que se tienen mensajes*/
            $_SESSION['mensajechat'] = $_GET['idContacto'];
            /*Llamar la funcion que trae la lista de chats*/
            $listadoChats = $this -> chats();
            /*Llamar la funcion que trae la lista de mensajes*/
            $listadoMensajes = $this -> mensajes();
            /*Comprobar si se ha traido con exito las listas de chats y mensajes enviados*/
            if($listadoChats && $listadoMensajes){
                /*Incluir la vista*/
                require_once "Vistas/Chat/Chat.html";
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para obtener el id del chat
        */

        public function obtenerIdChat(){
            /*instanciar el objeto*/
            $usuarioChat = new UsuarioChat();
            /*Crear el objeto*/
            $usuarioChat -> setIdRemitente($_SESSION['loginexitoso'] -> id);
            $usuarioChat -> setIdDestinatario($_SESSION['mensajechat']);
            /*Obtener el resultado*/
            $identificador = $usuarioChat -> obtenerIdentificadorPropioDeChat();
            /*Retornar el resultado*/
            return $identificador;
        }

    }

?>
