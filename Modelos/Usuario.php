<?php

    /*
    Clase modelo de usuario
    */

    class Usuario{

        private $id;
        private $activo;
        private $nombre;
        private $apellido;
        private $fechanacimiento;
        private $numerotelefono;
        private $correo;
        private $clave;
        private $departamento;
        private $municipio;
        private $foto;
        private $fecharegistro;
        private $fechaLimiteRecuperarCuenta;
        private $db;

        /*
        Funcion constructor
        */

        public function __construct(){
            /*Llamar conexion a la base de datos*/
            $this -> db = BaseDeDatos::connect();
        }

        /*
        Funcion getter de id
        */

        public function getId(){
            /*Retornar el resultado*/
            return $this->id;
        }

        /*
        Funcion setter de id
        */

        public function setId($id){
            /*Llamar parametro*/
            $this->id = $id;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de activo
        */

        public function getActivo(){
            /*Retornar el resultado*/
            return $this->activo;
        }

        /*
        Funcion setter de activo
        */

        public function setActivo($activo){
            /*Llamar parametro*/
            $this->activo = $activo;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de nombre
        */

        public function getNombre(){
            /*Retornar el resultado*/
            return $this->nombre;
        }

        /*
        Funcion setter de nombre
        */

        public function setNombre($nombre){
            /*Llamar parametro*/
            $this->nombre = $nombre;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de apellido
        */

        public function getApellido(){
            /*Retornar el resultado*/
            return $this->apellido;
        }

        /*
        Funcion setter de apellido
        */

        public function setApellido($apellido){
            /*Llamar parametro*/
            $this->apellido = $apellido;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de fecha de nacimiento
        */

        public function getFechanacimiento(){
            /*Retornar el resultado*/
            return $this->fechanacimiento;
        }

        /*
        Funcion setter de fecha de nacimiento
        */

        public function setFechanacimiento($fechanacimiento){
            /*Llamar parametro*/
            $this->fechanacimiento = $fechanacimiento;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de numero de telefono
        */

        public function getNumerotelefono(){
            /*Retornar el resultado*/
            return $this->numerotelefono;
        }

        /*
        Funcion setter de numero de telefono
        */

        public function setNumerotelefono($numerotelefono){
            /*Llamar parametro*/
            $this->numerotelefono = $numerotelefono;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de correo
        */

        public function getCorreo(){
            /*Retornar el resultado*/
            return $this->correo;
        }

        /*
        Funcion setter de correo
        */

        public function setCorreo($correo){
            /*Llamar parametro*/
            $this->correo = $correo;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de clave
        */

        public function getClave(){
            /*Retornar el resultado*/
            return $this->clave;
        }

        /*
        Funcion setter de clave
        */

        public function setClave($clave){
            /*Llamar parametro*/
            $this->clave = $clave;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de foto
        */

        public function getFoto(){
            /*Retornar el resultado*/
            return $this->foto;
        }

        /*
        Funcion setter de foto
        */

        public function setFoto($foto){
            /*Llamar parametro*/
            $this->foto = $foto;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de fecha de registro
        */

        public function getFechaRegistro(){
            /*Retornar el resultado*/
            return $this->fecharegistro;
        }

        /*
        Funcion setter de fecha de registro
        */

        public function setFechaRegistro($fecharegistro){
            /*Llamar parametro*/
            $this->fecharegistro = $fecharegistro;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de fecha limite
        */

        public function getFechaLimiteRecuperarCuenta(){
            /*Retornar el resultado*/
            return $this->fechaLimiteRecuperarCuenta;
        }

        /*
        Funcion setter de fecha limite
        */

        public function setFechaLimiteRecuperarCuenta($fechaLimiteRecuperarCuenta){
            /*Llamar parametro*/
            $this->fechaLimiteRecuperarCuenta = $fechaLimiteRecuperarCuenta;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de departamento
        */

        public function getDepartamento(){
            /*Retornar el resultado*/
            return $this->departamento;
        }

        /*
        Funcion setter de departamento
        */

        public function setDepartamento($departamento){
            /*Llamar parametro*/
            $this->departamento = $departamento;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de municipio
        */

        public function getMunicipio(){
            /*Retornar el resultado*/
            return $this->municipio;
        }

        /*
        Funcion setter de municipio
        */

        public function setMunicipio($municipio){
            /*Llamar parametro*/
            $this->municipio = $municipio;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para comprobar si ya se ha creado el usuario con anterioridad
        */

        public function comprobarUsuarioUnico($correoActual){
            /*Comprobar si se quiere comprobar si es unico atravez del id*/
            if($this -> getId() != null){
                /*Construir la consulta*/
                $consulta = "SELECT activo FROM usuarios WHERE id = {$this -> getId()}";
            /*Comprobar si se quiere comprobar si es unico atravez del correo*/
            }elseif($this -> getCorreo() != null){
                /*Construir la consulta*/
                $consulta = "SELECT activo FROM usuarios WHERE correo = '{$this -> getCorreo()}' AND correo != '{$correoActual}'";
            }
            /*Llamar la funcion que ejecuta la consulta*/
            $usuario = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $usuario -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para recuperar el usuario eliminado
        */

        public function recuperarUsuario(){
            /*Construir la consulta*/
            $consulta = "UPDATE usuarios SET activo = 1, fechaLimiteRecuperarCuenta = '{$this -> getFechaLimiteRecuperarCuenta()}' WHERE correo = '{$this -> getCorreo()}'";
            /*Llamar la funcion que ejecuta la consulta*/
            $recuperado = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $bandera = false;
            /*Comprobar si la consulta fue exitosa*/
            if($recuperado){
                /*Cambiar el estado de la variable bandera*/
                $bandera = true;
            }
            /*Retornar el resultado*/
            return $bandera;
        }

        /*
        Funcion para obtener la fecha limite que tiene el usuario para recuperar su cuenta
        */

        public function fechaLimite(){
            /*Construir la consulta*/
            $consulta = "SELECT fechaLimiteRecuperarCuenta FROM usuarios WHERE id = {$this -> getId()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $usuario = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $usuario -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para realizar el registro del usuario en la base de datos
        */

        public function guardar(){
            /*Obtener la clave*/
            $clave = $this -> getClave();
            /*Encriptar la clave*/
            $claveSegura = password_hash($clave, PASSWORD_BCRYPT, ['cost'=>4]);
            /*Construir la consulta*/
            $consulta = "INSERT INTO usuarios VALUES(NULL, {$this -> getActivo()},
                '{$this -> getNombre()}', '{$this -> getApellido()}', 
                '{$this -> getFechaNacimiento()}', {$this -> getNumeroTelefono()}, 
                '{$this -> getCorreo()}', '{$claveSegura}', 
                '{$this -> getDepartamento()}', '{$this -> getMunicipio()}', 
                '{$this -> getFoto()}', '{$this -> getFechaRegistro()}', '{$this -> getFechaLimiteRecuperarCuenta()}')";
            /*Llamar la funcion que ejecuta la consulta*/
            $registro = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $resultado = false;
            /*Comprobar si la consulta fue exitosa*/
            if($registro){
                /*Cambiar el estado de la variable bandera*/
                $resultado = true;
            }
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para traer la clave
        */

        public function traerClave($correo){
            /*Construir la consulta*/
            $consulta = "SELECT clave FROM usuarios WHERE correo = '$correo'";
            /*Llamar la funcion que ejecuta la consulta*/
            $clave = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $clave -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para realizar el inicio de sesion
        */

        public function login(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM usuarios WHERE correo = '{$this -> getCorreo()}' AND activo = 1";
            /*Llamar la funcion para obtener clave propia*/
            $clave = $this -> traerClave($this -> getCorreo());
            /*Traer clave*/
            $claveAsociada = $clave -> clave;
            /*Establecer una variable bandera*/
            $resultado = false;
            /*Verificar clave*/
            $verificarClave = password_verify($this -> getClave(), $claveAsociada);
            /*Si la clave es correcta*/
            if($verificarClave){
                /*Llamar la funcion que ejecuta la consulta*/
                $login = $this -> db -> query($consulta);
                /*Obtener el resultado*/
                $administrador = $login -> fetch_object();
                /*Comprobar si la consulta fue exitosa*/
                if($administrador){
                    /*Cambiar el estado de la variable bandera*/
                    $resultado = $administrador;
                }
            }
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para listar todos los usuarios
        */

        public function listar(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM usuarios";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para eliminar el usuario
        */

        public function eliminar(){
            /*Construir la consulta*/
            $consulta = "UPDATE usuarios SET activo = '{$this -> getActivo()}', fechaLimiteRecuperarCuenta = '{$this -> getFechaLimiteRecuperarCuenta()}' WHERE id = {$this -> getId()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $eliminado = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $bandera = false;
            /*Comprobar si la consulta fue exitosa*/
            if($eliminado){
                /*Cambiar el estado de la variable bandera*/
                $bandera = true;
            }
            /*Retornar el resultado*/
            return $bandera;
        }

        /*
        Funcion para obtener un usuario
        */

        public function obtenerUno(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM usuarios WHERE id = {$this -> getId()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $categoria = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $categoria -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener un usuario atravez del correo
        */

        public function obtenerIdPorCorreo(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT id, clave FROM usuarios WHERE correo = '{$this -> getCorreo()}'";
            /*Llamar la funcion para obtener clave propia*/
            $clave = $this -> traerClave($this -> getCorreo());
            /*Comprobar si el correo pertenece a un usuario registrado*/
            if($clave != null){
                /*Traer clave*/
                $claveAsociada = $clave -> clave;
                /*Establecer una variable bandera*/
                $resultado = false;
                /*Verificar clave*/
                $verificarClave = password_verify($this -> getClave(), $claveAsociada);
                /*Si la clave es correcta*/
                if($verificarClave){
                    /*Llamar la funcion que ejecuta la consulta*/
                    $login = $this -> db -> query($consulta);
                    /*Obtener el resultado*/
                    $administrador = $login -> fetch_object();
                    /*Comprobar si la consulta fue exitosa*/
                    if($administrador){
                        /*Cambiar el estado de la variable bandera*/
                        $resultado = $administrador;
                    }
                }
                /*Retornar el resultado*/
                return $resultado;
            /*De lo contrario*/
            }else{
                /*Retornar el resultado*/
                return null;
            }
        }

        /*
        Funcion para actualizar el usuario
        */

        public function actualizar(){
            /*Construir la consulta*/
            $consulta = "UPDATE usuarios SET nombre = '{$this -> getNombre()}', apellido = '{$this -> getApellido()}',
                numeroTelefono = '{$this -> getNumeroTelefono()}', correo = '{$this -> getCorreo()}', 
                departamento = '{$this -> getDepartamento()}', 
                municipio = '{$this -> getMunicipio()}' ";
            /*Comprobar si se desea actualizar la foto*/   
            if($this -> getFoto() != null){
                /*Construir la consulta*/
                $consulta .= ",foto = '{$this -> getFoto()}'";
            }
            /*Construir la consulta*/            
            $consulta .= "WHERE id = {$this -> getId()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $actualizado = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $bandera = false;
            /*Comprobar si la consulta fue exitosa y el total de columnas afectadas se altero llamando la ejecucion de la consulta*/
            if($actualizado && mysqli_affected_rows($this -> db) > 0){
                /*Cambiar el estado de la variable bandera*/
                $bandera = true;
            }
            /*Retornar el resultado*/
            return $bandera;
        }

        /*
        Funcion para actualizar la clave
        */

        public function actualizarClave(){
            /*Obtener la clave*/
            $clave = $this -> getClave();
            /*Encriptar la clave*/
            $claveSegura = password_hash($clave, PASSWORD_BCRYPT, ['cost'=>4]);
            /*Construir la consulta*/
            $consulta = "UPDATE usuarios SET clave = '{$claveSegura}' 
                WHERE id = {$this -> getId()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $actualizado = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $bandera = false;
            /*Comprobar si la consulta fue exitosa*/
            if($actualizado){
                /*Cambiar el estado de la variable bandera*/
                $bandera = true;
            }
            /*Retornar el resultado*/
            return $bandera;
        }

        /*
        Funcion para listar los usuarios con su informacion
        */

        public function obtenerInformacionUsuario(){
            /*Construir la consulta*/
            $consulta = "SELECT u.id AS 'idUsuario', u.nombre AS 'nombreUsuario', u.apellido AS 'apellidoUsuario', u.fechaRegistro AS 'fechaUsuario', u.departamento AS 'departamentoUsuario', u.municipio AS 'municipioUsuario', v.nombre AS 'nombreVideojuego', v.precio AS 'precioVideojuego', v.foto AS 'fotoVideojuego', v.id AS 'idVideojuego'
                FROM Videojuegos v
                INNER JOIN Usuarios u ON u.id = v.idUsuario
                WHERE v.idUsuario = {$this->getId()} AND v.activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultados = $this->db->query($consulta);
            /*Array para almacenar la información del usuario*/
            $informacionUsuario = array();
            /*Mientras hayan usuarios disponibles para recorrer*/
            while ($fila = $resultados->fetch_object()) {
                /*Comprobar si no existe la informacion del usuario*/
                if(!isset($informacionUsuario['usuario'])){
                    /*Crear array con informacion del usuario*/
                    $informacionUsuario['usuario'] = array(
                        'idUsuario' => $fila->idUsuario,
                        'nombreUsuario' => $fila->nombreUsuario,
                        'apellidoUsuario' => $fila->apellidoUsuario,
                        'fechaUsuario' => $fila->fechaUsuario,
                        'departamentoUsuario' => $fila->departamentoUsuario,
                        'municipioUsuario' => $fila->municipioUsuario,
                        /*Inicializar un array para almacenar los videojuegos del usuario*/
                        'videojuegos' => array() 
                    );
                }
                /*Almacenar la información del usuario en el array de usuario y videojuego*/
                $informacionUsuario['usuario']['videojuegos'][] = array(
                    'idVideojuego' => $fila->idVideojuego,
                    'nombreVideojuego' => $fila->nombreVideojuego,
                    'precioVideojuego' => $fila->precioVideojuego,
                    'fotoVideojuego' => $fila->fotoVideojuego
                );
            }
            /*Retornar el resultado*/
            return $informacionUsuario;
        } 

        /*
        Funcion para obtener el total de videojuego publicados por parte del usuario
        */

        public function obtenerTotalPublicados(){
            /*Construir la consulta*/
            $consulta = "SELECT COUNT(idUsuario) AS 'videojuegosPublicados' FROM videojuegos WHERE idUsuario = {$this -> getId()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $total = $this->db->query($consulta);
            /*Obtener el resultado*/
            $resultado = $total -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener el total de videojuego vendidos por parte del usuario
        */

        public function obtenerTotalVendidos(){
            /*Construir la consulta*/
            $consulta = "SELECT SUM(unidades) AS 'videojuegosVendidos' FROM transaccionvideojuego WHERE idtransaccion IN (SELECT id FROM transacciones WHERE idVendedor = {$this -> getId()})";
            /*Llamar la funcion que ejecuta la consulta*/
            $total = $this->db->query($consulta);
            /*Obtener el resultado*/
            $resultado = $total -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener los videojuegos creado por un usuario en concreto
        */

        public function obtenerVideojuegosCreadosPorUsuario(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT v.nombre AS 'nombreVideojuego', v.precio AS 'precioVideojuego', c.nombre AS 'nombreConsola', v.stock AS 'stockVideojuego', v.id AS 'idVideojuego', v.foto AS 'imagenVideojuego'
                FROM Videojuegos v
                INNER JOIN Consolas c ON c.id = v.idConsola
                INNER JOIN Usuarios u ON u.id = v.idUsuario
                WHERE u.id = {$this -> getId()} AND v.activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para listar algunos de los videojuegos, en concreto 6
        */

        public function listarAlgunos(){
            /*Comprobar si el id del usuario que llega es vacio*/
            if($this -> getId() == null){
                /*Construir la consulta*/
                $consulta = "SELECT DISTINCT v.*
                    FROM videojuegos v
                    INNER JOIN usuarios us ON us.id = v.idUsuario
                    INNER JOIN videojuegocategoria vc ON vc.idVideojuego = v.id
                    INNER JOIN categorias ca ON ca.id = vc.idCategoria
                    INNER JOIN consolas c ON c.id = v.idConsola
                    INNER JOIN usos u ON u.id = v.idUso
                    WHERE u.activo = 1 AND c.activo = 1 AND v.activo = 1 AND us.activo = 1 AND ca.activo = 1 ";
            /*De lo contrario*/        
            }else{
                /*Construir la consulta*/
                $consulta = "SELECT DISTINCT v.*
                    FROM videojuegos v
                    INNER JOIN usuarios us ON us.id = v.idUsuario
                    INNER JOIN videojuegocategoria vc ON vc.idVideojuego = v.id
                    INNER JOIN categorias ca ON ca.id = vc.idCategoria
                    INNER JOIN consolas c ON c.id = v.idConsola
                    INNER JOIN usos u ON u.id = v.idUso
                    WHERE u.activo = 1 AND c.activo = 1 AND c.activo = 1 AND v.activo = 1 AND us.activo = 1 AND ca.activo = 1 AND {$this -> getId()} != v.idUsuario
                    EXCEPT
                    SELECT v.*
                    FROM bloqueos b
                    INNER JOIN usuarios u ON u.id = b.idBloqueado
                    INNER JOIN videojuegos v ON v.idUsuario = u.id
                    AND b.idBloqueador = {$this -> getId()} AND b.activo = 1 ";
            }
            /*Construir la consulta*/
            $consulta .= "ORDER BY RAND() LIMIT 6";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener el videojuego buscado, si existe
        */

        public function buscar($nombreVideojuego){
            /*Comprobar si el id del usuario que llega es vacio*/
            if($this -> getId() == null){
                /*Construir la consulta*/
                $consulta = "SELECT DISTINCT v.* 
                    FROM videojuegos v
                    INNER JOIN usuarios us ON us.id = v.idUsuario
                    INNER JOIN videojuegocategoria vc ON vc.idVideojuego = v.id
                    INNER JOIN categorias ca ON ca.id = vc.idCategoria
                    INNER JOIN consolas c ON c.id = v.idConsola
                    INNER JOIN usos u ON u.id = v.idUso
                    WHERE v.nombre LIKE '%{$nombreVideojuego}%' AND v.activo = 1 AND us.activo = 1 AND ca.activo = 1 AND c.activo = 1 AND u.activo = 1 
                    ORDER BY v.nombre DESC";
            /*De lo contrario*/  
            }else{
                /*Construir la consulta*/
                $consulta = "SELECT DISTINCT v.* 
                    FROM videojuegos v
                    INNER JOIN usuarios us ON us.id = v.idUsuario
                    INNER JOIN videojuegocategoria vc ON vc.idVideojuego = v.id
                    INNER JOIN categorias ca ON ca.id = vc.idCategoria
                    INNER JOIN consolas c ON c.id = v.idConsola
                    INNER JOIN usos u ON u.id = v.idUso
                    WHERE v.nombre LIKE '%{$nombreVideojuego}%' AND v.activo = 1 AND us.activo = 1 AND us.activo = 1 AND ca.activo = 1 AND c.activo = 1 AND u.activo = 1 AND {$this -> getId()} != v.idUsuario
                    EXCEPT
                    SELECT v.*
                    FROM bloqueos b
                    INNER JOIN usuarios u ON u.id = b.idBloqueado
                    INNER JOIN videojuegos v ON v.idUsuario = u.id 
                    AND b.idBloqueador = {$this -> getId()} AND b.activo = 1 
                    ORDER BY nombre DESC";
            }
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para agregar un filtro de busqueda a los videojuegos
        */

        public function filtro($idCategoria, $idConsola, $idUso, $minimo, $maximo){
            /*Comprobar si el id del usuario que llega es vacio*/
            if($this -> getId() == null){
                /*Construir la consulta*/
                $consulta = "SELECT DISTINCT v.* 
                FROM videojuegocategoria cv
                INNER JOIN videojuegos v ON v.id = cv.idVideojuego
                INNER JOIN usuarios us ON us.id = v.idUsuario
                INNER JOIN categorias c ON c.id = cv.idCategoria 
                INNER JOIN consolas co ON co.id = v.idConsola
                INNER JOIN usos u ON u.id = v.idUso
                WHERE v.activo = 1 AND us.activo = 1 AND c.activo = 1";
            /*De lo contrario*/  
            }else{
                /*Construir la consulta*/
                $consulta = "SELECT DISTINCT v.* 
                FROM videojuegocategoria cv
                INNER JOIN videojuegos v ON v.id = cv.idVideojuego
                INNER JOIN usuarios us ON us.id = v.idUsuario
                INNER JOIN categorias c ON c.id = cv.idCategoria 
                INNER JOIN consolas co ON co.id = v.idConsola
                INNER JOIN usos u ON u.id = v.idUso
                WHERE v.activo = 1 AND us.activo = 1 AND c.activo = 1 AND co.activo = 1 AND u.activo = 1 AND {$this -> getId()} != v.idUsuario";
            }
            /*Crear array para almacenar las condiciones de filtro*/
            $condiciones = [];
            /*Comprobar si la consola llega como opcion a filtrar*/
            if($idConsola != 'null'){
                /*Agregar al array de filtro la condicion*/
                $condiciones[] = "v.idConsola = {$idConsola}";
            }
            /*Comprobar si el uso llega como opcion a filtrar*/
            if($idUso != 'null'){
                /*Agregar al array de filtro la condicion*/
                $condiciones[] = "v.idUso = {$idUso}";
            }
            /*Comprobar si el precio minimo llega como opcion a filtrar*/
            if($minimo != ''){
                /*Agregar al array de filtro la condicion*/
                $condiciones[] = "v.precio >= {$minimo}";
            }
            /*Comprobar si el precio maximo llega como opcion a filtrar*/
            if($maximo != ''){
                /*Agregar al array de filtro la condicion*/
                $condiciones[] = "v.precio <= {$maximo}";
            }
            /*Comprobar si la cateogoria llega como opcion a filtrar*/
            if($idCategoria != 'null'){
                /*Agregar al array de filtro la condicion*/
                $condiciones[] = "cv.idCategoria = {$idCategoria}";
            }
            /*Comprobar si el array de condiciones llega con datos*/
            if (!empty($condiciones)) {
                /*Agregar las condiciones del filtro*/
                $consulta .= " AND " . implode(" AND ", $condiciones);
            }
            /*Comprobar si el usuario ha sido logueado*/
            if($this -> getId() != null){
                $consulta .= " EXCEPT
                    SELECT v.*
                    FROM bloqueos b
                    INNER JOIN usuarios u ON u.id = b.idBloqueado
                    INNER JOIN videojuegos v ON v.idUsuario = u.id 
                    AND b.idBloqueador = {$this -> getId()} AND b.activo = 1";
            }
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this->db->query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para listar todos los videojuegos
        */

        public function listarTodos(){
            /*Comprobar si el id del usuario que llega es vacio*/
            if($this -> getId() == null){
                /*Construir la consulta*/
                $consulta = "SELECT DISTINCT v.*
                    FROM videojuegos v
                    INNER JOIN usuarios us ON us.id = v.idUsuario
                    INNER JOIN videojuegocategoria vc ON vc.idVideojuego = v.id
                    INNER JOIN categorias ca ON ca.id = vc.idCategoria
                    INNER JOIN consolas c ON c.id = v.idConsola
                    INNER JOIN usos u ON u.id = v.idUso
                    WHERE u.activo = 1 AND c.activo = 1 AND v.activo = 1 AND us.activo = 1 AND ca.activo = 1 ";
            /*De lo contrario*/ 
            }else{
                /*Construir la consulta*/
                $consulta = "SELECT DISTINCT v.*
                    FROM videojuegos v
                    INNER JOIN usuarios us ON us.id = v.idUsuario
                    INNER JOIN videojuegocategoria vc ON vc.idVideojuego = v.id
                    INNER JOIN categorias ca ON ca.id = vc.idCategoria
                    INNER JOIN consolas c ON c.id = v.idConsola
                    INNER JOIN usos u ON u.id = v.idUso
                    WHERE u.activo = 1 AND c.activo = 1 AND v.activo = 1 AND us.activo = 1 AND ca.activo = 1 AND {$this -> getId()} != v.idUsuario
                    EXCEPT
                    SELECT v.*
                    FROM bloqueos b
                    INNER JOIN usuarios u ON u.id = b.idBloqueado
                    INNER JOIN videojuegos v ON v.idUsuario = u.id
                    AND b.idBloqueador = {$this -> getId()} AND b.activo = 1";
            }
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener el usuario buscado, si existe
        */

        public function buscarUsuario(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * 
                FROM usuarios
                WHERE nombre LIKE '%{$this -> getNombre()}%' AND activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para obtener el ultimo envio registrado
        */

        public function obtenerEnvios(){
            /*Construir la consulta*/
            $consulta = "SELECT * FROM Envios e WHERE idUsuario = {$this -> getId()} AND activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para obtener los compradores mas destacados
        */

        public function compradoresDestacados(){
            /*Construir la consulta*/
            $consulta = "SELECT count(idComprador) 'comprador', sum(tv.unidades) AS 'unidades', nombre AS 'nombreComprador', apellido AS 'apellidoComprador', fechaRegistro AS 'fechaRegistroComprador', foto AS 'fotoComprador', fechaNacimiento AS 'fechaNacimiento'
                FROM transacciones t
                INNER JOIN usuarios u ON u.id = t.idComprador
                INNER JOIN transaccionvideojuego tv ON t.id = tv.idTransaccion
                WHERE u.activo = 1
                GROUP BY idComprador
                ORDER BY comprador DESC, unidades DESC
                LIMIT 5";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener los vendedores mas destacados
        */

        public function vendedoresDestacados(){
            /*Construir la consulta*/
            $consulta = "SELECT count(idVendedor) AS 'vendedor', count(idVideojuego) AS 'videojuegos', sum(unidades) AS 'cantidad', nombre AS 'nombreVendedor', apellido AS 'apellidoVendedor', fechaRegistro AS 'fechaRegistroVendedor', foto AS 'fotoVendedor', fechaNacimiento AS 'fechaNacimiento'
                FROM transaccionvideojuego tv
                INNER JOIN usuarios u ON tv.idVendedor = u.id
                WHERE u.activo = 1
                GROUP BY idVendedor
                ORDER BY vendedor DESC, cantidad DESC
                LIMIT 5";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener los usuarios nuevos
        */

        public function usuariosNuevos(){
            /*Construir la consulta*/
            $consulta = "SELECT count(id) AS 'usuario', nombre AS 'nombreUsuario', apellido AS 'apellidoUsuario', fechaRegistro AS 'fechaRegistroUsuario', foto AS 'fotoUsuario', fechaNacimiento AS 'fechaNacimiento'
                FROM usuarios u
                WHERE u.activo = 1
                AND DATEDIFF(CURDATE(), fechaRegistro) <= 7 
                GROUP BY id
                ORDER BY usuario DESC
                LIMIT 5";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener los compradores mas destacados
        */

        public function usuariosComentadores(){
            /*Construir la consulta*/
            $consulta = "SELECT count(idUsuario) 'comentador', nombre AS 'nombreComprador', apellido AS 'apellidoComprador', fechaRegistro AS 'fechaRegistroComprador', foto AS 'fotoComprador', fechaNacimiento AS 'fechaNacimiento'
                FROM comentariousuariovideojuego cuf
                INNER JOIN usuarios u ON u.id = cuf.idUsuario
                WHERE u.activo = 1
                AND cuf.activo = 1
                GROUP BY idUsuario
                ORDER BY comentador DESC
                LIMIT 5";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

    }

?>