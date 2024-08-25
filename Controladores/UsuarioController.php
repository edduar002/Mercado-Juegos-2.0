<?php

    /*Incluir el objeto de administrador*/
    require_once 'Modelos/Administrador.php';
    /*Incluir el objeto de usuario*/
    require_once 'Modelos/Usuario.php';
    /*Incluir el objeto de transaccion*/
    require_once 'Modelos/Transaccion.php';
    /*Incluir el objeto de transaccion videojuego*/
    require_once 'Modelos/TransaccionVideojuego.php';
    /*Incluir el objeto de bloqueo*/
    require_once 'Modelos/Bloqueo.php';

    /*
    Clase controlador de usuario
    */

    class UsuarioController{

        /*
        Funcion para realizar el inicio de sesion del usuario
        */

        public function login(){
            /*Incluir la vista*/
            require_once "Vistas/Usuario/Login.html";
        }

        /*
        Funcion para ver los nuevos usuarios
        */

        public function verNuevosUsuarios(){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Listar todos los usuarios nuevos*/
            $listadoNuevos = $usuario -> usuariosNuevos();
            /*Incluir la vista*/
            require_once "Vistas/Usuario/Nuevos.html";
        }

        /*
        Funcion para ver los usuarios comentadores
        */

        public function verUsuariosComentadores(){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Listar todos los usuarios comentadores*/
            $listadoComentadores = $usuario -> usuariosComentadores();
            /*Incluir la vista*/
            require_once "Vistas/Usuario/Comentadores.html";
        }

        /*
        Funcion para ver los vendedores destacados
        */

        public function verVendedoresDestacados(){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Listar todos los vendedores destacados*/
            $listadoDestacados = $usuario -> vendedoresDestacados();
            /*Incluir la vista*/
            require_once "Vistas/Usuario/VendedoresDestacados.html";
        }

        /*
        Funcion para ver los compradores destacados
        */

        public function verCompradoresDestacados(){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Listar todos los compradores destacados*/
            $listadoDestacados = $usuario -> compradoresDestacados();
            /*Incluir la vista*/
            require_once "Vistas/Usuario/CompradoresDestacados.html";
        }

        /*
        Funcion para cambiar la clave del usuario
        */

        public function cambiarClave(){
            /*Incluir la vista*/
            require_once "Vistas/Usuario/CambiarClave.html";
        }

        /*
        Funcion para realizar el registro del usuario
        */

        public function registro(){
            /*Incluir la vista*/
            require_once "Vistas/Usuario/Registro.html";
        }

        /*
        Funcion para obtener el dueño del correo
        */

        public function obtenerDuenio($email){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setCorreo($email);
            /*Ejecutar la consulta*/
            $usuario = $usuario -> obtenerIdPorCorreo();
            /*Obtener el resultado*/
            $id = $usuario -> id;
            /*Retornar el resultado*/
            return $id;
        }

        /*
        Funcion para guardar el usuario
        */

        public function guardarUsuario($nombre, $apellidos, $fechaNacimiento, $telefono, $email, $clave, $departamento, $municipio, $nombreArchivo){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setactivo(TRUE);
            $usuario -> setNombre($nombre);
            $usuario -> setApellido($apellidos);
            $usuario -> setFechanacimiento($fechaNacimiento);
            $usuario -> setNumerotelefono($telefono);
            $usuario -> setCorreo($email);
            $usuario -> setClave($clave);
            $usuario -> setDepartamento($departamento);
            $usuario -> setMunicipio($municipio);
            $usuario -> setFecharegistro(date('y-m-d'));
            $usuario -> setFoto($nombreArchivo);
            $usuario -> setFechaLimiteRecuperarCuenta(date("Y-m-d", 0));
            /*Ejecutar la consulta*/
            $guardado = $usuario -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para comprobar si el usuario ya ha sido creado previamente
        */ 

        public function comprobarUnicoUsuario($correo){ 
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setCorreo($correo);
            /*Ejecutar la consulta*/
            $resultado = $usuario -> comprobarUsuarioUnico($_SESSION['loginexitoso'] -> correo);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener el correo del usuario
        */

        public function obtenerCorreo($id){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setId($id);
            /*Ejecutar la consulta*/
            $usuarioUnico = $usuario -> obtenerUno();
            /*Obtener el resultado*/
            $resultado = $usuarioUnico -> nombre;
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para guardar el usuario en la base de datos
        */

        public function guardar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_POST)){
                /*Comprobar si cada dato existe*/
                $nombre = isset($_POST['nombreusu']) ? $_POST['nombreusu'] : false;
                $apellidos = isset($_POST['apellidosusu']) ? $_POST['apellidosusu'] : false;
                $fechaNacimiento = isset($_POST['fechaNacimientousu']) ? $_POST['fechaNacimientousu'] : false;
                $telefono = isset($_POST['telefonousu']) ? $_POST['telefonousu'] : false;
                $email = isset($_POST['emailusu']) ? $_POST['emailusu'] : false;
                $clave = isset($_POST['passwordusu']) ? $_POST['passwordusu'] : false;
                $departamento = isset($_POST['departamentousu']) ? $_POST['departamentousu'] : false;
                $municipio = isset($_POST['municipiousu']) ? $_POST['municipiousu'] : false;
                /*Establecer archivo de foto*/
                $archivo = $_FILES['foto'];
                /*Establecer nombre del archivo de la foto*/
                $foto = $archivo['name'];
                /*Comprobar si todos los datos exsiten*/
                if($nombre && $apellidos && $fechaNacimiento && $telefono && $clave && $email && $departamento && $municipio){
                    /*Llamar la funcion que comprueba si la persona es mayor de 15 años*/
                    $mayor15Anios = Ayudas::comprobarMayor15($fechaNacimiento);
                    /*Comprobar si el administrador es mayor de edad*/
                    if($mayor15Anios){
                        /*Llamar funcion que comprueba si el usuario ya ha sido registrado*/
                        $unico = $this -> comprobarUnicoUsuario($email);
                        /*Comprobar si el correo del usuario no se encuentra asociado a otro usuario*/
                        if($unico == null){
                            /*Comprobar si la contraseña es valida*/
                            $claveSegura = Ayudas::comprobarContrasenia($clave);
                            /*Comprobar si la clave es valida*/
                            if($claveSegura){
                                /*Comprobar si la foto es valida y ha sido guardada*/
                                $fotoGuardada = Ayudas::guardarImagen($archivo, "ImagenesUsuarios");
                                /*Comprobar si la foto ha sido validada y guardada*/
                                if($fotoGuardada){
                                    /*Llamar la funcion de guardar usuario*/
                                    $guardado = $this -> guardarUsuario($nombre, $apellidos, $fechaNacimiento, $telefono, $email, $clave, $departamento, $municipio, $foto);
                                    /*Comprobar si el usuario ha sido guardado*/
                                    if($guardado){
                                        /*Llamar la funcion de inicio de sesion del usuario*/
                                    Ayudas::iniciarSesionUsuario($email, $clave);
                                    /*De lo contrario*/  
                                    }else{
                                        /*Crear la sesion y redirigir a la ruta pertinente*/
                                        Ayudas::crearSesionYRedirigir("guardarusuarioerror", "Ha ocurrido un error al guardar el usuario", "?controller=UsuarioController&action=registro");
                                    }
                                /*De lo contrario*/  
                                }else{
                                    /*Crear la sesion y redirigir a la ruta pertinente*/
                                    Ayudas::crearSesionYRedirigir("guardarusuarioerror", "La imagen debe ser de tipo imagen", "?controller=UsuarioController&action=registro");
                                }
                            /*De lo contrario*/      
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir("guardarusuarioerror", "La clave debe contener un mayuscula, miniscula, numero, caracter especial y minimo 8 caracteres de longitud", "?controller=UsuarioController&action=registro");
                            } 
                        /*De lo contrario*/       
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarusuarioerror', "Este correo ya se encuentra asociado a un usuario", '?controller=UsuarioController&action=registro');
                        } 
                    /*De lo contrario*/         
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("guardarusuarioerror", "Debe ser mayor de 15 años para poderse registrar", "?controller=UsuarioController&action=registro");
                    }
                /*De lo contrario*/         
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("guardarusuarioerror", "Ha ocurrido un error al guardar el usuario", "?controller=UsuarioController&action=registro");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("guardarusuarioerror", "Ha ocurrido un error al guardar el usuario", "?controller=UsuarioController&action=registro");
            }
        }

        /*
        Funcion para realizar el inicio de sesion
        */

        public function inicioDeSesion(){
            /*Comprobar si los datos están llegando*/
            if(isset($_POST)){
                /*Comprobar si cada dato existe*/
                $email = isset($_POST['email']) ? $_POST['email'] : false;
                $clave = isset($_POST['password']) ? $_POST['password'] : false;
                /*Si los datos existen*/
                if($email && $clave){
                    /*Iniciar la sesion*/
                    Ayudas::iniciarSesion($email, $clave);
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("iniciarsesionerror", "Ha ocurrido un error al iniciar sesion", "?controller=UsuarioController&action=login");
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para eliminar un usuario de la base de datos
        */

        public function eliminarUsuario($idUsuario){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setId($idUsuario);
            $usuario -> setActivo(FALSE);
            $usuario -> setFechaLimiteRecuperarCuenta(Ayudas::sumarTresMeses());
            /*Ejecutar la consulta*/
            $eliminado = $usuario -> eliminar();
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar la cuenta del usuario de la base de datos
        */

        public function eliminar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idUsuario = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idUsuario){
                    /*Llamar la funcion para eliminar el usuario*/
                    $eliminado = $this -> eliminarUsuario($idUsuario);
                    /*Comprobar si el usuario ha sido eliminado exitosamente*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("eliminarusuarioacierto", "El usuario ha sido eliminado con exito", "?controller=VideojuegoController&action=inicio");
                        /*Eliminar el inicio de sesion*/
                        Ayudas::eliminarSesion('loginexitoso');
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("eliminarusuarioerror", "El usuario no ha sido eliminado con exito", "?controller=UsuarioController&action=perfil");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("eliminarusuarioerror", "El usuario no ha sido eliminado con exito", "?controller=UsuarioController&action=perfil");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para actualizar el usuario
        */

        public function actualizarUsuario($id, $nombre, $apellidos, $telefono, $email, $departamento, $municipio, $foto){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setId($id);
            $usuario -> setNombre($nombre);
            $usuario -> setApellido($apellidos);
            $usuario -> setNumerotelefono($telefono);
            $usuario -> setCorreo($email);
            $usuario -> setDepartamento($departamento);
            $usuario -> setMunicipio($municipio);
            $usuario -> setFoto($foto);
            /*Ejecutar la consulta*/
            $actualizado = $usuario -> actualizar();
            /*Retornar el resultado*/
            return $actualizado;
        }

        /*
        Funcion para actualizar un usuario
        */

        public function actualizar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existe*/
                $id = isset($_GET['id']) ? $_GET['id'] : false;
                $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
                $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : false;
                $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : false;
                $email = isset($_POST['email']) ? $_POST['email'] : false;
                $departamento = isset($_POST['departamento']) ? $_POST['departamento'] : false;
                $municipio = isset($_POST['municipio']) ? $_POST['municipio'] : false;
                /*Establecer archivo de foto*/
                $archivo = $_FILES['foto'];
                /*Establecer nombre del archivo de la foto*/
                $foto = $archivo['name'];
                /*Si los datos existen*/
                if($id && $nombre && $apellidos && $telefono && $email && $departamento && $municipio){
                    /*Llamar funcion que comprueba si el usuario ya ha sido registrado*/
                    $unico = $this -> comprobarUnicoUsuario($email);
                    if(!isset($SESSION['loginexitoso'])){
                        $unico = null;
                    }
                    /*Comprobar si el correo del usuario no existe*/
                    if($unico == null){
                        /*Comprobar si la foto no tiene formato de imagen o no ha llegado*/
                        if(Ayudas::comprobarImagen($archivo['type']) != 3){
                            /*Comprobar si la foto tiene formato de imagen*/
                            if(Ayudas::comprobarImagen($archivo['type']) == 1){
                                /*Comprobar si la foto ha sido validada y guardada*/
                                Ayudas::guardarImagen($archivo, "ImagenesUsuarios");
                            }
                            /*Llamar la funcion que actualiza el usuario*/
                            $actualizado = $this -> actualizarUsuario($id, $nombre, $apellidos, $telefono, $email, $departamento, $municipio, $foto);
                            /*Comprobar si el usuario ha sido actualizado*/
                            if($actualizado){
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('actualizarusuarioacierto', "Usuario actualizado con exito", '?controller=UsuarioController&action=miPerfil');
                            /*De lo contrario*/
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('actualizarusuariosugerencia', "Agrega nuevos datos", '?controller=UsuarioController&action=miPerfil');
                            }
                        /*De lo contrario*/    
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('actualizarusuarioerror', "El formato de la foto debe ser una imagen", '?controller=UsuarioController&action=miPerfil');
                        }
                    /*De lo contrario*/       
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarusuarioerror', "Este correo ya se encuentra asociado a un usuario", '?controller=UsuarioController&action=miPerfil');
                    } 
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('actualizarusuarioerror', "Ha ocurrido un error al actualizar el usuario", '?controller=UsuarioController&action=miPerfil');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para buscar un usuario
        */

        public function buscarUsuario($nombre){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setNombre($nombre);
            /*Obtener usuarios de la base de datos*/
            $listadoEstados = $usuario -> buscarUsuario();
            /*Retornar el resultado*/
            return $listadoEstados;
        }

        /*
        Funcion para buscar un usuario en concreto
        */

        public function buscar(){
            /*Comprobar si el dato está llegando*/
            if(isset($_POST)){
                /*Comprobar si el dato existe*/
                $nombre = isset($_POST['usuariob']) ? $_POST['usuariob'] : false;
                /*Si el dato existe*/
                if($nombre){
                    /*Llamar la funcion que busca un usuario*/
                    $listadoUsuarios = $this -> buscarUsuario($nombre);
                    /*Comprobar si hay usuarios encontrados*/
                    if(mysqli_num_rows($listadoUsuarios) > 0){
                        /*Incluir la vista*/
                        require_once 'Vistas/Usuario/Buscar.html';
                    /*De lo contrario*/    
                    }else{
                        /*Incluir la vista*/
                        require_once 'Vistas/Usuario/NoEncontrado.html';
                    }
                /*De lo contrario*/       
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=AdministradorController&action=gestionarUsuario");
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=AdministradorController&action=gestionarUsuario");
            }
        }

        /*
        Funcion para cerrar la sesión
        */

        public function cerrarSesion(){
            /*Llamar funciones para eliminar las sesiones*/
            Ayudas::eliminarSesion('loginexitosoa');
            Ayudas::eliminarSesion('loginexitoso');
            /*Crear sesion de sesion creada con exito*/
            $_SESSION['logincerrado'] = "Sesion cerrada con exito";
            /*Redirigir al menu principal*/
            header("Location:"."http://localhost/Mercado-Juegos/?controller=VideojuegoController&action=inicio");
        }

        /*
        Funcion para ver los datos del perfil de usuario
        */

        public function perfilDeUsuario($idVendedor){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setId($idVendedor);
            /*Obtener las listas de datos de usuario*/
            $listaDatosUsuario = $usuario -> obtenerInformacionUsuario();
            /*Retornar el resultado*/
            return $listaDatosUsuario;
        }

        /*
        Funcion para ver el total de videojuegos vendidos
        */

        public function totalVendidos($idVendedor){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setId($idVendedor);
            /*Obtener las listas de videojuegos vendidos*/
            $vendidos = $usuario -> obtenerTotalVendidos();
            /*Retornar el resultado*/
            return $vendidos;
        }

        /*
        Funcion para ver el total de videojuegos publicados
        */

        public function totalPublicados($idVendedor){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setId($idVendedor);
            /*Obtener las listas de videojuegos publicados*/
            $publicados = $usuario -> obtenerTotalPublicados();
            /*Retornar el resultado*/
            return $publicados;
        }

        /*
        Funcion para ver el perfil de un usuario
        */

        public function perfil(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idVendedor = isset($_GET['idVendedor']) ? $_GET['idVendedor'] : false;
                /*Si el dato existe*/
                if($idVendedor){
                    /*Llamar la funciones que trae datos del perfil de usuario, total juegos vendidos y total juegos publicados*/
                    $listaDatosUsuario = $this -> perfilDeUsuario($idVendedor);
                    $publicados = $this -> totalPublicados($idVendedor);                  
                    $vendidos = $this -> totalVendidos($idVendedor);
                    /*Incluir la vista*/
                    require_once "Vistas/Usuario/Perfil.html";
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
        Funcion para obtener el perfil del usuario
        */

        public function obtenerPerfil($id){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setId($id);
            /*Obtener el resultado*/
            $usuarioUnico = $usuario -> obtenerUno();
            /*Retornar el resultado*/
            return $usuarioUnico;
        }

        /*
        Funcion para ver el perfil del usuario indentificado
        */

        public function miPerfil(){
            /*Llamar la funcion auxiliar para redirigir en caso de que no haya inicio de sesion*/
            Ayudas::restringirAUsuario('?controller=UsuarioController&action=login');
            /*Comprobar si el dato está llegando*/
            if(isset($_GET)){
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                }else{
                    /*Comprobar si la sesion de inicio de sesion existe*/
                    $id = isset($_SESSION['loginexitoso']) ? $_SESSION['loginexitoso'] -> id : false;
                }
                /*Si la sesion de inicio de sesion existe*/
                if($id){
                    /*Llamar funcion para obtener el perfil del usuario*/
                    $usuarioUnico = $this -> obtenerPerfil($id);
                    /*Incluir la vista*/
                    require_once "Vistas/Usuario/miPerfil.html";
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("cargarperfilusuarioerror", "Ha ocurrido un error al cargar el perfil del usuario", "?controller=VideojuegoController&action=inicio");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para ver el listado de compras realizadas por el usuario
        */

        public function compras(){
            /*Instanciar el objeto*/
            $transaccion = new Transaccion();
            /*Construir el objeto*/
            $transaccion -> setIdComprador($_SESSION['loginexitoso'] -> id);
            /*Listar todas las compras desde la base de datos*/
            $listadoCompras = $transaccion -> obtenerCompras();
            /*Incluir la vista*/
            require_once "Vistas/Usuario/Compras.html";
        }

        /*
        Funcion para ver el listado de ventas realizadas por el usuario
        */

        public function ventas(){
            /*Instanciar el objeto*/
            $transaccionVideojuego = new TransaccionVideojuego();
            /*Construir el objeto*/
            $transaccionVideojuego -> setIdVendedor($_SESSION['loginexitoso'] -> id);
            /*Listar todas las ventas desde la base de datos*/
            $listadoVentas = $transaccionVideojuego -> obtenerVentas();
            /*Incluir la vista*/
            require_once "Vistas/Usuario/Ventas.html";
        }

        /*
        Funcion para ver el listado de bloqueos realizados por el usuario
        */

        public function bloqueos(){
            /*Instanciar el objeto*/
            $bloqueo = new Bloqueo();
            /*Construir el objeto*/
            $bloqueo -> setIdBloqueador($_SESSION['loginexitoso'] -> id);
            /*Listar todos las bloqueos desde la base de datos*/
            $listadoBloqueos = $bloqueo -> obtenerBloqueosPorUsuario();
            /*Incluir la vista*/
            require_once "Vistas/Usuario/Bloqueos.html";
        }

        /*
        Funcion para ver los videojuegos creados por el usuario indentificado
        */

        public function videojuegos(){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Construir el objeto*/
            $usuario -> setId($_SESSION['loginexitoso'] -> id);
            /*Listar todos las bloqueos videojuegos la base de datos*/
            $listaVideojuegos = $usuario -> obtenerVideojuegosCreadosPorUsuario();
            /*Incluir la vista*/
            require_once "Vistas/Usuario/Videojuegos.html";
        }

        /*
        Funcion para comprobar si la clave actual ingresada es correcta
        */

        public function comprobarClaves($actual){
            /*instanciar el objeto*/
            $usuario = new Usuario();
            /*Capturar el correo del usuario ingresado*/
            $correo = $_SESSION['loginexitoso'] -> correo;
            /*Obtener clave actual del usuario logueado*/
            $claveUsuario = $usuario -> traerClave($correo);
            /*Verificar clave actual y nueva*/
            $claveVerificada = password_verify($actual, $claveUsuario -> clave);
            /*Comprobar si la clave actual y nueva coinciden*/
            if($claveVerificada){
                /*Retornar el resultado*/
                return true;
            }
        }

        /*
        Funcion para actualizar la clave
        */

        public function actualizarNuevaClave($clave){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setId($_SESSION['loginexitoso'] -> id);
            $usuario -> setClave($clave);
            /*Ejecutar la consulta*/
            $actualizado = $usuario -> actualizarClave();
            /*Retornar el resultado*/
            return $actualizado;
        }

        /*
        Funcion para actualizar la clave del usuario
        */

        public function actualizarClave(){
            /*Comprobar si los datos están llegando*/
            if(isset($_POST)){
                /*Comprobar si los datos existen*/
                $actual = isset($_POST['passwordactual']) ? $_POST['passwordactual'] : false;
                $nueva = isset($_POST['passwordnueva']) ? $_POST['passwordnueva'] : false;
                /*Si los datos existen*/
                if($actual && $nueva){
                    /*Llamar la funcion para comprobar si la clave nueva es valida y segura*/
                    $segura = Ayudas::comprobarContrasenia($nueva);
                    /*Comprobar si la clave es valida y segura*/
                    if($segura){
                        /*Llamar la funcion para comprobar si las clave actual coincide*/
                        if($this -> comprobarClaves($actual)){
                            /*Llamar la funcion para actualizar la clave*/
                            $actualizada = $this -> actualizarNuevaClave($nueva);
                            /*Comprobar si la clave ha sido actualizada con exito*/
                            if($actualizada){
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('actualizarclaveacierto', "La clave ha sido actualizada con exito", '?controller=UsuarioController&action=cambiarClave');
                            /*De lo contrario*/  
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('actualizarclaveerror', "La clave no ha sido actualizada con exito", '?controller=UsuarioController&action=cambiarClave');
                            }
                        /*De lo contrario*/      
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('actualizarclaveerror', "Clave actual incorrecta", '?controller=UsuarioController&action=cambiarClave');
                        }
                    /*De lo contrario*/      
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarclaveerror', "Clave poco segura", '?controller=UsuarioController&action=cambiarClave');
                    }
                /*De lo contrario*/      
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('actualizarclaveerror', "Ha ocurrido un error al actualizar la clave", '?controller=UsuarioController&action=cambiarClave');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para ver el listado de envios del usuario
        */

        public function envios(){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Construir el objeto*/
            $usuario -> setId($_SESSION['loginexitoso'] -> id);
            /*Listar todos los envios desde la base de datos*/
            $listadoEnvios = $usuario -> obtenerEnvios();
            /*Incluir la vista*/
            require_once "Vistas/Usuario/Envios.html";
        }

    }

?>