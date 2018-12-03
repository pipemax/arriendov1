<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
$route['default_controller'] = 'inicio';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['validacion'] = 'Inicio/sesion_admin';
$route['intranet'] = 'Inicio/intranet';
$route['salir'] = 'Inicio/cerrar_sesion';
$route['agregar-administrador'] = 'Inicio/agregar_admin';
$route['modificar-administrador'] = 'Inicio/modificar_admin';
$route['eliminar-administrador'] = 'Inicio/eliminar_admin';
$route['obtener-administrador'] = 'Inicio/obtener_admin';
$route['contrasena-administrador'] = 'Inicio/contrasena_admin';
$route['administradores'] = 'Inicio/ver_admin';
$route['sucursales'] = 'Inicio/ver_sucursales';
$route['agregar-sucursal'] = 'Inicio/agregar_sucursal';
$route['modificar-sucursal'] = 'Inicio/modificar_sucursal';
$route['eliminar-sucursal'] = 'Inicio/eliminar_sucursal';
$route['obtener-sucursal'] = 'Inicio/obtener_sucursal';
$route['herramientas'] = 'Inicio/ver_herramientas';
$route['herramientas/(:num)'] = 'Inicio/vinculacion_herramienta/$1';
$route['agregar-herramienta'] = 'Inicio/agregar_herramienta';
$route['modificar-herramienta'] = 'Inicio/modificar_herramienta';
$route['eliminar-herramienta'] = 'Inicio/eliminar_herramienta';
$route['obtener-herramienta'] = 'Inicio/obtener_herramienta';
$route['vincular-herramienta'] = 'Inicio/vincular_herramienta';
$route['desvincular-herramienta'] = 'Inicio/desvincular_herramienta';
$route['obtener-vinculacion'] = 'Inicio/obtener_vinculacion';
$route['modificar-vinculacion'] = 'Inicio/modificar_vinculacion';
$route['usuarios'] = 'Inicio/ver_usuario';
$route['modificar-usuario'] = 'Inicio/modificar_usuario';
$route['obtener-usuario'] = 'Inicio/obtener_usuario';
$route['contrasena-usuario'] = 'Inicio/contrasena_usuario';
$route['arriendos'] = 'Inicio/arriendos';
$route['detalle/(:num)'] = 'Inicio/detalle/$1';
$route['detalle'] = 'Inicio/arriendos';
$route['obtener-detalle'] = 'Inicio/obtener_detalle';
$route['modificar-detalle'] = 'Inicio/modificar_detalle';
