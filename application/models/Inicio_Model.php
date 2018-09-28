<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_Model extends CI_model{

    function __construct(){
        parent::__construct();
        if(!isset($this->session->sucursal)){
            $this->session->sucursal = 2;
            date_default_timezone_set('America/Santiago');
			$hoy = strtotime("now");
			$manana = strtotime("+1 day");
			$this->session->inicio = date("d/m/Y",$hoy);
			$this->session->fin = date("d/m/Y",$manana);
		}
    }

    function Cantidad_Carro($rut){
        $this->load->database();
        $consulta = "SELECT COUNT(*) as CANTIDAD FROM CARRITO WHERE RUT = ?";
        $output = $this->db->query($consulta,array($rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Sucursal($sucursal){
        $this->load->database();
        $consulta = "SELECT COD_SUCURSAL,NOMBRE,DIRECCION,TELEFONO,URL_FOTO FROM SUCURSAL 
                    WHERE COD_SUCURSAL = ?";
        $output = $this->db->query($consulta,array($sucursal));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Sucursales(){
        $this->load->database();
        $consulta = "SELECT COD_SUCURSAL,NOMBRE FROM SUCURSAL";
        $output = $this->db->query($consulta);
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Categorias(){
        $this->load->database();
        $consulta = "SELECT C.COD_CATEGORIA,C.NOMBRE, COUNT(H.COD_HERRAMIENTA) AS CONTADOR 
        FROM CATEGORIA C
        JOIN HERRAMIENTA H
        ON H.COD_CATEGORIA = C.COD_CATEGORIA
        JOIN SUCURSAL_HERRAMIENTA SH
        ON SH.COD_HERRAMIENTA = H.COD_HERRAMIENTA
        WHERE SH.COD_SUCURSAL = ?
        GROUP BY C.COD_CATEGORIA,C.NOMBRE";
        $output = $this->db->query($consulta,array($this->session->sucursal));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Categoria($value){
        if($value==""){
            return "Todas las Herramientas";
        }else{
            $this->load->database();
            $consulta = "SELECT INITCAP(C.NOMBRE) AS NOMBRE FROM CATEGORIA C
            JOIN HERRAMIENTA H
            ON H.COD_CATEGORIA = C.COD_CATEGORIA
            JOIN SUCURSAL_HERRAMIENTA SH
            ON SH.COD_HERRAMIENTA = H.COD_HERRAMIENTA
            WHERE SH.COD_SUCURSAL = ? AND C.COD_CATEGORIA = ?";
            $output = $this->db->query($consulta,array($this->session->sucursal,$value));
            if($output->num_rows()>0){
                return "Herramientas de ".$output->result()[0]->nombre;
            }else{
                return 'No hay herramientas con esta categoria en esta sucursal';
            }
        }
    }

    function Obtener_Productos($value){
        $this->load->database();
        $output = NULL;
        $sucursal = $this->session->sucursal;
        if($value==""){
            $consulta = "SELECT H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE AS NOMBREC, (SH.STOCK - SUM(D.CANTIDAD)) AS STOCK
            FROM HERRAMIENTA H JOIN CATEGORIA C 
            ON H.COD_CATEGORIA=C.COD_CATEGORIA
            JOIN SUCURSAL_HERRAMIENTA SH
            ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
            JOIN DETALLE D 
            ON D.COD_H=H.COD_HERRAMIENTA
            WHERE H.COD_HERRAMIENTA IN (SELECT H.COD_HERRAMIENTA FROM ARRIENDO A JOIN DETALLE D 
                                        ON A.COD_ARRIENDO=D.ID_A JOIN HERRAMIENTA H
                                        ON D.COD_H=H.COD_HERRAMIENTA
                                        WHERE A.FECHA_INICIO BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.FECHA_FINAL BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY'))
            AND SH.COD_SUCURSAL = ".$this->session->sucursal." 
            GROUP BY H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE, SH.STOCK
            UNION 
            SELECT H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE AS NOMBREC, SH.STOCK AS STOCK
            FROM HERRAMIENTA H JOIN CATEGORIA C 
            ON H.COD_CATEGORIA=C.COD_CATEGORIA
            JOIN SUCURSAL_HERRAMIENTA SH
            ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
            FULL OUTER JOIN DETALLE D 
            ON D.COD_H=H.COD_HERRAMIENTA
            WHERE H.COD_HERRAMIENTA NOT IN (SELECT H.COD_HERRAMIENTA FROM ARRIENDO A JOIN DETALLE D 
                                        ON A.COD_ARRIENDO=D.ID_A JOIN HERRAMIENTA H
                                        ON D.COD_H=H.COD_HERRAMIENTA
                                        WHERE A.FECHA_INICIO BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.FECHA_FINAL BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY'))
            AND SH.COD_SUCURSAL = ".$this->session->sucursal."";
            $output = $this->db->query($consulta);
        }else{
            $consulta = "SELECT H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE AS NOMBREC, (SH.STOCK - SUM(D.CANTIDAD)) AS STOCK
            FROM HERRAMIENTA H JOIN CATEGORIA C 
            ON H.COD_CATEGORIA=C.COD_CATEGORIA
            JOIN SUCURSAL_HERRAMIENTA SH
            ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
            JOIN DETALLE D 
            ON D.COD_H=H.COD_HERRAMIENTA
            WHERE H.COD_HERRAMIENTA IN (SELECT H.COD_HERRAMIENTA FROM ARRIENDO A JOIN DETALLE D 
                                        ON A.COD_ARRIENDO=D.ID_A JOIN HERRAMIENTA H
                                        ON D.COD_H=H.COD_HERRAMIENTA
                                        WHERE A.FECHA_INICIO BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.FECHA_FINAL BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY'))
            AND SH.COD_SUCURSAL = ".$this->session->sucursal."
            AND H.COD_CATEGORIA = ?
            GROUP BY H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE, SH.STOCK
            UNION
            SELECT H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE AS NOMBREC, SH.STOCK AS STOCK
            FROM HERRAMIENTA H JOIN CATEGORIA C 
            ON H.COD_CATEGORIA=C.COD_CATEGORIA
            JOIN SUCURSAL_HERRAMIENTA SH
            ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
            FULL OUTER JOIN DETALLE D 
            ON D.COD_H=H.COD_HERRAMIENTA
            WHERE H.COD_HERRAMIENTA NOT IN (SELECT H.COD_HERRAMIENTA FROM ARRIENDO A JOIN DETALLE D 
                                        ON A.COD_ARRIENDO=D.ID_A JOIN HERRAMIENTA H
                                        ON D.COD_H=H.COD_HERRAMIENTA
                                        WHERE A.FECHA_INICIO BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.FECHA_FINAL BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY'))
            AND SH.COD_SUCURSAL = ".$this->session->sucursal."
            AND H.COD_CATEGORIA = ?";
            $output = $this->db->query($consulta,array((int)$value,(int)$value));
        }
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Producto($codigo){
        $this->load->database();
        $output = NULL;
        $consulta = "SELECT H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE AS NOMBREC, (SH.STOCK - SUM(D.CANTIDAD)) AS STOCK
        FROM HERRAMIENTA H JOIN CATEGORIA C 
        ON H.COD_CATEGORIA = C.COD_CATEGORIA
        JOIN SUCURSAL_HERRAMIENTA SH
        ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
        JOIN DETALLE D 
        ON D.COD_H=H.COD_HERRAMIENTA
        WHERE H.COD_HERRAMIENTA IN (SELECT H.COD_HERRAMIENTA FROM ARRIENDO A JOIN DETALLE D 
                                    ON A.COD_ARRIENDO=D.ID_A JOIN HERRAMIENTA H
                                    ON D.COD_H=H.COD_HERRAMIENTA
                                    WHERE A.FECHA_INICIO BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY')
                                    OR A.FECHA_FINAL BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY')
                                    AND H.COD_HERRAMIENTA = ".$codigo.")
        AND SH.COD_SUCURSAL = ".$this->session->sucursal." 
        AND H.COD_HERRAMIENTA = ".$codigo."
        GROUP BY H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE, SH.STOCK
        UNION 
        SELECT H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE AS NOMBREC, SH.STOCK AS STOCK
        FROM HERRAMIENTA H JOIN CATEGORIA C 
        ON H.COD_CATEGORIA=C.COD_CATEGORIA
        JOIN SUCURSAL_HERRAMIENTA SH
        ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
        FULL OUTER JOIN DETALLE D 
        ON D.COD_H=H.COD_HERRAMIENTA
        WHERE H.COD_HERRAMIENTA NOT IN (SELECT H.COD_HERRAMIENTA FROM ARRIENDO A JOIN DETALLE D 
                                    ON A.COD_ARRIENDO=D.ID_A JOIN HERRAMIENTA H
                                    ON D.COD_H=H.COD_HERRAMIENTA
                                    WHERE A.FECHA_INICIO BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY')
                                    OR A.FECHA_FINAL BETWEEN TO_DATE('".$this->session->inicio."', 'DD/MM/YYYY') AND TO_DATE('".$this->session->fin."', 'DD/MM/YYYY')
                                    AND H.COD_HERRAMIENTA = ".$codigo.")
        AND SH.COD_SUCURSAL = ".$this->session->sucursal."
        AND H.COD_HERRAMIENTA = ".$codigo."";
        $output = $this->db->query($consulta);
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Validar_Sesion($rut,$pass){
        $this->load->database();
        $conexion = oci_connect($this->db->username,$this->db->password,$this->db->hostname);
        if($this->Revisar_Conexion($conexion)==TRUE){
            $procedimiento = "BEGIN INICIO_SESION(:rut, :pass, :estado, :mensaje); END;";
            $consulta = oci_parse($conexion, $procedimiento);
            oci_bind_by_name($consulta, ':rut', $rut);
            oci_bind_by_name($consulta, ':pass', $pass);
            oci_bind_by_name($consulta, ':estado', $estado, 150);
            oci_bind_by_name($consulta, ':mensaje', $mensaje, 150);
            oci_execute($consulta);        
            oci_free_statement($consulta);
            oci_close($conexion);
            $respuesta = new stdClass();
            $respuesta->estado = $estado;
            $respuesta->mensaje = $mensaje;   
            if($estado=='TRUE'){
                $this->_AsignarSesion($rut);
            }         
        }else{
            $respuesta = new stdClass();
            $respuesta->estado = 'FALSE';
            $respuesta->mensaje = 'EL SISTEMA NO HA PODIDO CONECTARSE A LA BASE DE DATOS';
        }
        return $respuesta;
    }

    private function _AsignarSesion($rut){
        $this->load->database();
        $consulta = "SELECT RUT,NOMBRES,APELLIDOS,ROL FROM USUARIO WHERE RUT = ?";
        $output = $this->db->query($consulta,array($rut));
        if($output->num_rows()>0){
            $this->session->estado = TRUE;
            $this->session->rut = $output->result()[0]->RUT;
            $this->session->nombres = $output->result()[0]->NOMBRES;
            $this->session->apellidos = $output->result()[0]->APELLIDOS;
            $this->session->rol = $output->result()[0]->ROL;
        }
    }

    function Verificar_Carrito(){
        if($this->session->estado==TRUE){
            $this->load->database();
            $consulta = "SELECT * FROM CARRITO WHERE RUT = ?";
            $output = $this->db->query($consulta,array($this->session->rut));
            if($output->num_rows()>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    function Verificar_Sucursal_Carrito(){
        if($this->session->estado==TRUE){
            $this->load->database();
            $consulta = "SELECT COD_SUCURSAL FROM CARRITO WHERE RUT = ?";
            $output = $this->db->query($consulta,array($this->session->rut));
            if($output->num_rows()>0){
                return $output->result()[0];
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    function Obtener_Carro(){
        if($this->session->estado==TRUE){
            $this->load->database();
            $consulta = "SELECT C.COD_HERRAMIENTA, H.NOMBRE, H.URL_FOTO, C.CANTIDAD, SH.STOCK, C.TOTAL, 
            SH.PRECIO FROM CARRITO C
            JOIN HERRAMIENTA H
            ON C.COD_HERRAMIENTA = H.COD_HERRAMIENTA
            JOIN SUCURSAL_HERRAMIENTA SH
            ON SH.COD_HERRAMIENTA = H.COD_HERRAMIENTA
            WHERE SH.COD_SUCURSAL = ?
            AND C.RUT = ?";
            $output = $this->db->query($consulta,array($this->session->sucursal,$this->session->rut));
            if($output->num_rows()>0){
                return $output->result();
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    function Borrar_H_Carrito($datos){
        $this->load->database();
        $conexion = oci_connect($this->db->username,$this->db->password,$this->db->hostname);
        if($this->Revisar_Conexion($conexion)==TRUE){
            $procedimiento = "BEGIN BORRAR_HERRAMIENTA_CARRITO(:rut, :codigo_h, :codigo_s, :estado, :mensaje); END;";
            $consulta = oci_parse($conexion, $procedimiento);
            oci_bind_by_name($consulta, ':rut', $datos->rut);
            oci_bind_by_name($consulta, ':codigo_h', $datos->codigo);
            oci_bind_by_name($consulta, ':codigo_s', $datos->sucursal);
            oci_bind_by_name($consulta, ':estado', $estado, 150);
            oci_bind_by_name($consulta, ':mensaje', $mensaje, 150);
            oci_execute($consulta);        
            oci_free_statement($consulta);
            oci_close($conexion);
            $respuesta = new stdClass();
            $respuesta->estado = $estado;
            $respuesta->mensaje = $mensaje;   
        }else{
            $respuesta = new stdClass();
            $respuesta->estado = 'FALSE';
            $respuesta->mensaje = 'EL SISTEMA NO HA PODIDO CONECTARSE A LA BASE DE DATOS';
        }
        return $respuesta;
    }

    function Total_Carro($rut,$sucursal){
        $this->load->database();
        $consulta = "SELECT SUM(TOTAL) AS TOTAL FROM CARRITO 
        WHERE RUT = ?
        AND COD_SUCURSAL = ?";
        $output = $this->db->query($consulta,array($rut,$sucursal));
        if($output->num_rows()>0){
            return $output->result()[0]->TOTAL;
        }else{
            return FALSE;
        }

    }

    function Agregar_Carrito($codigo,$cantidad){
        $this->load->database();
        $consulta = "SELECT COD_SUCURSAL FROM CARRITO WHERE RUT = ?";
        $output = $this->db->query($consulta,array($this->session->rut));
        $verificador = FALSE;
        if($output->num_rows()>0){
            $suc = $output->result()[0]->COD_SUCURSAL;
            if($suc==$this->session->sucursal){
                $verificador = TRUE;
            }else{
                $limpiar_carro = $this->Limpiar_Carrito();
                if($limpiar_carro->estado==TRUE){
                    $respuesta = new stdClass();
                    $respuesta->estado = 'REFRESH';
                    $respuesta->mensaje = 'SE HA VACIADO EL CARRITO YA QUE CAMBIÓ LA SUCURSAL';
                }else{
                    $respuesta = new stdClass();
                    $respuesta->estado = 'ERROR';
                    $respuesta->mensaje = 'HA OCURRIDO UN ERROR EN LA FUNCION AGREGAR_CARRITO';
                }
                return $respuesta;
            }
        }else{
            $verificador = TRUE;
        }
        if($verificador==TRUE){
            $rut = $this->session->rut;
            $sucursal = $this->session->sucursal;
            $fecha_i = $this->session->inicio;
            $fecha_f = $this->session->fin;
            $conexion = oci_connect($this->db->username,$this->db->password,$this->db->hostname);
            if($this->Revisar_Conexion($conexion)==TRUE){
                $procedimiento = "BEGIN AGREGA_CARRITO(:rut, :codigo_h, :codigo_s, :fecha_i, :fecha_f, :cantidad, :estado, :mensaje); END;";
                $consulta = oci_parse($conexion, $procedimiento);
                oci_bind_by_name($consulta, ':rut', $rut);
                oci_bind_by_name($consulta, ':codigo_h', $codigo);
                oci_bind_by_name($consulta, ':codigo_s', $sucursal);
                oci_bind_by_name($consulta, ':fecha_i', $fecha_i);
                oci_bind_by_name($consulta, ':fecha_f', $fecha_f);
                oci_bind_by_name($consulta, ':cantidad', $cantidad);
                oci_bind_by_name($consulta, ':estado', $estado, 150);
                oci_bind_by_name($consulta, ':mensaje', $mensaje, 150);
                oci_execute($consulta);        
                oci_free_statement($consulta);
                oci_close($conexion);
                $respuesta = new stdClass();
                $respuesta->estado = $estado;
                $respuesta->mensaje = $mensaje;                   
            }else{
                $respuesta = new stdClass();
                $respuesta->estado = 'FALSE';
                $respuesta->mensaje = 'EL SISTEMA NO HA PODIDO CONECTARSE A LA BASE DE DATOS';
            }
            return $respuesta;
        }
    }

    function Quitar_Carrito($codigo,$cantidad){
        $this->load->database();
        $consulta = "SELECT COD_SUCURSAL FROM CARRITO WHERE RUT = ?";
        $output = $this->db->query($consulta,array($this->session->rut));
        $verificador = FALSE;
        if($output->num_rows()>0){
            $suc = $output->result()[0]->COD_SUCURSAL;
            if($suc==$this->session->sucursal){
                $verificador = TRUE;
            }else{
                $limpiar_carro = $this->Limpiar_Carrito();
                if($limpiar_carro->estado==TRUE){
                    $respuesta = new stdClass();
                    $respuesta->estado = 'REFRESH';
                    $respuesta->mensaje = 'SE HA VACIADO EL CARRITO YA QUE CAMBIÓ LA SUCURSAL';
                }else{
                    $respuesta = new stdClass();
                    $respuesta->estado = 'ERROR';
                    $respuesta->mensaje = 'HA OCURRIDO UN ERROR EN LA FUNCION QUITAR_CARRITO';
                }
                return $respuesta;
            }
        }else{
            $verificador = TRUE;
        }
        if($verificador==TRUE){
            $rut = $this->session->rut;
            $sucursal = $this->session->sucursal;
            $conexion = oci_connect($this->db->username,$this->db->password,$this->db->hostname);
            if($this->Revisar_Conexion($conexion)==TRUE){
                $procedimiento = "BEGIN QUITA_CARRITO(:rut, :codigo_h, :codigo_s, :cantidad, :estado, :mensaje); END;";
                $consulta = oci_parse($conexion, $procedimiento);
                oci_bind_by_name($consulta, ':rut', $rut);
                oci_bind_by_name($consulta, ':codigo_h', $codigo);
                oci_bind_by_name($consulta, ':codigo_s', $sucursal);
                oci_bind_by_name($consulta, ':cantidad', $cantidad);
                oci_bind_by_name($consulta, ':estado', $estado, 150);
                oci_bind_by_name($consulta, ':mensaje', $mensaje, 150);
                oci_execute($consulta);        
                oci_free_statement($consulta);
                oci_close($conexion);
                $respuesta = new stdClass();
                $respuesta->estado = $estado;
                $respuesta->mensaje = $mensaje;                   
            }else{
                $respuesta = new stdClass();
                $respuesta->estado = 'FALSE';
                $respuesta->mensaje = 'EL SISTEMA NO HA PODIDO CONECTARSE A LA BASE DE DATOS';
            }
            return $respuesta;
        }
    }

    function Efectuar_Registro($datos){
        $this->load->database();
        $conexion = oci_connect($this->db->username,$this->db->password,$this->db->hostname);
        if($this->Revisar_Conexion($conexion)==TRUE){
            $procedimiento = "BEGIN INSERTAR_USUARIO(:rut, :nombres, :apellidos, :correo, :pass, :direccion, :telefono, :estado, :mensaje); END;";
            $consulta = oci_parse($conexion, $procedimiento);
            oci_bind_by_name($consulta, ':rut', $datos->Rut);
            oci_bind_by_name($consulta, ':nombres', $datos->Nombres);
            oci_bind_by_name($consulta, ':apellidos', $datos->Apellidos);
            oci_bind_by_name($consulta, ':correo', $datos->Correo);
            oci_bind_by_name($consulta, ':pass', $datos->Pass);
            oci_bind_by_name($consulta, ':direccion', $datos->Direccion);
            oci_bind_by_name($consulta, ':telefono', $datos->Telefono);
            oci_bind_by_name($consulta, ':estado', $estado, 150);
            oci_bind_by_name($consulta, ':mensaje', $mensaje, 150);
            oci_execute($consulta);        
            oci_free_statement($consulta);
            oci_close($conexion);
            $respuesta = new stdClass();
            $respuesta->estado = $estado;
            $respuesta->mensaje = $mensaje;                   
        }else{
            $respuesta = new stdClass();
            $respuesta->estado = 'FALSE';
            $respuesta->mensaje = 'EL SISTEMA NO HA PODIDO CONECTARSE A LA BASE DE DATOS';
        }
        return $respuesta;
    }

    function Limpiar_Carrito(){
        if($this->session->estado==TRUE){
            $rut = $this->session->rut;
            $this->load->database();
            $conexion = oci_connect($this->db->username,$this->db->password,$this->db->hostname);
            if($this->Revisar_Conexion($conexion)==TRUE){
                $procedimiento = "BEGIN VACIAR_CARRITO(:rut, :estado, :mensaje); END;";
                $consulta = oci_parse($conexion, $procedimiento);
                oci_bind_by_name($consulta, ':rut', $rut);
                oci_bind_by_name($consulta, ':estado', $estado, 150);
                oci_bind_by_name($consulta, ':mensaje', $mensaje, 150);
                oci_execute($consulta);        
                oci_free_statement($consulta);
                oci_close($conexion);
                $respuesta = new stdClass();
                $respuesta->estado = $estado;
                $respuesta->mensaje = $mensaje;                   
            }else{
                $respuesta = new stdClass();
                $respuesta->estado = 'FALSE';
                $respuesta->mensaje = 'EL SISTEMA NO HA PODIDO CONECTARSE A LA BASE DE DATOS';
            }
            return $respuesta;
        }else{
            redirect(base_url());
        }
    }

    function Generar_Arriendo(){
        if($this->session->estado==TRUE){
            $rut = $this->session->rut;
            $sucursal = $this->session->sucursal;
            $fecha_inicio = $this->session->inicio;
            $fecha_final = $this->session->fin;
            $this->load->database();
            $conexion = oci_connect($this->db->username,$this->db->password,$this->db->hostname);
            if($this->Revisar_Conexion($conexion)==TRUE){
                $procedimiento = "BEGIN ARRENDAR(:inicio, :fin, :rut, :sucursal, :estado, :mensaje, :id_arriendo); END;";
                $consulta = oci_parse($conexion, $procedimiento);
                oci_bind_by_name($consulta, ':inicio', $fecha_inicio);
                oci_bind_by_name($consulta, ':fin', $fecha_final);
                oci_bind_by_name($consulta, ':rut', $rut);
                oci_bind_by_name($consulta, ':sucursal', $sucursal);
                oci_bind_by_name($consulta, ':estado', $estado, 150);
                oci_bind_by_name($consulta, ':mensaje', $mensaje, 150);
                oci_bind_by_name($consulta, ':id_arriendo', $arriendo, 100);
                oci_execute($consulta);        
                oci_free_statement($consulta);
                oci_close($conexion);
                $respuesta = new stdClass();
                $respuesta->estado = $estado;
                $respuesta->mensaje = $mensaje;
                $respuesta->arriendo = $arriendo;                       
            }else{
                $respuesta = new stdClass();
                $respuesta->estado = 'FALSE';
                $respuesta->mensaje = 'EL SISTEMA NO HA PODIDO CONECTARSE A LA BASE DE DATOS';
                $respuesta->arriendo = $arriendo;    
            }            
        }else{
            $respuesta = new stdClass();
            $respuesta->estado = 'FALSE';
            $respuesta->mensaje = 'LA SESIÓN SE HA TERMINADO, NO PUEDE REALIZAR EL ARRIENDO';
            $respuesta->arriendo = $arriendo;  
        }
        return $respuesta;
    }

    function Obtener_Arriendo($value){
        $this->load->database();
        $consulta = "SELECT A.COD_ARRIENDO,TO_CHAR(A.FECHA_INICIO, 'DD/MM/YYYY') as FECHA_INICIO,
                    TO_CHAR(A.FECHA_FINAL, 'DD/MM/YYYY') AS FECHA_FINAL,A.TOTAL,A.RUT_U,U.NOMBRES,
                    U.APELLIDOS,TO_CHAR(A.FECHA_ARRIENDO, 'DD/MM/YYYY') AS FECHA_ARRIENDO,
                    S.COD_SUCURSAL,S.NOMBRE
                    FROM ARRIENDO A JOIN SUCURSAL S 
                    ON A.COD_S = S.COD_SUCURSAL JOIN USUARIO U
                    ON A.RUT_U = U.RUT
                    WHERE COD_ARRIENDO = ?
                    AND U.RUT = ?";
        $output = $this->db->query($consulta,array($value,$this->session->rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Mas_Arrendados(){
        $this->load->database();
        $consulta = "SELECT D.COD_H, H.NOMBRE, H.URL_FOTO,SH.PRECIO, COUNT(D.ID_A) AS CANTIDAD_ARRIENDOS 
        FROM DETALLE D JOIN HERRAMIENTA H
        ON D.COD_H = H.COD_HERRAMIENTA
        JOIN SUCURSAL_HERRAMIENTA SH
        ON SH.COD_HERRAMIENTA = H.COD_HERRAMIENTA
        WHERE SH.COD_SUCURSAL = ?
        GROUP BY COD_H, H.NOMBRE, H.URL_FOTO, SH.PRECIO ORDER BY CANTIDAD_ARRIENDOS DESC";
        $output = $this->db->query($consulta,array($this->session->sucursal));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Detalle($value, $sucursal){
        $this->load->database();
        $consulta = "SELECT D.COD_H, H.NOMBRE,H.URL_FOTO,D.CANTIDAD,D.TOTAL_DETALLE,SH.PRECIO 
                    FROM DETALLE D JOIN HERRAMIENTA H
                    ON D.COD_H = H.COD_HERRAMIENTA JOIN SUCURSAL_HERRAMIENTA SH
                    ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
                    WHERE ID_A = ?
                    AND SH.COD_SUCURSAL = ?";
        $output = $this->db->query($consulta,array($value,$sucursal));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Revisar_Conexion($conexion){
        if($conexion){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}