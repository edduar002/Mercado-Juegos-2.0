<?php

    /*Iniciar el buffer de salida*/
    ob_start();
    /*Activar la sesión*/ 
    session_start();
    /*Incluir los archivo de autocarga de controladores*/
    require_once 'Autoload.php';
    /*Incluir los archivo de ayudas*/
    require_once 'Ayudas/Ayudas.php';
    /*Incluir los archivo de configuracion de rutas*/
    require_once 'Configuracion/Rutas.php';
    /*Incluir la clave de encriptacion*/
    Require_once 'Configuracion/Clave.php';
    /*Incluir archivo de configuracion de base de datos*/
    require_once 'Configuracion/BaseDeDatos.php';
    /*Incluir el archivo de la vista de cabecera*/
    require_once 'Vistas/Layout/Cabecera.html';
    
    /*
    Funcion para mostrar un error
    */

    function showError(){
        /*Instanciar la case*/
        $error = new ErrorController();
        /*Llamar metodo*/
        $error -> index();
        /*Retornar el resultado*/
        return $error;
    }

    /*Comprobar si llega el controlador por la URL*/
    if(isset($_GET['controller'])){
        /*Establecer nombre del controlador*/
        $nombre = $_GET['controller'];
    /*Comprobar si no llega el controlador y no existe el metodo*/
    }elseif(!isset($_GET['controller']) && !isset($_GET['action'])){
        /*Comprobar si existe el inicio de sesion exitoso del administrador*/
        if(isset($_SESSION['loginexitosoa'])){
            /*Establecer nombre del controlador*/
            $nombre = "AdministradorController";
        /*De lo contrario*/    
        }else{
            /*Establecer nombre del controlador*/
            $nombre = "VideojuegoController";
        }
    /*De lo contrario*/      
    }else{
        /*Mostrar error*/
        showError();
        /*Salir*/
        exit();
    }
    /*Comprobar si existe el controlador*/
    if(class_exists($nombre)){
        /*instanciar el objeto*/
        $controlador = new $nombre();
        /*Comprobar si llega la acción y si el metodo existe dentro del controlador*/
        if(isset($_GET['action']) && method_exists($controlador, $_GET['action'])){
            /*Invocar o llamar a ese metodo*/
            $action = $_GET['action'];
            /*Llamar la acción*/
            $controlador -> $action();
        /*Comprobar si no llega el controlador y no existe la accion*/
        }elseif(!isset($_GET['controller']) && !isset($_GET['action'])){
            /*Comprobar si existe la sesion de login administrador exitoso*/
            if(isset($_SESSION['loginexitosoa'])){
                /*Establecer metodo por defecto*/
                $actionDefault = "Administrar";
            /*De lo contrario*/      
            }else{
                /*Establecer metodo por defecto*/
                $actionDefault = "Inicio";
            }
            /*Realizar accion*/
            $controlador -> $actionDefault();
        /*De lo contrario*/      
        }else{
            /*Mostrar error*/
            showError();
        }
    /*De lo contrario*/          
    }else{
        /*Mostrar error*/
        showError();
    }
    /*Incluir el archivo de la vista de pie de pagina*/
    require_once 'Vistas/Layout/PieDePagina.html';

?>