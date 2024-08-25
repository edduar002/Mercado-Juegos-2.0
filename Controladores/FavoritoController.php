<?php

    /*Incluir el objeto de videojuego*/
    require_once 'Modelos/Videojuego.php';
    /*Incluir el objeto de favorito*/
    require_once 'Modelos/Favorito.php';
    /*Incluir el objeto de videojuego favorito*/
    require_once 'Modelos/FavoritoVideojuego.php';

    /*
    Clase controlador de favorito
    */

    class FavoritoController{

        /*
        Funcion para ver los favoritos
        */

        public function ver(){
            /*Instanciar el objeto*/
            $favorito = new Favorito();
            /*Construir objeto*/
            $favorito -> setIdUsuario($_SESSION['loginexitoso'] -> id);
            /*Obtener la lista de favoritos*/
            $listadoFavoritos = $favorito -> listar();
            /*Incluir la vista*/
            require_once "Vistas/Favorito/Favoritos.html";
        }

        /*
        Funcion para guardar el favorito en la base de datos
        */

        public function guardarFavorito(){
            /*Instanciar el objeto*/
            $favorito = new Favorito();
            /*Crear el objeto*/
            $favorito -> setIdUsuario($_SESSION['loginexitoso'] -> id);
            /*Guardar en la base de datos*/
            $guardado = $favorito -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para eliminar un videojuego
        */

        public function eliminarVideojuego($idUsuario, $idVideojuego){
            /*Instanciar el objeto*/
            $favoritoVideojuego = new FavoritoVideojuego();
            /*Crear el objeto*/
            $favoritoVideojuego -> setIdVideojuego($idVideojuego);
            $favoritoVideojuego -> setActivo(FALSE);
            /*Ejecutar la consulta*/
            $eliminado = $favoritoVideojuego -> eliminarVideojuego($idUsuario);
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar un usuario desde el administrador
        */

        public function eliminarFavorito(){
            /*Comprobar si los datos estan llegando*/
            if(isset($_GET)){
                /*Comprobar si los datos existen*/
                $idVideojuego = isset($_GET['idVideojuego']) ? $_GET['idVideojuego'] : false;
                $idUsuario = $_SESSION['loginexitoso'] -> id;
                /*Si los datos existen*/
                if($idVideojuego && $idUsuario){
                    /*Llamar la funcion que elimina el videojuego*/
                    $eliminado = $this -> eliminarVideojuego($idUsuario, $idVideojuego);
                    /*Comprobar si el videojuego ha sido eliminado*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarvideojuegoacierto', "El videojuego ha sido eliminado exitosamente", '?controller=FavoritoController&action=ver');
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarvideojuegoerror', "El videojuego no ha sido eliminado exitosamente", '?controller=FavoritoController&action=ver');
                    }
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('eliminarvideojuegoerror', "Ha ocurrido un error al eliminar el videojuego", '?controller=FavoritoController&action=ver');
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para obtener el ultimo favorito guardado
        */

        public function obtenerUltimoFavorito(){
            /*Instanciar el objeto*/
            $favorito = new Favorito();
            /*Obtener id del ultimo videojuego registrado*/
            $idFavorito = $favorito -> ultimo();
            /*Retornar el resultado*/
            return $idFavorito;
        }

        /*
        Funcion para traer un videojuego en concreto
        */

        public function traer($videojuegoId){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Crear el objeto*/
            $videojuego -> setId($videojuegoId);
            /*Obtener id del ultimo videojuego registrado*/
            $videojuegoActual = $videojuego -> traerUno();
            /*Retornar el resultado*/
            return $videojuegoActual;
        }

        /*
        Funcion para guardar el favorito videojuego en la base de datos
        */

        public function guardarFavoritoVideojuego($videojuegoId, $idFavorito){
            /*Llamar la funcion que trae el videojuego*/
            $videojuego = $this -> traer($videojuegoId);
            /*Instanciar el objeto*/
            $favoritoVideojuego = new FavoritoVideojuego();
            /*Crear el objeto*/
            $favoritoVideojuego -> setactivo(TRUE);
            $favoritoVideojuego -> setIdFavorito($idFavorito);
            $favoritoVideojuego -> setIdVideojuego($videojuegoId);
            $favoritoVideojuego -> setPrecio($videojuego['videojuego']['precioVideojuego']);
            /*Guardar en la base de datos*/
            $guardadoVideojuegoFavorito = $favoritoVideojuego -> guardar();
            /*Retornar el resultado*/
            return $guardadoVideojuegoFavorito;
        }

        /*
        Funcion para comprobar si el favorito ya ha sido creado previamente
        */ 

        public function comprobarUnicoFavorito($idVideojuego){ 
            /*Comprobar si el usuario esta logueado*/
            if(isset($_SESSION['loginexitoso'])){
                /*Instanciar el objeto*/ 
                $favorito = new Favorito(); 
                /*Crear el objeto*/ 
                $favorito -> setIdUsuario($_SESSION['loginexitoso'] -> id); 
                /*Ejecutar la consulta*/ 
                $resultado = $favorito -> comprobarFavorito($idVideojuego); 
                /*Retornar el resultado*/ 
                return $resultado; 
            }
        }

        /*
        Funcion para comprobar el inicio de sesion
        */

        public function comprobarLogin($videojuegoId){
            /*Comprobar si la solicitud de videojuego favorito es desde el catalogo de algunos videojuegos y no desde el detalle del videojuego*/
            if(isset($_GET['cat'])){
                /*Crear sesion*/
                $_SESSION['catalogofavorito'] = true;
            /*Comprobar si la solicitud de videojuego favorito es desde el catalogo de todos los videojuegos y no desde el detalle del videojuego*/
            }else if(isset($_GET['catt'])){
                /*Crear sesion*/
                $_SESSION['catalogofavoritot'] = true;
            /*De lo contrario*/    
            }else{
                /*Comprobar si no hay inicio de sesion de usuario*/
                if(!Ayudas::comprobarInicioDeSesionUsuario()){
                    /*Crear sesion*/
                    $_SESSION['idvideojuegopendientefavorito'] = $videojuegoId;
                }
            }
            /*Llamar la funcion de ayuda en caso de que el usuario no este logueado*/
            Ayudas::restringirAUsuarioAlAgregarFavorito('?controller=UsuarioController&action=login', $videojuegoId);
        }

        /*
        Funcion para guardar el favorito en la base de datos
        */

        public function guardar(){
            /*Comprobar si el dato está llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $videojuegoId = isset($_GET['idVideojuego']) ? $_GET['idVideojuego'] : false;
                /*Si el dato existe*/
                if($videojuegoId){
                    /*Llamar la funcion para obtener el inicio de sesion*/
                    $this -> comprobarLogin($videojuegoId);
                    /*Llamar la funcion que comprueba si un videojuego ya ha sido agregado a la lista de favoritos*/
                    $unico = $this -> comprobarUnicoFavorito($videojuegoId);
                    /*Comprobar si el videojuego no ha sido agregado a la lista de favoritos*/
                    if($unico == null){
                        /*Llamar la funcion de guardar favorito*/
                        $guardado = $this -> guardarFavorito();
                        /*Comprobar si se guardo con exito el favorito*/
                        if($guardado){
                            /*Llamar la funcion que obtiene el id del ultimo favorito guardado*/
                            $ultimoFavorito = $this -> obtenerUltimoFavorito();
                            /*Llamar la funcion de guardar favorito videojuego*/
                            $guardadoVideojuegoFavorito = $this -> guardarFavoritoVideojuego($videojuegoId, $ultimoFavorito);
                            /*Comprobar si se guardo con exito el favorito videojuego*/
                            if($guardadoVideojuegoFavorito){
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('guardarfavoritoacierto', "Videojuego agregado a la lista de favoritos con exito", "?controller=FavoritoController&action=ver");
                            /*De lo contrario*/   
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('guardarfavoritoerror', "Videojuego no agregado a la lista de favoritos", "?controller=FavoritoController&action=ver");
                            }
                        /*De lo contrario*/    
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarfavoritoerror', "Ha ocurrido un error al guardar el favorito", "?controller=FavoritoController&action=ver");
                        }
                    /*De lo contrario*/       
                    }else{
                        /*Comprobar si la solicitud de videojuego favorito es desde el catalogo de algunos*/
                        if(isset($_SESSION['catalogofavorito'])){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarfavoritoerror', "Este videojuego ya ha sido agregado a la lista de favoritos", "?controller=VideojuegoController&action=inicio");
                        /*Comprobar si la solicitud de videojuego favorito es desde el catalogo de todos*/
                        }else if(isset($_SESSION['catalogofavoritot'])){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarfavoritoerror', "Este videojuego ya ha sido agregado a la lista de favoritos", "?controller=VideojuegoController&action=todos");
                        /*De lo contario*/
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarfavoritoerror', "Este videojuego ya ha sido agregado a la lista de favoritos", '?controller=VideojuegoController&action=detalle&id='.$videojuegoId);
                        }
                    } 
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('guardarfavoritoerror', "Ha ocurrido un error al guardar el favorito", "?controller=FavoritoController&action=ver");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

    }

?>