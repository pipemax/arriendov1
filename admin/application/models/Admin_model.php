<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_model{

    function validar_sesion($rut,$pass){
        $this->load->database();
        $procedimiento = "select bool,message from inicio_sesion_admin(?,?)";
        $consulta = $this->db->query($procedimiento,array($rut,$pass));
        $respuesta = new stdClass();
        $respuesta->estado = $consulta->result()[0]->bool;
        $respuesta->mensaje = $consulta->result()[0]->message;   
        if($respuesta->estado=='TRUE'){
            $this->_asignar_sesion($rut);
        }
        return $respuesta;
    }

    private function _asignar_sesion($rut){
        $this->load->database();
        $consulta = "SELECT rut,nombres,apellidos,rol FROM administrador WHERE rut = ?";
        $output = $this->db->query($consulta,array($rut));
        if($output->num_rows()>0){
            $this->session->estado = TRUE;
            $this->session->rut = $output->result()[0]->rut;
            $this->session->nombres = $output->result()[0]->nombres;
            $this->session->apellidos = $output->result()[0]->apellidos;
            $this->session->rol = $output->result()[0]->rol;
        }
    }

    public function obtener_usuarios(){
        $this->load->database();
        $consulta = "select rut, nombres, apellidos, correo, celular, direccion, estado
                    from usuario
                    where rut IN (select rut_u
                                from arriendo A
                                join detalle D
                                on A.cod_arriendo = D.id_a
                                where empresa = (select empresa 
                                    from administrador
                                    where rut = ?))";
        $output = $this->db->query($consulta,array($this->session->rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function obtener_usuario($rut){
        $this->load->database();
        $consulta = "select rut, nombres, apellidos, correo, celular, direccion, estado
                    from usuario
                    where rut = ?";
        $output = $this->db->query($consulta,array($rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function modificar_usuario($data){
        $this->load->database();
        $consulta = "select bool,message from actualizar_user(?,?,?,?,?,?)";
        $output = $this->db->query($consulta,array($data->rut,$data->nombres,$data->apellidos,$data->correo,$data->direccion,$data->celular));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function contrasena_usuario($data){
        $this->load->database();
        $consulta = "select bool,message from actualizar_password_user(?,?)";
        $output = $this->db->query($consulta,array($data->rut,$data->pass));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function obtener_administrador($rut){
        $this->load->database();
        $consulta = "select rut,nombres,apellidos,correo,celular,empresa,comuna  
                    from administrador
                    where empresa = (select empresa 
                                    from administrador
                                    where rut = ?)
                    and rut = ?";
        $output = $this->db->query($consulta,array($this->session->rut,$rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function contrasena_administrador($data){
        $this->load->database();
        $consulta = "select bool,message from actualizar_password_administrador(?,?)";
        $output = $this->db->query($consulta,array($data->rut,$data->pass));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function obtener_administradores(){
        $this->load->database();
        $consulta = "select ad.rut,ad.nombres,ad.apellidos,ad.correo,ad.celular,co.comuna_nombre
                    from administrador ad join comuna co
                    on ad.comuna = co.comuna_id
                    where ad.empresa = (select empresa 
                                    from administrador
                                    where rut = ?)";
        $output = $this->db->query($consulta,array($this->session->rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function insertar_administrador($data){
        $this->load->database();
        $consulta = "select bool,message from insertar_administrador(?,?,?,?,?,?,?,?,?)";
        $output = $this->db->query($consulta,array($data->rut,$data->nombres,$data->apellidos,$data->correo,$data->pass,$data->celular,$data->empresa,$data->comuna,$this->session->rut));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function modificar_administrador($data){
        $this->load->database();
        $consulta = "select bool,message from actualizar_administrador(?,?,?,?,?,?)";
        $output = $this->db->query($consulta,array($data->rut,$data->nombres,$data->apellidos,$data->comuna,$data->correo,$data->celular));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function eliminar_administrador($data){
        $this->load->database();
        $consulta = "select bool,message from eliminar_administrador(?)";
        $output = $this->db->query($consulta,array($data->rut));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function obtener_empresa_administrador(){
        $this->load->database();
        $consulta = "select empresa from administrador where rut = ?";
        $output = $this->db->query($consulta,array($this->session->rut));
        if($output->num_rows()>0){
            return $output->result()[0]->empresa;
        }else{
            return FALSE;
        }
    }
    
    public function obtener_comuna_administrador(){
        $this->load->database();
        $consulta = "select comuna from administrador where rut = ?";
        $output = $this->db->query($consulta,array($this->session->rut));
        if($output->num_rows()>0){
            return $output->result()[0]->comuna;
        }else{
            return FALSE;
        }
    }

    public function insertar_sucursal($data){
        $this->load->database();
        $consulta = "select bool,message from nueva_sucursal(?,?,?,null,?,?)";
        $output = $this->db->query($consulta,array($data->nombre,$data->direccion,$data->telefono,$data->empresa,$data->comuna));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function modificar_sucursal($data){
        $this->load->database();
        $consulta = "select bool,message from actualizar_sucursal(?,?,?,?,?)";
        $output = $this->db->query($consulta,array($data->codigo,$data->nombre,$data->direccion,$data->telefono,$data->empresa));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function eliminar_sucursal($data){
        $this->load->database();
        $consulta = "select bool,message from eliminar_sucursal(?,?)";
        $output = $this->db->query($consulta,array($data->codigo,$data->empresa));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function obtener_sucursal($codigo){
        $this->load->database();
        $consulta = "select cod_sucursal,nombre,direccion,telefono,comuna
                    from sucursal
                    where cod_empresa = (select empresa from administrador where rut = ?)
                    and cod_sucursal = ?";
        $output = $this->db->query($consulta,array($this->session->rut,$codigo));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function insertar_herramienta($data){
        $this->load->database();
        $consulta = "select bool,message from nueva_herramienta(?,?,?,?,?,?)";
        $output = $this->db->query($consulta,array($data->codigo,$data->nombre,$data->descripcion,$data->ruta,$data->categoria,$this->obtener_empresa_administrador()));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function modificar_herramienta($data){
        $this->load->database();
        $consulta = "select bool,message from actualizar_herramienta(?,?,?,?,?,?)";
        $output = $this->db->query($consulta,array($data->codigo,$data->nombre,$data->descripcion,null,$data->categoria,$data->empresa));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function eliminar_herramienta($data){
        $this->load->database();
        $consulta = "select bool,message from eliminar_herramienta(?,?)";
        $output = $this->db->query($consulta,array($data->codigo,$data->empresa));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function vincular_herramienta($data){
        $this->load->database();
        $consulta = "select bool,message from vincular_herramienta_sucursal(?,?,?,?,?,?,?,?)";
        $output = $this->db->query($consulta,array($data->herramienta,$data->sucursal,$data->precio,$data->stock,$data->empresa,$data->descuento,$data->inicio,$data->final));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function desvincular_herramienta($data){
        $this->load->database();
        $consulta = "select bool,message from desvincular_h_sucursal(?,?,?)";
        $output = $this->db->query($consulta,array($data->herramienta,$data->sucursal,$data->empresa));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function obtener_vinculacion($data){
        $this->load->database();
        $consulta = "select cod_herramienta,cod_sucursal,stock,precio,descuento,to_char(f_inicio_d,'DD/MM/YYYY') as f_inicio_d,to_char(f_final_d,'DD/MM/YYYY') as f_final_d
                    from sucursal_herramienta
                    where cod_herramienta = ?
                    and cod_sucursal = ?
                    and empresa = ?";
        $output = $this->db->query($consulta,array($data->herramienta,$data->sucursal,$data->empresa));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function modificar_vinculacion($data){
        $this->load->database();
        $consulta = "select bool,message from actualizar_h_sucursal(?,?,?,?,?,?,?,?)";
        $output = $this->db->query($consulta,array($data->herramienta,$data->sucursal,$data->stock,$data->precio,$data->empresa,$data->descuento,$data->inicio,$data->final));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

    public function obtener_herramienta($codigo){
        $this->load->database();
        $consulta = "select he.cod_herramienta,he.nombre,he.descripcion,ca.cod_categoria,ca.nombre as categoria,he.url_foto
                    from herramienta he join categoria ca
                    on he.cod_categoria = ca.cod_categoria
                    where empresa = (select empresa
                                    from administrador
                                    where rut = ?)
                    and he.cod_herramienta = ?";
        $output = $this->db->query($consulta,array($this->session->rut,$codigo));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function obtener_herramienta_vinculada($codigo){
        $this->load->database();
        $consulta = "select su.cod_sucursal, su.nombre, 'NO' as vinculado, sh.descuento
                    from sucursal su join sucursal_herramienta sh
                    on su.cod_sucursal = sh.cod_sucursal
                    where su.cod_sucursal not in (select cod_sucursal
                                                from sucursal_herramienta 
                                                where cod_herramienta = ?)
                    and su.cod_empresa = (select empresa from administrador where rut = ".$this->session->rut.")
                    union
                    select su.cod_sucursal, su.nombre, 'SI' as vinculado, sh.descuento
                    from sucursal su join sucursal_herramienta sh
                    on su.cod_sucursal = sh.cod_sucursal
                    where su.cod_sucursal in (select cod_sucursal
                                            from sucursal_herramienta 
                                            where cod_herramienta = ?)
                    and sh.cod_herramienta = ?
                    and su.cod_empresa = (select empresa from administrador where rut = ".$this->session->rut.")
                    group by su.cod_sucursal, su.nombre, sh.descuento";
        $output = $this->db->query($consulta,array($codigo,$codigo,$codigo));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function obtener_herramientas($datos){
        $this->load->database();
        $pagina = $datos->pagina;
        $busqueda = $datos->busqueda;
        $categoria = $datos->categoria;
        $filas = $datos->filas;
        $limite = $this->limite_paginacion($pagina,$filas,$datos->items);
        $output = NULL;
        if($busqueda==null){
            if($categoria==null){
                $consulta = "select he.cod_herramienta,he.nombre,he.descripcion,ca.nombre as categoria,he.url_foto
                            from herramienta he join categoria ca
                            on he.cod_categoria = ca.cod_categoria
                            where empresa = (select empresa
                                            from administrador
                                            where rut = ?)
                            limit ".$limite->limite."
                            offset ".$limite->offset.""; 
                $output = $this->db->query($consulta,array($this->session->rut));                  
            }else{
                $consulta = "select he.cod_herramienta,he.nombre,he.descripcion,ca.nombre as categoria,he.url_foto
                from herramienta he join categoria ca
                on he.cod_categoria = ca.cod_categoria
                where he.empresa = (select empresa
                                from administrador
                                where rut = ?)
                and he.cod_categoria = ?
                limit ".$limite->limite."
                offset ".$limite->offset.""; 
                $output = $this->db->query($consulta,array($this->session->rut,(int)$categoria));    
            }        
        }else{
            $consulta = "select he.cod_herramienta,he.nombre,he.descripcion,ca.nombre as categoria,he.url_foto
                from herramienta he join categoria ca
                on he.cod_categoria = ca.cod_categoria
                where he.empresa = (select empresa
                                from administrador
                                where rut = ?)";
            if(is_numeric($busqueda)){
                $consulta.=" and he.cod_herramienta = ".(int)$busqueda."";
            }else{
                $consulta.=" and (lower(he.nombre) like '%".strtolower($busqueda)."%'
                or lower(he.descripcion) like '%".strtolower($busqueda)."%')";
            }
            $consulta.=" limit ".$limite->limite."
            offset ".$limite->offset."";
            $output = $this->db->query($consulta,array($this->session->rut));    
        }
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function obtener_categorias(){
        $this->load->database();
        $consulta = "select * from categoria";
        $output = $this->db->query($consulta);
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function obtener_categorias_herramienta($datos){
        $this->load->database();
        $output = NULL;
        $categoria = $datos->categoria;
        $busqueda = $datos->busqueda;
        if($busqueda==null){
            if($categoria==null){
                $consulta = "select ca.cod_categoria,ca.nombre
                            from herramienta he join categoria ca
                            on he.cod_categoria = ca.cod_categoria
                            where empresa = (select empresa
                                            from administrador
                                            where rut = ?)
                            group by ca.cod_categoria";      
                $output = $this->db->query($consulta,array($this->session->rut));
            }else{
                $consulta = "select ca.cod_categoria,ca.nombre
                            from herramienta he join categoria ca
                            on he.cod_categoria = ca.cod_categoria
                            where empresa = (select empresa
                                            from administrador
                                            where rut = ?)
                            and ca.cod_categoria = ?
                            group by ca.cod_categoria";  
                $output = $this->db->query($consulta,array($this->session->rut,(int)$categoria));
            }   
        }else{
            $consulta = "select ca.cod_categoria,ca.nombre
                from herramienta he join categoria ca
                on he.cod_categoria = ca.cod_categoria
                where he.empresa = (select empresa
                                from administrador
                                where rut = ?)";
            if(is_numeric($busqueda)){
                $consulta.=" and he.cod_herramienta = ".(int)$busqueda."";
            }else{
                $consulta.=" and (lower(he.nombre) like '%".strtolower($busqueda)."%'
                or lower(he.descripcion) like '%".strtolower($busqueda)."%')";
            }
            $consulta.=" group by ca.cod_categoria";
            $output = $this->db->query($consulta,array($this->session->rut));    
        }       
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function obtener_total_herramientas($datos){
        $this->load->database();
        $output = NULL;
        $categoria = $datos->categoria;
        $busqueda = $datos->busqueda;
        if($busqueda==null){
            if($categoria==null){
                $consulta = "select *
                            from herramienta
                            where empresa = (select empresa
                                            from administrador
                                            where rut = ?)";      
                $output = $this->db->query($consulta,array($this->session->rut));
            }else{
                $consulta = "select *
                            from herramienta
                            where empresa = (select empresa
                                            from administrador
                                            where rut = ?)
                            and cod_categoria = ?";  
                $output = $this->db->query($consulta,array($this->session->rut,(int)$categoria));
            }   
        }else{
            $consulta = "select *
                from herramienta he join categoria ca
                on he.cod_categoria = ca.cod_categoria
                where he.empresa = (select empresa
                                from administrador
                                where rut = ?)";
            if(is_numeric($busqueda)){
                $consulta.=" and he.cod_herramienta = ".(int)$busqueda."";
            }else{
                $consulta.=" and (lower(he.nombre) like '%".strtolower($busqueda)."%'
                or lower(he.descripcion) like '%".strtolower($busqueda)."%')";
            }
            $output = $this->db->query($consulta,array($this->session->rut));    
        }       
        if($output->num_rows()>0){
            return $output->num_rows();
        }else{
            return FALSE;
        }
    }

    function limite_paginacion($pagina, $filas, $items){
        $limite = $filas;
        $offset = ($pagina - 1)  * $items;
        $data = new stdClass();
        $data->limite = $items;
        $data->offset = $offset;
        return $data;
    }

    function obtener_comunas($region){
        $this->load->database();
        $consulta = "select c.comuna_id, c.comuna_nombre 
                    from region r
                    join provincia p
                    on r.region_id = p.provincia_region_id
                    join comuna c
                    on p.provincia_id = c.comuna_provincia_id
                    join sucursal su
                    on c.comuna_id = su.comuna
                    where su.cod_empresa = (select empresa from administrador where rut = ?)
                    and r.region_id = ?
                    group by c.comuna_id
                    order by c.comuna_nombre";
        $output = $this->db->query($consulta,array($this->session->rut,$region));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_comunas_sucursal($region){
        $this->load->database();
        $consulta = "select c.comuna_id, c.comuna_nombre 
                    from region r
                    join provincia p
                    on r.region_id = p.provincia_region_id
                    join comuna c
                    on p.provincia_id = c.comuna_provincia_id
                    where r.region_id = ?";
        $output = $this->db->query($consulta,array($region));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_nombre_comuna($comuna){
        $this->load->database();
        $consulta = "select comuna_nombre
                    from comuna 
                    where comuna_id = ?";
        $output = $this->db->query($consulta,array($comuna));
        if($output->num_rows()>0){
            return $output->result()[0]->comuna_nombre;
        }else{
            return FALSE;
        }
    }

    function obtener_region_sucursal($comuna){
        $this->load->database();
        $consulta = "select re.region_id,re.region_nombre
                    from region re 
                    join provincia pro
                    on re.region_id = pro.provincia_region_id
                    join comuna c
                    on pro.provincia_id = c.comuna_provincia_id
                    where c.comuna_id = ?
                    group by re.region_id";
        $output = $this->db->query($consulta,array($comuna));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_regiones_sucursal(){
        $this->load->database();
        $consulta = "select region_id,region_nombre
                    from region
                    order by region_id";
        $output = $this->db->query($consulta);
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
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
                    where su.cod_empresa = (select empresa from administrador where rut = ?)
                    group by re.region_id";
        $output = $this->db->query($consulta,array($this->session->rut));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_region_administrador($rut){
        $this->load->database();
        $consulta = "select re.region_id,re.region_nombre 
                    from region re join provincia pro
                    on re.region_id = pro.provincia_region_id
                    join comuna c
                    on pro.provincia_id = c.comuna_provincia_id
                    where c.comuna_id = (select comuna from administrador
                                        where rut = ?)";
        $output = $this->db->query($consulta,array($rut));
        if($output->num_rows()>0){
            return $output->result()[0];
        }else{
            return FALSE;
        }
    }

    function obtener_sucursales(){
        $this->load->database();
        $consulta = "select su.cod_sucursal,su.nombre,su.direccion,su.telefono,su.comuna,co.comuna_nombre
                from sucursal su
                join comuna co
                on su.comuna=co.comuna_id
                where cod_empresa = (select empresa
                                    from administrador where
                                    rut = ?)
                order by co.comuna_nombre";
        $output = $this->db->query($consulta,array($this->session->rut));     
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_arriendos(){
        $this->load->database();
        $consulta = "SELECT A.cod_arriendo ,A.total, A.rut_u,
                    to_char(A.fecha_arriendo, 'DD/MM/YYYY') AS fecha_arriendo, A.estado, A.total
                    FROM arriendo A JOIN usuario U
                    ON A.rut_U = U.rut
                    where A.cod_arriendo IN (select id_a
                                            from detalle 
                                            where empresa = ?
                                            group by id_a)
                    ORDER BY A.fecha_arriendo DESC";
        $output = $this->db->query($consulta,array($this->obtener_empresa_administrador()));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_arriendos_rut($rut){
        $this->load->database();
        $consulta = "SELECT A.cod_arriendo ,A.total, A.rut_u,
                    to_char(A.fecha_arriendo, 'DD/MM/YYYY') AS fecha_arriendo, A.estado, A.total
                    FROM arriendo A JOIN usuario U
                    ON A.rut_u = U.rut
                    Where A.rut_u = ?
                    AND A.cod_arriendo IN (select id_a
                                            from detalle 
                                            where empresa = ?
                                            group by id_a)
                    ORDER BY A.fecha_arriendo DESC";
        $output = $this->db->query($consulta,array($rut,$this->obtener_empresa_administrador()));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_arriendos_fecha($fecha){
        $this->load->database();
        $consulta = "SELECT A.cod_arriendo ,A.total, A.rut_u,
                    to_char(A.fecha_arriendo, 'DD/MM/YYYY') AS fecha_arriendo, A.estado, A.total
                    FROM arriendo A JOIN usuario U
                    ON A.rut_U = U.rut
                    where A.cod_arriendo IN (select id_a
                                            from detalle 
                                            where empresa = ?
                                            group by id_a)
                    and to_char(A.fecha_arriendo, 'DD/MM/YYYY') = ?
                    ORDER BY A.fecha_arriendo DESC";
        $output = $this->db->query($consulta,array($this->obtener_empresa_administrador(),$fecha));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    function obtener_detalle($value){
        $this->load->database();
        $consulta = "SELECT D.cod_h, H.nombre, H.url_foto, D.cantidad, D.total_detalle, SH.precio, D.total_unitario, D.descuento,
                    D.estado, D.empresa, E.nombre as nombre_empresa, D.cod_sucursal, SU.nombre as nombre_sucursal, D.id_a
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

    function obtener_detalle_resumen($datos){
        $this->load->database();
        $consulta = "SELECT H.nombre, D.cantidad, D.total_detalle, D.total_unitario, D.descuento,
                    D.estado, E.nombre as nombre_empresa, SU.nombre as nombre_sucursal, D.id_a
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
                    WHERE D.id_a = ? 
                    AND D.cod_h = ?
                    AND D.empresa = ?
                    AND D.cod_sucursal = ?";
        $output = $this->db->query($consulta,array($datos->arriendo,$datos->herramienta,$this->obtener_empresa_administrador(),$datos->sucursal));
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
                    WHERE cod_arriendo = ?";
        $output = $this->db->query($consulta,array($value));
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

    public function modificar_detalle($data){
        $this->load->database();
        $consulta = "select bool,message from modificar_detalle(?,?,?,?,?)";
        $output = $this->db->query($consulta,array($data->herramienta,$data->sucursal,$data->empresa,$data->arriendo,$data->estado));
        $respuesta = new stdClass();
        $respuesta->estado = $output->result()[0]->bool;
        $respuesta->mensaje = $output->result()[0]->message;  
        return $respuesta;
    }

}