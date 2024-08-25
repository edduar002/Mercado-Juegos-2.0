<?php

    /*Incluir el objeto de bloqueo*/
    require_once "Modelos/Bloqueo.php";

    /*
    Clase controlador de bloqueo
    */

    class BloqueoController{

        /*
        Funcion para ver los usuarios bloqueados
        */

        public function bloqueos(){
            /*Instanciar el objeto*/
            $bloqueo = new Bloqueo();
            /*Construir el objeto*/
            $bloqueo -> setIdBloqueador($_SESSION['loginexitoso'] -> id);
            /*Listar todos los usuarios desde la base de datos*/
            $listadoBloqueos = $bloqueo -> obtenerBloqueosPorUsuario();
            /*Incluir la vista*/
            require_once "Vistas/Usuario/Bloqueos.html";
        }

        /*
        Funcion para bloquear a un usuario
        */

        public function bloquear(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Obtener dato*/
                $idUsuarioABloquear = $_GET['aBloquear'];
                /*Comprobar si el dato ha sido obtenido*/
                if($idUsuarioABloquear){
                    /*Obtener el usuario bloqueado*/
                    $usuario = Ayudas::obtenerUsuarioEnConcreto($idUsuarioABloquear);
                    /*Incluir la vista*/
                    require_once 'Vistas/Bloqueo/Bloqueo.html';
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para eliminar el bloqueo del usuario
        */

        public function desbloquearUsuario($idUsuarioADesbloquear){
            /*Instanciar el objeto*/
            $bloqueo = new Bloqueo();
            /*Construir el objeto*/
            $bloqueo -> setActivo(FALSE);
            $bloqueo -> setIdBloqueado($idUsuarioADesbloquear);
            $bloqueo -> setIdBloqueador($_SESSION['loginexitoso'] -> id);
            /*Eliminar el bloqueo del usuario*/
            $desbloqueo = $bloqueo -> eliminar();
            /*Retornar el resultado*/
            return $desbloqueo;
        }

        /*
        Funcion para desbloquear a un usuario
        */

        public function desbloquear(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Obtener dato*/
                $idUsuarioADesbloquear = $_GET['usuarioBloqueo'];
                /*Comprobar si ha sido obtenido el dato*/
                if($idUsuarioADesbloquear){
                    /*Llamar la funcion que desbloquea el usuario*/
                    $desbloqueo = $this -> desbloquearUsuario($idUsuarioADesbloquear);
                    /*Comprobar si el desbloqueo ha sido realizado con extio*/
                    if($desbloqueo){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("guardardesbloqueoacierto", "El desbloqueo ha sido guardado con exito", "?controller=UsuarioController&action=bloqueos");
                    /*De lo contrario*/   
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("guardardesbloqueoerror", "El desbloqueo no ha sido guardado con exito", "?controller=UsuarioController&action=bloqueos");
                    }   
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("guardardesbloqueoerror", "El desbloqueo no ha sido guardado con exito", "?controller=UsuarioController&action=bloqueos");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para guardar el bloqueo en la base de datos
        */

        public function guardarBloqueo($motivo, $idUsuarioABloquear){
            /*Instanciar el objeto*/
            $bloqueo = new Bloqueo();
            /*Crear el objeto*/
            $bloqueo -> setactivo(TRUE);
            $bloqueo -> setIdBloqueador($_SESSION['loginexitoso'] -> id);
            $bloqueo -> setIdBloqueado($idUsuarioABloquear);
            $bloqueo -> setMotivo($motivo);
            $bloqueo -> setFechaHora(date('Y-m-d H:i:s'));
            /*Guardar en la base de datos*/
            $guardado = $bloqueo -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para guardar el bloqueo
        */

        public function guardar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_POST) && isset($_GET)){
                /*Obtener cada dato*/
                $idUsuarioABloquear = $_GET['idBloqueado'];
                $motivo = isset($_POST['motivo']) ? $_POST['motivo'] : false;
                /*Comprobar si cada dato ha sido obtenido*/
                if($idUsuarioABloquear && $motivo){
                    /*Llamar funcion que guarda el bloqueo*/
                    $bloqueoGuardado = $this -> guardarBloqueo($motivo, $idUsuarioABloquear);
                    /*Comprobar si el bloqueo se guardo con exito*/
                    if($bloqueoGuardado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("guardarbloqueoacierto", "El bloqueo ha sido guardado con exito", "?controller=UsuarioController&action=bloqueos");
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("guardarbloqueoerror", "Ha ocurrido un error al guardar el bloqueo", "?controller=UsuarioController&action=perfil&idVendedor=$idUsuarioABloquear");
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("guardarbloqueoerror", "Ha ocurrido un error al guardar el bloqueo", "?controller=UsuarioController&action=perfil&idVendedor=$idUsuarioABloquear");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

    }

?>