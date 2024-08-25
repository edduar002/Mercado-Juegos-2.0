<?php

    /*Incluir el objeto de carrito*/
    require_once 'Modelos/Carrito.php';
    /*Incluir el objeto de videojuego*/
    require_once 'Modelos/Videojuego.php';
    /*Incluir el objeto de carrito videojuego*/
    require_once 'Modelos/CarritoVideojuego.php';

    /*
    Clase controlador de carrito
    */

    class CarritoController{

        /*
        Funcion para ver el carrito con los videojuegos
        */

        public function ver(){
            /*Instanciar el objeto*/
            $carrito = new Carrito();
            /*Construir el objeto*/
            $carrito -> setIdUsuario($_SESSION['loginexitoso'] -> id);
            /*Obtener la lista de videojuegos del carrito*/
            $listadoCarritos = $carrito -> listar();
            /*Incluir la vista*/
            require_once "Vistas/Carrito/Carrito.html";
        }

        /*
        Funcion para eliminar un videojuego
        */

        public function eliminarVideojuego($idUsuario, $idVideojuego){
            /*Instanciar el objeto*/
            $carritoVideojuego = new CarritoVideojuego();
            /*Crear el objeto*/
            $carritoVideojuego -> setIdVideojuego($idVideojuego);
            $carritoVideojuego -> setActivo(FALSE);
            /*Ejecutar la consulta*/
            $eliminado = $carritoVideojuego -> eliminarVideojuego($idUsuario);
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para comprobar si el carrito ya ha sido creado previamente
        */ 

        public function comprobarUnicoCarrito($idVideojuego){ 
            /*Comprobar si el usuario esta logueado*/
            if(isset($_SESSION['loginexitoso'])){
                /*Instanciar el objeto*/ 
                $carrito = new Carrito(); 
                /*Crear el objeto*/ 
                $carrito -> setIdUsuario($_SESSION['loginexitoso'] -> id); 
                /*Ejecutar la consulta*/ 
                $resultado = $carrito -> comprobarCarrito($idVideojuego); 
                /*Retornar el resultado*/ 
                return $resultado; 
            }
        }

        /*
        Funcion para eliminar un videojuego del carrito
        */

        public function eliminarVideojuegoCarrito(){
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
                        Ayudas::crearSesionYRedirigir('eliminarvideojuegoacierto', "El videojuego ha sido eliminado exitosamente", '?controller=CarritoController&action=ver');
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarvideojuegoerror', "El videojuego no ha sido eliminado exitosamente", '?controller=CarritoController&action=ver');
                    }
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('eliminarvideojuegoerror', "Ha ocurrido un error al eliminar el videojuego", '?controller=CarritoController&action=ver');
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para eliminar el carrito
        */

        public function eliminarCarritoCompleto($idUsuario){
            /*Instanciar el objeto*/
            $carrito = new Carrito();
            /*Crear el objeto*/
            $carrito -> setIdUsuario($idUsuario);
            $carrito -> setActivo(FALSE);
            /*Ejecutar la consulta*/
            $eliminado = $carrito -> eliminarCarrito();
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar el carrito completo
        */

        public function eliminarCarrito(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idUsuario = $_SESSION['loginexitoso'] -> id;
                /*Si el dato existe*/
                if($idUsuario){
                    /*Llamar la funcion que elimina el videojuego*/
                    $eliminado = $this -> eliminarCarritoCompleto($idUsuario);
                    /*Comprobar si el videojuego ha sido eliminado*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarcarritoacierto', "El carrito ha sido eliminado exitosamente", '?controller=VideojuegoController&action=inicio');
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarcarritoerror', "El carrito no ha sido eliminado exitosamente", '?controller=CarritoController&action=ver');
                    }
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('eliminarcarritoerror', "Ha ocurrido un error al eliminar el carrito", '?controller=CarritoController&action=ver');
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para disminuir unidades de carrito
        */

        public function disminuirUnidadesCarrito($idUsuario, $idVideojuego){
            /*Instanciar el objeto*/
            $carritoVideojuego = new CarritoVideojuego();
            /*Crear el objeto*/
            $carritoVideojuego -> setIdVideojuego($idVideojuego);
            /*Ejecutar la consulta*/
            $disminuido = $carritoVideojuego -> disminuirUnidades($idUsuario);
            /*Retornar el resultado*/
            return $disminuido;
        }

        /*
        Funcion para disminuir unidades del carrito de compras
        */

        public function disminuirUnidades(){
            /*Comprobar si los datos estan llegando*/
            if(isset($_GET)){
                /*Comprobar si los datos existen*/
                $idVideojuego = isset($_GET['idVideojuego']) ? $_GET['idVideojuego'] : false;
                $idUsuario = $_SESSION['loginexitoso'] -> id;
                /*Si los datos existen*/
                if($idVideojuego && $idUsuario){
                    /*Llamar la funcion que disminuye las unidades del carrito*/
                    $disminuido = $this -> disminuirUnidadesCarrito($idUsuario, $idVideojuego);
                    /*Comprobar si las unidades han sido disminuidas*/
                    if($disminuido){
                        /*Redirigir*/
                        header("Location:"."http://localhost/Mercado-Juegos/?controller=CarritoController&action=ver");
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('disminuircantidaderror', "Las cantidades no han sido disminuidas con exito", '?controller=CarritoController&action=ver');
                    }
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('disminuircantidaderror', "Ha ocurrido un error al disminuir las cantidades", '?controller=CarritoController&action=ver');
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para aumentar unidades de carrito
        */

        public function aumentarUnidadesCarrito($idUsuario, $idVideojuego){
            /*Instanciar el objeto*/
            $carritoVideojuego = new CarritoVideojuego();
            /*Crear el objeto*/
            $carritoVideojuego -> setIdVideojuego($idVideojuego);
            /*Ejecutar la consulta*/
            $aumentado = $carritoVideojuego -> aumentarUnidades($idUsuario);
            /*Retornar el resultado*/
            return $aumentado;
        }

        /*
        Funcion para aumentar unidades del carrito de compras
        */

        public function aumentarUnidades(){
            /*Comprobar si los datos estan llegando*/
            if(isset($_GET)){
                /*Comprobar si los datos existen*/
                $idVideojuego = isset($_GET['idVideojuego']) ? $_GET['idVideojuego'] : false;
                $idUsuario = $_SESSION['loginexitoso'] -> id;
                /*Si los datos existen*/
                if($idVideojuego && $idUsuario){
                    /*Llamar la funcion que aumenta las unidades del carrito*/
                    $aumentado = $this -> aumentarUnidadesCarrito($idUsuario, $idVideojuego);
                    /*Comprobar si las unidades han sido aumentadas*/
                    if($aumentado){
                        /*Redirigir*/
                        header("Location:"."http://localhost/Mercado-Juegos/?controller=CarritoController&action=ver");
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('aumentarcantidaderror', "Las cantidades no han sido aumentadas con exito", '?controller=CarritoController&action=ver');
                    }
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('aumentarcantidaderror', "Ha ocurrido un error al aumentar las cantidades", '?controller=CarritoController&action=ver');
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para guardar el carrito en la base de datos
        */

        public function guardarCarrito(){
            /*Instanciar el objeto*/
            $carrito = new Carrito();
            /*Crear el objeto*/
            $carrito -> setactivo(TRUE);
            $carrito -> setIdUsuario($_SESSION['loginexitoso'] -> id);
            /*Guardar en la base de datos*/
            $guardado = $carrito -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para obtener el ultimo videojuego guardado en el carrito
        */

        public function obtenerUltimoCarrito(){
            /*Instanciar el objeto*/
            $carrito = new Carrito();
            /*Obtener id del ultimo videojuego registrado*/
            $idCarrito = $carrito -> ultimo();
            /*Retornar el resultado*/
            return $idCarrito;
        }

        /*
        Funcion para traer un videojuego en concreto
        */

        public function traer($videojuegoId){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Crear el objeto*/
            $videojuego -> setId($videojuegoId);
            /*Traer el videojuego*/
            $videojuegoActual = $videojuego -> traerUno();
            /*Retornar el resultado*/
            return $videojuegoActual;
        }

        /*
        Funcion para comprobar el inicio de sesion
        */

        public function comprobarLogin($videojuegoId){
            /*Comprobar si el usuario no esta logueado*/
            if(!Ayudas::comprobarInicioDeSesionUsuario()){
                $_SESSION['idvideojuegopendientecarrito'] = $videojuegoId;
            }
            /*Llamar la funcion de ayuda en caso de que el usuario no este logueado*/
            Ayudas::restringirAUsuarioAlAgregarCarrito('?controller=UsuarioController&action=login', $videojuegoId);
        }

        /*
        Funcion para guardar el carrito videojuego en la base de datos
        */

        public function guardarCarritoVideojuego($videojuegoId, $idCarrito, $unidades){
            /*Llamar la funcion que trae un videojuego en concreto*/
            $videojuego = $this -> traer($videojuegoId);
            /*Instanciar el objeto*/
            $carritoVideojuego = new CarritoVideojuego();
            /*Crear el objeto*/
            $carritoVideojuego -> setactivo(TRUE);
            $carritoVideojuego -> setIdCarrito($idCarrito);
            $carritoVideojuego -> setIdVideojuego($videojuegoId);
            $carritoVideojuego -> setUnidades($unidades);
            $carritoVideojuego -> setPrecio($videojuego['videojuego']['precioVideojuego']);
            /*Guardar en la base de datos*/
            $guardadoVideojuegoCarrito = $carritoVideojuego -> guardar();
            /*Retornar el resultado*/
            return $guardadoVideojuegoCarrito;
        }

        /*
        Funcion para guardar el carrito en la base de datos
        */

        public function guardar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_GET)){
                /*Comprobar si cada dato existe*/
                $videojuegoId = isset($_GET['idVideojuego']) ? $_GET['idVideojuego'] : false;
                $unidades = isset($_GET['unidades']) ? $_GET['unidades'] : false;
                /*Si los datos existen*/
                if($videojuegoId && $unidades){
                    /*Llamar la funcion que comprueba si un videojuego ya ha sido agregado al carrito*/
                    $unico = $this -> comprobarUnicoCarrito($videojuegoId);
                    /*Comprobar si el videojuego no ha sido agregado al carrito*/
                    if($unico == null){
                        /*Llamar la funcion para obtener el inicio de sesion*/
                        $this -> comprobarLogin($videojuegoId);
                        /*Llamar la funcion que guarda el carrito*/
                        $guardado = $this -> guardarCarrito();
                        /*Comprobar si se guardo con exito el carrito*/
                        if($guardado){
                            /*Llamar la funcion que obtiene el id del ultimo carrito guardado*/
                            $ultimoCarrito = $this -> obtenerUltimoCarrito();
                            /*Llamar la funcion de guardar carrito videojuego*/
                            $guardadoVideojuegoCarrito = $this -> guardarCarritoVideojuego($videojuegoId, $ultimoCarrito, $unidades);
                            /*Comprobar si se guardo con exito el carrito videojuego*/
                            if($guardadoVideojuegoCarrito){
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('guardarcarritoacierto', "Videojuego agregado a la lista de carritos con exito", "?controller=CarritoController&action=ver");
                            /*De lo contrario*/ 
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('guardarcarritoerror', "Videojuego no agregado a la lista de carritos", "?controller=CarritoController&action=ver");
                            }
                        /*De lo contrario*/ 
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarcarritoerror', "Videojuego no agregado a la lista de carritos", "?controller=CarritoController&action=ver");
                        }
                    /*De lo contrario*/       
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('guardarcarritoerror', "Este videojuego ya ha sido agregado al carrito", '?controller=VideojuegoController&action=detalle&id='.$videojuegoId);
                    } 
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('guardarcarritoerror', "Ha ocurrido un error al guardar el carrito", "?controller=CarritoController&action=ver");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir('guardarcarritoerror', "Ha ocurrido un error al guardar el carrito", "?controller=CarritoController&action=ver");
            }
        }

    }

?>