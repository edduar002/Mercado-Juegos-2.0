<?php

    /*Incluir el objeto de estado*/
    require_once 'Modelos/Estado.php';

    /*
    Clase controlador de estado
    */

    class EstadoController{
        
        /*
        Funcion para crear un estado
        */

        public function crear(){
            /*Incluir la vista*/
            require_once "Vistas/Estado/Crear.html";
        }

        /*
        Funcion para ver los estados eliminados
        */

        public function verEstadosEliminados(){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Listar todas las consolas desde la base de datos*/
            $listadoEstados = $estado -> listarInactivos();
            /*Incluir la vista*/
            require_once "Vistas/Estado/Inactivos.html";
        }

        /*
        Funcion para obtener el nombre del estado
        */

        public function obtenerNombre($id){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Crear el objeto*/
            $estado -> setId($id);
            /*Ejecutar la consulta*/
            $estadoUnico = $estado -> obtenerUno();
            /*Obtener el resultado*/
            $resultado = $estadoUnico -> nombre;
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para guardar un estado en la base de datos
        */

        public function guardarEstado($nombre){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Crear el objeto*/
            $estado -> setactivo(TRUE);
            $estado -> setNombre($nombre);
            /*Ejecutar la consulta*/
            $guardado = $estado -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para comprobar si el estado ya ha sido creado previamente
        */

        public function comprobarUnicoEstado($id, $nombre){
            /*Comprobar si el id es nulo*/
            if($id != null){
                /*Llamar la funcion que obtiene el nombre del estado en concreto*/
                $nombreActual = $this -> obtenerNombre($id);
            }
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Crear el objeto*/
            $estado -> setNombre($nombre);
            /*Ejecutar la consulta*/
            $resultado = $estado -> comprobarEstadoUnico($nombreActual);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para recuperar el estado eliminado
        */

        public function recuperarEstado($nombre){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Crear el objeto*/
            $estado -> setNombre($nombre);
            /*Ejecutar la consulta*/
            $resultado = $estado -> recuperarEstado();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para guardar un estado
        */

        public function guardar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_POST)){
                /*Comprobar si el dato existe*/
                $nombre = isset($_POST['nombreest']) ? $_POST['nombreest'] : false;
                /*Si el dato existe*/
                if($nombre){
                    /*Llamar funcion que comprueba si el estado ya ha sido registrado*/
                    $unico = $this -> comprobarUnicoEstado(null, $nombre);
                    /*Comprobar si el nombre del estado no existe*/
                    if($unico == null){
                        /*Llamar la funcion de guardar estado*/
                        $guardado = $this -> guardarEstado($nombre);
                        /*Comprobar se ejecutó con exito la consulta*/
                        if($guardado){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarestadoacierto', "El estado ha sido creado con exito", '?controller=AdministradorController&action=gestionarEstado');
                        /*De lo contrario*/  
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarestadoerror', "El estado no ha sido creado con exito", '?controller=EstadoController&action=crear');
                        }
                    /*Comprobar si el estado existe y esta activo*/    
                    }elseif($unico -> activo == TRUE){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('guardarestadoerror', "Este estado ya se encuentra registrado", '?controller=EstadoController&action=crear');
                    /*Comprobar si el estado existe y no esta activo*/ 
                    }elseif($unico -> activo == FALSE){
                        /*Llamar funcion para recuperar el estado eliminado*/
                        $recuperado = $this -> recuperarEstado($nombre);
                        /*Comprobar si el estado ha sido recuperado*/
                        if($recuperado){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarestadoacierto', "El estado ha sido recuperado", '?controller=AdministradorController&action=gestionarEstado');
                        /*De lo contrario*/
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarestadoerror', "El estado no ha sido recuperado con exito", '?controller=EstadoController&action=crear');
                        }
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('guardarestadoerror', "Ha ocurrido un error al guardar el estado", '?controller=EstadoController&action=crear');
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir('guardarestadoerror', "Ha ocurrido un error al guardar el estado", '?controller=EstadoController&action=crear');
            }
        }

        /*
        Funcion para eliminar un estado en la base de datos
        */

        public function eliminarEstado($idEstado){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Crear el objeto*/
            $estado -> setId($idEstado);
            $estado -> setActivo(FALSE);
            /*Ejecutar la consulta*/
            $eliminado = $estado -> eliminar();
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
                $idEstado = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idEstado){
                    /*Llamar la funcion que elimina el estado*/
                    $eliminado = $this -> eliminarEstado($idEstado);
                    /*Comprobar si el estado ha sido eliminado con exito*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarestadoacierto', "El estado ha sido eliminado exitosamente", '?controller=AdministradorController&action=gestionarEstado');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarestadoerror', "El estado no ha sido eliminado exitosamente", '?controller=AdministradorController&action=gestionarEstado');
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('eliminarestadoerror', "Ha ocurrido un error al eliminar el estado", '?controller=AdministradorController&action=gestionarEstado');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para editar un estado en la base de datos
        */

        public function editarEstado($id){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Crear el objeto*/
            $idEstado = $estado -> setId($id);
            /*Retornar el resultado*/
            return $idEstado;
        }

        /*
        Funcion para editar un estado
        */

        public function editar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idEstado = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idEstado){
                    /*Llamar la funcion de editar estado*/
                    $estado = $this -> editarEstado($idEstado);
                    /*Comprobar si el estado ha sido editado*/
                    if($estado){
                        /*Llamar la funcion para obtener un estado en concreto*/
                        $estadoUnico = $estado -> obtenerUno();
                        /*Comprobar si el estado ha sido obtenido*/
                        if($estadoUnico){
                            /*Incluir la vista*/
                            require_once "Vistas/Estado/Actualizar.html";
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
        Funcion para actualizar un estado en la base de datos
        */

        public function actualizarEstado($idEstado, $nombre){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Crear el objeto*/
            $estado -> setId($idEstado);
            $estado -> setNombre($nombre);
            /*Ejecutar la consulta*/
            $actualizado = $estado -> actualizar();
            /*Retornar el resultado*/
            return $actualizado;
        }

        /*
        Funcion para actualizar un estado
        */

        public function actualizar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $idEstado = isset($_GET['id']) ? $_GET['id'] : false;
                $nombre = isset($_POST['nombreestact']) ? $_POST['nombreestact'] : false;
                /*Si los datos existen*/
                if($idEstado){
                    /*Llamar funcion que comprueba si el estado ya ha sido registrado*/
                    $unico = $this -> comprobarUnicoEstado($idEstado, $nombre);
                    /*Comprobar si el nombre del estado no existe*/
                    if($unico == null){
                        /*Llamar la funcion de actualizar el estado*/
                        $actualizado = $this -> actualizarEstado($idEstado, $nombre);
                        /*Comprobar si el estado ha sido actualizado*/
                        if($actualizado){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('actualizarestadoacierto', "El estado ha sido actualizado exitosamente", '?controller=AdministradorController&action=gestionarEstado');
                        /*De lo contrario*/
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('actualizarestadosugerencia', "Introduce nuevos datos", '?controller=EstadoController&action=editar&id='.$idEstado);
                        }
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarestadoerror', "Este nombre ya se encuentra asociado a un estado", '?controller=EstadoController&action=editar&id='.$idEstado);
                    } 
                /*De lo contrario*/
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('actualizarestadoerror', "Ha ocurrido un error al actualizar el estado", '?controller=EstadoController&action=editar&id='.$idEstado);
                }  
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para buscar un estado
        */

        public function buscarEstado($nombre){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Crear el objeto*/
            $estado -> setNombre($nombre);
            /*Obtener estados de la base de datos*/
            $listadoEstados = $estado -> buscar();
            /*Retornar el resultado*/
            return $listadoEstados;
        }

        /*
        Funcion para buscar un estado en concreto
        */

        public function buscar(){
            /*Comprobar si el dato está llegando*/
            if(isset($_POST)){
                /*Comprobar si el dato existe*/
                $nombre = isset($_POST['estadob']) ? $_POST['estadob'] : false;
                /*Si el dato existe*/
                if($nombre){
                    /*Llamar la funcion que busca un estado*/
                    $listadoEstados = $this -> buscarEstado($nombre);
                    /*Comprobar si hay estados encontrados*/
                    if(mysqli_num_rows($listadoEstados) > 0){
                        /*Incluir la vista*/
                        require_once 'Vistas/Estado/Buscar.html';
                    /*De lo contrario*/    
                    }else{
                        /*Incluir la vista*/
                        require_once 'Vistas/Estado/NoEncontrado.html';
                    }
                /*De lo contrario*/       
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=AdministradorController&action=gestionarEstado");
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=AdministradorController&action=gestionarEstado");
            }
        }

        /*
        Funcion para recuperar un estado en la base de datos
        */

        public function restaurarEstado($idEstado){
            /*Instanciar el objeto*/
            $estado = new Estado();
            /*Crear el objeto*/
            $estado -> setId($idEstado);
            $estado -> setActivo(TRUE);
            /*Ejecutar la consulta*/
            $eliminado = $estado -> recuperarEstado();
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar un estado
        */

        public function restaurar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idEstado = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idEstado){
                    /*Llamar la funcion que elimina el estado*/
                    $eliminado = $this -> restaurarEstado($idEstado);
                    /*Comprobar si el estado ha sido eliminado con exito*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('restaurarestadoacierto', "La estado ha sido restaurado con exito", '?controller=AdministradorController&action=gestionarEstado');
                    /*De lo contrario*/   
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('restaurarestadoerror', "La estado no ha sido restaurado exitosamente", '?controller=EstadoController&action=verEstadosEliminados');
                    }
                /*De lo contrario*/   
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('restaurarestadoerror', "Ha ocurrido un error al restaurar el estado", '?controller=EstadoController&action=verEstadosEliminados');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

    }

?>
