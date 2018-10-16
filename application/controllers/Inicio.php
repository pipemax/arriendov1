<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
		if(!isset($this->session->sucursal))
		{
			$this->session->sucursal = 2;
			date_default_timezone_set('America/Santiago');
			$fecha_hoy = strtotime("now");
			$fecha_manana = strtotime("+1 day");
			$this->session->inicio = date("d/m/Y",$fecha_hoy);
			$this->session->fin = date("d/m/Y",$fecha_manana);
		}		
	}

	private function _head()
	{
		if($this->session->estado==TRUE)
		{
			$head = new stdClass();
			$head->cantidad = $this->Inicio_model->obtener_cantidad_carro($this->session->rut);
		}
		else
		{ 
			$head = new stdClass();
			$head->cantidad = 0;
		}
		$head->sucursal = $this->Inicio_model->obtener_sucursal($this->session->sucursal);
		$head->sucursales = $this->Inicio_model->obtener_sucursales();
		$head->categorias = $this->Inicio_model->obtener_categorias();
		return $head;
	}

	public function index()
	{		
		$this->load->model("Inicio_model");		
		$arriendos = new stdClass();
		$arriendos->arriendos = $this->Inicio_model->obtener_mas_arrendados();
		$arriendos->regiones = $this->Inicio_model->obtener_regiones();
		$this->load->view("head", $this->_head());
		$this->load->view("inicio", $arriendos);
		$this->load->view("footer");
	}

	public function obtener_comunas()
	{
		if($this->input->post('region'))
		{
			$this->load->model("Inicio_model");
			$output = $this->Inicio_model->obtener_comunas();
			if($output!=FALSE)
			{
				echo json_encode($output);
			}
			else
			{
				redirect(base_url());
			}
		}	
		else
		{
			redirect(base_url());
		}
	}

	public function validacion()
	{
		if($this->input->post('rut_sesion'))
		{
			$rut = $this->input->post('rut_sesion');
			$rut = preg_replace("/[^0-9]/", "", $rut);
			$pass = $this->input->post('password_sesion');
			$this->load->model('Inicio_model');
			$output = $this->Inicio_model->validar_sesion($rut,$pass);
			echo json_encode($output);
		}
		else
		{
			redirect(base_url());
		}
	}

	public function sucursal()
	{
		if($this->input->post('sucursal'))
		{
			$sucursal = $this->input->post('sucursal');
			$this->load->model("Inicio_model");
			$output = $this->Inicio_model->verificar_carro();
			if($output!=FALSE)
			{
				$output2 = $this->Inicio_model->verificar_sucursal_carro();
				if($this->session->estado==TRUE && $sucursal!=$output2->cod_sucursal)
				{
					$this->load->model("Inicio_model");
					$output = $this->Inicio_model->limpiar_carro();
				}
			}
			$this->session->sucursal = $sucursal;
			echo "TRUE";
		}else{
			redirect(base_url());
		}
	}

	public function fechas()
	{
		if($this->input->post('inicio'))
		{
			$fecha_inicio = $this->input->post('inicio');
			$fecha_fin = $this->input->post('fin');
			$this->session->inicio = $fecha_inicio;
			$this->session->fin = $fecha_fin;
			echo 'TRUE';
		}else{
			redirect(base_url());
		}
	}

	public function mision()
	{
		echo "PENDIENTE";
	}

	public function vision()
	{
		echo "PENDIENTE";
	}

	public function quienes_somos()
	{
		echo "PENDIENTE";
	}

	public function guardar_registro()
	{
		if($this->input->post('rut'))
		{
			$datos = new stdClass();
			$datos->rut = preg_replace("/[^0-9]/", "", $this->input->post('rut'));
			$datos->nombres = $this->input->post('nombres');
			$datos->apellidos = $this->input->post('apellidos');
			$datos->correo = $this->input->post('correo');
			$datos->direccion = $this->input->post('direccion');
			$datos->telefono = $this->input->post('telefono');
			$datos->pass = $this->input->post('password1');
			$this->load->model("Inicio_model");
			$output = $this->Inicio_model->realizar_registro($datos);
			echo json_encode($output);
		}else{
			redirect(base_url());
		}
	}

	public function registro()
	{
		if($this->session->estado==FALSE)
		{
			$this->load->model("Inicio_model");
			$this->load->view("head",$this->_head());
			$this->load->view("registro");
			$this->load->view("footer");
		}
		else
		{
			redirect(base_url());
		}
	}

	public function agregar_carrito()
	{
		if($this->input->post('codigo'))
		{
			if($this->session->estado==TRUE)
			{
				$codigo = $this->input->post('codigo');
				$cantidad = $this->input->post('cantidad');
				$this->load->model("Inicio_model");
				$output = $this->Inicio_model->agregar_carro($codigo,$cantidad);
				echo json_encode($output);
			}
			else
			{
				$respuesta = new stdClass();
				$respuesta->estado = 'LOGIN';
				echo json_encode($respuesta);
			}
		}
		else
		{
			redirect(base_url());
		}
	}

	public function quitar_carrito()
	{
		if($this->input->post('codigo'))
		{
			if($this->session->estado==TRUE)
			{
				$codigo = $this->input->post('codigo');
				$cantidad = $this->input->post('cantidad');
				$this->load->model("Inicio_model");
				$output = $this->Inicio_model->quitar_carro($codigo,$cantidad);
				echo json_encode($output);
			}
			else
			{
				$respuesta = new stdClass();
				$respuesta->estado = 'LOGIN';
				echo json_encode($respuesta);
			}
		}
		else
		{
			redirect(base_url());
		}
	}

	public function borrar_herramienta_carro()
	{
		if($this->input->post('codigo'))
		{
			if($this->session->estado==TRUE)
			{
				$datos = new stdClass();
				$datos->codigo = $this->input->post('codigo');
				$datos->rut = $this->session->rut;
				$datos->sucursal = $this->session->sucursal;
				$this->load->model("Inicio_model");
				$output = $this->Inicio_model->borrar_herramienta_carro($datos);
				echo json_encode($output);
			}
			else
			{
				$respuesta = new stdClass();
				$respuesta->estado = 'LOGIN';
				$respuesta->mensaje = 'Debe iniciar sesión para manipular el carro de arriendos';
				echo json_encode($respuesta);
			}
		}
		else
		{
			redirect(base_url());
		}
	}

	public function total_carro()
	{
		if($this->session->estado==TRUE)
		{
			$rut = $this->session->rut;
			$sucursal = $this->session->sucursal;
			$this->load->model("Inicio_model");
			$output = $this->Inicio_model->obtener_total_carro($rut,$sucursal);
			echo $output;
		}
		else
		{
			redirect(base_url());
		}
	}

	public function carrito()
	{
		if($this->session->estado==TRUE)
		{
			$this->load->model("Inicio_model");			
			$this->load->view("head", $this->_head());
			$carro = new stdClass();
			$carro->carrito = $this->Inicio_model->obtener_carro();
			if($carro->carrito!=FALSE)
			{
				$carro->total = $this->Inicio_model->obtener_total_carro($this->session->rut, $this->session->sucursal);
				$this->load->view("carrito",$carro);
				$this->load->view("footer");
			}
			else
			{
				redirect('productos');
			}
		}
		else
		{
			redirect(base_url());
		}
	}

	public function arriendo()
	{
		if($this->session->estado==TRUE)
		{
			if($this->input->post('arriendo')=="YES")
			{
				$this->load->model("Inicio_model");
				$output = $this->Inicio_model->realizar_arriendo();
				echo json_encode($output);
			}
			else
			{
				redirect('carrito');
			}
		}
		else
		{
			redirect(base_url());
		}
	}

	public function resumen($value = "")
	{
		if($this->session->estado==TRUE)
		{
			if($value!="")
			{
				$retorno = new stdClass();
				$this->load->model("Inicio_model");
				$arriendo = $this->Inicio_model->obtener_arriendo($value);
				if($arriendo!=FALSE)
				{
					$detalle = $this->Inicio_model->obtener_detalle($value,$arriendo[0]->cod_sucursal);
					if($detalle!=FALSE)
					{
						$retorno->arriendo = $arriendo;
						$retorno->detalle = $detalle;
						$retorno->orden = $value;
					}
					else
					{
						$retorno->arriendo = FALSE;
						$retorno->detalle = FALSE;
					}
				}
				else
				{
					redirect('productos');
				}				
				$this->load->view("head",$this->_head());
				$this->load->view("resumen",$retorno);
				$this->load->view("footer");
			}
			else
			{
				redirect('productos');
			}
		}
		else
		{
			redirect('productos');
		}
	}

	public function contacto()
	{
		echo "PENDIENTE";
	}

	public function detalle($value = "")
	{
		if($value!="")
		{
			$this->load->model("Inicio_model");			
			$this->load->view("head",$this->_head());
			$productos = new stdClass();
			$productos->herramienta = $this->Inicio_model->obtener_producto($value);
			$this->load->view("detalle",$productos);
			$this->load->view("footer");
		}
		else
		{
			redirect('productos/1');
		}
	}

	public function productos()
	{
		$this->load->model("Inicio_model");		
		$this->load->library("pagination");
		$datos = new stdClass();
		$precio = 1;
		$stock = 1;	
		if($this->input->get('precio') == null)
		{
			$datos->precio = $this->_orden(1);
		}
		else
		{			
			$datos->precio = $this->_orden($this->input->get('precio'));
			$precio = $this->input->get('precio');			
		}
		if($this->input->get('stock') == null)
		{
			$datos->stock = $this->_orden(1);
		}
		else
		{
			$datos->stock = $this->_orden($this->input->get('stock'));
			$stock = $this->input->get('stock');
		}
		if($this->input->get('categoria') == null)
		{
			$datos->categoria = null;
		}
		else
		{
			$datos->categoria = $this->input->get('categoria');
		}	
		if($this->input->get('pagina') == null)
		{
			$datos->pagina = 1;
		}
		else
		{
			$datos->pagina = $this->input->get('pagina');
		}		
		$total_filas = $this->Inicio_model->total_productos($datos,filter_var($this->input->get('search'),FILTER_SANITIZE_SPECIAL_CHARS));
		$datos->items = 6;
		$config['base_url'] = base_url()."productos/";
		$config['total_rows'] = $total_filas;
		$config['per_page'] = $datos->items;
		$config['reuse_query_string'] = TRUE;	
		$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'pagina';
		$config['reuse_query_string'] = TRUE;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['first_link'] = 'Primero';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a>';	
		$config['cur_tag_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = '&laquo;';
		$config['prev_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['last_link'] = 'Último';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$this->pagination->initialize($config);		
		$this->load->view("head",$this->_head());		
		$datos->filas = $total_filas;		
		$productos = new stdClass();
		if(filter_var($this->input->get('search'),FILTER_SANITIZE_SPECIAL_CHARS) == null)
		{
			$productos->herramientas = $this->Inicio_model->obtener_productos($datos);
			$productos->titulo = $this->Inicio_model->obtener_categoria($datos->categoria);
		}
		else
		{
			$productos->herramientas = $this->Inicio_model->obtener_busqueda($datos,filter_var($this->input->get('search'),FILTER_SANITIZE_SPECIAL_CHARS));
			if($datos->filas>0)
			{
				$productos->titulo = 'Mostrando '.$datos->filas.' resultado(s) para: "'.filter_var($this->input->get('search'),FILTER_SANITIZE_SPECIAL_CHARS).'"';
			}
			else
			{
				$productos->titulo = 'No hemos encontrado resultados para: "'.filter_var($this->input->get('search'),FILTER_SANITIZE_SPECIAL_CHARS).'"';
			}			
		}
		$productos->id = $datos->categoria;
		$productos->precio = $precio;
		$productos->stock = $stock;
		$productos->filas = $datos->filas;
		$this->load->view("productos",$productos);
		$this->load->view("footer");
	}

	private function _orden($value = null)
	{
		if($value=="1")
		{
			return 'asc';
		}
		else if($value=="0")
		{
			return 'desc';
		}
		else
		{
			return 'asc';
		}
	}

	public function cerrar_sesion()
	{
		if($this->session->estado==TRUE)
		{
			$this->load->model("Inicio_model");
			$output = $this->Inicio_model->verificar_carro();
			if($output!=FALSE)
			{
				$this->Inicio_model->limpiar_carro();				
			}
			$this->session->sess_destroy();
			redirect(base_url());
		}
		else
		{
			redirect(base_url());
		}
	}
}
