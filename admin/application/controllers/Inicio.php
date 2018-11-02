<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function index()
	{
        if($this->session->estado==FALSE)
        {
            $this->load->view('head');
            $this->load->view('inicio');
            $this->load->view('end');
        }
        else
        {
            redirect('intranet');
        }
        
    }

    public function agregar_admin()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('rut_nuevo_administrador'))
            {
                $data = new stdClass();
                $data->rut = preg_replace("/[^0-9]/", "", $this->input->post('rut_nuevo_administrador'));
                $data->nombres = $this->input->post('nombres_nuevo_administrador');
                $data->apellidos = $this->input->post('apellidos_nuevo_administrador');
                $data->correo = $this->input->post('correo_nuevo_administrador');
                $data->celular = $this->input->post('telefono_nuevo_administrador');
                $data->pass = $this->input->post('password_nuevo_administrador');
                $this->load->model('Admin_model');
                $data->empresa = $this->Admin_model->obtener_empresa_administrador();
                $data->comuna = $this->input->post('comuna_nuevo_administrador'); 
                $output = $this->Admin_model->insertar_administrador($data);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function obtener_admin()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('rut'))
            {
                $this->load->model('Admin_model');
                $rut = $this->input->post('rut');
                $output = $this->Admin_model->obtener_administrador($rut);
                if($output!=FALSE)
                {
                    $output[0]->region = $this->Admin_model->obtener_region_administrador($rut);
                    echo json_encode($output[0]);
                }
                else
                {
                    echo 'FALSE';
                }
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function modificar_admin()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('rut_modificar_administrador'))
            {
                $data = new stdClass();
                $data->rut = preg_replace("/[^0-9]/", "", $this->input->post('rut_modificar_administrador'));
                $data->nombres = $this->input->post('nombres_modificar_administrador');
                $data->apellidos = $this->input->post('apellidos_modificar_administrador');
                $data->correo = $this->input->post('correo_modificar_administrador');
                $data->celular = $this->input->post('telefono_modificar_administrador');
                $this->load->model('Admin_model');
                $data->comuna = $this->input->post('comuna_modificar_administrador'); 
                $output = $this->Admin_model->modificar_administrador($data);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function contrasena_admin()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('rut') && $this->input->post('pass'))
            {
                $data = new stdClass();
                $data->rut = preg_replace("/[^0-9]/", "", $this->input->post('rut'));
                $data->pass = $this->input->post('pass');
                $this->load->model('Admin_model');
                $output = $this->Admin_model->contrasena_administrador($data);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function eliminar_admin()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('rut'))
            {
                $data = new stdClass();
                $data->rut = $this->input->post('rut');
                $this->load->model('Admin_model');
                $output = $this->Admin_model->eliminar_administrador($data);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function ver_admin()
    {
        if($this->session->estado==TRUE)
        {
            $this->load->model('Admin_model');
            $output = $this->Admin_model->obtener_administradores();
            $view = new stdClass();
            if($output!=FALSE)
            {                
                $view->output = $output;  
                $view->regiones = $this->Admin_model->obtener_regiones();                
            }
            else
            {
                $view->output = FALSE;
            }
            $this->load->view('Intranet/head');
            $this->load->view('Intranet/administradores',$view);
            $this->load->view('Intranet/footer');
            $this->load->view('Intranet/end');
        }
        else
        {
            redirect(base_url());
        }
    }
    
    public function sesion_admin()
    {
        if($this->input->post('rut'))
		{
			$rut = $this->input->post('rut');
			$rut = preg_replace("/[^0-9]/", "", $rut);
			$pass = $this->input->post('pass');
			$this->load->model('Admin_model');
			$output = $this->Admin_model->validar_sesion($rut,$pass);
			echo json_encode($output);
		}
		else
		{
			redirect(base_url());
		}
    }

    public function agregar_sucursal()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('nombre_nueva_sucursal'))
            {
                $data = new stdClass();
                $data->nombre = $this->input->post('nombre_nueva_sucursal');
                $data->direccion = $this->input->post('direccion_nueva_sucursal');
                $data->telefono = $this->input->post('telefono_nueva_sucursal');
                $data->comuna = $this->input->post('comuna_nueva_sucursal');
                $this->load->model('Admin_model');
                $data->empresa = $this->Admin_model->obtener_empresa_administrador();
                $output = $this->Admin_model->insertar_sucursal($data);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function eliminar_sucursal()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('codigo'))
            {
                $data = new stdClass();
                $data->codigo = $this->input->post('codigo');
                $this->load->model('Admin_model');
                $data->empresa = $this->Admin_model->obtener_empresa_administrador();
                $output = $this->Admin_model->eliminar_sucursal($data);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function modificar_sucursal()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('codigo_modificar_sucursal'))
            {
                $data = new stdClass();
                $data->codigo = $this->input->post('codigo_modificar_sucursal');
                $data->nombre = $this->input->post('nombre_modificar_sucursal');
                $data->direccion = $this->input->post('direccion_modificar_sucursal');
                $data->telefono = $this->input->post('telefono_modificar_sucursal');
                $this->load->model('Admin_model');
                $data->empresa = $this->Admin_model->obtener_empresa_administrador();                
                $output = $this->Admin_model->modificar_sucursal($data);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function obtener_sucursal()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('codigo'))
            {
                $this->load->model('Admin_model');
                $codigo = $this->input->post('codigo');
                $output = $this->Admin_model->obtener_sucursal($codigo);
                if($output!=FALSE)
                {
                    $output[0]->region = $this->Admin_model->obtener_region_sucursal($output[0]->comuna);
                    $output[0]->comuna = $this->Admin_model->obtener_nombre_comuna($output[0]->comuna);
                    echo json_encode($output[0]);
                }
                else
                {
                    echo 'FALSE';
                }
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function ver_sucursales()
    {
        if($this->session->estado==TRUE)
        {
            $this->load->model('Admin_model');
            $output = $this->Admin_model->obtener_sucursales();
            $view = new stdClass();
            if($output!=FALSE)
            {                
                $view->output = $output;    
                $view->regiones = $this->Admin_model->obtener_regiones_sucursal();
            }
            else
            {
                $view->output = FALSE;
            }
            $this->load->view('Intranet/head');
            $this->load->view('Intranet/sucursales',$view);
            $this->load->view('Intranet/footer');
            $this->load->view('Intranet/end');
        }
        else
        {
            redirect(base_url());
        }
    }

    public function agregar_herramienta()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('codigo_nueva_herramienta'))
            {
                $datos = new stdClass();
                $datos->codigo = $this->input->post('codigo_nueva_herramienta');
                $datos->nombre = $this->input->post('nombre_nueva_herramienta');
                $datos->descripcion = $this->input->post('descripcion_nueva_herramienta');
                $datos->categoria = $this->input->post('categoria_nueva_sucursal');
                $datos->nombre_foto = $this->input->post('foto_n_nueva_sucursal');
                $config['upload_path'] = '../assets/herramientas';
                $config['allowed_types'] = 'png|jpg';
                $config['max_size'] = 5000;
                $config['max_width'] = 1920;
                $config['max_height'] = 1080;
                $config['overwrite'] = FALSE;
                $config['file_name'] = $datos->nombre_foto;
                $config['remove_spaces'] = TRUE;                
                $this->load->library('upload', $config);
                $this->load->model('Admin_model');
                $verificacion = $this->Admin_model->obtener_herramienta($datos->codigo);
                if($verificacion==FALSE)
                {
                    if (!$this->upload->do_upload("foto_nueva_sucursal"))
                    {
                        $respuesta = new stdClass();
                        $respuesta->estado = 'FALSE';
                        $respuesta->mensaje = $this->upload->display_errors('','');
                        echo json_encode($respuesta);
                    }
                    else
                    {
                        $resultado = $this->upload->data();
                        $datos->ruta = $resultado['file_name'];                        
                        $output = $this->Admin_model->insertar_herramienta($datos);
                        echo json_encode($output);
                    }
                }
                else
                {
                    $respuesta = new stdClass();
                    $respuesta->estado = 'FALSE';
                    $respuesta->mensaje = "LA HERRAMIENTA QUE INTENTA REGISTRAR YA EXISTE, MODIFIQUE EL STOCK EN LA HERRAMIENTA CÓDIGO: ".$datos->codigo;
                    echo json_encode($respuesta);
                }
                @unlink($_FILES["foto_nueva_sucursal"]);
                
                
            }else{
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function modificar_herramienta()
    {   
        if($this->session->estado==TRUE)
        {
            if($this->input->post('codigo_modificar_herramienta'))
            {
                $data = new stdClass();
                $data->codigo = $this->input->post('codigo_modificar_herramienta');
                $data->nombre = $this->input->post('nombre_modificar_herramienta');
                $data->descripcion = $this->input->post('descripcion_modificar_herramienta');
                $data->categoria = $this->input->post('categoria_modificar_herramienta');
                $this->load->model('Admin_model');
                $data->empresa = $this->Admin_model->obtener_empresa_administrador();                
                $output = $this->Admin_model->modificar_herramienta($data);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function eliminar_herramienta()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('codigo'))
            {
                $this->load->model('Admin_model');
                $data = new stdClass();
                $data->codigo = $this->input->post('codigo');
                $data->empresa = $this->Admin_model->obtener_empresa_administrador();   
                $nombre = $this->Admin_model->obtener_herramienta($data->codigo);             
                $output = $this->Admin_model->eliminar_herramienta($data);
                if($output->estado==TRUE)
                {
                    $ruta = '../assets/herramientas/';                    
                    $ruta_completa = $ruta.$nombre[0]->url_foto;
                    if (file_exists($ruta_completa))
                    {
                        unlink($ruta_completa);     
                    }
                }
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function obtener_herramienta()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('codigo'))
            {
                $this->load->model('Admin_model');
                $codigo = $this->input->post('codigo');
                $output = $this->Admin_model->obtener_herramienta($codigo);
                if($output!=FALSE)
                {                    
                    echo json_encode($output[0]);
                }
                else
                {
                    echo 'FALSE';
                }
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function vinculacion_herramienta($value = null)
    {
        if($this->session->estado==TRUE)
        {   
            if($value!=null)
            {
                $datos = new stdClass();
                $this->load->view('Intranet/head');
                $this->load->model('Admin_model');
                $output = $this->Admin_model->obtener_herramienta($value);
                $sucursales = $this->Admin_model->obtener_herramienta_vinculada($value);
                $datos->herramienta = $value;
                $datos->producto = $output;
                $datos->sucursales = $sucursales;
                $this->load->view('Intranet/vinculacion',$datos);
                $this->load->view('Intranet/footer');
                $this->load->view('Intranet/end');
            }
            else
            {
                redirect(base_url()."herramientas/");
            }   
        }
        else
        {
            redirect(base_url());
        }
    }

    public function vincular_herramienta(){
        if($this->session->estado==TRUE)
        {   
            if($this->input->post('sucursal_nueva_vinculacion') && $this->input->post('herramienta_nueva_vinculacion'))
            {
                $datos = new stdClass();
                $datos->sucursal = $this->input->post('sucursal_nueva_vinculacion');
                $datos->herramienta = $this->input->post('herramienta_nueva_vinculacion');
                $datos->stock = $this->input->post('stock_nueva_vinculacion');
                $datos->precio = $this->input->post('precio_nueva_vinculacion');
                $this->load->model('Admin_model');
                $datos->empresa = $this->Admin_model->obtener_empresa_administrador();
                $output = $this->Admin_model->vincular_herramienta($datos);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function modificar_vinculacion()
    {   
        if($this->session->estado==TRUE)
        {
            if($this->input->post('sucursal_vinculacion_modificacion'))
            {
                $data = new stdClass();
                $data->sucursal = $this->input->post('sucursal_vinculacion_modificacion');
                $data->herramienta = $this->input->post('herramienta_vinculacion_modificacion');
                $data->stock = $this->input->post('stock_vinculacion_modificacion');
                $data->precio = $this->input->post('precio_vinculacion_modificacion');
                $this->load->model('Admin_model');
                $data->empresa = $this->Admin_model->obtener_empresa_administrador();                
                $output = $this->Admin_model->modificar_vinculacion($data);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }


    public function obtener_vinculacion(){
        if($this->session->estado==TRUE)
        {   
            if($this->input->post('codigo') && $this->input->post('herramienta'))
            {
                $datos = new stdClass();
                $datos->sucursal = $this->input->post('codigo');
                $datos->herramienta = $this->input->post('herramienta');
                $this->load->model('Admin_model');
                $datos->empresa = $this->Admin_model->obtener_empresa_administrador();
                $output = $this->Admin_model->obtener_vinculacion($datos);
                echo json_encode($output[0]);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function desvincular_herramienta(){
        if($this->session->estado==TRUE)
        {   
            if($this->input->post('codigo') && $this->input->post('herramienta'))
            {
                $datos = new stdClass();
                $datos->sucursal = $this->input->post('codigo');
                $datos->herramienta = $this->input->post('herramienta');
                $this->load->model('Admin_model');
                $datos->empresa = $this->Admin_model->obtener_empresa_administrador();
                $output = $this->Admin_model->desvincular_herramienta($datos);
                echo json_encode($output);
            }
            else
            {
                echo 'FALSE';
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    public function ver_herramientas()
    {
        if($this->session->estado==TRUE)
        {            
            $view = new stdClass();
            if($this->input->get('search') == null)
            {
                $view->busqueda = null;
            }
            else
            {
                $view->busqueda = filter_var($this->input->get('search'),FILTER_SANITIZE_SPECIAL_CHARS);
            }	
            if($this->input->get('pagina') == null)
            {
                $view->pagina = 1;
            }
            else
            {
                $view->pagina = $this->input->get('pagina');
            }	
            if($this->input->get('categoria') == null)
            {
                $view->categoria = null;
            }
            else
            {
                $view->categoria = $this->input->get('categoria');
            }	
            $this->load->model('Admin_model');
            $this->load->library("pagination");            
            $total_filas = $this->Admin_model->obtener_total_herramientas($view);    
            $view->items = 6;
            $view->filas = $total_filas;
            $config['base_url'] = base_url()."herramientas/";
            $config['total_rows'] = $total_filas;
            $config['per_page'] = $view->items;
            $config['reuse_query_string'] = TRUE;	
            $config['use_page_numbers'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['query_string_segment'] = 'pagina';
            $config['reuse_query_string'] = TRUE;
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tag_close'] = '</li>';
            $config['first_link'] = 'Primero';
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';	
            $config['cur_tag_close'] = '</a></li>';
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_link'] = '&laquo;';
            $config['prev_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tag_close'] = '</li>';
            $config['last_link'] = 'Último';
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';
            $config['next_link'] = '&raquo;';
            $config['attributes'] = array('class' => 'page-link');
            $this->pagination->initialize($config);		
            $view->categorias_herramientas = $this->Admin_model->obtener_categorias();
            $output = $this->Admin_model->obtener_herramientas($view);
            if($output!=FALSE)
            {                
                $view->output = $output;   
                $view->categorias = $this->Admin_model->obtener_categorias_herramienta($view);                
                $view->categoria = $view->categoria;
            }
            else
            {
                $view->output = FALSE;
            }
            $this->load->view('Intranet/head');
            $this->load->view('Intranet/herramientas',$view);
            $this->load->view('Intranet/footer');
            $this->load->view('Intranet/end');
        }
        else
        {
            redirect(base_url());
        }
    }

    public function intranet()
    {
        if($this->session->estado==TRUE)
        {
            $this->load->view('Intranet/head');
            $this->load->view('Intranet/intranet');
            $this->load->view('Intranet/footer');
            $this->load->view('Intranet/end');
        }
        else
        {
            redirect(base_url());
        }
    }

    public function cerrar_sesion()
    {
        if($this->session->estado)
        {
            $this->session->sess_destroy();
            redirect(base_url());
        }
        else
        {
            redirect(base_url());
        }
    }

    public function obtener_comunas()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('region'))
            {
                $region = $this->input->post('region');
                $this->load->model("Admin_model");
                $output = $this->Admin_model->obtener_comunas($region);
                if($output!=FALSE)
                {
                    echo json_encode($output);
                }
                else
                {
                    echo 'FALSE';
                }
            }
            else
            {
                echo 'FALSE';
            }
        }   
        else
        {
            redirect(base_url());
        }

    }

    public function obtener_comunas_sucursal()
    {
        if($this->session->estado==TRUE)
        {
            if($this->input->post('region'))
            {
                $region = $this->input->post('region');
                $this->load->model("Admin_model");
                $output = $this->Admin_model->obtener_comunas_sucursal($region);
                if($output!=FALSE)
                {
                    echo json_encode($output);
                }
                else
                {
                    echo 'FALSE';
                }
            }
            else
            {
                echo 'FALSE';
            }
        }   
        else
        {
            redirect(base_url());
        }

    }
}
