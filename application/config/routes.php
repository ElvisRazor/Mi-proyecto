<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controlador predeterminado
$route['default_controller'] = 'login';

// Rutas para el controlador Login
$route['login'] = 'login/index'; // Ruta para la acción index del controlador Login
$route['login/autenticar'] = 'login/autenticar'; // Ruta para el método autenticar del controlador Login

// Ruta para el controlador Dashboard
$route['dashboard'] = 'dashboard/index'; // Ruta para la acción index del controlador Dashboard

// Rutas para Usuarios
$route['usuarios'] = 'usuarios/index';
$route['usuarios/agregar'] = 'usuarios/agregar';

$route['usuarios/editar/(:num)'] = 'usuarios/editar/$1';
$route['usuarios/eliminar/(:num)'] = 'usuarios/eliminar/$1';
$route['usuarios/eliminados'] = 'usuarios/eliminados';
$route['usuarios/habilitar/(:num)'] = 'usuarios/habilitar/$1';

// Configuración para manejar errores 404
$route['404_override'] = 'errors/page_missing'; // Ruta para el controlador de errores 404 (opcional)

// Configuración para traducir guiones en las URI a guiones bajos en los nombres de métodos
$route['translate_uri_dashes'] = FALSE;
