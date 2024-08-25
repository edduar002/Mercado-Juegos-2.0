<?php

    /*Incluir el objeto de videojuego comentario usuario videojuego*/
    require_once 'Modelos/ComentarioUsuarioVideojuego.php';

    /*
    Clase controlador de videojuego comentario
    */

    class ComentarioController{

        /*
        Funcion para guardar el comentario en la base de datos
        */

        public function guardarComentario($contenido, $videojuego){
            /*Instanciar el objeto*/
            $comentarioUsuarioVideojuego = new ComentarioUsuarioVideojuego();
            /*Crear el objeto*/
            $comentarioUsuarioVideojuego -> setactivo(TRUE);
            $comentarioUsuarioVideojuego -> setIdUsuario($_SESSION['loginexitoso'] -> id);
            $comentarioUsuarioVideojuego -> setContenido($contenido);
            $comentarioUsuarioVideojuego -> setIdVideojuego($videojuego);
            $comentarioUsuarioVideojuego -> setFechaHora(date('Y-m-d H:i:s'));
            /*Guardar en la base de datos*/
            $guardado = $comentarioUsuarioVideojuego -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para guardar un comentario en la base de datos
        */

        public function guardar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_POST) && (isset($_GET))){
                /*Comprobar si los datos existen*/
                $contenido = isset($_POST['comentario']) ? $_POST['comentario'] : false;
                $videojuego = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si los datos existen*/
                if($contenido && $videojuego){
                    /*Llamar la funcion de restringir al usuario en caso de que no este logueado y quiera comentar*/
                    Ayudas::restringirAUsuarioAlComentar('?controller=UsuarioController&action=login', $videojuego);
                    /*Llamar la funcion que guarda el comentario*/
                    $guardado = $this -> guardarComentario($contenido, $videojuego);
                    /*Comprobar si el comentario ha sido guardado*/
                    if($guardado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('guardarcomentarioacierto', "El comentario se ha hecho con exito", "?controller=VideojuegoController&action=detalle&id=$videojuego");
                    /*De lo contrario*/       
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('guardarcomentarioerror', "El comentario no se ha hecho con exito", "?controller=VideojuegoController&action=detalle&id=$videojuego");
                    }
                /*De lo contrario*/   
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('guardarcomentarioerror', "El comentario no se ha hecho con exito", "?controller=VideojuegoController&action=detalle&id=$videojuego");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para eliminar un comentario
        */

        public function eliminarComentario($idComentario){
            /*Instanciar el objeto*/
            $comentarioUsuarioVideojuego = new ComentarioUsuarioVideojuego();
            /*Crear el objeto*/
            $comentarioUsuarioVideojuego -> setId($idComentario);
            $comentarioUsuarioVideojuego -> setActivo(FALSE);
            /*Ejecutar la consulta*/
            $eliminado = $comentarioUsuarioVideojuego -> eliminar();
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar el comentario
        */

        public function eliminar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_GET)){
                /*Comprobar si los datos existen*/
                $idComentario = isset($_GET['idComentario']) ? $_GET['idComentario'] : false;
                $idVideojuego = isset($_GET['idVideojuego']) ? $_GET['idVideojuego'] : false;
                /*Si los datos existen*/
                if($idComentario && $idVideojuego){
                    /*Llamar la funcion para eliminar el comentario*/
                    $eliminado = $this -> eliminarComentario($idComentario);
                    /*Comprobar si el comentario ha sido eliminado*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarcomentarioacierto', "El comentario ha sido eliminado exitosamente", '?controller=VideojuegoController&action=detalle&id='.$idVideojuego);
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarcomentarioerror', "El comentario no ha sido eliminado exitosamente", '?controller=VideojuegoController&action=detalle&id='.$idVideojuego);
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('eliminarcomentarioerror', "Ha ocurrido un error al eliminar el comentario", '?controller=VideojuegoController&action=detalle&id='.$idVideojuego);
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

    }

?>