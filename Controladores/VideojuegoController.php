<?php

    /*Incluir el objeto de usuario*/
    require_once 'Modelos/Usuario.php';
    /*Incluir el objeto de videojuego*/
    require_once 'Modelos/Videojuego.php';
    /*Incluir el objeto de categoria*/
    require_once 'Modelos/Categoria.php';
    /*Incluir el objeto de comentario usuario videojuego*/
    require_once 'Modelos/ComentarioUsuarioVideojuego.php';
    /*Incluir el objeto de consola*/
    require_once 'Modelos/Consola.php';
    /*Incluir el objeto de uso*/
    require_once 'Modelos/Uso.php';
    /*Incluir el objeto de videojuego categoria*/
    require_once 'Modelos/VideojuegoCategoria.php';

    /*
    Clase controlador de videojuego
    */

    class VideojuegoController{

        /*
        Funcion para listar la pantalla de inicio
        */

        public function inicio(){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Comprobar si existe la sesion de administrador*/
            if(isset($_SESSION['loginexitosoa'])){
                /*Incluir la vista*/
                require_once 'Vistas/Administrador/Inicio.html';
            /*De lo contrario*/    
            }else{
                /*Comprobar si existe la sesion de usuario*/
                if(isset($_SESSION['loginexitoso'])){
                    /*Constuir el objeto*/
                    $usuario -> setId($_SESSION['loginexitoso'] -> id);
                }
                /*Traer el listado de algunos videojuegos*/
                $listadoAlgunos = $usuario -> listarAlgunos();
                /*Traer el listado de todos los videojuegos*/
                $listadoTodos = $usuario -> listarTodos();
                /*Incluir la vista*/
                require_once 'Vistas/Layout/Catalogo.html';
            }
        }

        /*
        Funcion para obtener los comentarios referentes a una publicacion
        */

        public function obtenerComentariosDeVideojuego($id){
            /*Instanciar el objeto*/
            $comentarioUsuarioVideojuego = new ComentarioUsuarioVideojuego();
            /*Construir el objeto*/
            $comentarioUsuarioVideojuego -> setIdVideojuego($id);
            /*Traer el resultado*/
            $listaComentarios = $comentarioUsuarioVideojuego -> obtenerComentariosDeVideojuego();
            /*Retornar el resultado*/
            return $listaComentarios;
        }

        /*
        Funcion para ver los videojuegos comentados
        */

        public function verVideojuegosComentados(){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Listar todos los videojuegos comentados*/
            $listadoComentados = $videojuego -> videojuegosComentados();
            /*Incluir la vista*/
            require_once "Vistas/Videojuego/Comentados.html";
        }

        /*
        Funcion para ver los nuevos videojuegos
        */

        public function verNuevosVideojuegos(){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Listar todos los videojuegos nuevos*/
            $listadoNuevos = $videojuego -> videojuegosNuevos();
            /*Incluir la vista*/
            require_once "Vistas/Videojuego/Nuevos.html";
        }

        /*
        Funcion para ver los videojuegos que mas estan gustando
        */

        public function verLoQueMasEstaGustando(){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Listar todos los videojuegos que mas estan gustando*/
            $listadoDestacados = $videojuego -> videojuegosQueEstanGustando();
            /*Incluir la vista*/
            require_once "Vistas/Videojuego/LoQueMasEstaGustando.html";
        }

        /*
        Funcion para ver los videojuegos destacados
        */

        public function verVideojuegosDestacados(){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Listar todos los videojuegos destacados*/
            $listadoDestacados = $videojuego -> videojuegosDestacados();
            /*Incluir la vista*/
            require_once "Vistas/Videojuego/Destacados.html";
        }

        /*
        Funcion para ver los videojuegos del vendedor con la mayor cantidad de ventas
        */

        public function verVideojuegosMayorVendedor(){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Listar todos los videojuegos destacados*/
            $listadoVendidos = $videojuego -> mayorVendidos();
            /*Incluir la vista*/
            require_once "Vistas/Videojuego/MayorVendidos.html";
        }

        /*
        Funcion para traer un videojuego en concreto
        */

        public function traerVideojuegoEspecifico($id){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Construir el objeto*/
            $videojuego -> setId($id);
            /*Traer videojuego en concreto*/
            $videojuegoEspecifico = $videojuego -> traerUno();
            /*Retornar el resultado*/
            return $videojuegoEspecifico;
        }

        /*
        Funcion para obtener el usuario vendedor del videojuego
        */

        public function obtenerUsuarioVendedor($id){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Construir el objeto*/
            $videojuego -> setId($id);
            /*Traer el resultado*/
            $datosVendedor = $videojuego -> obtenerUsuarioVendedor();
            /*Retornar el resultado*/
            return $datosVendedor;
        }

        /*
        Funcion para ver el detalle del videojuego
        */

        public function detalle(){
            /*Comprobar si el dato está llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($id){
                    /*Llamar funcion que trae un videojuego en especifico*/
                    $videojuegoEspecifico = $this -> traerVideojuegoEspecifico($id);
                    /*Comprobar si el videojuego ha llegado*/
                    if($videojuegoEspecifico){
                        /*Llamar funcion que trae el usuario vendedor*/
                        $datosVendedor = $this -> obtenerUsuarioVendedor($id);
                        /*Llamar funcion que trae comentarios del videojuego*/
                        $listaComentarios = $this -> obtenerComentariosDeVideojuego($id);
                        /*Incluir la vista*/
                        require_once 'Vistas/Videojuego/Detalle.html';
                    /*De lo contrario*/    
                    }else{
                        /*Redirigir*/
                        header("Location:"."http://localhost/Mercado-Juegos/?controller=VideojuegoController&action=inicio");
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
        Funcion para obtener la lista de categorias
        */

        public function obtenerListaDeCategorias(){
            /*Instanciar el objeto*/
            $categoria = new Categoria();
            /*Listar todas las categorias desde la base de datos*/
            $listadoCategorias = $categoria -> listar();
            /*Retornar el resultado*/
            return $listadoCategorias;
        }

        /*
        Funcion para obtener la lista de usos
        */

        public function obtenerListaDeUsos(){
            /*Instanciar el objeto*/
            $uso = new Uso();
            /*Listar todos los usos desde la base de datos*/
            $listadoUsos = $uso -> listar();
            /*Retornar el resultado*/
            return $listadoUsos;
        }

        /*
        Funcion para obtener la lista de consolas
        */

        public function obtenerListaDeConsolas(){
            /*Instanciar el objeto*/
            $consola = new Consola();
            /*Listar todas las consolas desde la base de datos*/
            $listadoConsolas = $consola -> listar();
            /*Retornar el resultado*/
            return $listadoConsolas;
        }

        /*
        Funcion para crear un videojuego
        */

        public function crear(){
            /*Traer lista de consolas, categorias y usos*/
            $listadoCategorias = $this -> obtenerListaDeCategorias();
            $listadoConsolas = $this -> obtenerListaDeConsolas();
            $listadoUsos = $this -> obtenerListaDeUsos();
            /*Incluir la vista*/
            require_once 'Vistas/Videojuego/Crear.html';
        }

        /*
        Funcion para buscar un videojuego
        */

        public function buscarVideojuego($nombre){
            /*Instanciar el objeto*/
            $usuario = new Usuario();
            /*Comprobar si existe la sesion de usuario*/
            if(isset($_SESSION['loginexitoso'])){
                /*Constuir el objeto*/
                $usuario -> setId($_SESSION['loginexitoso'] -> id);
            }
            /*Crear el objeto*/
            $usuario -> setNombre($nombre);
            /*Obtener videojuegos de la base de datos*/
            $listaVideojuegos = $usuario -> buscar($nombre);
            /*Retornar el resultado*/
            return $listaVideojuegos;
        }

        /*
        Funcion para buscar un videojuego en concreto
        */

        public function buscar(){
            /*Comprobar si el dato está llegando*/
            if(isset($_POST)){
                /*Comprobar si el dato existe*/
                $nombre = isset($_POST['nombrebus']) ? $_POST['nombrebus'] : false;
                /*Si el dato existe*/
                if($nombre){
                    /*Llamar la funcion que busca un videojuego*/
                    $listaVideojuegos = $this -> buscarVideojuego($nombre);
                    /*Comprobar si hay videojuegos encontrados*/
                    if(mysqli_num_rows($listaVideojuegos) > 0){
                        /*Incluir la vista*/
                        require_once 'Vistas/Videojuego/Buscar.html';
                    /*De lo contrario*/    
                    }else{
                        /*Incluir la vista*/
                        require_once 'Vistas/Videojuego/NoEncontrado.html';
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
        Funcion para guardar el videojuego en la base de datos
        */

        public function guardarVideojuego($usuario, $nombre, $consola, $uso, $precio, $descripcion, $stock, $nombreArchivo){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Crear el objeto*/
            $videojuego -> setactivo(TRUE);
            $videojuego -> setIdUsuario($usuario);
            $videojuego -> setNombre($nombre);
            $videojuego -> setIdConsola($consola);
            $videojuego -> setIdUso($uso);
            $videojuego -> setPrecio($precio);
            $videojuego -> setDescripcion($descripcion);
            $videojuego -> setStock($stock);
            $videojuego -> setFechaCreacion(date('y-m-d'));
            $videojuego -> setFoto($nombreArchivo);
            /*Guardar el videojuego en la base de datos*/
            $guardado = $videojuego -> guardar();
            /*Retornar el resultado*/
            return $guardado;
        }

        /*
        Funcion para obtener el ultimo videojuego guardado
        */

        public function obtenerUltimoVideojuego(){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Obtener id del ultimo videojuego registrado*/
            $id = $videojuego -> ultimo();
            /*Retornar el resultado*/
            return $id;
        }

        /*
        Funcion para guardar el videojuego categoria en la base de datos
        */

        public function guardarVideojuegoCategoria($categorias){
            /*Instanciar el objeto*/
            $videojuegoCategoria = new VideojuegoCategoria();
            /*Crear el objeto*/
            $videojuegoCategoria -> setIdVideojuego($this -> obtenerUltimoVideojuego());
            $videojuegoCategoria -> setIdCategoria($categorias);
            /*Guardar en la base de datos*/
            $guardadoVideojuegoCategoria = $videojuegoCategoria -> guardar();
            /*Retornar el resultado*/
            return $guardadoVideojuegoCategoria;
        }

        /*
        Funcion para guardar el videojuego en la base de datos
        */

        public function guardar(){
            /*Comprobar si existe la sesion de usuario logueado*/
            $usuarioId = isset($_SESSION['loginexitoso']) ? $_SESSION['loginexitoso'] -> id : false;
            /*Comprobar si los datos están llegando*/
            if(isset($_POST) && $usuarioId){
                /*Comprobar si cada dato existe*/
                $nombre = isset($_POST['nombrevid']) ? $_POST['nombrevid'] : false;
                $consola = isset($_POST['consolavid']) ? $_POST['consolavid'] : false;
                $uso = isset($_POST['usovid']) ? $_POST['usovid'] : false;
                $precio = isset($_POST['preciovid']) ? $_POST['preciovid'] : false;
                $stock = isset($_POST['stockvid']) ? $_POST['stockvid'] : false;
                $descripcion = isset($_POST['descripcionvid']) ? $_POST['descripcionvid'] : false;
                $categorias = isset($_POST['categoriasvid']) ? $_POST['categoriasvid'] : false;
                /*Establecer archivo de foto*/
                $archivo = $_FILES['foto'];
                /*Establecer nombre del archivo de la foto*/
                $foto = $archivo['name'];
                /*Si cada dato existe*/
                if($nombre && $consola && $uso && $precio && $descripcion && $stock){
                    /*Comprobar si la foto es valida y ha sido guardada*/
                    $fotoGuardada = Ayudas::guardarImagen($archivo, "ImagenesVideojuegos");
                    /*Comprobar si la foto ha sido validada y guardada*/
                    if($fotoGuardada){
                        /*Llamar la funcion de guardar videojuego*/
                        $guardado = $this -> guardarVideojuego($usuarioId, $nombre, $consola, $uso, $precio, $descripcion, $stock, $foto);
                        /*Comprobar si el videojuego ha sido guardado*/
                        if($guardado){
                            /*Guardar el videojuego cateogoria y videojuego usuario*/
                            $guardadoCategoriaVideojuego = $this -> guardarVideojuegoCategoria($categorias);
                            /*Comprobar si el videojuego cateogoria y videojuego usuario han sido guardados exitosamente*/
                            if($guardadoCategoriaVideojuego){
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir("guardarvideojuegoacierto", "El videojuego ha sido guardado con exito", "?controller=UsuarioController&action=videojuegos");
                            /*De lo contrario*/    
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Ayudas::crearSesionYRedirigir("guardarvideojuegoerror", "Ha ocurrido un error al guardar el videojuego", "?controller=VideojuegoController&action=crear");
                            }
                        /*De lo contrario*/       
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Ayudas::crearSesionYRedirigir("guardarvideojuegoerror", "Ha ocurrido un error al guardar el videojuego", "?controller=VideojuegoController&action=crear");
                        }
                    /*De lo contrario*/      
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir("guardarvideojuegoerror", "La imagen debe ser formato imagen", "?controller=VideojuegoController&action=crear");
                    }
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir("guardarvideojuegoerror", "Ha ocurrido un error al guardar el videojuego", "?controller=VideojuegoController&action=crear");
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para editar una consola en la base de datos
        */

        public function editarVideojuego($id){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Crear el objeto*/
            $idVideojuego = $videojuego -> setId($id);
            /*Retornar el resultado*/
            return $idVideojuego;
        }

        /*
        Funcion para editar un videojuego
        */

        public function editar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idVideojuego = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idVideojuego){
                    /*Llamar funcion para editar el videojuego*/
                    $videojuego = $this -> editarVideojuego($idVideojuego);
                    /*Llamar funcion que trae un videojuego*/
                    $videojuegoUnico = $videojuego -> traerUno();
                    /*Comprobar si el videojuego ha sido traido*/
                    if($videojuegoUnico){
                        /*Incluir la vista*/
                        require_once "Vistas/Videojuego/Actualizar.html";
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
        Funcion para acutalizar el videojuego
        */
            
        public function actualizarVideojuego($id, $precio, $stock, $descripcion){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Crear el objeto*/
            $videojuego -> setId($id);
            $videojuego -> setPrecio($precio);
            $videojuego -> setStock($stock);
            $videojuego -> setDescripcion($descripcion);
            /*Ejecutar la consulta*/
            $actualizado = $videojuego -> actualizar();
            /*Retornar el resultado*/
            return $actualizado;
        }

        /*
        Funcion para actualizar un usuario
        */

        public function actualizar(){
            /*Comprobar si los datos están llegando*/
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $id = isset($_GET['id']) ? $_GET['id'] : false;
                $precio = isset($_POST['precio']) ? $_POST['precio'] : false;
                $stock = isset($_POST['stock']) ? $_POST['stock'] : false;
                $descripcion = isset($_POST['descripcionvid']) ? $_POST['descripcionvid'] : false;
                /*Si los datos existen*/
                if($id && $precio && $stock && $descripcion){
                    /*Llamar la funcion de actualizar videojuego*/
                    $actualizado = $this -> actualizarVideojuego($id, $precio, $stock, $descripcion);
                    /*Comprobar si el videojuego ha sido actualizado*/
                    if($actualizado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarvideojuegoacierto', "Videojuego actualizado con exito", '?controller=UsuarioController&action=videojuegos');
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('actualizarvideojuegosugerencia', "Agrega nuevos datos", '?controller=VideojuegoController&action=editar&id='.$id);
                    }
                /*De lo contrario*/       
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('actualizarvideojuegoerror', "Ha ocurrido un error al actualizar el videojuego", '?controller=VideojuegoController&action=inicio');
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir('actualizarvideojuegoerror', "Ha ocurrido un error al actualizar el videojuego", '?controller=VideojuegoController&action=inicio');
            }
        }

        /*
        Funcion para eliminar un usuario
        */

        public function eliminarVideojuego($idVideojuego){
            /*Instanciar el objeto*/
            $videojuego = new Videojuego();
            /*Crear el objeto*/
            $videojuego -> setId($idVideojuego);
            $videojuego -> setActivo(FALSE);
            /*Ejecutar la consulta*/
            $eliminado = $videojuego -> eliminar();
            /*Retornar el resultado*/
            return $eliminado;
        }

        /*
        Funcion para eliminar un usuario desde el administrador
        */

        public function eliminar(){
            /*Comprobar si el dato esta llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $idVideojuego = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($idVideojuego){
                    /*Llamar la funcion que elimina el videojuego*/
                    $eliminado = $this -> eliminarVideojuego($idVideojuego);
                    /*Comprobar si el videojuego ha sido eliminado*/
                    if($eliminado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarvideojuegoacierto', "El videojuego ha sido eliminado exitosamente", '?controller=UsuarioController&action=videojuegos');
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Ayudas::crearSesionYRedirigir('eliminarvideojuegoerror', "El videojuego no ha sido eliminado exitosamente", '?controller=UsuarioController&action=videojuegos');
                    }
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Ayudas::crearSesionYRedirigir('eliminarvideojuegoerror', "Ha ocurrido un error al eliminar el videojuego", '?controller=UsuarioController&action=videojuegos');
                }
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para listar todos los videojuegos en la pantalla de inicio
        */

        public function todos(){
            /*Instaciar el objeto*/
            $usuario = new Usuario();
            /*Comprobar si existe la sesion del usuario logueado*/
            if(isset($_SESSION['loginexitoso'])){
                /*Construir objeto*/
                $usuario -> setId($_SESSION['loginexitoso'] -> id);
            }
            /*Traer todos los videojuegos creados*/
            $listadoTodos = $usuario -> listarTodos();
            /*Comprobar si se ha traido la lista de videojuegos*/
            if($listadoTodos){
                /*Incluir la vista*/
                require_once 'Vistas/Videojuego/Todos.html';
            /*De lo contrario*/       
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Ayudas::crearSesionYRedirigir("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*
        Funcion para filtrar los videojuegos
        */

        public function aplicarFiltro($categoria, $consola, $uso, $minimo, $maximo){
            /*Instaciar el objeto*/
            $usuario = new Usuario();
            /*Comprobar si existe la sesion de usuario*/
            if(isset($_SESSION['loginexitoso'])){
                /*Constuir el objeto*/
                $usuario -> setId($_SESSION['loginexitoso'] -> id);
            }
            /*Traer los datos de la consulta*/
            $listadoFiltro = $usuario -> filtro($categoria, $consola, $uso, $minimo, $maximo);
            /*Retornar el resultado*/
            return $listadoFiltro;
        }

        /*
        Funcion para aplicar un filtro a los videojuegos
        */

        public function filtro(){
            /*Comprobar si los datos estan llegando*/
            if(isset($_POST)){
                /*Comprobar si los datos existen*/
                $consola = isset($_POST['consolavid']) ? $_POST['consolavid'] : false;
                $uso = isset($_POST['usovid']) ? $_POST['usovid'] : false;
                $categoria = isset($_POST['categoriavid']) ? $_POST['categoriavid'] : false;
                $minimo = isset($_POST['minimo']) ? $_POST['minimo'] : false;
                $maximo = isset($_POST['maximo']) ? $_POST['maximo'] : false;
                /*Si los datos existen*/
                if($consola && $uso && $categoria){
                    /*Llamar funcion que aplica el filtro a los videojuegos*/
                    $listadoFiltro = $this -> aplicarFiltro($categoria, $consola, $uso, $minimo, $maximo);
                    /*Comprobar si ha llegado la lista del filtro*/
                    if($listadoFiltro){
                        /*Incluir la vista*/
                        require_once 'Vistas/Videojuego/Filtro.html';
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

    }

?>