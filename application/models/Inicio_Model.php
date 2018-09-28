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

    function Obtener_Cantidad_Carro($rut){
        $this->load->database();
        $consulta = "SELECT COUNT(*) as cantidad FROM carrito WHERE rut = ?";
        $output = $this->db->query($consulta,array($rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Sucursal($sucursal){
        $this->load->database();
        $consulta = "SELECT cod_sucursal,nombre,direccion,telefono,url_foto FROM sucursal 
                    WHERE cod_sucursal = ?";
        $output = $this->db->query($consulta,array($sucursal));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Sucursales(){
        $this->load->database();
        $consulta = "SELECT cod_sucursal,nombre FROM sucursal";
        $output = $this->db->query($consulta);
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Categorias(){
        $this->load->database();
        $consulta = "SELECT C.cod_categoria,C.nombre, COUNT(H.cod_herramienta) AS contador 
        FROM categoria C
        JOIN herramienta H
        ON H.cod_categoria = C.cod_categoria
        JOIN sucursal_herramienta SH
        ON SH.cod_herramienta = H.cod_herramienta
        WHERE SH.cod_sucursal = ?
        GROUP BY C.cod_categoria,C.nombre";
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
            $consulta = "SELECT initcap(C.nombre) AS nombre FROM categoria C
            JOIN herramienta H
            ON H.cod_categoria = C.cod_categoria
            JOIN sucursal_herramienta SH
            ON SH.cod_herramienta = H.cod_herramienta
            WHERE SH.cod_sucursal = ? AND C.cod_categoria = ?";
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
            $consulta = "SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre AS nombreC, (SH.stock - SUM(D.cantidad)) AS stock
            FROM herramienta H JOIN categoria C 
            ON H.cod_categoria=C.cod_categoria
            JOIN sucursal_herramienta SH
            ON H.cod_herramienta = SH.cod_herramienta
            JOIN detalle D 
            ON D.cod_h=H.cod_herramienta
            WHERE H.cod_herramienta IN (SELECT H.cod_herramienta FROM arriendo A JOIN detalle D 
                                        ON A.cod_arriendo=D.id_a JOIN herramienta H
                                        ON D.cod_h=H.cod_herramienta
                                        WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
            AND SH.cod_sucursal = ".$this->session->sucursal." 
            GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre, SH.stock
            UNION 
            SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre AS nombreC, SH.stock AS stock
            FROM herramienta H JOIN categoria C 
            ON H.cod_categoria=C.cod_categoria
            JOIN sucursal_herramienta SH
            ON H.cod_herramienta = SH.cod_herramienta
            FULL OUTER JOIN detalle D 
            ON D.cod_h=H.cod_herramienta
            WHERE H.cod_herramienta NOT IN (SELECT H.cod_herramienta FROM arriendo A JOIN detalle D 
                                        ON A.cod_arriendo=D.id_a JOIN herramienta H
                                        ON D.cod_h=H.cod_herramienta
                                        WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
            AND SH.cod_sucursal = ".$this->session->sucursal."";
            $output = $this->db->query($consulta);
        }else{
            $consulta = "SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre AS nombreC, (SH.stock - SUM(D.cantidad)) AS stock
            FROM herramienta H JOIN categoria C 
            ON H.cod_categoria=C.cod_categoria
            JOIN sucursal_herramienta SH
            ON H.cod_herramienta = SH.cod_herramienta
            JOIN detalle D 
            ON D.cod_h=H.cod_herramienta
            WHERE H.cod_herramienta IN (SELECT H.cod_herramienta FROM arriendo A JOIN detalle D 
                                        ON A.cod_arriendo=D.id_a JOIN herramienta H
                                        ON D.cod_h=H.cod_herramienta
                                        WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
            AND SH.cod_sucursal = ".$this->session->sucursal."
            AND H.cod_categoria = ?
            GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre, SH.stock
            UNION
            SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre AS nombreC, SH.stock AS stock
            FROM herramienta H JOIN categoria C 
            ON H.cod_categoria=C.cod_categoria
            JOIN sucursal_herramienta SH
            ON H.cod_herramienta = SH.cod_herramienta
            FULL OUTER JOIN detalle D 
            ON D.cod_h=H.cod_herramienta
            WHERE H.cod_herramienta NOT IN (SELECT H.cod_herramienta FROM arriendo A JOIN detalle D 
                                        ON A.cod_arriendo=D.id_a JOIN herramienta H
                                        ON D.cod_h=H.cod_herramienta
                                        WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
            AND SH.cod_sucursal = ".$this->session->sucursal."
            AND H.cod_categoria = ?";
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
        $consulta = "SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre AS nombreC, (SH.stock - SUM(D.cantidad)) AS stock
        FROM herramienta H JOIN categoria C 
        ON H.cod_categoria = C.cod_categoria
        JOIN sucursal_herramienta SH
        ON H.cod_herramienta = SH.cod_herramienta
        JOIN detalle D 
        ON D.cod_h=H.cod_herramienta
        WHERE H.cod_herramienta IN (SELECT H.cod_herramienta FROM arriendo A JOIN detalle D 
                                    ON A.cod_arriendo=D.id_a JOIN herramienta H
                                    ON D.cod_h=H.cod_herramienta
                                    WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                    OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                    AND H.cod_herramienta = ".$codigo.")
        AND SH.cod_sucursal = ".$this->session->sucursal." 
        AND H.cod_herramienta = ".$codigo."
        GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre, SH.stock
        UNION 
        SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre AS nombreC, SH.stock AS stock
        FROM herramienta H JOIN categoria C 
        ON H.cod_categoria=C.cod_categoria
        JOIN sucursal_herramienta SH
        ON H.cod_herramienta = SH.cod_herramienta
        FULL OUTER JOIN detalle D 
        ON D.cod_h=H.cod_herramienta
        WHERE H.cod_herramienta NOT IN (SELECT H.cod_herramienta FROM arriendo A JOIN detalle D 
                                    ON A.cod_arriendo=D.id_a JOIN herramienta H
                                    ON D.cod_h=H.cod_herramienta
                                    WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                    OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                    AND H.cod_herramienta = ".$codigo.")
        AND SH.cod_sucursal = ".$this->session->sucursal."
        AND H.cod_herramienta = ".$codigo."";
        $output = $this->db->query($consulta);
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Validar_Sesion($rut,$pass){
        $this->load->database();
        $procedimiento = "select bool,message from inicio_sesion(?,?);";
        $consulta = $this->db->query($procedimiento,array($rut,$pass));
        $respuesta = new stdClass();
        $respuesta->estado = $consulta->result()[0]->bool;
        $respuesta->mensaje = $consulta->result()[0]->message;   
        if($respuesta->estado=='TRUE'){
            $this->_AsignarSesion($rut);
        }
        return $respuesta;
    }

    private function _AsignarSesion($rut){
        $this->load->database();
        $consulta = "SELECT rut,nombres,apellidos,rol FROM usuario WHERE rut = ?";
        $output = $this->db->query($consulta,array($rut));
        if($output->num_rows()>0){
            $this->session->estado = TRUE;
            $this->session->rut = $output->result()[0]->rut;
            $this->session->nombres = $output->result()[0]->nombres;
            $this->session->apellidos = $output->result()[0]->apellidos;
            $this->session->rol = $output->result()[0]->rol;
        }
    }

    function Verificar_Carro(){
        if($this->session->estado==TRUE){
            $this->load->database();
            $consulta = "SELECT * FROM carrito WHERE rut = ?";
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

    function Verificar_Sucursal_Carro(){
        if($this->session->estado==TRUE){
            $this->load->database();
            $consulta = "SELECT cod_sucursal FROM carrito WHERE rut = ?";
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
            $consulta = "SELECT C.cod_herramienta, H.nombre, H.url_foto, C.cantidad, SH.stock, C.total, 
            SH.precio FROM carrito C
            JOIN herramienta H
            ON C.cod_herramienta = H.cod_herramienta
            JOIN sucursal_herramienta SH
            ON SH.cod_herramienta = H.cod_herramienta
            WHERE SH.cod_sucursal = ?
            AND C.rut = ?";
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
        $procedimiento = "select bool,message from borrar_herramienta_carrito(?,?,?);";
        $consulta = $this->db->query($procedimiento,array($datos->rut,$datos->codigo,$datos->sucursal));
        $respuesta = new stdClass();
        $respuesta->estado = $consulta->result()[0]->bool;
        $respuesta->mensaje = $consulta->result()[0]->message;  
        return $respuesta;
    }

    function Obtener_Total_Carro($rut,$sucursal){
        $this->load->database();
        $consulta = "SELECT sum(total) AS total FROM carrito 
        WHERE rut = ?
        AND cod_sucursal = ?";
        $output = $this->db->query($consulta,array($rut,$sucursal));
        if($output->num_rows()>0){
            return $output->result()[0]->total;
        }else{
            return FALSE;
        }

    }

    function Agregar_Carro($codigo,$cantidad){
        $this->load->database();
        $consulta = "SELECT cod_sucursal FROM carrito WHERE rut = ?";
        $output = $this->db->query($consulta,array($this->session->rut));
        $verificador = FALSE;
        if($output->num_rows()>0){
            $suc = $output->result()[0]->cod_sucursal;
            if($suc==$this->session->sucursal){
                $verificador = TRUE;
            }else{
                $limpiar_carro = $this->Limpiar_Carro();
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
            $procedimiento = "select bool,message from agrega_carrito(?,?,?,?,?,?);";
            $consulta = $this->db->query($procedimiento,array($rut,$codigo,$sucursal,$fecha_i,$fecha_f,$cantidad));
            $respuesta = new stdClass();
            $respuesta->estado = $consulta->result()[0]->bool;
            $respuesta->mensaje = $consulta->result()[0]->message; 
            return $respuesta;
        }
    }

    function Quitar_Carro($codigo,$cantidad){
        $this->load->database();
        $consulta = "SELECT cod_sucursal FROM carrito WHERE rut = ?";
        $output = $this->db->query($consulta,array($this->session->rut));
        $verificador = FALSE;
        if($output->num_rows()>0){
            $suc = $output->result()[0]->cod_sucursal;
            if($suc==$this->session->sucursal){
                $verificador = TRUE;
            }else{
                $limpiar_carro = $this->Limpiar_Carro();
                if($limpiar_carro->estado==TRUE){
                    $respuesta = new stdClass();
                    $respuesta->estado = 'REFRESH';
                    $respuesta->mensaje = 'SE HA VACIADO EL carrito YA QUE CAMBIÓ LA sucursal';
                }else{
                    $respuesta = new stdClass();
                    $respuesta->estado = 'ERROR';
                    $respuesta->mensaje = 'HA OCURRIDO UN ERROR EN LA FUNCION QUITAR_carrito';
                }
                return $respuesta;
            }
        }else{
            $verificador = TRUE;
        }
        if($verificador==TRUE){
            $rut = $this->session->rut;
            $sucursal = $this->session->sucursal;
            $procedimiento = "select bool,message from quita_carrito(?,?,?,?);";
            $consulta = $this->db->query($procedimiento,array($rut,$codigo,$sucursal,$cantidad));
            $respuesta = new stdClass();
            $respuesta->estado = $consulta->result()[0]->bool;
            $respuesta->mensaje = $consulta->result()[0]->message; 
            return $respuesta;
        }
    }

    function Realizar_Registro($datos){
        $this->load->database();
        $procedimiento = "select bool,message from insertar_usuario(?,?,?,?,?,?,?);";
        $consulta = $this->db->query($procedimiento,array($datos->Rut,$datos->Nombres,$datos->Apellidos,$datos->Correo,$datos->Pass,$datos->Direccion,$datos->Telefono));
        $respuesta = new stdClass();
        $respuesta->estado = $consulta->result()[0]->bool;
        $respuesta->mensaje = $consulta->result()[0]->message;                   
        return $respuesta;
    }

    function Limpiar_Carro(){
        if($this->session->estado==TRUE){
            $rut = $this->session->rut;
            $this->load->database();
            $procedimiento = "select bool,message from vaciar_carrito(?);";
            $consulta = $this->db->query($procedimiento,array($rut));
            $conexion = oci_connect($this->db->username,$this->db->password,$this->db->hostname);
            $respuesta = new stdClass();
            $respuesta->estado = $consulta->result()[0]->bool;
            $respuesta->mensaje = $consulta->result()[0]->message;                   
            return $respuesta;
        }else{
            redirect(base_url());
        }
    }

    function Realizar_Arriendo(){
        if($this->session->estado==TRUE){
            $rut = $this->session->rut;
            $sucursal = $this->session->sucursal;
            $fecha_inicio = $this->session->inicio;
            $fecha_final = $this->session->fin;
            $this->load->database();
            $procedimiento = "select bool,message,codigo_arriendo from arrendar(?,?,?,?);";
            $consulta = $this->db->query($procedimiento,array($fecha_inicio,$fecha_final,$rut,$sucursal));
            $respuesta = new stdClass();
            $respuesta->estado = $consulta->result()[0]->bool;
            $respuesta->mensaje = $consulta->result()[0]->message;
            $respuesta->arriendo = $consulta->result()[0]->codigo_arriendo;                               
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
        $consulta = "SELECT A.cod_arriendo,to_char(A.fecha_inicio, 'DD/MM/YYYY') as fecha_inicio,
                    to_char(A.fecha_final, 'DD/MM/YYYY') AS fecha_final,A.total,A.rut_U,U.nombres,
                    U.apellidos,to_char(A.fecha_arriendo, 'DD/MM/YYYY') AS fecha_arriendo,
                    S.cod_sucursal,S.nombre
                    FROM arriendo A JOIN sucursal S 
                    ON A.COD_S = S.cod_sucursal JOIN usuario U
                    ON A.rut_U = U.rut
                    WHERE cod_arriendo = ?
                    AND U.rut = ?";
        $output = $this->db->query($consulta,array($value,$this->session->rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Mas_Arrendados(){
        $this->load->database();
        $consulta = "SELECT D.cod_h, H.nombre, H.url_foto,SH.precio, COUNT(D.id_a) AS cantidad_arriendos 
        FROM detalle D JOIN herramienta H
        ON D.cod_h = H.cod_herramienta
        JOIN sucursal_herramienta SH
        ON SH.cod_herramienta = H.cod_herramienta
        WHERE SH.cod_sucursal = ?
        GROUP BY cod_h, H.nombre, H.url_foto, SH.precio ORDER BY cantidad_arriendos DESC";
        $output = $this->db->query($consulta,array($this->session->sucursal));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function Obtener_Detalle($value, $sucursal){
        $this->load->database();
        $consulta = "SELECT D.cod_h, H.nombre,H.url_foto,D.cantidad,D.total_detalle,SH.precio 
                    FROM detalle D JOIN herramienta H
                    ON D.cod_h = H.cod_herramienta JOIN sucursal_herramienta SH
                    ON H.cod_herramienta = SH.cod_herramienta
                    WHERE id_a = ?
                    AND SH.cod_sucursal = ?";
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