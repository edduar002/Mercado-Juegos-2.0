<?php

    /*Incluir el objeto de envio*/
    require_once 'Modelos/Envio.php';

    /*
    Clase controlador de envio
    */

    class EnvioController{

        /*
        Funcion para crear un envio
        */

        public function crear(){
            /*Incluir la vista*/
            require_once "Vistas/Envio/Crear.html";
        }

        /*
        Funcion para guardar un envio en la base de datos
        */

        public function guardarEnvio($departamento, $municipio, $codigoPostal, $barrio, $direccion){
            /*Instanciar el objeto*/
            $envio = new Envio();
            /*Crear el objeto*/
            $envio -> setactivo(TRUE);
            $envio -> setIdUsuario($_SESSION['loginexitoso'] -> id);
            $envio -> setDepartamento($departamento);
            $envio -> setMunicipio($municipio);
            $envio -> setCodigoPostal($codigoPostal);
            $envio -> setBarrio($barrio);
            $envio -> setDireccion($direccion);
            /*Ejecutar la consulta*/
            $guardado = $envio -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para guardar un envio
        */

        public function guardar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_POST)){
                /*Comprobar si cada dato existe*/
                $departamento = isset($_POST['departamento']) ? $_POST['departamento'] : false;
                $municipio = isset($_POST['municipio']) ? $_POST['municipio'] : false;
                $codigoPostal = isset($_POST['codigoPostal']) ? $_POST['codigoPostal'] : false;
                $barrio = isset($_POST['barrio']) ? $_POST['barrio'] : false;
                $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : false;
                /*Si los datos existen*/
                if($departamento && $municipio && $codigoPostal && $barrio && $direccion){
                    /*Llamar la funcion que guarda el envio*/
                    $guardado = $this -> guardarEnvio($departamento, $municipio, $codigoPostal, $barrio, $direccion);
                    /*Comprobar se ejecutó con exito la consulta*/
                    if($guardado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('guardarenvioacierto', "El envio ha sido creado con exito", '?controller=UsuarioController&action=envios');
                    /*De lo contrario*/       
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('guardarenvioerror', "El envio no ha sido creado con exito", '?controller=EnvioController&action=crear');
                    }
                /*De lo contrario*/       
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('guardarenvioerror', "Ha ocurrido un error al guardar el envio", '?controller=EnvioController&action=crear');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir('guardarenvioerror', "Ha ocurrido un error al guardar el envio", '?controller=EnvioController&action=crear');
            }
        }

        /*
        Funcion para eliminar un envio en la base de datos
        */

        public function eliminarEnvio($idEnvio){
            /*Instanciar el objeto*/
            $envio = new Envio();
            /*Crear el objeto*/
            $envio -> setId($idEnvio);
            $envio -> setActivo(FALSE);
            /*Ejecutar la consulta*/
            $eliminado = $envio -> eliminar();
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar un estado
        */

        public function eliminar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idEnvio = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idEnvio){
                    /*Llamar la funcion que elimina el envio*/
                    $eliminado = $this -> eliminarEnvio($idEnvio);
                    /*Comprobar si el envio ha sido eliminado con exito*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarenvioacierto', "El envio ha sido eliminado exitosamente", '?controller=UsuarioController&action=envios');
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarenvioerror', "El envio no ha sido eliminado exitosamente", '');
                    }
                /*De lo contrario*/      
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('eliminarenvioerror', "Ha ocurrido un error al eliminar el envio", '');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para editar un envio en la base de datos
        */

        public function editarEnvio($id){
            /*Instanciar el objeto*/
            $envio = new Envio();
            /*Crear el objeto*/
            $idEnvio = $envio -> setId($id);
            /*Retornar el resultado*/
            return $idEnvio;
        }

        /*
        Funcion para editar un envio
        */

        public function editar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idEnvio = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idEnvio){
                    /*Llamar la funcion para editar el envio*/
                    $envio = $this -> editarEnvio($idEnvio);
                    /*Comprobar si el envio ha sido editado*/
                    if($envio){
                        /*Obtener envio*/
                        $envioUnico = $envio -> obtenerUno();
                        /*Comprobar si el envio ha sido obtenido*/
                        if($envioUnico){
                            /*Incluir la vista*/
                            require_once "Vistas/Envio/Actualizar.html";
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
        Funcion para actualizar un envio en la base de datos
        */

        public function actualizarEnvio($idEnvio, $departamento, $municipio, $codigoPostal, $barrio, $direccion){
            /*Instanciar el objeto*/
            $envio = new Envio();
            /*Crear el objeto*/
            $envio -> setId($idEnvio);
            $envio -> setDepartamento($departamento);
            $envio -> setMunicipio($municipio);
            $envio -> setCodigoPostal($codigoPostal);
            $envio -> setBarrio($barrio);
            $envio -> setDireccion($direccion);
            /*Ejecutar la consulta*/
            $actualizado = $envio -> actualizar();
            /*Retornar el resultado*/
            return $actualizado;
        }

        /*
        Funcion para actualizar un envio
        */

        public function actualizar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $idEnvio = isset($_GET['id']) ? $_GET['id'] : false;
                $departamento = isset($_POST['departamento']) ? $_POST['departamento'] : false;
                $municipio = isset($_POST['municipio']) ? $_POST['municipio'] : false;
                $codigoPostal = isset($_POST['codigoPostal']) ? $_POST['codigoPostal'] : false;
                $barrio = isset($_POST['barrio']) ? $_POST['barrio'] : false;
                $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : false;
                /*Si los datos existen*/
                if($idEnvio && $departamento && $municipio && $codigoPostal && $barrio && $direccion){
                    /*Llamar la funcion que actualiza el envio*/
                    $actualizado = $this -> actualizarEnvio($idEnvio, $departamento, $municipio, $codigoPostal, $barrio, $direccion);
                    /*Comprobar si el envio ha sido actualizado*/
                    if($actualizado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarenvioacierto', "El envio ha sido actualizado exitosamente", '?controller=UsuarioController&action=envios');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarenviosugerencia', "Introduce nuevos datos", '?controller=EnvioController&action=editar&id='.$idEnvio);
                    }
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('actualizarenvioerror', "Ha ocurrido un error al actualizar el envio", '?controller=EnvioController&action=editar&id='.$idEnvio);
                }  
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

    }

?>