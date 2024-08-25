<?php

    /*
    Clase modelo de transaccion
    */

    class Transaccion{

        private $id;
        private $numeroFactura;
        private $idComprador;
        private $total;
        private $idEnvio;
        private $fechaHora;
        private $comprobante;
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
        Funcion getter de numero factura
        */

        public function getNumeroFactura(){
            /*Retornar el resultado*/
            return $this->numeroFactura;
        }

        /*
        Funcion setter de numero factura
        */

        public function setNumeroFactura($numeroFactura){
            /*Llamar parametro*/
            $this->numeroFactura = $numeroFactura;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de id comprador
        */

        public function getIdComprador(){
            /*Retornar el resultado*/
            return $this->idComprador;
        }

        /*
        Funcion setter de id comprador
        */

        public function setIdComprador($idComprador){
            /*Llamar parametro*/
            $this->idComprador = $idComprador;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de total
        */

        public function getTotal(){
            /*Retornar el resultado*/
            return $this->total;
        }

        /*
        Funcion setter de total
        */


        public function setTotal($total){
            /*Llamar parametro*/
            $this->total = $total;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de fecha hora
        */

        public function getFechaHora(){
            /*Retornar el resultado*/
            return $this->fechaHora;
        }

        /*
        Funcion setter de fecha hora
        */

        public function setFechaHora($fechaHora){
            /*Llamar parametro*/
            $this->fechaHora = $fechaHora;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de id envio
        */

        public function getIdEnvio(){
            /*Retornar el resultado*/
            return $this->idEnvio;
        }

        /*
        Funcion setter de id envio
        */

        public function setIdEnvio($idEnvio){
            /*Llamar parametro*/
            $this->idEnvio = $idEnvio;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion getter de id
        */

        public function getComprobante(){
            /*Retornar el resultado*/
            return $this->comprobante;
        }

        /*
        Funcion setter de id
        */

        public function setComprobante($comprobante){
            /*Llamar parametro*/
            $this->comprobante = $comprobante;
            /*Retornar el resultado*/
            return $this;
        }

        /*
        Funcion para realizar el registro de la transaccion en la base de datos
        */

        public function guardar(){
            /*Construir la consulta*/
            $consulta = "INSERT INTO transacciones VALUES(NULL, {$this -> getNumeroFactura()}, {$this -> getIdComprador()}, 
                {$this -> getIdEnvio()}, {$this -> getTotal()}, '{$this -> getFechaHora()}', NULL)";
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
        Funcion para obtener todas las compras realizadas por un usuario
        */

        public function obtenerCompras(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM transacciones t
                INNER JOIN transaccionVideojuego tv ON t.id = tv.idTransaccion 
                WHERE t.idComprador = {$this -> getIdComprador()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $lista = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $lista;
        }

        /*
        Funcion para obtener el ultimo id de la transaccion
        */

        public function  videojuegosPorFecha($fechaInicial, $fechaFinal){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT * FROM videojuegos WHERE id IN 
                (SELECT DISTINCT idVideojuego FROM transaccionvideojuego 
                WHERE id IN (SELECT DISTINCT id FROM transacciones WHERE fechaHora >= '$fechaInicial' 
                AND fechaHora <= '$fechaFinal'))";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*
        Funcion para obtener el ultimo id de la transaccion
        */

        public function traerUltimoIdTransaccion(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT id FROM transacciones ORDER BY id DESC LIMIT 1";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultado = $this -> db -> query($consulta);
            /*Obtener el resultado*/
            $ultimo = $resultado -> fetch_object();
            /*Devolver resultado*/
            $id = $ultimo -> id;
            /*Retornar el resultado*/
            return $id;
        }

        /*
        Funcion para adjuntar un comprobante de pago
        */

        public function adjuntarComprobante(){
            /*Construir la consulta*/
            $consulta = "UPDATE transacciones SET comprobante = '{$this -> getComprobante()}' WHERE numeroFactura = {$this -> getNumeroFactura()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $adjuntado = $this -> db -> query($consulta);
            /*Establecer una variable bandera*/
            $bandera = false;
            /*Comprobar si la consulta fue exitosa y el total de columnas afectadas se altero llamando la ejecucion de la consulta*/
            if($adjuntado){
                /*Cambiar el estado de la variable bandera*/
                $bandera = true;
            }
            /*Retornar el resultado*/
            return $bandera;
        }

        /*
        Funcion para obtener el detalle de la compra
        */

        public function detalleCompra(){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT  v.foto AS 'imagenVideojuego', te.nombre AS 'nombreEstado', ve.nombre AS 'nombreVendedor', ve.apellido AS 'apellidoVendedor', ve.numeroTelefono AS 'telefonoVendedor', ve.correo AS 'correoVendedor', en.departamento AS 'departamentoEnvio', en.municipio AS 'municipioEnvio', en.codigoPostal AS 'codigoPostalEnvio', en.direccion AS 'direccionEnvio', en.barrio AS 'barrioEnvio', t.total AS 'totalTransaccion', v.nombre AS 'nombreVideojuegoCompra', u.nombre AS 'usoVideojuegoCompra', c.nombre AS 'consolaVideojuegoCompra', v.precio AS 'precioVideojuegoCompra', t.numeroFactura AS 'factura', tv.unidades AS 'unidadesCompra'
                FROM TransaccionVideojuego tv
                INNER JOIN Transacciones t ON t.id = tv.idTransaccion
                INNER JOIN Estados te ON te.id = tv.idEstado
                INNER JOIN Videojuegos v ON v.id = tv.idVideojuego
                INNER JOIN Consolas c ON c.id = v.idConsola
                INNER JOIN usuarios ve ON tv.idVendedor = ve.id
                INNER JOIN Usos u ON u.id = v.idUso
                INNER JOIN Envios en ON en.id = t.idEnvio
                WHERE t.numeroFactura = {$this -> getNumeroFactura()}";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultados = $this->db->query($consulta);
            /*Array para almacenar la información de la compra*/
            $informacionCompra = array();
            /*Mientras hayan compras disponibles para recorrer*/
            while ($fila = $resultados->fetch_object()) {
                /*Comprobar si no existe la informacion de la compra*/
                if(!isset($informacionCompra['compra'])){
                    /*Crear array con informacion de la compra*/
                    $informacionCompra['compra'] = array(
                        'factura' => $fila->factura,
                        'departamentoEnvio' => $fila->departamentoEnvio,
                        'municipioEnvio' => $fila->municipioEnvio,
                        'codigoPostalEnvio' => $fila->codigoPostalEnvio,
                        'direccionEnvio' => $fila->direccionEnvio,
                        'barrioEnvio' => $fila->barrioEnvio,
                        'totalTransaccion' => $fila->totalTransaccion,
                        /*Inicializar un array para almacenar los videojuegos de la compra*/
                        'videojuegos' => array()
                    );
                }
                /*Almacenar la información del videojuego en el array de compra y videojuego*/
                $informacionCompra['compra']['videojuegos'][] = array(
                    'nombreVideojuegoCompra' => $fila->nombreVideojuegoCompra,
                    'unidadesCompra' => $fila->unidadesCompra,
                    'usoVideojuegoCompra' => $fila->usoVideojuegoCompra,
                    'consolaVideojuegoCompra' => $fila->consolaVideojuegoCompra,
                    'precioVideojuegoCompra' => $fila->precioVideojuegoCompra
                );
                /*Almacenar la información del vendedor en el array de compra y vendedor*/
                $informacionCompra['compra']['vendedores'][] = array(
                    'nombreVendedor' => $fila->nombreVendedor,
                    'apellidoVendedor' => $fila->apellidoVendedor,
                    'telefonoVendedor' => $fila->telefonoVendedor,
                    'correoVendedor' => $fila->correoVendedor,
                    'nombreEstado' => $fila->nombreEstado,
                    'imagenVideojuego' => $fila->imagenVideojuego,
                );
            }
            /*Retornar el resultado*/
            return $informacionCompra;
        }  

        /*
        Funcion para obtener el detalle de la venta
        */

        public function detalleVenta($idVendedor){
            /*Construir la consulta*/
            $consulta = "SELECT DISTINCT co.nombre AS 'nombreComprador', co.apellido AS 'apellidoComprador', co.numeroTelefono AS 'telefonoComprador', co.correo AS 'correoComprador', en.departamento AS 'departamentoEnvio', en.municipio AS 'municipioEnvio', en.codigoPostal AS 'codigoPostalEnvio', en.direccion AS 'direccionEnvio', en.barrio AS 'barrioEnvio', t.total AS 'totalTransaccion', v.foto AS 'imagenVideojuego', tv.unidades AS 'unidadesCompra', v.precio AS 'precioVideojuegoVenta', t.numeroFactura AS 'facturaVenta', t.id AS 'idTransaccion', te.nombre AS 'nombreEstado', t.comprobante AS 'comprobante'
                FROM TransaccionVideojuego tv
                INNER JOIN Transacciones t ON t.id = tv.idTransaccion
                INNER JOIN Estados te ON te.id = tv.idEstado
                INNER JOIN Videojuegos v ON v.id = tv.idVideojuego
                INNER JOIN Envios en ON en.id = t.idEnvio
                INNER JOIN usuarios co ON t.idComprador = co.id
                WHERE t.numeroFactura = {$this -> getNumeroFactura()} AND tv.idVendedor = {$idVendedor}";
            /*Llamar la funcion que ejecuta la consulta*/
            $resultados = $this->db->query($consulta);
            /*Array para almacenar la información de la compra*/
            $informacionVenta = array();
            /*Establecer una variable total*/
            $total = 0;
            /*Mientras hayan compras disponibles para recorrer*/
            while ($fila = $resultados->fetch_object()) {
                /*Comprobar si no existe la informacion de la venta*/
                if(!isset($informacionVenta['venta'])){
                    /*Crear array con informacion de la compra*/
                    $informacionVenta['venta'] = array(
                        'facturaVenta' => $fila->facturaVenta,
                        'idTransaccion' => $fila->idTransaccion,
                        'nombreComprador' => $fila->nombreComprador,
                        'apellidoComprador' => $fila->apellidoComprador,
                        'telefonoComprador' => $fila->telefonoComprador,
                        'correoComprador' => $fila->correoComprador,
                        'departamentoEnvio' => $fila->departamentoEnvio,
                        'municipioEnvio' => $fila->municipioEnvio,
                        'codigoPostalEnvio' => $fila->codigoPostalEnvio,
                        'direccionEnvio' => $fila->direccionEnvio,
                        'barrioEnvio' => $fila->barrioEnvio,
                        'nombreEstado' => $fila->nombreEstado,
                        'comprobante' => $fila->comprobante,
                        /*Inicializar un array para almacenar los videojuegos de la venta*/
                        'videojuegos' => array()
                    );
                }
                /*Almacenar la información del videojuego en el array de venta y videojuego*/
                $informacionVenta['venta']['videojuegos'][] = array(
                    'unidadesCompra' => $fila->unidadesCompra,
                    'imagenVideojuego' => $fila->imagenVideojuego,
                    'precioVideojuegoVenta' => $fila->precioVideojuegoVenta
                );
                /*Obtener precios, unidades y el total del carrito*/
                $precios = $fila -> precioVideojuegoVenta;
                $unidades = $fila -> unidadesCompra;
                $total += $precios * $unidades;
                /*Almacenar la información del total del carrito en el array de carrito*/
                $informacionVenta['totalVenta'] = array(
                    'totalVenta' => $total
                );
            }
            /*Retornar el resultado*/
            return $informacionVenta;
        }
        
    }

?>