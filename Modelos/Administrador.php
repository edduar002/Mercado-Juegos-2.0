<?php

    /*
    Clase modelo de administrador
    */

    class Administrador{

        private $id;
        private $activo;
        private $nombre;
        private $apellido;
        private $fechanacimiento;
        private $numerotelefono;
        private $correo;
        private $clave;
        private $foto;
        private $fecharegistro;
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

        public function getFecharegistro(){
            /*Retornar el resultado*/
            return $this->fecharegistro;
        }

        /*
        Funcion setter de fecha de registro
        */

        public function setFecharegistro($fecharegistro){
            /*Llamar parametro*/
            $this->fecharegistro = $fecharegistro;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para comprobar si ya se ha creado el administrador con anterioridad
        */

        public function comprobarAdministradorUnico($correoActual){
            /*Comprobar si se quiere comprobar si es unico atravez del id*/
            if($this -> getId() != null){
                /*Construir la consulta*/
                $consulta = "SELECT activo FROM administradores WHERE id = {$this -> getId()}";
            /*Comprobar si se quiere comprobar si es unico atravez del correo*/
            }elseif($this -> getCorreo() != null){
                /*Construir la consulta*/
                $consulta = "SELECT activo FROM administradores WHERE correo = '{$this -> getCorreo()}' AND correo != '{$correoActual}'";
            }
            /*Llamar la funcion que ejecuta la consulta*/
            $administrador = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $administrador -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para listar todos los administradores
        */

        public function listar(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM administradores";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para recuperar el administrador eliminado
        */

        public function recuperarAdministrador(){
            /*Comprobar si se quiere recuperar atravez del id*/
            if($this -> getId() != null){
                /*Construir la consulta*/
                $consulta = "UPDATE administradores SET activo = 1 WHERE id = {$this -> getId()}";
            /*Comprobar si se quiere recuperar atravez del correo*/
            }elseif($this -> getCorreo() != null){
                /*Construir la consulta*/
                $consulta = "UPDATE administradores SET activo = 1 WHERE correo = '{$this -> getCorreo()}'";
            }
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
        Funcion para realizar el registro del administrador en la base de datos
        */

        public function guardar(){
            /*Obtener la clave*/
            $clave = $this -> getClave();
            /*Encriptar la clave*/
            $claveSegura = password_hash($clave, PASSWORD_BCRYPT, ['cost'=>4]);
            /*Construir la consulta*/
            $consulta = "INSERT INTO administradores VALUES(NULL, {$this -> getActivo()},
                '{$this -> getNombre()}', '{$this -> getApellido()}', 
                '{$this -> getFechaNacimiento()}', {$this -> getNumeroTelefono()}, 
                '{$this -> getCorreo()}', '{$claveSegura}', 
                '{$this -> getFoto()}', '{$this -> getFechaRegistro()}')";
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
            $consulta = "SELECT clave FROM administradores WHERE correo = '$correo' AND activo = 1";
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
            $consulta = "SELECT DISTINCT * FROM administradores WHERE correo = '{$this -> getCorreo()}' AND activo = 1";
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
                $usuario = $login -> fetch_object();
                /*Comprobar si la consulta fue exitosa*/
                if($usuario){
                    /*Cambiar el estado de la variable bandera*/
                    $resultado = $usuario;
                }
            }
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener el administrador buscado, si existe
        */

        public function buscar(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * 
                FROM administradores
                WHERE nombre LIKE '%{$this -> getNombre()}%' AND activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para eliminar el administrador desde el administrador
        */

        public function eliminar(){
            /*Construir la consulta*/
            $consulta = "UPDATE administradores SET activo = '{$this -> getActivo()}' WHERE id = {$this -> getId()}";
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
        Funcion para actualizar el administrador
        */

        public function actualizar(){
            /*Construir la consulta*/
            $consulta = "UPDATE administradores SET nombre = '{$this -> getNombre()}', apellido = '{$this -> getApellido()}', 
                numeroTelefono = '{$this -> getNumeroTelefono()}', correo = '{$this -> getCorreo()}' ";
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
        Funcion para obtener un administrador
        */

        public function obtenerUno(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM administradores WHERE id = {$this -> getId()} AND activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $categoria = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $resultado = $categoria -> fetch_object();
            /*Retornar el resultado*/
            return $resultado;
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
            $consulta = "UPDATE administradores SET clave = '{$claveSegura}' 
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

    }

?>