<?php

    /*
    Clase modelo de videojuego
    */

    class Videojuego{

        private $id;
        private $idUsuario;
        private $activo;
        private $idConsola;
        private $idUso;
        private $nombre;
        private $precio;
        private $descripcion;
        private $foto;
        private $fechaCreacion;
        private $stock;
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
        Funcion getter de id usuario
        */

        public function getIdUsuario(){
            /*Retornar el resultado*/
            return $this->idUsuario;
        }

        /*
        Funcion setter de id de usuario
        */

        public function setIdUsuario($idUsuario){
            /*Llamar parametro*/
            $this->idUsuario = $idUsuario;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de id de consola
        */

        public function getIdConsola(){
            /*Retornar el resultado*/
            return $this->idConsola;
        }

        /*
        Funcion setter de id de consola
        */

        public function setIdConsola($idConsola){
            /*Llamar parametro*/
            $this->idConsola = $idConsola;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de id de uso
        */

        public function getIdUso(){
            /*Retornar el resultado*/
            return $this->idUso;
        }

        /*
        Funcion setter de id de uso
        */

        public function setIdUso($idUso){
            /*Llamar parametro*/
            $this->idUso = $idUso;
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
        Funcion getter de precio
        */

        public function getPrecio(){
            /*Retornar el resultado*/
            return $this->precio;
        }

        /*
        Funcion setter de precio
        */

        public function setPrecio($precio){
            /*Llamar parametro*/
            $this->precio = $precio;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de descripcion
        */

        public function getDescripcion(){
            /*Retornar el resultado*/
            return $this->descripcion;
        }

        /*
        Funcion setter de descripcion
        */

        public function setDescripcion($descripcion){
            /*Llamar parametro*/
            $this->descripcion = $descripcion;
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
        Funcion getter de fecha de creacion
        */

        public function getFechaCreacion(){
            /*Retornar el resultado*/
            return $this->fechaCreacion;
        }

        /*
        Funcion setter de fecha de creacion
        */

        public function setFechaCreacion($fechaCreacion){
            /*Llamar parametro*/
            $this->fechaCreacion = $fechaCreacion;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de stock
        */

        public function getStock(){
            /*Retornar el resultado*/
            return $this->stock;
        }

        /*
        Funcion setter de stock
        */

        public function setStock($stock){
            /*Llamar parametro*/
            $this->stock = $stock;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para realizar el registro del videojuego en la base de datos
        */

        public function guardar(){
            /*Construir la consulta*/
            $consulta = "INSERT INTO videojuegos VALUES(NULL, {$this -> getActivo()}, {$this -> getIdUsuario()}, {$this -> getIdConsola()}, 
                {$this -> getIdUso()}, '{$this -> getNombre()}', 
                {$this -> getPrecio()}, '{$this -> getDescripcion()}', 
                '{$this -> getFoto()}', '{$this -> getFechaCreacion()}', 
                {$this -> getStock()})";
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
        Funcion para actualizar el videojuego
        */

        public function actualizar(){
            /*Construir la consulta*/
            $consulta = "UPDATE videojuegos SET precio = {$this -> getPrecio()}, stock = {$this -> getStock()}, descripcion = '{$this -> getDescripcion()}'  
                WHERE id = {$this -> getId()}";
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
        Funcion para traer un videojuego
        */

        public function traerUno(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT v.id AS 'idVideojuego', v.nombre AS 'nombreVideojuego', v.precio AS 'precioVideojuego', v.stock AS 'stockVideojuego', u.id AS 'idVendedor', us.nombre AS 'nombreUso', c.nombre 'categoriaNombre', v.foto AS 'imagenVideojuego', v.descripcion AS 'descripcionVideojuego', co.nombre AS 'nombreConsola', v.fechaCreacion AS 'fechaCreacionVideojuego' 
                FROM Videojuegos v
                INNER JOIN Usuarios u ON u.id = v.idUsuario 
                INNER JOIN Usos us ON us.id = v.idUso
                INNER JOIN Consolas co ON co.id = v.idConsola
                INNER JOIN VideojuegoCategoria cv ON cv.idVideojuego = v.id
                INNER JOIN Categorias c ON cv.idCategoria = c.id
                WHERE v.id = {$this -> getId()} AND v.activo = 1 AND c.activo = 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultados = $this->db->query($consulta);
            /*Array para almacenar la información del videojuego*/
            $informacionVideojuego = array();
            /*Mientras hayan videojuegos disponibles para recorrer*/
            while ($fila = $resultados->fetch_object()) {
                /*Comprobar si no existe la informacion del videojuego*/
                if(!isset($informacionVideojuego['videojuego'])){
                    /*Crear array con informacion del videojuego*/
                    $informacionVideojuego['videojuego'] = array(
                        'idVideojuego' => $fila->idVideojuego,
                        'nombreVideojuego' => $fila->nombreVideojuego,
                        'precioVideojuego' => $fila->precioVideojuego,
                        'nombreConsola' => $fila->nombreConsola,
                        'descripcionVideojuego' => $fila->descripcionVideojuego,
                        'imagenVideojuego' => $fila->imagenVideojuego,
                        'stockVideojuego' => $fila->stockVideojuego,
                        'idVendedor' => $fila->idVendedor,
                        'nombreUso' => $fila->nombreUso,
                        'fechaCreacionVideojuego' => $fila->fechaCreacionVideojuego,
                        /*Inicializar un array para almacenar los videojuegos*/
                        'categorias' => array()
                    );
                }
                /*Almacenar la información de la categoria en el array de videojuego y categoria*/
                $informacionVideojuego['videojuego']['categorias'][] = array(
                    'categoriaNombre' => $fila->categoriaNombre,
                );
            }
            /*Retornar el resultado*/
            return $informacionVideojuego;     
        }

        /*
        Funcion para obtener el ultimo videojuego registrado
        */

        public function ultimo(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT id FROM videojuegos ORDER BY id DESC LIMIT 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $ultimo = $resultado -> fetch_object();
            /*Devolver resultado*/
            $ultimoVideojuego = $ultimo -> id;
            /*Retornar el resultado*/
            return $ultimoVideojuego;
        }

        /*
        Funcion para eliminar un videojuego
        */

        public function eliminar(){
            /*Construir la consulta*/
            $consulta = "UPDATE videojuegos SET activo = '{$this -> getActivo()}' WHERE id = {$this -> getId()}";
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
        Funcion para actualizar el stock de un videojuego
        */

        public function actualizarStock(){
            /*Construir la consulta*/
            $consulta = "UPDATE videojuegos SET stock = {$this -> getStock()} WHERE id = {$this -> getId()}";
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
        Funcion para obtener el usuario vendedor
        */

        public function obtenerUsuarioVendedor(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT id AS 'idVendedor', nombre AS 'nombreVendedor', apellido AS 'apellidoVendedor' FROM usuarios WHERE id IN (SELECT idUsuario FROM videojuegos WHERE id = {$this -> getId()})";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $nombre = $resultado -> fetch_object();
            /*Retornar el resultado*/
            return $nombre;
        }

        /*
        Funcion para obtener el usuario dueño del videojuego creado
        */

        public function obtenerUsuarioVideojuego(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT idUsuario FROM videojuegos WHERE id = {$this -> getId()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $usuario = $resultado -> fetch_object();
            /*Retornar el resultado*/
            return $usuario;
        }

        /*
        Funcion para obtener los ultimos videojuegos
        */

        public function videojuegosNuevos(){
            /*Construir la consulta*/
            $consulta = "SELECT v.nombre AS 'nombreVideojuego', v.precio AS 'precioVideojuego', v.foto AS 'fotoVideojuego', v.fechaCreacion AS 'fechaPublicacion', u.nombre AS 'nombreUso', c.nombre AS 'nombreConsola'  
                FROM videojuegos v
                INNER JOIN usos u ON u.id = v.idUso
                INNER JOIN consolas c ON c.id = v.idConsola 
                WHERE DATEDIFF(CURDATE(), fechaCreacion) <= 7 AND v.activo = 1
                ORDER BY fechaCreacion DESC";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener los videojuegos destacados
        */

        public function videojuegosDestacados(){
            /*Construir la consulta*/
            $consulta = "SELECT count(idVideojuego) AS 'vecesComprado', sum(unidades) AS 'unidadesCompradas', v.nombre AS 'nombreVideojuego', v.precio AS 'precioVideojuego', v.foto AS 'fotoVideojuego', u.nombre AS 'nombreUso', c.nombre AS 'nombreConsola' 
                FROM transaccionvideojuego tv
                INNER JOIN videojuegos v ON v.id = tv.idVideojuego
                INNER JOIN usos u ON u.id = v.idUso
                INNER JOIN consolas c ON c.id = v.idConsola 
                WHERE v.activo = 1
                GROUP BY idVideojuego
                ORDER BY vecesComprado DESC
                LIMIT 10";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para ver los videojuegos del vendedor con la mayor cantidad de ventas
        */

        public function mayorVendidos(){
            /*Construir la consulta*/
            $consulta = "SELECT u.id AS 'idUsuario', u.nombre AS 'nombreUsuario', u.apellido AS 'apellidoUsuario', u.fechaRegistro AS 'fechaUsuario', u.departamento AS 'departamentoUsuario', u.municipio AS 'municipioUsuario', v.nombre AS 'nombreVideojuego', v.precio AS 'precioVideojuego', v.foto AS 'fotoVideojuego', v.id AS 'idVideojuego'
                FROM Videojuegos v
                INNER JOIN Usuarios u ON u.id = v.idUsuario
                WHERE v.idUsuario = (SELECT idVendedor
                FROM transaccionvideojuego tv
                INNER JOIN usuarios u ON tv.idVendedor = u.id
                WHERE u.activo = 1
                GROUP BY idVendedor
                ORDER BY count(idVendedor) DESC, sum(unidades) DESC
                LIMIT 1)";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener los videojuegos que mas estan gustando
        */

        public function videojuegosQueEstanGustando(){
            /*Construir la consulta*/
            $consulta = "SELECT count(idVideojuego) AS 'vecesAgregado', v.nombre AS 'nombreVideojuego', v.precio AS 'precioVideojuego', v.foto AS 'fotoVideojuego', u.nombre AS 'nombreUso', c.nombre AS 'nombreConsola' 
                FROM videojuegofavorito vf
                INNER JOIN videojuegos v ON v.id = vf.idVideojuego
                INNER JOIN usos u ON u.id = v.idUso
                INNER JOIN consolas c ON c.id = v.idConsola 
                WHERE vf.activo = 1
                GROUP BY idvideojuego
                ORDER BY vecesAgregado DESC
                LIMIT 15";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener los videojuegos que mas estan gustando
        */

        public function videojuegosComentados(){
            /*Construir la consulta*/
            $consulta = "SELECT count(idVideojuego) AS 'vecesComentado', v.nombre AS 'nombreVideojuego', v.precio AS 'precioVideojuego', v.foto AS 'fotoVideojuego', u.nombre AS 'nombreUso', c.nombre AS 'nombreConsola' 
                FROM comentariousuariovideojuego cuf
                INNER JOIN videojuegos v ON v.id = cuf.idVideojuego
                INNER JOIN usos u ON u.id = v.idUso
                INNER JOIN consolas c ON c.id = v.idConsola 
                WHERE v.activo = 1
                AND cuf.activo = 1
                GROUP BY idvideojuego
                ORDER BY vecesComentado DESC
                LIMIT 15";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

    }

?>