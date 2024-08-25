<?php

    /*Incluir el objeto de categoria*/
    require_once 'Modelos/Categoria.php';

    /*
    Clase controlador de categoria
    */

    class CategoriaController{

        /*
        Funcion para crear una categoria
        */

        public function crear(){
            /*Incluir la vista*/
            require_once "Vistas/Categoria/Crear.html";
        }

        /*
        Funcion para ver las categorias eliminadas
        */

        public function verCategoriasEliminadas(){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Listar todas las consolas desde la base de datos*/
            $listadoCategorias = $categoria -> listarInactivas();
            /*Incluir la vista*/
            require_once "Vistas/Categoria/Inactivas.html";
        }

        /*
        Funcion para obtener el nombre de la categoria
        */

        public function obtenerNombre($id){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Crear el objeto*/
            $categoria -> setId($id);
            /*Ejecutar la consulta*/
            $categoriaUnica = $categoria -> obtenerUna();
            /*Obtener el resultado*/
            $resultado = $categoriaUnica -> nombre;
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para guardar una categoria en la base de datos
        */

        public function guardarCategoria($nombre){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Crear el objeto*/
            $categoria -> setactivo(TRUE);
            $categoria -> setNombre($nombre);
            /*Ejecutar la consulta*/
            $guardado = $categoria -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para comprobar si la categoria ya ha sido creada previamente
        */

        public function comprobarUnicaCategoria($id, $nombre){
            /*Comprobar si el id es nulo*/
            if($id != null){
                /*Llamar la funcion que obtiene el nombre de la cateogoria en concreto*/
                $nombreActual = $this -> obtenerNombre($id);
            }
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Crear el objeto*/
            $categoria -> setNombre($nombre);
            /*Ejecutar la consulta*/
            $resultado = $categoria -> comprobarCategoriaUnica($nombreActual);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para recuperar la categoria eliminada
        */

        public function recuperarCategoria($nombre){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Crear el objeto*/
            $categoria -> setNombre($nombre);
            /*Ejecutar la consulta*/
            $resultado = $categoria -> recuperarCategoria();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para buscar una categoria
        */

        public function buscarCategoria($nombre){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Crear el objeto*/
            $categoria -> setNombre($nombre);
            /*Obtener categorias de la base de datos*/
            $listadoCategorias = $categoria -> buscar();
            /*Retornar el resultado*/
            return $listadoCategorias;
        }

        /*
        Funcion para buscar una categoria en concreto
        */

        public function buscar(){
            /*Comprobar si el dato está llegando*/
            if(isset($_POST)){
                /*Comprobar si el dato existe*/
                $nombre = isset($_POST['categoriab']) ? $_POST['categoriab'] : false;
                /*Si el dato existe*/
                if($nombre){
                    /*Llamar la funcion que busca una categoria*/
                    $listadoCategorias = $this -> buscarCategoria($nombre);
                    /*Comprobar si hay categorias encontradas*/
                    if(mysqli_num_rows($listadoCategorias) > 0){
                        /*Incluir la vista*/
                        require_once 'Vistas/Categoria/Buscar.html';
                    /*De lo contrario*/    
                    }else{
                        /*Incluir la vista*/
                        require_once 'Vistas/Categoria/NoEncontrada.html';
                    }
                /*De lo contrario*/       
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=AdministradorController&action=gestionarCategoria");
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=AdministradorController&action=gestionarCategoria");
            }
        }

        /*
        Funcion para guardar una categoria
        */

        public function guardar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_POST)){
                /*Comprobar si el dato existe*/
                $nombre = isset($_POST['nombrecat']) ? $_POST['nombrecat'] : false;
                /*Si el dato existe*/
                if($nombre){
                    /*Llamar funcion que comprueba si la categoria ya ha sido registrada*/
                    $unico = $this -> comprobarUnicaCategoria(null, $nombre);
                    /*Comprobar si el nombre de la categoria no existe*/
                    if($unico == null){
                        /*Llamar la funcion de guardar categoria*/
                        $guardado = $this -> guardarCategoria($nombre);
                        /*Comprobar se ejecutó con exito la consulta*/
                        if($guardado){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarcategoriaacierto', "La categoria ha sido creada con exito", '?controller=AdministradorController&action=gestionarCategoria');
                        /*De lo contrario*/    
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarcategoriaerror', "La categoria no ha sido creada con exito", '?controller=CategoriaController&action=crear');
                        }  
                    /*Comprobar si la categoria existe y esta activa*/    
                    }elseif($unico -> activo == TRUE){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('guardarcategoriaerror', "Esta categoria ya se encuentra registrada", '?controller=CategoriaController&action=crear');
                    /*Comprobar si la categoria existe y no esta activa*/ 
                    }elseif($unico -> activo == FALSE){
                        /*Llamar funcion para recuperar la categoria eliminada*/
                        $recuperada = $this -> recuperarCategoria($nombre);
                        /*Comprobar si la categoria ha sido recuperada*/
                        if($recuperada){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarcategoriaacierto', "La categoria ha sido recuperada", '?controller=AdministradorController&action=gestionarCategoria');
                        /*De lo contrario*/
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('guardarcategoriaerror', "La categoria no ha sido recuperada con exito", '?controller=CategoriaController&action=crear');
                        }
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
                    }
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('guardarcategoriaerror', "Ha ocurrido un error al guardar la categoria", '?controller=CategoriaController&action=crear');
                }
            /*De lo contrario*/     
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir('guardarcategoriaerror', "Ha ocurrido un error al guardar la categoria", '?controller=CategoriaController&action=crear');
            }
        }

        /*
        Funcion para eliminar una categoria en la base de datos
        */

        public function eliminarCategoria($idCategoria){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Crear el objeto*/
            $categoria -> setId($idCategoria);
            $categoria -> setActivo(FALSE);
            /*Ejecutar la consulta*/
            $eliminado = $categoria -> eliminar();
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar una categoria
        */

        public function eliminar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idCategoria = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idCategoria){
                    /*Llamar la funcion que elimina la categoria*/
                    $eliminado = $this -> eliminarCategoria($idCategoria);
                    /*Comprobar si la categoria ha sido eliminada con exito*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarcategoriaacierto', "La categoria ha sido eliminada exitosamente", '?controller=AdministradorController&action=gestionarCategoria');
                    /*De lo contrario*/   
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarcategoriaerror', "La categoria no ha sido eliminada exitosamente", '?controller=AdministradorController&action=gestionarCategoria');
                    }
                /*De lo contrario*/   
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('eliminarcategoriaerror', "Ha ocurrido un error al eliminar la categoria", '?controller=AdministradorController&action=gestionarCategoria');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para editar una categoria en la base de datos
        */

        public function editarCategoria($id){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Crear el objeto*/
            $idCategoria = $categoria -> setId($id);
            /*Retornar el resultado*/
            return $idCategoria;
        }

        /*
        Funcion para editar una categoria
        */

        public function editar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idCategoria = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idCategoria){
                    /*Llamar la funcion de editar categoria*/
                    $categoria = $this -> editarCategoria($idCategoria);
                    /*Comprobar si la categoria ha sido editada*/
                    if($categoria){
                        /*Llamar la funcion para obtener una categoria en concreto*/
                        $categoriaUnica = $categoria -> obtenerUna();
                        /*Comprobar si la cateogoria ha sido obtenida*/
                        if($categoriaUnica){
                            /*Incluir la vista*/
                            require_once "Vistas/Categoria/Actualizar.html";
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
        Funcion para actualizar una categoria en la base de datos
        */

        public function actualizarCategoria($idCategoria, $nombre){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Crear el objeto*/
            $categoria -> setId($idCategoria);
            $categoria -> setNombre($nombre);
            /*Ejecutar la consulta*/
            $actualizado = $categoria -> actualizar();
            /*Retornar el resultado*/
            return $actualizado;
        }

        /*
        Funcion para actualizar una categoria
        */

        public function actualizar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $idCategoria = isset($_GET['id']) ? $_GET['id'] : false;
                $nombre = isset($_POST['nombrecatact']) ? $_POST['nombrecatact'] : false;
                /*Si los datos existen*/
                if($idCategoria){
                    /*Llamar funcion que comprueba si la categoria ya ha sido registrada*/
                    $unico = $this -> comprobarUnicaCategoria($idCategoria, $nombre);
                    /*Comprobar si el nombre de la consola no existe*/
                    if($unico == null){
                        /*Llamar la funcion de actualizar categoria*/
                        $actualizado = $this -> actualizarCategoria($idCategoria, $nombre);
                        /*Comprobar si la categoria ha sido actualizada*/
                        if($actualizado){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('actualizarcategoriaacierto', "La categoria ha sido actualizada exitosamente", '?controller=AdministradorController&action=gestionarCategoria');
                        /*De lo contrario*/ 
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir('actualizarcategoriasugerencia', "Introduce nuevos datos", '?controller=CategoriaController&action=editar&id='.$idCategoria);
                        }      
                    /*De lo contrario*/     
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarcategoriaerror', "Este nombre ya se encuentra asociado a una categoria", '?controller=CategoriaController&action=editar&id='.$idCategoria);
                    } 
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('actualizarcategoriaerror', "Ha ocurrido un error al actualizar la cateogoria", '?controller=CategoriaController&action=editar&id='.$idCategoria);
                }  
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para recuperar una categoria en la base de datos
        */

        public function restaurarCategoria($idCategoria){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Crear el objeto*/
            $categoria -> setId($idCategoria);
            $categoria -> setActivo(TRUE);
            /*Ejecutar la consulta*/
            $eliminado = $categoria -> recuperarCategoria();
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar una categoria
        */

        public function restaurar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idCategoria = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idCategoria){
                    /*Llamar la funcion que elimina la categoria*/
                    $eliminado = $this -> restaurarCategoria($idCategoria);
                    /*Comprobar si la categoria ha sido eliminada con exito*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('restaurarcategoriaacierto', "La categoria ha sido restaurada exitosamente", '?controller=AdministradorController&action=gestionarCategoria');
                    /*De lo contrario*/   
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('restaurarcategoriaerror', "La categoria no ha sido restaurada exitosamente", '?controller=CategoriaController&action=verCategoriasEliminadas');
                    }
                /*De lo contrario*/   
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('restaurarcategoriaerror', "Ha ocurrido un error al restaurar la categoria", '?controller=CategoriaController&action=verCategoriasEliminadas');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

    }

?>