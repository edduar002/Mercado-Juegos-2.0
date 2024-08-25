<?php

    /*Incluir el objeto de uso*/
    require_once 'Modelos/Uso.php';
    /*Incluir el objeto de consola*/
    require_once 'Modelos/Consola.php';
    /*Incluir el objeto de estado*/
    require_once 'Modelos/Estado.php';
    /*Incluir el objeto de categoria*/
    require_once 'Modelos/Categoria.php';
    /*Incluir el objeto de administrador*/
    require_once 'Modelos/Administrador.php';
    /*Incluir el objeto de usuario*/
    require_once 'Modelos/Usuario.php';
    /*Incluir el objeto de bloqueo*/
    require_once 'Modelos/Bloqueo.php';

    /*
    Clase controlador de administrador
    */

    class AdministradorController{

        /*
        Funcion para realizar el registro del administrador
        */

        public function registro(){
            /*Incluir la vista*/
            require_once "Vistas/Administrador/Registro.html";
        }

        /*
        Funcion para ver los destacados
        */

        public function verDestacados(){
            /*Incluir la vista*/
            require_once "Vistas/Administrador/Destacados.html";
        }

        /*
        Funcion para cambiar la clave del administrador
        */

        public function cambiarClave(){
            /*Incluir la vista*/
            require_once "Vistas/Administrador/CambiarClave.html";
        }

        /*
        Funcion para entrar a las funciones de administrador
        */

        public function administrar(){
            /*Incluir la vista*/
            require_once "Vistas/Administrador/Inicio.html";
        }

        /*
        Funcion para obtener el perfil del administrador
        */

        public function perfilDeAdministrador($id){
            /*Instanciar el objeto*/
            $administrador = new Administrador();
            /*Crear el objeto*/
            $administrador -> setId($id);
            /*Obtener el resultado*/
            $adminUnico = $administrador -> obtenerUno();
            /*Retornar el resultado*/
            return $adminUnico;
        }

        /*
        Funcion para ver el perfil del administrador indentificado
        */

        public function miPerfil(){
            /*Llamar la funcion auxiliar para redirigir en caso de que no haya inicio de sesion*/
            Ayudas::restringirAAdministrador();
            /*Comprobar si el dato está llegando*/
            if(isset($_GET)){
                /*Comprobar si la sesion de inicio de sesion existe*/
                $id = isset($_SESSION['loginexitosoa']) ? $_SESSION['loginexitosoa'] -> id : false;
                /*Si la sesion de inicio de sesion existe*/
                if($id){
                    /*Llamar funcion para obtener el perfil del administrador*/
                    $adminUnico = $this -> perfilDeAdministrador($id);
                    /*Incluir la vista*/
                    require_once "Vistas/Administrador/miPerfil.html";
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("cargarperfiladminerror", "Ha ocurrido un error al cargar el perfil del administrador", "?controller=VideojuegoController&action=inicio");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para guardar el administrador
        */

        public function guardarAdministrador($nombre, $apellidos, $fechaNacimiento, $telefono, $email, $clave, $nombreArchivo){
            /*Instanciar el objeto*/
            $administrador = new Administrador();
            /*Crear el objeto*/
            $administrador -> setActivo(TRUE);
            $administrador -> setNombre($nombre);
            $administrador -> setApellido($apellidos);
            $administrador -> setFechanacimiento($fechaNacimiento);
            $administrador -> setNumerotelefono($telefono);
            $administrador -> setCorreo($email);
            $administrador -> setClave($clave);
            $administrador -> setFecharegistro(date('y-m-d'));
            $administrador -> setFoto($nombreArchivo);
            /*Guardar el administrador en la base de datos*/
            $guardado = $administrador -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para comprobar si el administrador ya ha sido creado previamente
        */

        public function comprobarUnicoAdministrador($correo){
            /*Instanciar el objeto*/
            $administrador = new Administrador();
            /*Crear el objeto*/
            $administrador -> setCorreo($correo);
            /*Ejecutar la consulta*/
            $resultado = $administrador -> comprobarAdministradorUnico($_SESSION['loginexitosoa'] -> correo);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para recuperar el administrador eliminado
        */

        public function recuperarAdministrador($correo){
            /*Instanciar el objeto*/
            $administrador = new Administrador();
            /*Crear el objeto*/
            $administrador -> setCorreo($correo);
            /*Ejecutar la consulta*/
            $resultado = $administrador -> recuperarAdministrador();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para guardar el administrador en la base de datos
        */

        public function guardar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_POST)){
                /*Comprobar si cada dato existe*/
                $nombre = isset($_POST['nombreadmin']) ? $_POST['nombreadmin'] : false;
                $apellidos = isset($_POST['apellidosadmin']) ? $_POST['apellidosadmin'] : false;
                $fechaNacimiento = isset($_POST['fechaNacimientoadmin']) ? $_POST['fechaNacimientoadmin'] : false;
                $telefono = isset($_POST['telefonoadmin']) ? $_POST['telefonoadmin'] : false;
                $email = isset($_POST['emailadmin']) ? $_POST['emailadmin'] : false;
                $clave = isset($_POST['passwordadmin']) ? $_POST['passwordadmin'] : false;
                /*Establecer archivo de foto*/
                $archivo = $_FILES['foto'];
                /*Establecer nombre del archivo de la foto*/
                $foto = $archivo['name'];
                /*Comprobar si todos los datos existen*/
                if($nombre && $apellidos && $fechaNacimiento && $telefono && $clave && $email){
                    /*Llamar la funcion que comprueba si la persona es mayor de edad*/
                    $mayorEdad = Ayudas::comprobarMayorEdad($fechaNacimiento);
                    /*Comprobar si el administrador es mayor de edad*/
                    if($mayorEdad){
                        /*Llamar funcion que comprueba si el adminustrador ya ha sido registrado*/
                        $unico = $this -> comprobarUnicoAdministrador($email);
                        /*Comprobar si el correo del administrador no se encuentra asociado a otro administrador*/
                        if($unico == null){
                            /*Comprobar si la contraseña es valida*/
                            $claveSegura = Ayudas::comprobarContrasenia($clave);
                            /*Comprobar si la clave es valida*/
                            if($claveSegura){
                                /*Comprobar si la foto es valida y ha sido guardada*/
                                $fotoGuardada = Ayudas::guardarImagen($archivo, "ImagenesAdministradores");
                                /*Comprobar si la foto ha sido validada y guardada*/
                                if($fotoGuardada){
                                    /*Llamar la funcion de guardar administrador*/
                                    $guardado = $this -> guardarAdministrador($nombre, $apellidos, $fechaNacimiento, $telefono, $email, $clave, $foto);
                                    /*Comprobar si el administrador ha sido guardado*/
                                    if($guardado){
                                        /*Llamar la funcion de inicio de sesion del administrador*/
                                    Ayudas::iniciarSesionAdmnistrador($email, $clave);
                                    /*De lo contrario*/  
                                    }else{
                                        /*Crear la sesion y redirigir a la ruta pertinente*/
                                        Ayudas::crearSesionYRedirigir("guardaradministradorerror", "Ha ocurrido un error al guardar el administrador", "?controller=AdministradorController&action=registro");
                                    }
                                /*De lo contrario*/      
                                }else{
                                    /*Crear la sesion y redirigir a la ruta pertinente*/
                                    Ayudas::crearSesionYRedirigir("guardaradministradorerror", "La imagen debe ser de tipo imagen", "?controller=AdministradorController&action=registro");
                                }
                            /*De lo contrario*/      
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir("guardaradministradorerror", "La clave debe contener un mayuscula, miniscula, numero, caracter especial y minimo 8 caracteres de longitud", "?controller=AdministradorController&action=registro");
                            }
                        /*Comprobar si el administrador existe y esta activo*/    
                        }elseif($unico -> activo == TRUE){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardaradministradorerror', "Este administrador ya se encuentra registrado", '?controller=AdministradorController&action=registro');
                        /*Comprobar si el administrador existe y no esta activo*/ 
                        }elseif($unico -> activo == FALSE){
                            /*Llamar funcion para recuperar el administrador eliminado*/
                            $recuperada = $this -> recuperarAdministrador($email);
                            /*Comprobar si la categoria ha sido recuperada*/
                            if($recuperada){
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('guardaradministradoracierto', "El administrador ha sido recuperado", '?controller=UsuarioController&action=login');
                            /*De lo contrario*/
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('guardaradministradorerror', "El administrador no ha sido recuperado con exito", '?controller=AdministradorController&action=registro');
                            }
                        }
                    /*De lo contrario*/       
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("guardaradministradorerror", "Debe ser mayor de edad para registrarse como administrador", "?controller=AdministradorController&action=registro");
                    }
                /*De lo contrario*/       
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("guardaradministradorerror", "Ha ocurrido un error al guardar el administrador", "?controller=AdministradorController&action=registro");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("guardaradministradorerror", "Ha ocurrido un error al guardar el administrador", "?controller=AdministradorController&action=registro");
            }
        }

        /*
        Funcion para eliminar el administrador
        */

        public function eliminarAdministrador($idAdmin){
            /*Instanciar el objeto*/
            $administrador = new Administrador();
            /*Crear el objeto*/
            $administrador -> setId($idAdmin);
            $administrador -> setActivo(FALSE);
            /*Ejecutar la consulta*/
            $eliminado = $administrador -> eliminar();
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar la cuenta del administrador de la base de datos
        */

        public function eliminar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idAdmin = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idAdmin){
                    /*Llamar la funcion para eliminar el administrador*/
                    $eliminado = $this -> eliminarAdministrador($idAdmin);
                    /*Comprobar si el administrador ha sido eliminado exitosamente*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminaradministradoracierto', "El administrador ha sido eliminado exitosamente", '?controller=VideojuegoController&action=inicio');
                        /*Eliminar el inicio de sesion*/
                        Ayudas::eliminarSesion('loginexitosoa');
                    /*De lo contrario*/       
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminaradministradorerror', "El administrador no ha sido eliminado exitosamente", '?controller=AdminsitradorController&action=miPerfil');
                    }
                /*De lo contrario*/       
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('eliminaradministradorerror', "Ha ocurrido un error al eliminar el adminsitrador", '?controller=Adminsitrador&action=miPerfil');
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para ver los usuarios bloqueados
        */

        public function verBloqueos(){
            /*instanciar el objeto*/
            $bloqueo = new Bloqueo();
            /*Ejecutar la consulta*/
            $listaBloqueos = $bloqueo -> obtenerListaBloqueos();
            /*Incluir la vista*/
            require_once "Vistas/Administrador/VerBloqueos.html";
        }

        /*
        Funcion para ver los administradores
        */

        public function verAdministradores(){
            /*instanciar el objeto*/
            $administrador = new Administrador();
            /*Ejecutar la consulta*/
            $listadoAdministradores = $administrador -> listar();
            /*Incluir la vista*/
            require_once "Vistas/Administrador/VerAdministradores.html";
        }

        /*
        Funcion para gestionar los usuarios
        */

        public function gestionarUsuario(){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Listar todos los usuarios desde la base de datos*/
            $listadoUsuarios = $usuario -> listar();
            /*Incluir la vista*/
            require_once "Vistas/Administrador/GestionUsuarios.html";
        }

        /*
        Funcion para gestionar las categorias
        */

        public function gestionarCategoria(){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Listar todas las categorias desde la base de datos*/
            $listadoCategorias = $categoria -> listar();
            /*Incluir la vista*/
            require_once "Vistas/Administrador/GestionCategorias.html";
        }
        
        /*
        Funcion para gestionar los usos
        */

        public function gestionarUso(){
            /*Instanciar el objeto*/
            $uso = new Uso();
            /*Listar todos los usos desde la base de datos*/
            $listadoUsos = $uso -> listar();
            /*Incluir la vista*/
            require_once "Vistas/Administrador/GestionUsos.html";
        }

        /*
        Funcion para gestionar las consolas
        */

        public function gestionarConsola(){
            /*Instanciar el objeto*/
            $consola = new Consola();
            /*Listar todas las consolas desde la base de datos*/
            $listadoConsolas = $consola -> listar();
            /*Incluir la vista*/
            require_once "Vistas/Administrador/GestionConsolas.html";
        }

        /*
        Funcion para gestionar los estados
        */

        public function gestionarEstado(){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Listar todos los estados desde la base de datos*/
            $listadoEstados = $estado -> listar();
            /*Incluir la vista*/
            require_once "Vistas/Administrador/GestionEstados.html";
        }

        /*
        Funcion para eliminar un usuario
        */

        public function eliminarUsuarioDesdeAdministrador($idUsu){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Crear el objeto*/
            $usuario -> setId($idUsu);
            $usuario -> setActivo(FALSE);
            $usuario -> setFechaLimiteRecuperarCuenta(date("Y-m-d", 0));
            /*Ejecutar la consulta*/
            $eliminado = $usuario -> eliminar();
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar un usuario desde el administrador
        */

        public function eliminarUsuario(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idUsuario = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idUsuario){
                    /*Llamar la funcion de eliminar usuario*/
                    $eliminado = $this -> eliminarUsuarioDesdeAdministrador($idUsuario);
                    /*Comprobar si el usuario ha sido eliminado*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminaradministradorusuarioacierto', "El usuario ha sido eliminado exitosamente", '?controller=AdministradorController&action=gestionarUsuario');
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminaradministradorusuarioerror', "El usuario no ha sido eliminado exitosamente", '?controller=AdministradorController&action=gestionarUsuario');
                    }
                /*De lo contrario*/      
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('eliminaradministradorusuarioerror', "Ha ocurrido un error al eliminar el usuario", '?controller=AdministradorController&action=gestionarUsuario');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para actualizar el administrador
        */

        public function actualizarAdministrador($id, $nombre, $apellidos, $email, $telefono, $foto){
            /*Instanciar el objeto*/
            $administrador = new Administrador();
            /*Crear el objeto*/
            $administrador -> setId($id);
            $administrador -> setNombre($nombre);
            $administrador -> setApellido($apellidos);
            $administrador -> setCorreo($email);
            $administrador -> setNumerotelefono($telefono);
            $administrador -> setFoto($foto);
            /*Ejecutar la consulta*/
            $actualizado = $administrador -> actualizar();
            /*Retornar el resultado*/
            return $actualizado;
        }

        /*
        Funcion para actualizar un administrador
        */

        public function actualizar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existe*/
                $id = isset($_GET['id']) ? $_GET['id'] : false;
                $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
                $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : false;
                $email = isset($_POST['email']) ? $_POST['email'] : false;
                $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : false;
                /*Establecer archivo de foto*/
                $archivo = $_FILES['foto'];
                /*Establecer nombre del archivo de la foto*/
                $foto = $archivo['name'];
                /*Si los datos existen*/
                if($id && $nombre && $apellidos && $email && $telefono){
                    /*Llamar funcion que comprueba si el administrador ya ha sido registrado*/
                    $unico = $this -> comprobarUnicoAdministrador($email);
                    /*Comprobar si el correo del administrador no existe*/
                    if($unico == null){
                        /*Comprobar si la foto no tiene formato de imagen o no ha llegado*/
                        if(Ayudas::comprobarImagen($archivo['type']) != 3){
                            /*Comprobar si la foto tiene formato de imagen*/
                            if(Ayudas::comprobarImagen($archivo['type']) == 1){
                                /*Comprobar si la foto ha sido validada y guardada*/
                                Ayudas::guardarImagen($archivo, "ImagenesAdministradores");
                            }
                            /*Llamar la funcion que actualiza el administrador*/
                            $actualizado = $this -> actualizarAdministrador($id, $nombre, $apellidos, $email, $telefono, $foto);
                            /*Comprobar si el administrador ha sido actualizado*/
                            if($actualizado){
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('actualizaradministradoracierto', "Administrador actualizado con exito", '?controller=AdministradorController&action=miPerfil');
                            /*De lo contrario*/    
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('actualizaradministradorsugerencia', "Agrega nuevos datos", '?controller=AdministradorController&action=miPerfil');
                            }
                        /*De lo contrario*/      
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('actualizaradministradorerror', "El formato de la foto debe ser una imagen", '?controller=AdministradorController&action=miPerfil');
                        }
                    /*De lo contrario*/       
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizaradministradorerror', "Este correo ya se encuentra asociado a un administrador", '?controller=AdministradorController&action=miPerfil');
                    } 
                /*De lo contrario*/       
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('actualizaradministrador', "Ha ocurrido un error al actualizar el administrador", '?controller=AdministradorController&action=miPerfil');
                } 
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para comprobar si la clave actual ingresada es correcta
        */
        
        public function comprobarClaves($actual){
            /*instanciar el objeto*/
            $administrador = new Administrador();
            /*Capturar el correo del administrador ingresado*/
            $correo = $_SESSION['loginexitosoa'] -> correo;
            /*Obtener clave actual del administrador logueado*/
            $claveAdministrador = $administrador -> traerClave($correo);
            /*Verificar clave actual y nueva*/
            $claveVerificada = password_verify($actual, $claveAdministrador -> clave);
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
            $administrador = new Administrador();
            /*Crear el objeto*/
            $administrador -> setId($_SESSION['loginexitosoa'] -> id);
            $administrador -> setClave($clave);
            /*Ejecutar la consulta*/
            $actualizado = $administrador -> actualizarClave();
            /*Retornar el resultado*/
            return $actualizado;
        }

        /*
        Funcion para buscar un administrador
        */

        public function buscarAdministrador($nombre){
            /*Instanciar el objeto*/
            $administrador = new Administrador();
            /*Crear el objeto*/
            $administrador -> setNombre($nombre);
            /*Obtener administradores de la base de datos*/
            $listadoAdministradores = $administrador -> buscar();
            /*Retornar el resultado*/
            return $listadoAdministradores;
        }

        /*
        Funcion para buscar un administrador en concreto
        */

        public function buscar(){
            /*Comprobar si el dato está llegando*/
            if(isset($_POST)){
                /*Comprobar si el dato existe*/
                $nombre = isset($_POST['administradorb']) ? $_POST['administradorb'] : false;
                /*Si el dato existe*/
                if($nombre){
                    /*Llamar la funcion que busca un administrador*/
                    $listadoAdministradores = $this -> buscarAdministrador($nombre);
                    /*Comprobar si hay administradores encontrados*/
                    if(mysqli_num_rows($listadoAdministradores) > 0){
                        /*Incluir la vista*/
                        require_once 'Vistas/Administrador/Buscar.html';
                    /*De lo contrario*/    
                    }else{
                        /*Incluir la vista*/
                        require_once 'Vistas/Administrador/NoEncontrado.html';
                    }
                /*De lo contrario*/       
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=AdministradorController&action=verAdministradores");
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=AdministradorController&action=verAdministradores");
            }
        }

        /*
        Funcion para actualizar la clave del administrador
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
                                Ayudas::crearSesionYRedirigir('actualizarclaveacierto', "La clave ha sido actualizada con exito", '?controller=AdministradorController&action=cambiarClave');
                            /*De lo contrario*/    
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir('actualizarclaveerror', "La clave no ha sido actualizada con exito", '?controller=AdministradorController&action=cambiarClave');
                            }
                        /*De lo contrario*/  
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('actualizarclaveerror', "Clave actual incorrecta", '?controller=AdministradorController&action=cambiarClave');
                        }
                    /*De lo contrario*/      
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarclaveerror', "Clave poco segura", '?controller=AdministradorController&action=cambiarClave');
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('actualizarclaveerror', "Ha ocurrido un error al actualizar la clave", '?controller=AdministradorController&action=cambiarClave');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

    }

?>