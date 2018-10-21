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
$route['404_override'] = 'Not_Found';
$route['translate_uri_dashes'] = FALSE;
$route['productos'] = 'Inicio/productos/';
$route['detalle/(:num)'] = 'Inicio/detalle/$1';
$route['detalle'] = 'Inicio/detalle';
$route['mision'] = 'Inicio/mision';
$route['vision'] = 'Inicio/vision';
$route['quienes-somos'] = 'Inicio/quienes_somos';
$route['registrarse'] = 'Inicio/registro';
$route['registro'] = 'Inicio/guardar_registro';
$route['mi-cuenta'] = 'Inicio/cuenta';
$route['salir'] = 'Inicio/cerrar_sesion';
$route['contacto'] = 'Inicio/contacto';
$route['carrito-agregar'] = 'Inicio/agregar_carrito';
$route['carrito-borrar'] = 'Inicio/borrar_herramienta_carro';
$route['carrito-quitar'] = 'Inicio/quitar_carrito';
$route['carrito'] = 'Inicio/carrito';
$route['validacion'] = 'Inicio/validacion';
$route['sucursal'] = 'Inicio/sucursal';
$route['comuna'] = 'Inicio/comuna';
$route['fechas'] = 'Inicio/fechas';
$route['total-carro'] = 'Inicio/total_carro';
$route['arriendo'] = 'Inicio/arriendo';
$route['resumen/(:num)'] = 'Inicio/resumen/$1';
$route['resumen'] = 'Inicio/resumen';
$route['busqueda'] = 'Inicio/revisar_busqueda';