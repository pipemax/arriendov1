<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_Model extends CI_model{

    function obtener_cantidad_carro($rut){
        $this->load->database();
        $consulta = "SELECT COUNT(*) as cantidad FROM carrito WHERE rut = ?";
        $output = $this->db->query($consulta,array($rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    //SIN USO
    function obtener_sucursal($sucursal){
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

    //SIN USO
    function obtener_sucursales(){
        $this->load->database();
        $consulta = "SELECT cod_sucursal,nombre FROM sucursal";
        $output = $this->db->query($consulta);
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    //REVISADO
    function obtener_categorias(){
        $this->load->database();
        $consulta = "SELECT C.cod_categoria,C.nombre, COUNT(H.cod_herramienta) AS contador 
        FROM categoria C
        JOIN herramienta H
        ON H.cod_categoria = C.cod_categoria
        JOIN sucursal_herramienta SH
        ON SH.cod_herramienta = H.cod_herramienta
        AND SH.empresa = H.empresa
        JOIN sucursal SU
        ON SH.cod_sucursal = SU.cod_sucursal
        AND SU.comuna = ?
        GROUP BY C.cod_categoria,C.nombre";
        $output = $this->db->query($consulta,array($this->session->comuna));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    //REVISADO
    function obtener_categoria($value){
        if($value==null){
            return "Todas las Herramientas";
        }else{
            $this->load->database();
            $consulta = "SELECT initcap(C.nombre) AS nombre FROM categoria C
            JOIN herramienta H
            ON H.cod_categoria = C.cod_categoria
            JOIN sucursal_herramienta SH
            ON SH.cod_herramienta = H.cod_herramienta
            AND SH.empresa = H.empresa
            WHERE C.cod_categoria = ?";
            $output = $this->db->query($consulta,array($value));
            if($output->num_rows()>0){
                return "Herramientas de ".$output->result()[0]->nombre;
            }else{
                return 'No hay herramientas con esta categoria en esta sucursal';
            }
        }
    }

    //REVISADO
    function total_productos($datos, $busqueda = null){
        $this->load->database();
        $output = NULL;
        $categoria = $datos->categoria;
        if($busqueda == null){
            if($categoria==null){
                $consulta = "SELECT cod_herramienta, nombre, descripcion, url_foto, precio, verificar_descuento(cod_herramienta,empresa,cod_sucursal) as descuento, nombreC, empresa, empresan, cod_sucursal, nombres, stock FROM
                (SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, (SH.stock - SUM(D.cantidad)) AS stock
                FROM herramienta H JOIN categoria C 
                ON H.cod_categoria=C.cod_categoria
                JOIN sucursal_herramienta SH
                ON H.cod_herramienta = SH.cod_herramienta
                AND H.empresa = SH.empresa
                JOIN sucursal SU
                ON SH.cod_sucursal = SU.cod_sucursal
                JOIN detalle D 
                ON D.cod_h = SH.cod_herramienta
                AND D.empresa = SH.empresa
                AND D.cod_sucursal = SH.cod_sucursal
                JOIN empresa E
                ON E.cod_empresa = SU.cod_empresa
                WHERE D.id_a IN (SELECT A.cod_arriendo FROM arriendo A JOIN detalle D 
                                            ON A.cod_arriendo=D.id_a JOIN sucursal_herramienta H
                                            ON D.cod_h = H.cod_herramienta
                                            AND D.empresa = H.empresa
                                            AND D.cod_sucursal = H.cod_sucursal
                                            WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            GROUP BY A.cod_arriendo)
                AND SU.comuna = ".$this->session->comuna." 
                GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre, H.empresa, E.nombre, SU.cod_sucursal, SU.nombre, SH.stock
                UNION 
                SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, SH.stock AS stock
                FROM herramienta H JOIN categoria C 
                ON H.cod_categoria = C.cod_categoria
                JOIN sucursal_herramienta SH
                ON H.cod_herramienta = SH.cod_herramienta
                AND H.empresa = SH.empresa
                JOIN sucursal SU
                ON SH.cod_sucursal = SU.cod_sucursal
                FULL OUTER JOIN detalle D 
                ON D.cod_h = SH.cod_herramienta
                AND D.empresa = SH.empresa
                AND D.cod_sucursal = SH.cod_sucursal
                JOIN empresa E
                ON E.cod_empresa = SU.cod_empresa
                WHERE (H.cod_herramienta,SH.cod_sucursal) NOT IN (SELECT H.cod_herramienta,H.cod_sucursal FROM arriendo A JOIN detalle D 
                                            ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                            ON D.cod_h = H.cod_herramienta
                                            AND D.empresa = H.empresa
                                            AND D.cod_sucursal = H.cod_sucursal
                                            WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
                AND SU.comuna = ".$this->session->comuna.") as RETORNO";
                $output = $this->db->query($consulta);
            }else{
                $consulta = "SELECT cod_herramienta, nombre, descripcion, url_foto, precio, verificar_descuento(cod_herramienta,empresa,cod_sucursal) as descuento, nombreC, empresa, empresan, cod_sucursal, nombres, stock FROM
                (SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, (SH.stock - SUM(D.cantidad)) AS stock
                FROM herramienta H JOIN categoria C 
                ON H.cod_categoria=C.cod_categoria
                JOIN sucursal_herramienta SH
                ON H.cod_herramienta = SH.cod_herramienta
                AND H.empresa = SH.empresa
                JOIN sucursal SU
                ON SH.cod_sucursal = SU.cod_sucursal
                JOIN detalle D 
                ON D.cod_h = SH.cod_herramienta
                AND D.empresa = SH.empresa
                AND D.cod_sucursal = SH.cod_sucursal
                JOIN empresa E
                ON E.cod_empresa = SU.cod_empresa
                WHERE D.id_a IN (SELECT A.cod_arriendo FROM arriendo A JOIN detalle D 
                                            ON A.cod_arriendo=D.id_a JOIN sucursal_herramienta H
                                            ON D.cod_h = H.cod_herramienta
                                            AND D.empresa = H.empresa
                                            AND D.cod_sucursal = H.cod_sucursal
                                            WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            GROUP BY A.cod_arriendo)
                AND SU.comuna = ".$this->session->comuna."
                AND H.cod_categoria = ?
                GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre, H.empresa, E.nombre, SU.cod_sucursal, SU.nombre, SH.stock
                UNION
                SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, SH.stock AS stock
                FROM herramienta H JOIN categoria C 
                ON H.cod_categoria = C.cod_categoria
                JOIN sucursal_herramienta SH
                ON H.cod_herramienta = SH.cod_herramienta
                AND H.empresa = SH.empresa
                JOIN sucursal SU
                ON SH.cod_sucursal = SU.cod_sucursal
                FULL OUTER JOIN detalle D 
                ON D.cod_h = SH.cod_herramienta
                AND D.empresa = SH.empresa
                AND D.cod_sucursal = SH.cod_sucursal
                JOIN empresa E
                ON E.cod_empresa = SU.cod_empresa
                WHERE (H.cod_herramienta,SH.cod_sucursal) NOT IN (SELECT H.cod_herramienta,H.cod_sucursal FROM arriendo A JOIN detalle D 
                                            ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                            ON D.cod_h = H.cod_herramienta
                                            AND D.empresa = H.empresa
                                            AND D.cod_sucursal = H.cod_sucursal
                                            WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
                AND SU.comuna = ".$this->session->comuna."
                AND H.cod_categoria = ?) as RETORNO";
                $output = $this->db->query($consulta,array((int)$categoria,(int)$categoria));
            }
        }else{
            if($categoria==null){
                $consulta = "SELECT cod_herramienta, nombre, descripcion, url_foto, precio, verificar_descuento(cod_herramienta,empresa,cod_sucursal) as descuento,nombreC, empresa, empresa, cod_sucursal, nombres, stock FROM
                (SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, (SH.stock - SUM(D.cantidad)) AS stock
                FROM herramienta H JOIN categoria C 
                ON H.cod_categoria=C.cod_categoria
                JOIN sucursal_herramienta SH
                ON H.cod_herramienta = SH.cod_herramienta
                AND H.empresa = SH.empresa
                JOIN sucursal SU
                ON SH.cod_sucursal = SU.cod_sucursal
                JOIN detalle D 
                ON D.cod_h = SH.cod_herramienta
                AND D.empresa = SH.empresa
                AND D.cod_sucursal = SH.cod_sucursal
                JOIN empresa E
                ON E.cod_empresa = SU.cod_empresa
                WHERE D.id_a IN (SELECT A.cod_arriendo FROM arriendo A JOIN detalle D 
                                            ON A.cod_arriendo=D.id_a JOIN sucursal_herramienta H
                                            ON D.cod_h = H.cod_herramienta
                                            AND D.empresa = H.empresa
                                            AND D.cod_sucursal = H.cod_sucursal
                                            WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            GROUP BY A.cod_arriendo)
                AND SU.comuna = ".$this->session->comuna."
                AND (LOWER(H.nombre) like '%".strtolower($busqueda)."%'
                OR LOWER(H.descripcion) like '%".strtolower($busqueda)."%')
                GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre, H.empresa, E.nombre, SU.cod_sucursal, SU.nombre, SH.stock
                UNION 
                SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, SH.stock AS stock
                FROM herramienta H JOIN categoria C 
                ON H.cod_categoria = C.cod_categoria
                JOIN sucursal_herramienta SH
                ON H.cod_herramienta = SH.cod_herramienta
                AND H.empresa = SH.empresa
                JOIN sucursal SU
                ON SH.cod_sucursal = SU.cod_sucursal
                FULL OUTER JOIN detalle D 
                ON D.cod_h = SH.cod_herramienta
                AND D.empresa = SH.empresa
                AND D.cod_sucursal = SH.cod_sucursal
                JOIN empresa E
                ON E.cod_empresa = SU.cod_empresa
                WHERE (H.cod_herramienta,SH.cod_sucursal) NOT IN (SELECT H.cod_herramienta,H.cod_sucursal FROM arriendo A JOIN detalle D 
                                            ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                            ON D.cod_h = H.cod_herramienta
                                            AND D.empresa = H.empresa
                                            AND D.cod_sucursal = H.cod_sucursal
                                            WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
                AND SU.comuna = ".$this->session->comuna."
                AND (LOWER(H.nombre) like '%".strtolower($busqueda)."%'
                OR LOWER(H.descripcion) like '%".strtolower($busqueda)."%')) as RETORNO";
                $output = $this->db->query($consulta);
            }else{
                $consulta = "SELECT cod_herramienta, nombre, descripcion, url_foto, precio, verificar_descuento(cod_herramienta,empresa,cod_sucursal) as descuento, nombreC, empresa, empresan, cod_sucursal, nombres, stock FROM
                (SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, (SH.stock - SUM(D.cantidad)) AS stock
                FROM herramienta H JOIN categoria C 
                ON H.cod_categoria = C.cod_categoria
                JOIN sucursal_herramienta SH
                ON H.cod_herramienta = SH.cod_herramienta
                AND H.empresa = SH.empresa
                JOIN sucursal SU
                ON SH.cod_sucursal = SU.cod_sucursal
                JOIN detalle D 
                ON D.cod_h = SH.cod_herramienta
                AND D.empresa = SH.empresa
                AND D.cod_sucursal = SH.cod_sucursal
                JOIN empresa E
                ON E.cod_empresa = SU.cod_empresa
                WHERE D.id_a IN (SELECT A.cod_arriendo FROM arriendo A JOIN detalle D 
                                            ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                            ON D.cod_h = H.cod_herramienta
                                            AND D.empresa = H.empresa
                                            AND D.cod_sucursal = H.cod_sucursal
                                            WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            GROUP BY A.cod_arriendo)
                AND SU.comuna = ".$this->session->comuna."
                AND H.cod_categoria = ?
                AND LOWER(H.nombre) like '%".strtolower($busqueda)."%'
                OR LOWER(H.descripcion) like '%".strtolower($busqueda)."%'
                GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre, H.empresa, E.nombre, SU.cod_sucursal, SU.nombre, SH.stock
                UNION
                SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, SH.stock AS stock
                FROM herramienta H JOIN categoria C 
                ON H.cod_categoria = C.cod_categoria
                JOIN sucursal_herramienta SH
                ON H.cod_herramienta = SH.cod_herramienta
                AND H.empresa = SH.empresa
                JOIN sucursal SU
                ON SH.cod_sucursal = SU.cod_sucursal
                FULL OUTER JOIN detalle D 
                ON D.cod_h = SH.cod_herramienta
                AND D.empresa = SH.empresa
                AND D.cod_sucursal = SH.cod_sucursal
                JOIN empresa E
                ON E.cod_empresa = SU.cod_empresa
                WHERE (H.cod_herramienta,SH.cod_sucursal) NOT IN (SELECT H.cod_herramienta,H.cod_sucursal FROM arriendo A JOIN detalle D 
                                            ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                            ON D.cod_h = H.cod_herramienta
                                            AND D.empresa = H.empresa
                                            AND D.cod_sucursal = H.cod_sucursal
                                            WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                            OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
                AND SU.comuna = ".$this->session->comuna."
                AND H.cod_categoria = ?
                AND (LOWER(H.nombre) like '%".strtolower($busqueda)."%'
                OR LOWER(H.descripcion) like '%".strtolower($busqueda)."%')) as RETORNO";
                $output = $this->db->query($consulta,array((int)$categoria,(int)$categoria));
            }
        }
        if($output->num_rows()>0){
            return $output->num_rows();
        }else{
            return 0;
        }
    }

    //REVISADO
    function limite_paginacion($pagina, $filas, $items){
        $limite = $filas;
        $offset = ($pagina - 1)  * $items;
        $data = new stdClass();
        $data->limite = $items;
        $data->offset = $offset;
        return $data;
    }

    //REVISADO
    function obtener_busqueda($datos,$busqueda){
        $this->load->database();
        $output = NULL;
        $pagina = $datos->pagina;
        $categoria = $datos->categoria;
        $stock = $datos->stock;
        $filas = $datos->filas;
        $precio = $datos->precio;
        $sucursal = $this->session->sucursal;
        $limite = $this->limite_paginacion($pagina,$filas,$datos->items);
        $consulta = "SELECT cod_herramienta, nombre, descripcion, url_foto, precio, verificar_descuento(cod_herramienta,empresa,cod_sucursal) as descuento, nombreC, empresa, empresan, cod_sucursal, nombres, stock FROM
        (SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, (SH.stock - SUM(D.cantidad)) AS stock
        FROM herramienta H JOIN categoria C 
        ON H.cod_categoria=C.cod_categoria
        JOIN sucursal_herramienta SH
        ON H.cod_herramienta = SH.cod_herramienta
        AND H.empresa = SH.empresa
        JOIN sucursal SU
        ON SH.cod_sucursal = SU.cod_sucursal
        JOIN detalle D 
        ON D.cod_h = SH.cod_herramienta
        AND D.empresa = SH.empresa
        AND D.cod_sucursal = SH.cod_sucursal
        JOIN empresa E
        ON E.cod_empresa = SU.cod_empresa
        WHERE D.id_a IN (SELECT A.cod_arriendo FROM arriendo A JOIN detalle D 
                                    ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                    ON D.cod_h = H.cod_herramienta
                                    AND D.empresa = H.empresa
                                    AND D.cod_sucursal = H.cod_sucursal
                                    WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                    OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                    GROUP BY A.cod_arriendo)
        AND SU.comuna = ".$this->session->comuna."
        AND (LOWER(H.nombre) like '%".strtolower($busqueda)."%'
        OR LOWER(H.descripcion) like '%".strtolower($busqueda)."%')
        GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre, H.empresa, E.nombre, SU.cod_sucursal, SU.nombre, SH.stock
        UNION 
        SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, SH.stock AS stock
        FROM herramienta H JOIN categoria C 
        ON H.cod_categoria = C.cod_categoria
        JOIN sucursal_herramienta SH
        ON H.cod_herramienta = SH.cod_herramienta
        AND H.empresa = SH.empresa
        JOIN sucursal SU
        ON SH.cod_sucursal = SU.cod_sucursal
        FULL OUTER JOIN detalle D 
        ON D.cod_h = SH.cod_herramienta
        AND D.empresa = SH.empresa
        AND D.cod_sucursal = SH.cod_sucursal
        JOIN empresa E
        ON E.cod_empresa = SU.cod_empresa
        WHERE (H.cod_herramienta,SH.cod_sucursal) NOT IN (SELECT H.cod_herramienta,H.cod_sucursal FROM arriendo A JOIN detalle D 
                                    ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                    ON D.cod_h = H.cod_herramienta
                                    AND D.empresa = H.empresa
                                    AND D.cod_sucursal = H.cod_sucursal
                                    WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                    OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
        AND SU.comuna = ".$this->session->comuna."
        AND (LOWER(H.nombre) like '%".strtolower($busqueda)."%'
        OR LOWER(H.descripcion) like '%".strtolower($busqueda)."%')) as RETORNO
        order by stock ".$stock.",precio ".$precio."
        limit ".$limite->limite."
        offset ".$limite->offset."";
        $output = $this->db->query($consulta);        
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    //REVISADO
    function obtener_productos($datos){
        $this->load->database();
        $output = NULL;
        $pagina = $datos->pagina;
        $categoria = $datos->categoria;
        $stock = $datos->stock;
        $filas = $datos->filas;
        $precio = $datos->precio;
        $sucursal = $this->session->sucursal;
        $limite = $this->limite_paginacion($pagina,$filas,$datos->items);
        if($categoria==null){
            $consulta = "SELECT cod_herramienta, nombre, descripcion, url_foto, precio, verificar_descuento(cod_herramienta,empresa,cod_sucursal) as descuento, nombreC, empresa, empresan, cod_sucursal, nombres, stock FROM
            (SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, (SH.stock - SUM(D.cantidad)) AS stock
            FROM herramienta H JOIN categoria C 
            ON H.cod_categoria=C.cod_categoria
            JOIN sucursal_herramienta SH
            ON H.cod_herramienta = SH.cod_herramienta
            AND H.empresa = SH.empresa
            JOIN sucursal SU
            ON SH.cod_sucursal = SU.cod_sucursal
            JOIN detalle D 
            ON D.cod_h = SH.cod_herramienta
            AND D.empresa = SH.empresa
            AND D.cod_sucursal = SH.cod_sucursal
            JOIN empresa E
            ON E.cod_empresa = SU.cod_empresa
            WHERE D.id_a IN (SELECT A.cod_arriendo FROM arriendo A JOIN detalle D 
                                        ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                        ON D.cod_h = H.cod_herramienta
                                        AND D.empresa = H.empresa
                                        AND D.cod_sucursal = H.cod_sucursal
                                        WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                        GROUP BY A.cod_arriendo)
            AND SU.comuna = ".$this->session->comuna."
            GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre, H.empresa, E.nombre, SU.cod_sucursal, SU.nombre, SH.stock
            UNION 
            SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, SH.stock AS stock
            FROM herramienta H JOIN categoria C 
            ON H.cod_categoria = C.cod_categoria
            JOIN sucursal_herramienta SH
            ON H.cod_herramienta = SH.cod_herramienta
            AND H.empresa = SH.empresa
            JOIN sucursal SU
            ON SH.cod_sucursal = SU.cod_sucursal
            FULL OUTER JOIN detalle D 
            ON D.cod_h = SH.cod_herramienta
            AND D.empresa = SH.empresa
            AND D.cod_sucursal = SH.cod_sucursal
            JOIN empresa E
            ON E.cod_empresa = SU.cod_empresa
            WHERE (H.cod_herramienta,SH.cod_sucursal) NOT IN (SELECT H.cod_herramienta,H.cod_sucursal FROM arriendo A JOIN detalle D 
                                        ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                        ON D.cod_h = H.cod_herramienta
                                        AND D.empresa = H.empresa
                                        AND D.cod_sucursal = H.cod_sucursal
                                        WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
            AND SU.comuna = ".$this->session->comuna.") as RETORNO
            order by stock ".$stock.",precio ".$precio."
            limit ".$limite->limite."
            offset ".$limite->offset."";
            $output = $this->db->query($consulta);
        }else{
            $consulta = "SELECT cod_herramienta, nombre, descripcion, url_foto, precio, verificar_descuento(cod_herramienta,empresa,cod_sucursal) as descuento, nombreC, empresa, empresan, cod_sucursal, nombres, stock FROM
            (SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, (SH.stock - SUM(D.cantidad)) AS stock
            FROM herramienta H JOIN categoria C 
            ON H.cod_categoria = C.cod_categoria
            JOIN sucursal_herramienta SH
            ON H.cod_herramienta = SH.cod_herramienta
            AND H.empresa = SH.empresa
            JOIN sucursal SU
            ON SH.cod_sucursal = SU.cod_sucursal
            JOIN detalle D 
            ON D.cod_h = SH.cod_herramienta
            AND D.empresa = SH.empresa
            AND D.cod_sucursal = SH.cod_sucursal
            JOIN empresa E
            ON E.cod_empresa = SU.cod_empresa
            WHERE D.id_a IN (SELECT A.cod_arriendo FROM arriendo A JOIN detalle D 
                                        ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                        ON D.cod_h = H.cod_herramienta
                                        AND D.empresa = H.empresa
                                        AND D.cod_sucursal = H.cod_sucursal
                                        WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                        GROUP BY A.cod_arriendo)
            AND SU.comuna = ".$this->session->comuna."
            AND H.cod_categoria = ?
            GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre, H.empresa, E.nombre, SU.cod_sucursal, SU.nombre, SH.stock
            UNION
            SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, SH.stock AS stock
            FROM herramienta H JOIN categoria C 
            ON H.cod_categoria = C.cod_categoria
            JOIN sucursal_herramienta SH
            ON H.cod_herramienta = SH.cod_herramienta
            AND H.empresa = SH.empresa
            JOIN sucursal SU
            ON SH.cod_sucursal = SU.cod_sucursal
            FULL OUTER JOIN detalle D 
            ON D.cod_h = SH.cod_herramienta
            AND D.empresa = SH.empresa
            AND D.cod_sucursal = SH.cod_sucursal
            JOIN empresa E
            ON E.cod_empresa = SU.cod_empresa
            WHERE (H.cod_herramienta,SH.cod_sucursal) NOT IN (SELECT H.cod_herramienta,H.cod_sucursal FROM arriendo A JOIN detalle D 
                                        ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                        ON D.cod_h = H.cod_herramienta
                                        AND D.empresa = H.empresa
                                        AND D.cod_sucursal = H.cod_sucursal
                                        WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                        OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY'))
            AND SU.comuna = ".$this->session->comuna."
            AND H.cod_categoria = ?) as RETORNO
            order by stock ".$stock.",precio ".$precio."
            limit ".$limite->limite."
            offset ".$limite->offset."";
            $output = $this->db->query($consulta,array((int)$categoria,(int)$categoria));
        }
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    //REVISADO
    function obtener_producto($codigo_h,$codigo_s,$codigo_e){
        $this->load->database();
        $output = NULL;
        $consulta = "SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, verificar_descuento(H.cod_herramienta,H.empresa,SU.cod_sucursal) as descuento, C.nombre AS nombreC, H.empresa, E.nombre as empresan, SU.cod_sucursal, SU.nombre as nombres, (SH.stock - SUM(D.cantidad)) AS stock
        FROM herramienta H JOIN categoria C 
        ON H.cod_categoria = C.cod_categoria
        JOIN sucursal_herramienta SH
        ON H.cod_herramienta = SH.cod_herramienta
        AND H.empresa = SH.empresa
        JOIN sucursal SU
        ON SH.cod_sucursal = SU.cod_sucursal
        JOIN detalle D 
        ON D.cod_h = SH.cod_herramienta
        AND D.empresa = SH.empresa
        AND D.cod_sucursal = SH.cod_sucursal
        JOIN empresa E
        ON E.cod_empresa = SU.cod_empresa
        WHERE D.id_a IN (SELECT A.cod_arriendo FROM arriendo A JOIN detalle D 
                            ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                            ON D.cod_h = H.cod_herramienta
                            AND D.empresa = H.empresa
                            AND D.cod_sucursal = H.cod_sucursal
                            WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                            OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                            AND H.cod_herramienta = ".$codigo_h."
                            AND H.empresa = ".$codigo_e."
                            AND H.cod_sucursal = ".$codigo_s."
                            GROUP BY A.cod_arriendo)
        AND SU.comuna = ".$this->session->comuna."
        AND H.cod_herramienta = ".$codigo_h."
        AND H.empresa = ".$codigo_e."
        AND SH.cod_sucursal = ".$codigo_s."
        GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre, H.empresa, E.nombre, SU.cod_sucursal, SU.nombre, SH.stock
        UNION 
        SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, SH.descuento, C.nombre AS nombreC, H.empresa, E.nombre, SU.cod_sucursal, SU.nombre as nombres, SH.stock AS stock
        FROM herramienta H JOIN categoria C 
        ON H.cod_categoria = C.cod_categoria
        JOIN sucursal_herramienta SH
        ON H.cod_herramienta = SH.cod_herramienta
        AND H.empresa = SH.empresa
        JOIN sucursal SU
        ON SH.cod_sucursal = SU.cod_sucursal
        FULL OUTER JOIN detalle D 
        ON D.cod_h = SH.cod_herramienta
        AND D.empresa = SH.empresa
        AND D.cod_sucursal = SH.cod_sucursal
        JOIN empresa E
        ON E.cod_empresa = SU.cod_empresa
        WHERE (H.cod_herramienta,SH.cod_sucursal) NOT IN (SELECT H.cod_herramienta,H.cod_sucursal FROM arriendo A JOIN detalle D 
                                    ON A.cod_arriendo = D.id_a JOIN sucursal_herramienta H
                                    ON D.cod_h = H.cod_herramienta
                                    AND D.empresa = H.empresa
                                    AND D.cod_sucursal = H.cod_sucursal
                                    WHERE A.fecha_inicio between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                    OR A.fecha_final between to_date('".$this->session->inicio."', 'DD/MM/YYYY') AND to_date('".$this->session->fin."', 'DD/MM/YYYY')
                                    AND H.cod_herramienta = ".$codigo_h."
                                    AND H.empresa = ".$codigo_e."
                                    AND H.cod_sucursal = ".$codigo_s.")
        AND SU.comuna = ".$this->session->comuna."
        AND H.cod_herramienta = ".$codigo_h."
        AND H.empresa = ".$codigo_e."
        AND SH.cod_sucursal = ".$codigo_s."";
        $output = $this->db->query($consulta);
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    //REVISADO
    function validar_sesion($rut,$pass){
        $this->load->database();
        $procedimiento = "select bool,message from inicio_sesion(?,?);";
        $consulta = $this->db->query($procedimiento,array($rut,$pass));
        $respuesta = new stdClass();
        $respuesta->estado = $consulta->result()[0]->bool;
        $respuesta->mensaje = $consulta->result()[0]->message;   
        if($respuesta->estado=='TRUE'){
            $this->_asignar_sesion($rut);
        }
        return $respuesta;
    }

    //REVISADO
    private function _asignar_sesion($rut){
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


    function verificar_carro(){
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

    function verificar_comuna_carro($region,$comuna){
        $this->load->database();
        $consulta = "select bool,message from verificar_comuna(?,?,?)";
        $output = $this->db->query($consulta,array($this->session->rut,$region,$comuna));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    function obtener_carro(){
        if($this->session->estado==TRUE){
            $this->load->database();
            $consulta = "SELECT C.cod_herramienta, H.nombre, H.url_foto, C.cantidad, C.total, C.cod_sucursal, SU.nombre as nombres,
            E.cod_empresa, E.nombre as nombree, verificar_descuento(C.cod_herramienta,E.cod_empresa,C.cod_sucursal) as descuento,
            verificar_producto_venta(?,?,C.cod_sucursal,C.cod_herramienta,C.empresa) as disponibilidad, SH.precio 
            FROM carrito C
            JOIN herramienta H
            ON C.cod_herramienta = H.cod_herramienta
            AND C.empresa = H.empresa
            JOIN sucursal_herramienta SH
            ON SH.cod_herramienta = H.cod_herramienta
            AND SH.empresa = H.empresa
            JOIN sucursal SU
            ON SU.cod_sucursal = SH.cod_sucursal
            AND C.cod_sucursal = SH.cod_sucursal
            JOIN empresa E
            ON H.empresa = E.cod_empresa
            WHERE C.rut = ?";
            $output = $this->db->query($consulta,array(
                                        $this->session->inicio,
                                        $this->session->fin,
                                        $this->session->rut));
            if($output->num_rows()>0){
                return $output->result();
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    function borrar_herramienta_carro($datos){
        $this->load->database();
        $procedimiento = "select bool,message from borrar_herramienta_carrito(?,?,?,?);";
        $consulta = $this->db->query($procedimiento,array($datos->rut,$datos->codigo,$datos->sucursal,$datos->empresa));
        $respuesta = new stdClass();
        $respuesta->estado = $consulta->result()[0]->bool;
        $respuesta->mensaje = $consulta->result()[0]->message;  
        return $respuesta;
    }

    function obtener_total_carro($rut){
        $this->load->database();
        $consulta = "SELECT sum(total) AS total FROM carrito 
        WHERE rut = ?
        AND cantidad <= verificar_producto_venta(?,?,cod_sucursal,cod_herramienta,empresa);";
        $output = $this->db->query($consulta,array($rut,$this->session->inicio,$this->session->fin));
        if($output->num_rows()>0){
            return $output->result()[0]->total;
        }else{
            return FALSE;
        }

    }

    function agregar_carro($datos){
        $this->load->database();
        $consulta = "select comuna 
                    from sucursal 
                    where cod_sucursal in (SELECT cod_sucursal 
                                        FROM carrito 
                                        WHERE rut = ?)
                    group by comuna";
        $output = $this->db->query($consulta,array($this->session->rut));
        $verificador = FALSE;
        if($output->num_rows()>0){
            $comuna = $output->result()[0]->comuna;
            if($comuna==$this->session->comuna){
                $verificador = TRUE;
            }else{
                $limpiar_carro = $this->limpiar_carro();
                if($limpiar_carro->estado==TRUE){
                    $respuesta = new stdClass();
                    $respuesta->estado = 'REFRESH';
                    $respuesta->mensaje = 'SE HA VACIADO EL CARRITO YA QUE LA COMUNA HA CAMBIADO';
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
            $fecha_i = $this->session->inicio;
            $fecha_f = $this->session->fin;
            $comuna = $this->session->comuna;
            $procedimiento = "select bool,message from agrega_carrito(?,?,?,?,?,?,?,?);";
            $consulta = $this->db->query($procedimiento,array($rut,$datos->codigo,$datos->sucursal,$datos->empresa,$comuna,$fecha_i,$fecha_f,$datos->cantidad));
            $respuesta = new stdClass();
            $respuesta->estado = $consulta->result()[0]->bool;
            $respuesta->mensaje = $consulta->result()[0]->message; 
            return $respuesta;
        }
    }

    function quitar_carro($datos){
        $this->load->database();
        $consulta = "select comuna 
                    from sucursal 
                    where cod_sucursal in (SELECT cod_sucursal 
                                        FROM carrito 
                                        WHERE rut = ?)
                    group by comuna";
        $output = $this->db->query($consulta,array($this->session->rut));
        $verificador = FALSE;
        if($output->num_rows()>0){
           $comuna = $output->result()[0]->comuna;
            if($comuna==$this->session->comuna){
                $verificador = TRUE;
            }else{
                $limpiar_carro = $this->limpiar_carro();
                if($limpiar_carro->estado==TRUE){
                    $respuesta = new stdClass();
                    $respuesta->estado = 'REFRESH';
                    $respuesta->mensaje = 'SE HA VACIADO EL CARRITO YA QUE LA COMUNA HA CAMBIADO';
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
            $procedimiento = "select bool,message from quita_carrito(?,?,?,?,?);";
            $consulta = $this->db->query($procedimiento,array($rut,$datos->codigo,$datos->sucursal,$datos->empresa,$datos->cantidad));
            $respuesta = new stdClass();
            $respuesta->estado = $consulta->result()[0]->bool;
            $respuesta->mensaje = $consulta->result()[0]->message; 
            return $respuesta;
        }
    }

    function realizar_registro($datos){
        $this->load->database();
        $procedimiento = "select bool,message from insertar_usuario(?,?,?,?,?,?,?);";
        $consulta = $this->db->query($procedimiento,array($datos->rut,$datos->nombres,$datos->apellidos,$datos->correo,$datos->pass,$datos->direccion,$datos->telefono));
        $respuesta = new stdClass();
        $respuesta->estado = $consulta->result()[0]->bool;
        $respuesta->mensaje = $consulta->result()[0]->message;                   
        return $respuesta;
    }

    //REVISADO
    function limpiar_carro(){
        if($this->session->estado==TRUE){
            $rut = $this->session->rut;
            $this->load->database();
            $procedimiento = "select bool,message from vaciar_carrito(?);";
            $consulta = $this->db->query($procedimiento,array($rut));
            $respuesta = new stdClass();
            $respuesta->estado = $consulta->result()[0]->bool;
            $respuesta->mensaje = $consulta->result()[0]->message;                   
            return $respuesta;
        }else{
            redirect(base_url());
        }
    }

    function realizar_arriendo(){
        if($this->session->estado==TRUE){
            $rut = $this->session->rut;
            $fecha_inicio = $this->session->inicio;
            $fecha_final = $this->session->fin;
            $this->load->database();
            $procedimiento = "select bool,message,codigo_arriendo from arrendar(?,?,?);";
            $consulta = $this->db->query($procedimiento,array($fecha_inicio,$fecha_final,$rut));
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

    function obtener_arriendos(){
        $this->load->database();
        $consulta = "SELECT A.cod_arriendo,to_char(A.fecha_inicio, 'DD/MM/YYYY') as fecha_inicio,
                    to_char(A.fecha_final, 'DD/MM/YYYY') AS fecha_final,A.total, A.rut_U,
                    to_char(A.fecha_arriendo, 'DD/MM/YYYY') AS fecha_arriendo, A.estado, A.total
                    FROM arriendo A JOIN usuario U
                    ON A.rut_U = U.rut
                    AND U.rut = ?
                    ORDER BY A.fecha_arriendo DESC";
        $output = $this->db->query($consulta,array($this->session->rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_arriendo($value){
        $this->load->database();
        $consulta = "SELECT A.cod_arriendo,to_char(A.fecha_inicio, 'DD/MM/YYYY') as fecha_inicio,
                    to_char(A.fecha_final, 'DD/MM/YYYY') AS fecha_final,A.total,A.rut_U,U.nombres,
                    U.apellidos,to_char(A.fecha_arriendo, 'DD/MM/YYYY') AS fecha_arriendo, A.estado
                    FROM arriendo A JOIN usuario U
                    ON A.rut_U = U.rut
                    WHERE cod_arriendo = ?
                    AND U.rut = ?";
        $output = $this->db->query($consulta,array($value, $this->session->rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_mas_arrendados(){
        $this->load->database();
        $consulta = "SELECT D.cod_h, H.nombre, H.url_foto, SH.precio, COUNT(D.id_a) AS cantidad_arriendos 
        FROM detalle D JOIN sucursal_herramienta SH
        ON D.cod_h = SH.cod_herramienta
        AND D.empresa = SH.empresa
        AND D.cod_sucursal = SH.cod_sucursal
        JOIN herramienta H
        ON SH.cod_herramienta = H.cod_herramienta
        AND SH.empresa = H.empresa
        JOIN sucursal SU
        ON SH.cod_sucursal = SU.cod_sucursal
        WHERE SU.comuna = ?
        GROUP BY cod_h, H.nombre, H.url_foto, SH.precio ORDER BY cantidad_arriendos DESC";
        $output = $this->db->query($consulta,array($this->session->comuna));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_detalle($value){
        $this->load->database();
        $consulta = "SELECT D.cod_h, H.nombre, H.url_foto, D.cantidad, D.total_detalle, SH.precio, D.descuento,
                    D.estado, D.empresa, E.nombre as nombre_empresa, D.cod_sucursal, SU.nombre as nombre_sucursal
                    FROM detalle D JOIN sucursal_herramienta SH
                    ON D.cod_h = SH.cod_herramienta
                    AND D.empresa = SH.empresa
                    AND D.cod_sucursal = SH.cod_sucursal
                    JOIN herramienta H
                    ON H.cod_herramienta = SH.cod_herramienta
                    AND H.empresa = SH.empresa
                    JOIN empresa E
                    ON H.empresa = E.cod_empresa
                    JOIN sucursal SU
                    ON SH.cod_sucursal = SU.cod_sucursal
                    WHERE id_a = ?";
        $output = $this->db->query($consulta,array($value));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_usuario(){
        $this->load->database();
        $rut = $this->session->rut;
        $consulta = "select rut, nombres, apellidos, correo, direccion, celular
                    from usuario where rut = ?";
        $output = $this->db->query($consulta,array($rut));
        if($output->num_rows()>0){
            return $output->result()[0];
        }else{
            return FALSE;
        }
    }

    function actualizar_pass($data){
        $this->load->database();
        $rut = $this->session->rut;
        $procedimiento = "select bool,message from actualizar_password_user_verificar(?,?,?);";
        $consulta = $this->db->query($procedimiento,array($rut,$data->pass_old,$data->pass_new));
        $respuesta = new stdClass();
        $respuesta->estado = $consulta->result()[0]->bool;
        $respuesta->mensaje = $consulta->result()[0]->message;
        return $respuesta;
    }

    function actualizar_datos($data){
        $this->load->database();
        $rut = $this->session->rut;
        $procedimiento = "select bool,message from actualizar_user(?,?,?,?,?,?);";
        $consulta = $this->db->query($procedimiento,array($data->rut,$data->nombres,$data->apellidos,$data->correo,$data->direccion,$data->telefono));
        $respuesta = new stdClass();
        $respuesta->estado = $consulta->result()[0]->bool;
        $respuesta->mensaje = $consulta->result()[0]->message;
        return $respuesta;
    }

   
    function obtener_comunas($region){
        $this->load->database();
        $consulta = "select distinct c.comuna_id, c.comuna_nombre 
                    from region r
                    join provincia p
                    on r.region_id = p.provincia_region_id
                    join comuna c
                    on p.provincia_id = c.comuna_provincia_id
                    join sucursal su
                    on c.comuna_id = su.comuna
                    where su.cod_sucursal in (select cod_sucursal 
                                            from sucursal_herramienta
                                            group by cod_sucursal
                                            having count(cod_herramienta) > 0)
                    and r.region_id = ?
                    order by c.comuna_nombre";
        $output = $this->db->query($consulta,array($region));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function verificar_descuentos(){
        $this->load->database();
        $procedimiento = "select verificar_descuentos()";
        $this->db->query($procedimiento);
    }

    
    function obtener_regiones(){
        $this->load->database();
        $consulta = "select re.region_id,re.region_nombre 
                    from region re join provincia pro
                    on re.region_id = pro.provincia_region_id
                    join comuna c
                    on pro.provincia_id = c.comuna_provincia_id
                    join sucursal su
                    on c.comuna_id = su.comuna
                    where su.cod_sucursal in (select cod_sucursal 
                                            from sucursal_herramienta
                                            group by cod_sucursal
                                            having count(cod_herramienta) > 0)
                    group by re.region_id";
        $output = $this->db->query($consulta);
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

}