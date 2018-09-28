<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Inicio';
$route['404_override'] = 'NotFound';
$route['translate_uri_dashes'] = FALSE;
$route['productos/(:num)/(:num)'] = 'Inicio/Productos/$1/$2';
$route['productos/(:num)'] = 'Inicio/Productos/$1';
$route['productos'] = 'Inicio/Productos/1';
$route['detalle/(:num)'] = 'Inicio/Detalle/$1';
$route['detalle'] = 'Inicio/Detalle';
$route['mision'] = 'Inicio/Mision';
$route['vision'] = 'Inicio/Vision';
$route['quienes-somos'] = 'Inicio/Quienes_Somos';
$route['registrarse'] = 'Inicio/Registro';
$route['registro'] = 'Inicio/Guardar_Registro';
$route['mi-cuenta'] = 'Inicio/Cuenta';
$route['salir'] = 'Inicio/CerrarSesion';
$route['contacto'] = 'Inicio/Contacto';
$route['carrito-agregar'] = 'Inicio/Agregar_Carrito';
$route['carrito-borrar'] = 'Inicio/Borrar_Herramienta_Carrito';
$route['carrito-quitar'] = 'Inicio/Quitar_Carrito';
$route['carrito'] = 'Inicio/Carrito';
$route['validacion'] = 'Inicio/Validacion';
$route['sucursal'] = 'Inicio/Sucursal';
$route['fechas'] = 'Inicio/Fechas';
$route['total-carro'] = 'Inicio/Total_Carro';
$route['arriendo'] = 'Inicio/Arriendo';
$route['resumen/(:num)'] = 'Inicio/Resumen/$1';
$route['resumen'] = 'Inicio/Resumen';