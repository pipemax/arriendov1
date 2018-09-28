<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct(){
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

	private function _Head(){
		if($this->session->estado==TRUE){
			$Head = new stdClass();
			$Head->Cantidad = $this->Inicio_Model->Obtener_Cantidad_Carro($this->session->rut);
		}else{ 
			$Head = new stdClass();
			$Head->Cantidad = 0;
		}
		$Head->Sucursal = $this->Inicio_Model->Obtener_Sucursal($this->session->sucursal);
		$Head->Sucursales = $this->Inicio_Model->Obtener_Sucursales();
		$Head->Categorias = $this->Inicio_Model->Obtener_Categorias();
		return $Head;
	}

	public function index(){		
		$this->load->model("Inicio_Model");		
		$Arriendos = new stdClass();
		$Arriendos->Arriendos = $this->Inicio_Model->Obtener_Mas_Arrendados();
		$this->load->view("Head", $this->_Head());
		$this->load->view("Inicio", $Arriendos);
		$this->load->view("Footer");
	}

	public function Validacion(){
		if($this->input->post('rut_sesion')){
			$rut = $this->input->post('rut_sesion');
			$rut = preg_replace("/[^0-9]/", "", $rut);
			$pass = $this->input->post('password_sesion');
			$this->load->model('Inicio_Model');
			$output = $this->Inicio_Model->Validar_Sesion($rut,$pass);
			echo json_encode($output);
		}else{
			redirect(base_url());
		}
	}

	public function Sucursal(){
		if($this->input->post('sucursal')){
			$sucursal = $this->input->post('sucursal');
			$this->load->model("Inicio_Model");
			$output = $this->Inicio_Model->Verificar_Carro();
			if($output!=FALSE){
				$output2 = $this->Inicio_Model->Verificar_Sucursal_Carro();
				if($this->session->estado==TRUE && $sucursal!=$output2->cod_sucursal){
					$this->load->model("Inicio_Model");
					$output = $this->Inicio_Model->Limpiar_Carro();
				}
			}
			$this->session->sucursal = $sucursal;
			echo "TRUE";
		}else{
			redirect(base_url());
		}
	}

	public function Fechas(){
		if($this->input->post('inicio')){
			$inicio = $this->input->post('inicio');
			$fin = $this->input->post('fin');
			$this->session->inicio = $inicio;
			$this->session->fin = $fin;
			echo 'TRUE';
		}else{
			redirect(base_url());
		}
	}

	public function Mision(){
		echo "PENDIENTE";
	}

	public function Vision(){
		echo "PENDIENTE";
	}

	public function Quienes_Somos(){
		echo "PENDIENTE";
	}

	public function Guardar_Registro(){
		if($this->input->post('rut')){
			$Datos = new stdClass();
			$Datos->Rut = preg_replace("/[^0-9]/", "", $this->input->post('rut'));
			$Datos->Nombres = $this->input->post('nombres');
			$Datos->Apellidos = $this->input->post('apellidos');
			$Datos->Correo = $this->input->post('correo');
			$Datos->Direccion = $this->input->post('direccion');
			$Datos->Telefono = $this->input->post('telefono');
			$Datos->Pass = $this->input->post('password1');
			$this->load->model("Inicio_Model");
			$output = $this->Inicio_Model->Realizar_Registro($Datos);
			echo json_encode($output);
		}else{
			redirect(base_url());
		}
	}

	public function Registro(){
		if($this->session->estado==FALSE){
			$this->load->model("Inicio_Model");
			$this->load->view("Head",$this->_Head());
			$this->load->view("Registro");
			$this->load->view("Footer");
		}else{
			redirect(base_url());
		}
	}

	public function Agregar_Carrito(){
		if($this->input->post('codigo')){
			if($this->session->estado==TRUE){
				$codigo = $this->input->post('codigo');
				$cantidad = $this->input->post('cantidad');
				$this->load->model("Inicio_Model");
				$output = $this->Inicio_Model->Agregar_Carro($codigo,$cantidad);
				echo json_encode($output);
			}else{
				$respuesta = new stdClass();
				$respuesta->estado = 'LOGIN';
				echo json_encode($respuesta);
			}
		}else{
			redirect(base_url());
		}
	}

	public function Quitar_Carrito(){
		if($this->input->post('codigo')){
			if($this->session->estado==TRUE){
				$codigo = $this->input->post('codigo');
				$cantidad = $this->input->post('cantidad');
				$this->load->model("Inicio_Model");
				$output = $this->Inicio_Model->Quitar_Carro($codigo,$cantidad);
				echo json_encode($output);
			}else{
				$respuesta = new stdClass();
				$respuesta->estado = 'LOGIN';
				echo json_encode($respuesta);
			}
		}else{
			redirect(base_url());
		}
	}

	public function Borrar_Herramienta_Carrito(){
		if($this->input->post('codigo')){
			if($this->session->estado==TRUE){
				$datos = new stdClass();
				$datos->codigo = $this->input->post('codigo');
				$datos->rut = $this->session->rut;
				$datos->sucursal = $this->session->sucursal;
				$this->load->model("Inicio_Model");
				$output = $this->Inicio_Model->Borrar_H_Carrito($datos);
				echo json_encode($output);
			}else{
				$respuesta = new stdClass();
				$respuesta->estado = 'LOGIN';
				$respuesta->mensaje = 'DEBE INICIAR SESIÓN PARA MANIPULAR EL CARRITO DE ARRIENDOS';
				echo json_encode($respuesta);
			}
		}else{
			redirect(base_url());
		}
	}

	public function Total_Carro(){
		if($this->session->estado==TRUE){
			$rut = $this->session->rut;
			$sucursal = $this->session->sucursal;
			$this->load->model("Inicio_Model");
			$output = $this->Inicio_Model->Obtener_Total_Carro($rut,$sucursal);
			echo $output;
		}else{
			redirect(base_url());
		}
	}

	public function Carrito(){
		if($this->session->estado==TRUE){
			$this->load->model("Inicio_Model");			
			$this->load->view("Head", $this->_Head());
			$Carro = new stdClass();
			$Carro->Carrito = $this->Inicio_Model->Obtener_Carro();
			if($Carro->Carrito!=FALSE){
				$Carro->Total = $this->Inicio_Model->Obtener_Total_Carro($this->session->rut,$this->session->sucursal);
				$this->load->view("Carrito",$Carro);
				$this->load->view("Footer");
			}else{
				redirect('productos');
			}
		}else{
			redirect(base_url());
		}
	}

	public function Arriendo(){
		if($this->session->estado==TRUE){
			if($this->input->post('arriendo')=="YES"){
				$this->load->model("Inicio_Model");
				$output = $this->Inicio_Model->Realizar_Arriendo();
				echo json_encode($output);
			}else{
				redirect('carrito');
			}
		}else{
			redirect(base_url());
		}
	}

	public function Resumen($value = ""){
		if($this->session->estado==TRUE){
			if($value!=""){
				$retorno = new stdClass();
				$this->load->model("Inicio_Model");
				$arriendo = $this->Inicio_Model->Obtener_Arriendo($value);
				if($arriendo!=FALSE){
					$detalle = $this->Inicio_Model->Obtener_Detalle($value,$arriendo[0]->cod_sucursal);
					if($detalle!=FALSE){
						$retorno->arriendo = $arriendo;
						$retorno->detalle = $detalle;
						$retorno->orden = $value;
					}else{
						$retorno->arriendo = FALSE;
						$retorno->detalle = FALSE;
					}
				}else{
					redirect('productos');
				}				
				$this->load->view("Head",$this->_Head());
				$this->load->view("Resumen",$retorno);
				$this->load->view("Footer");
			}else{
				redirect('productos');
			}
		}else{
			redirect('productos');
		}
	}

	public function Contacto(){
		echo "PENDIENTE";
	}

	public function Detalle($value = ""){
		if($value!=""){
			$this->load->model("Inicio_Model");			
			$this->load->view("Head",$this->_Head());
			$Productos = new stdClass();
			$Productos->Herramienta = $this->Inicio_Model->Obtener_Producto($value);
			//print_r($Productos->Herramienta);
			$this->load->view("Detalle",$Productos);
		}else{
			redirect('productos/1');
		}
	}

	public function Productos($pagina = "",$value = ""){
		$this->load->model("Inicio_Model");		
		$this->load->view("Head",$this->_Head());
		$Productos = new stdClass();
		$Productos->Herramientas = $this->Inicio_Model->Obtener_Productos($value);
		$Productos->ID = $value;
		$Productos->Titulo = $this->Inicio_Model->Obtener_Categoria($value);
		$this->load->view("Productos",$Productos);
		$this->load->view("Footer");
	}

	public function CerrarSesion(){
		if($this->session->estado==TRUE){
			$this->load->model("Inicio_Model");
			$output = $this->Inicio_Model->Verificar_Carro();
			if($output!=FALSE){
				$this->Inicio_Model->Limpiar_Carro();				
			}
			$this->session->sess_destroy();
			redirect(base_url());
		}else{
			redirect(base_url());
		}
	}
}
