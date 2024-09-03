<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Ruta predeterminada
$route['default_controller'] = 'login'; // Redirige a la página de login por defecto

// Rutas para el controlador Login
$route['login'] = 'login/index'; // Muestra la página de login
$route['login/login'] = 'login/login'; // Procesa el inicio de sesión

// Ruta para recuperar la contraseña
$route['recuperar_contrasena'] = 'recuperar_contrasena'; // Muestra la página para recuperar la contraseña

// Rutas para el controlador Dashboard
$route['dashboard'] = 'dashboard/index'; // Muestra la página del dashboard

// Ruta para cerrar sesión
$route['logout'] = 'login/logout'; // Procesa el cierre de sesión

// Ruta para actualizar la sesión del usuario
$route['update_session'] = 'login/updateSession'; // Actualiza los datos del usuario en la sesión

// Rutas para Usuarios
$route['usuarios'] = 'usuarios/index';
$route['usuarios/agregar'] = 'usuarios/agregar';
$route['usuarios/editar/(:num)'] = 'usuarios/editar/$1';
$route['usuarios/eliminar/(:num)'] = 'usuarios/eliminar/$1';
$route['usuarios/eliminados'] = 'usuarios/eliminados';
$route['usuarios/habilitar/(:num)'] = 'usuarios/habilitar/$1';

// Rutas para el controlador de categorías
$route['categorias'] = 'categorias/index'; // Muestra la lista de categorías activas
$route['categorias/agregar'] = 'categorias/agregar'; // Agrega una nueva categoría
$route['categorias/editar/(:num)'] = 'categorias/editar/$1'; // Edita una categoría específica
$route['categorias/eliminar/(:num)'] = 'categorias/eliminar/$1'; // Elimina una categoría específica
$route['categorias/inactivos'] = 'categorias/inactivos'; // Muestra la lista de categorías inactivas
$route['categorias/habilitar/(:num)'] = 'categorias/habilitar/$1'; // Habilita una categoría específica

// Configuración para manejar errores 404
$route['404_override'] = 'errors/page_missing'; // Ruta para el controlador de errores 404 (opcional)

// Configuración para traducir guiones en las URI a guiones bajos en los nombres de métodos
$route['translate_uri_dashes'] = FALSE;
