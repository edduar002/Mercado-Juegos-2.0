<?php

    /*Iniciar el buffer de salida*/
    ob_start();
    /*Incluir archivo de ayuda para generar el PDF*/
    require_once 'Ayudas/Ayudas.php';
    /*Incluir el objeto de transaccion de videojuego*/
    require_once 'Modelos/TransaccionVideojuego.php';
    /*Incluir el objeto de transaccion*/
    require_once 'Modelos/Transaccion.php';
    /*Incluir el objeto de estado*/
    require_once 'Modelos/Estado.php';
    /*Incluir el objeto de envio*/
    require_once 'Modelos/Envio.php';
    /*Incluir el objeto de chat*/
    require_once 'Modelos/Chat.php';
    /*Incluir el objeto de usuario chat*/
    require_once 'Modelos/UsuarioChat.php';
    /*Incluir el objeto de videojuego*/
    require_once 'Modelos/Videojuego.php';
    /*Incluir el objeto de usuario*/
    require_once 'Modelos/Usuario.php';
    /*Incluir el objeto de carrito*/
    require_once 'Modelos/Carrito.php';

    /*
    Clase controlador de transaccion
    */

    class TransaccionController{

        /*
        Funcion para editar el estado de la transaccion
        */

        public function editarEstado($id, $estado){
            /*Instanciar el objeto*/
            $transaccionVideojuego = new TransaccionVideojuego();
            /*Crear el objeto*/
            $transaccionVideojuego -> setIdTransaccion($id);
            $transaccionVideojuego -> setIdEstado($estado);
            $transaccionVideojuego -> setIdVendedor($_SESSION['loginexitoso'] -> id);
            /*Ejecutar la consulta*/
            $actualizado = $transaccionVideojuego -> cambiarEstado();
            /*Retornar el resultado*/
            return $actualizado;
        }

        /*
        Funcion para cambiar el estado de la transaccion
        */

        public function cambiarEstado(){
            /*Comprobar si los datos están llegando*/
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $id = isset($_GET['id']) ? $_GET['id'] : false;
                $idEstado = isset($_POST['estado']) ? $_POST['estado'] : false;
                /*Si los datos existen*/
                if($id && $idEstado){
                    /*Llamar la funcion de editar estado*/
                    $actualizado = $this -> editarEstado($id, $idEstado);
                    /*Establecer valor de la factura*/
                    $factura = $id + 999;
                    /*Comprobar si el estado ha sido actualizado*/
                    if($actualizado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarestadoacierto', "Estado actualizado con exito", '?controller=TransaccionController&action=verVenta&factura='.$factura);
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarestadosugerencia', "Agrega un nuevo estado", '?controller=TransaccionController&action=verVenta&factura='.$factura);
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('actualizarestadoerror', "Ha ocurrido un error al actualizar el estado de la transaccion", '?controller=UsuarioController&action=ventas');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para realizar la transaccion, ya se compra o agregar al carrito
        */

        public function transaccionVideojuego(){
            /*Comprobar si los datos estan llegando*/
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $id = isset($_GET['idVideojuego']) ? $_GET['idVideojuego'] : false;
                $unidades = isset($_POST['cantidadAComprar']) ? $_POST['cantidadAComprar'] : false;
                $carrito = isset($_GET['carrito']) ? $_GET['carrito'] : false;
                $accion = isset($_POST['accion']) ? $_POST['accion'] : false;
                /*Si los datos existen*/
                if($id && $unidades && $accion){
                    /*Llamar la funcion que redirige a la seccion de compra*/
                    $this -> redirigirSeccionCompra($id, $unidades, $accion);
                /*Si la redireccion a carrito es verdadera*/    
                }elseif($carrito == 'true'){
                    /*Llamar la funcion que redirige a la seccion de carrito*/
                    $this -> redirigirSeccionCarrito();
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para realizar la redireccion a la seccion de compra
        */

        public function redirigirSeccionCompra($id, $unidades, $accion){
            /*Comprobar si la accion es comprar el videojuego*/
            if($accion == "Comprar Ahora"){
                /*Llamar la funcion que envia a la seccion de direccion*/
                $this -> direccion($id, $unidades, 2);
            /*Comprobar si la accion es agregar al carrito el videojuego*/    
            }elseif($accion == "Agregar al carrito"){
                /*Redirigir*/
                header("Location:"."http://localhost/Mercado-Juegos/?controller=CarritoController&action=guardar&idVideojuego=$id&unidades=$unidades");
            }
        }

        /*
        Funcion para listar los videojuegos del carrito
        */

        public function listarCarritos(){
            /*Instanciar el objeto*/
            $carrito = new Carrito();
            /*Crear el objeto*/
            $carrito -> setIdUsuario($_SESSION['loginexitoso'] -> id);
            /*Obtener la lista de videojuegos del carrito*/
            $lista = $carrito -> listar();
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para realizar la redireccion a la seccion de carrito
        */

        public function redirigirSeccionCarrito(){
            /*Llamar la funcion que trae la lista de videojuegos del carrito*/
            $lista = $this -> listarCarritos();
            /*Obtener lista de videojuegos del carrito*/
            $videojuego = $lista['carrito']['videojuegos'];
            /*Recorrer la lista de videojuegos*/
            for($i = 0; $i < count($videojuego); $i++){
                /*Obtener datos del videojuego*/
                $idVideojuego = $videojuego[$i]['idVideojuegoCarrito'];
                $unidadesComprar = $videojuego[$i]['unidadesCarrito'];
                /*Llamar la funcion que envia a la seccion de direccion y pago*/
                $this -> direccion($idVideojuego, $unidadesComprar, 1);
            }
        }

        /*
        Funcion para listar los envios del usuario
        */

        public function listarEnvios(){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setId($_SESSION['loginexitoso'] -> id);
            /*Obtener la lista de videojuegos del carrito*/
            $listadoEnvios = $usuario -> obtenerEnvios();
            /*Retornar el resultado*/
            return $listadoEnvios;
        }

        /*
        Funcion para ver el formulario de direccion y compra al comprar un videojuego
        */

        public function direccion($idVideojuego, $unidadComprar, $opcionCarrito){
            /*Llamar funciones que traen lista de carritos, envios y pagos*/
            $listadoCarritos = $this -> listarCarritos();
            $listadoEnvios = $this -> listarEnvios();
            /*Incluir la vista*/
            require_once "Vistas/Transaccion/Envio.html";
        }

        /*
        Funcion para ver los videojuegos comprados en un rango de tiempo detarminado
        */

        public function verPorRangoFechas(){
            /*Incluir la vista*/
            require_once "Vistas/Videojuego/Fecha.html";
        }

        /*
        Funcion para ver videojuegos comprados por un rango especifico
        */

        public function verPorRango($fechaInicial, $fechaFinal){
            /*Instanciar el objeto*/
            $transaccion = new Transaccion();
            /*Obtener la lista de videojuegos*/
            $listadoVideojuegos = $transaccion -> videojuegosPorFecha($fechaInicial, $fechaFinal);
            /*Retornar el resultado*/
            return $listadoVideojuegos;
        }

        /*
        Funcion para ver los videojuegos comprados en un rango de tiempo
        */

        public function verPorFechas(){
            /*Comprobar si los datos están llegando*/
            if(isset($_POST)){
                /*Comprobar si los datos existen*/
                $fechaInicial = isset($_POST['fechaInicial']) ? $_POST['fechaInicial'] : false;
                $fechaFinal = isset($_POST['fechaFinal']) ? $_POST['fechaFinal'] : false;
                /*Si los datos existen*/
                if($fechaInicial && $fechaFinal){
                    /*Comprobar si las fechas son coherentes*/
                    if($fechaInicial <= $fechaFinal){
                        /*Llamar la funcion que trae la lista de videojuegos*/
                        $listadoVideojuegos = $this -> verPorRango($fechaInicial, $fechaFinal);
                        /*Comprobar si la lista ha sido obtenida con exito*/
                        if($listadoVideojuegos){
                            /*Incluir la vista*/
                            require_once "Vistas/Videojuego/PorFechas.html";
                        /*De lo contrario*/      
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
                        }
                    /*De lo contrario*/      
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("errorfechas", "Las fechas no son coherentes", "?controller=TransaccionController&action=verPorRangoFechas");
                    }
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
        Funcion para obtener la factura
        */

        public function obtenerFactura(){
            /*Instanciar el objeto*/
            $transaccion = new Transaccion();
            /*Traer el ultimo id de transaccion*/
            $ultimoId = $transaccion -> traerUltimoIdTransaccion();
            /*Retornar el resultado*/
            return $ultimoId;
        }

        /*
        Funcion para obtener la ultima transaccion guardada
        */

        public function obtenerUltimaTransaccion(){
            /*Instanciar el objeto*/
            $transaccion = new Transaccion();
            /*Obtener id del ultimo videojuego registrado*/
            $id = $transaccion -> traerUltimoIdTransaccion();
            /*Retornar el resultado*/
            return $id;
        }

        /*
        Funcion para traer el dueño del videojuego
        */

        public function traerDuenioDeVideojuego($idVideojuego){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Crear el objeto*/
            $videojuego -> setId($idVideojuego);
            /*Obtener el usuario*/
            $idUsuario = $videojuego -> obtenerUsuarioVideojuego();
            /*Obtener el id del usuario*/
            $id = $idUsuario -> idUsuario;
            /*Retornar el resultado*/
            return $id;
        }

        /*
        Funcion para guardar la transaccion en la base de datos
        */

        public function guardarTransaccion($idVideojuego, $unidadesCompra, $opcion, $factura, $idEnvio){
            /*Comprobar si la transaccion es del carrito*/
            if($opcion == 1){
                /*Llamar la funcion para guardar la transaccion del carrito*/
                $transaccion = $this -> guardarTransaccionCarrito($factura, $idEnvio);
            /*Comprobar si la transaccion es del videojuego unicamente*/    
            }elseif($opcion == 2){
                /*Llamar la funcion para guardar la transaccion del videojuego*/
                $transaccion = $this -> guardarTransaccionVideojuegoUnico($factura, $idVideojuego, $unidadesCompra, $idEnvio);
            }
            /*Retornar el resultado*/
            return $transaccion;
        }

        /*
        Funcion para guardar la transaccion del videojuego
        */

        public function guardarTransaccionCarrito($factura, $idEnvio){
            /*Llamar la funcion que trae la lista de los videojuegos del carrito*/
            $lista = $this -> listarCarritos();
            /*instanciar el objeto*/
            $transaccion = new Transaccion();
            /*Crear el objeto*/
            $transaccion -> setNumeroFactura($factura + 1000);
            $transaccion -> setIdComprador($_SESSION['loginexitoso'] -> id);
            /*Obtener total de la transaccion*/
            $total = $lista['totalCarrito']['totalCarrito'];
            /*Crear el objeto*/
            $transaccion -> setTotal($total);
            $transaccion -> setIdEnvio($idEnvio);
            $transaccion -> setFechaHora(date('Y-m-d H:i:s'));
            /*Guardar la transaccion en la base de datos*/
            $guardadoTransaccion = $transaccion -> guardar();
            /*Retornar el resultado*/
            return $guardadoTransaccion;
        }

        /*
        Funcion para guardar la transaccion del carrito
        */

        public function guardarTransaccionVideojuegoUnico($factura, $idVideojuego, $unidadesCompra, $idEnvio){
            /*instanciar el objeto*/
            $transaccion = new Transaccion();
            /*Crear el objeto*/
            $transaccion -> setNumeroFactura($factura + 1000);
            $transaccion -> setIdComprador($_SESSION['loginexitoso'] -> id);
            /*Llamar la funcion que obtiene un videojuego en concreto*/
            $videojuegoUnico = Ayudas::obtenerVideojuegoEnConcreto($idVideojuego);
            /*Obtener el precio del videojuego*/
            $precio = $videojuegoUnico['videojuego']['precioVideojuego'];
            /*Obtener el total de la transaccion*/
            $total = $unidadesCompra * $precio;
            /*Crear el objeto*/
            $transaccion -> setTotal($total);
            $transaccion -> setIdEnvio($idEnvio);
            $transaccion -> setFechaHora(date('Y-m-d H:i:s'));
            /*Guardar la transaccion en la base de datos*/
            $guardadoTransaccion = $transaccion -> guardar();
            /*Retornar el resultado*/
            return $guardadoTransaccion;
        }

        /*
        Funcion para guardar la transaccion videojuego en la base de datos
        */

        public function guardarTransaccionVideojuegoOpcionUnico($id, $idVideojuego, $unidades){
            /*Instanciar el objeto*/
            $transaccionVideojuego = new TransaccionVideojuego();
            /*Crear el objeto*/
            $transaccionVideojuego -> setIdTransaccion($id);
            $transaccionVideojuego -> setIdVideojuego($idVideojuego);
            /*Llamar la funcion que trae el dueño del videojuego*/
            $vendedor = $this -> traerDuenioDeVideojuego($idVideojuego);
            /*Crear el objeto*/
            $transaccionVideojuego -> setIdVendedor($vendedor);
            $transaccionVideojuego -> setIdEstado(1);
            $transaccionVideojuego -> setUnidades($unidades);
            /*Guardar en la base de datos*/
            $guardadoTransaccionVideojuego = $transaccionVideojuego -> guardar();
            /*Retornar el resultado*/
            return $guardadoTransaccionVideojuego;
        }

        /*
        Funcion para guardar la transaccion videojuego en la base de datos
        */

        public function guardarTransaccionVideojuegoOpcionCarrito($id, $idVideojuego, $idVendedor, $unidades){
            /*Instanciar el objeto*/
            $transaccionVideojuego = new TransaccionVideojuego();
            /*Crear el objeto*/
            $transaccionVideojuego -> setIdTransaccion($id);
            $transaccionVideojuego -> setIdVideojuego($idVideojuego);
            $transaccionVideojuego -> setIdVendedor($idVendedor);
            $transaccionVideojuego -> setIdEstado(1);
            $transaccionVideojuego -> setUnidades($unidades);
            /*Guardar en la base de datos*/
            $guardadoTransaccionVideojuego = $transaccionVideojuego -> guardar();
            /*Retornar el resultado*/
            return $guardadoTransaccionVideojuego;
        }

        /*
        Funcion para traer un envio en concreto
        */

        public function traerEnvio($id){
            /*Instanciar el objeto*/
            $envio = new Envio();
            /*Crear el objeto*/
            $envio -> setId($id);
            /*Obtener el resultado*/
            $envioUnico = $envio -> obtenerUno();
            /*Retornar el resultado*/
            return $envioUnico;
        }

        /*
        Funcion para guardar el envio en la base de datos
        */

        public function guardarEnvio($envioUnico){
            /*Instanciar el objeto*/
            $envio = new Envio();
            /*Crear el objeto*/
            $envio -> setactivo(TRUE);
            $envio -> setIdUsuario($_SESSION['loginexitoso'] -> id);
            $envio -> setDepartamento($envioUnico -> departamento);
            $envio -> setMunicipio($envioUnico -> municipio);
            $envio -> setCodigoPostal($envioUnico -> codigoPostal);
            $envio -> setBarrio($envioUnico -> barrio);
            $envio -> setDireccion($envioUnico -> direccion);
            /*Guardar en la base de datos*/
            $guardadoEnvio = $envio -> guardar();
            /*Retornar el resultado*/
            return $guardadoEnvio;
        }

        /*
        Funcion para guardar el chat en la base de datos
        */

        public function guardarChat(){
            /*Instanciar el objeto*/
            $chat = new Chat;
            /*Crear el objeto*/
            $chat -> setFechaCreacion(date('Y-m-d'));
            /*Guardar en la base de datos*/
            $guardado = $chat -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para obtener el ultimo chat guardado
        */

        public function obtenerUltimoChat(){
            /*Instanciar el objeto*/
            $chat = new Chat();
            /*Obtener id del ultimo videojuego registrado*/
            $id = $chat -> ultimo();
            /*Retornar EL resultado*/
            return $id;
        }

        /*
        Funcion para guardar el usuario chat en la base de datos
        */

        public function guardarUsuarioChat($destinatario){
            /*Instanciar el objeto*/
            $usuarioChat = new UsuarioChat;
            /*Crear el objeto*/
            $usuarioChat -> setIdRemitente($_SESSION['loginexitoso'] -> id);
            $usuarioChat -> setIdDestinatario($destinatario);
            $usuarioChat -> setIdChat($this -> obtenerUltimoChat());
            $usuarioChat -> setFechaHora(date('Y-m-d H:i:s'));
            /*Guardar en la base de datos*/
            $guardado = $usuarioChat -> guardar();
            /*Retonar el resultado*/
            return $guardado;
        }

        /*
        Funcion para obtener los vendedores de los videojuegos
        */

        public function obtenerVendedores($idVideojuego){
            /*Crear el arreglo*/
            $listaVendedores = array();
            /*Recorrer la lista de videojuegos del carrito*/
            foreach($idVideojuego as $videojuego){
                /*Llamar la funcion que trae el dueño del videojuego*/
                $vendedor = $this -> traerDuenioDeVideojuego($videojuego);
                /*Llenar el arreglo de vendedores*/
                array_push($listaVendedores, $vendedor);
            }
            /*Retornar el resultado*/
            return $listaVendedores;
        }

        /*
        Funcion para guardar la transaccion en la base de datos
        */

        public function guardar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_POST) && isset($_GET)){
                /*Comprobar si cada dato existe*/
                $idVideojuego = isset($_POST['idVideojuego']) ? $_POST['idVideojuego'] : false;
                $unidades = isset($_POST['unidad']) ? $_POST['unidad'] : false;
                $envio = isset($_POST['idEnvio']) ? $_POST['idEnvio'] : false;
                $opcionCarrito = isset($_GET['opcionCompra']) ? $_GET['opcionCompra'] : false;
                /*Si los datos existen*/
                if($envio && $idVideojuego && $unidades && $opcionCarrito){
                    /*Llamar funcion que trae la ultima factura*/
                    $factura = $this -> obtenerFactura();
                    /*Comprobar si la opcion de la transaccion es del carrito*/
                    if($opcionCarrito == 1){
                        /*Llamar la funcion que obtiene los vendedores de los videojuegos*/
                        $idVendedor = $this -> obtenerVendedores($idVideojuego);
                        /*Llamar la funcion que realiza la transaccion del carrito*/
                        $this -> realizarTransaccionCarrito($idVideojuego, $idVendedor, $unidades, $opcionCarrito, $factura, $envio);
                    /*Comprobar si la opcion de la transaccion es del videojuego*/    
                    }elseif($opcionCarrito == 2){
                        /*Llamar la funcion que realiza la transaccion del videojuego*/
                        $this -> realizarTransaccionVideojuego($idVideojuego, $unidades, $opcionCarrito, $factura, $envio);
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("comprarvideojuegoerror", "Ha ocurrido un error al comprar el videojuego", "?controller=TransaccionController&action=direccionYPago&idVideojuego=$idVideojuego");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("comprarvideojuegoerror", "Ha ocurrido un error al comprar el videojuego", "?controller=VideojuegoController&action=inicio");
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
        Funcion para realizar la transaccion del carrito
        */

        public function realizarTransaccionCarrito($idVideojuego, $idVendedor, $unidades, $opcionCarrito, $factura, $envio){
            /*Obtener el id del usuario logueado*/
            $idUsuario = $_SESSION['loginexitoso'] -> id;
            /*Llamar la funcion que lista los videojuegos del carrito*/
            $lista = $this -> listarCarritos();
            /*Obtener la cantidad de videojuegos que hay en el carrito*/
            $listado = count($lista['carrito']['videojuegos']);
            /*Llamar la funcion que guarda la transaccion*/
            $guardadoTransaccion = $this -> guardarTransaccion($idVideojuego, $unidades, $opcionCarrito, $factura, $envio);
            /*Comprobar si la transaccion que guardo con exito*/
            if($guardadoTransaccion){
                /*Llamar la funcio para obtener id de la ultima transaccion*/
                $idTransaccion = $this -> obtenerUltimaTransaccion();
                /*Recorrer la lista de videojuegos del carrito*/
                for($i = 0; $i < $listado; $i++){
                    /*Llamar la funcion que obtiene la transaccion del videojuego*/
                    $guardadoTransaccionVideojuego = $this -> guardarTransaccionVideojuegoOpcionCarrito($idTransaccion, $idVideojuego[$i], $idVendedor[$i], $unidades[$i]);
                    /*Comprobar si la transaccion videojueo se guardo con exito*/
                    if($guardadoTransaccionVideojuego){
                        /*Llamar la funcion que actualiza el stock del videojuego*/
                        $this -> actualizarStock($idVideojuego[$i], $unidades[$i]);
                        /*Llamar funcion que elimina el carrito*/
                        $this -> eliminarCarritoCompleto($idUsuario);
                        /*Llamar la funcion para guardar el chat*/
                        $guardadoChat = $this -> guardarChat();
                        /*Comprobar si el chat ha sido guardado con exito*/
                        if($guardadoChat){
                            /*Llamar la funcion para guardar el usuario del chat*/
                            $guardadoUsuarioChat = $this -> guardarUsuarioChat($this -> traerDuenioDeVideojuego($idVideojuego[$i]));
                        /*De lo contrario*/  
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
                        }
                    /*De lo contrario*/      
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("comprarvideojuegoerror", "Ha ocurrido un error al comprar el videojuego", "?controller=TransaccionController&action=direccionYPago&idVideojuego=$idVideojuego");
                    }
                }
                /*Comprobar si el usuario chat ha sido guardado con exito*/
                if($guardadoUsuarioChat){
                    /*Redirigir al menu de direccion y pago*/
                    header("Location:"."http://localhost/Mercado-Juegos/?controller=TransaccionController&action=exito"); 
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("comprarvideojuegoerror", "Ha ocurrido un error al comprar el videojuego", "?controller=TransaccionController&action=direccionYPago&idVideojuego=$idVideojuego");
            }
        }

        /*
        Funcion para realizar la transaccion del videojuego
        */

        public function realizarTransaccionVideojuego($idVideojuego, $unidades, $opcionCarrito, $factura, $envio){
            /*Llamar la funcion que guarda la transaccion*/
            $guardadoTransaccion = $this -> guardarTransaccion($idVideojuego, $unidades, $opcionCarrito, $factura, $envio);
            /*Comprobar si la transaccion que guardo con exito*/
            if($guardadoTransaccion){
                /*Llamar la funcio para obtener id de la ultima transaccion*/
                $idTransaccion = $this -> obtenerUltimaTransaccion();
                /*Llamar la funcion que obtiene la transaccion del videojuego*/
                $guardadoTransaccionVideojuego = $this -> guardarTransaccionVideojuegoOpcionUnico($idTransaccion, $idVideojuego, $unidades);
                /*Comprobar si la transaccion videojueo se guardo con exito*/
                if($guardadoTransaccionVideojuego){
                    /*Llamar la funcion para acutalizar el stock*/
                    $this -> actualizarStock($idVideojuego, $unidades);
                    /*Llamar la funcion para guardar el chat*/
                    $guardadoChat = $this -> guardarChat();
                    /*Comprobar si el chat ha sido guardado con exito*/
                    if($guardadoChat){
                        /*Llamar la funcion para guardar el usuario del chat*/
                        $guardadoUsuarioChat = $this -> guardarUsuarioChat($this -> traerDuenioDeVideojuego($idVideojuego));
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
                    }
                /*De lo contrario*/      
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("comprarvideojuegoerror", "Ha ocurrido un error al comprar el videojuego", "?controller=TransaccionController&action=direccionYPago&idVideojuego=$idVideojuego");
                }
            }
            /*Comprobar si el usuario chat ha sido guardado con exito*/
            if($guardadoUsuarioChat){
                /*Redirigir al menu de direccion y pago*/
                header("Location:"."http://localhost/Mercado-Juegos/?controller=TransaccionController&action=exito"); 
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("comprarvideojuegoerror", "Ha ocurrido un error al comprar el videojuego", "?controller=TransaccionController&action=direccionYPago&idVideojuego=$idVideojuego");
            }
        }

        /*
        Funcion para ver el mensaje de exito al comprar un videojuego de manera correcta
        */

        public function exito(){
            /*Incluir la vista*/
            require_once "Vistas/Transaccion/Exito.html";
        }

        /*
        Funcion para traer el detalle de la compra
        */

        public function traerDetalleCompra($factura){
            /*Instanciar el objeto*/
            $transaccion = new Transaccion();
            /*Crear el objeto*/
            $transaccion -> setNumeroFactura($factura);
            /*Obtener detalle de la compra*/
            $detalle = $transaccion -> detalleCompra();
            /*Retornar el resultado*/
            return $detalle;
        }

        /*
        Funcion para adjuntar un comprobante de pago
        */

        public function adjuntarComprobante($factura, $comprobante){
            /*Instanciar el objeto*/
            $transaccion = new Transaccion();
            /*Crear el objeto*/
            $transaccion -> setNumeroFactura($factura);
            $transaccion -> setComprobante($comprobante);
            /*Ejecutar la consulta*/
            $comprobado = $transaccion -> adjuntarComprobante();
            /*Retornar el resultado*/
            return $comprobado;
        }

        /*
        Funcion para ver el detalle de la compra realizada
        */

        public function subirComprobante(){
            /*Comprobar si el dato está llegando*/
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $factura = isset($_GET['factura']) ? $_GET['factura'] : false;
                $comprobante = isset($_POST['comprobante']) ? $_POST['comprobante'] : false;
                /*Establecer archivo de foto*/
                $archivo = $_FILES['comprobante'];
                /*Establecer nombre del archivo de la foto*/
                $foto = $archivo['name'];
                /*Si los datos existen*/
                if($factura && $archivo){
                    /*Comprobar si la foto no tiene formato de imagen o no ha llegado*/
                    if(Ayudas::comprobarImagen($archivo['type']) != 3){
                        /*Comprobar si la foto tiene formato de imagen*/
                        if(Ayudas::comprobarImagen($archivo['type']) == 1){
                            /*Comprobar si la foto ha sido validada y guardada*/
                            Ayudas::guardarImagen($archivo, "ImagenesComprobantes");
                        }
                        /*Llamar la funcion que actualiza el usuario*/
                        $adjuntado = $this -> adjuntarComprobante($factura, $foto);
                        /*Comprobar si el usuario ha sido actualizado*/
                        if($adjuntado){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('comprobanteacierto', "Comprobante cargado con exito", '?controller=UsuarioController&action=compras');
                        /*De lo contrario*/
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('comprobanteerror', "No se ha podido cargar el comprobante con exito", '?controller=UsuarioController&action=compras');
                        }
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('comprobanteerror', "El archivo no corresponde a una imagen", '?controller=UsuarioController&action=compras');
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("comprobanteerror", "Ha ocurrido un error inesperado", "?controller=UsuarioController&action=compras");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("comprobanteerror", "Ha ocurrido un error inesperado", "?controller=UsuarioController&action=compras");
            }
        }

        /*
        Funcion para ver el detalle de la compra realizada
        */

        public function verCompra(){
            /*Comprobar si el dato está llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $factura = isset($_GET['factura']) ? $_GET['factura'] : false;
                /*Si el dato existe*/
                if($factura){
                    /*Llamar la funcion que obtiene el detlle de la compra*/
                    $detalleCompra = $this -> traerDetalleCompra($factura);
                    /*Comprobar si el detalle ha llegado*/
                    if($detalleCompra){
                        /*Incluir la vista*/
                        require_once "Vistas/Compra/Factura.html";
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("verCompraError", "Ha ocurrido un error al ver el detalle de la compra", "?controller=UsuarioController&action=compras");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("verCompraError", "Ha ocurrido un error al ver el detalle de la compra", "?controller=VideojuegoController&action=inicio");
            }
            /*Retornar el resultado*/
            return $detalleCompra;
        }

        /*
        Funcion para generar reporte de factura en formato PDF
        */

        public function generarPdf(){
            /*Llamar la funcion para obtener la compra*/
            $detalleCompra = $this -> verCompra();
            /*Llamar la funcion de ayuda que genera el archivo PDF*/
            Ayudas::pdf($detalleCompra);
        }

        /*
        Funcion para traer la lista de los estados
        */

        public function traerEstados(){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Listar todos los estados*/
            $lista = $estado -> listar();
            /*Retornar el restultado*/
            return $lista;
        }

        /*
        Funcion para traer el detalle de la venta
        */

        public function traerDetalleVenta($factura){
            /*Instanciar el objeto*/
            $transaccion = new Transaccion();
            /*Crear el objeto*/
            $transaccion -> setNumeroFactura($factura);
            /*Obtener detalle de la compra*/
            $detalle = $transaccion -> detalleVenta($_SESSION['loginexitoso'] -> id);
            /*Retornar el resultado*/
            return $detalle;
        }

        /*
        Funcion para ver el detalle de la venta realizada
        */

        public function verVenta(){
            /*Comprobar si el dato está llegando*/
            if(isset($_GET['factura'])){
                /*Comprobar si el dato existe*/
                $factura = isset($_GET['factura']) ? $_GET['factura'] : false;
                /*Si el dato existe*/
                if($factura){
                    /*Llamar la funcion que trae el detalle de la venta*/
                    $detalle = $this -> traerDetalleVenta($factura);
                    /*Si se ha traido el detalle de la venta*/
                    if($detalle){
                        /*Llamar funcion que trae los estados*/
                        $listadoEstados = $this -> traerEstados();
                        /*Establecer bandera del precio de la venta*/
                        $totalVenta = 0;
                        /*Incluir la vista*/
                        require_once "Vistas/Venta/Detalle.html";
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("verVentaError", "Ha ocurrido un error al ver el detalle de la venta", "?controller=UsuarioController&action=ventas");
                    }
                /*De lo contrario*/      
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("verVentaError", "Ha ocurrido un error al ver el detalle de la venta", "?controller=VideojuegoController&action=inicio");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("verVentaError", "Ha ocurrido un error al ver el detalle de la venta", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion par actualizar el stock del videojuego al realizar una transaccion
        */

        public function actualizarStock($id, $unidadesCompradas){
            /*Llamar la funcion que obtiene el videojuego en concreto*/
            $videojuegoUnico = Ayudas::obtenerVideojuegoEnConcreto($id);
            /*Obtener stock del videojuego*/
            $stockActual = $videojuegoUnico['videojuego']['stockVideojuego'];
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Crear el objeto*/
            $videojuego -> setId($id);
            $videojuego -> setStock($stockActual - $unidadesCompradas);
            /*Actualizar stock*/
            $stock = $videojuego -> actualizarStock();
            /*Comprobar si el stock no ha sido actualizado con exito*/
            if(!$stock){
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

    }

?>