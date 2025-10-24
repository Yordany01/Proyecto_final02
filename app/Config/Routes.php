<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
    $routes->get('/', 'Home::index');

// Ruta para hashear contraseñas (para desarrollo/pruebas)
   $routes->get('/hash/(:any)', 'ControladorUsuario::index/$1');
   $routes->get('/hash-password/(:any)', 'LogeoController::hashPassword/$1');

// Rutas de autenticación
    $routes->get('/login', 'LoginController::index');
    $routes->post('/login/login', 'LogeoController::login'); // Ruta para procesar el login 
    $routes->get('/logout', 'LogeoController::logout');     // Ruta para cerrar sesión

// Rutas de vistas
    $routes->group('vistas',['filter'=> 'CambioFiltro'],function($routes){

    });

    $routes->get('/obtenerusuarios', 'UsuarioController::index');
    $routes->get('/tareas_admin', 'TareasAdminController::index');
    $routes->get('/ajuste', 'AjusteController::index');
    $routes->get('/perfil', 'PerfilController::index');
    $routes->get('/trabajadores', 'TrabajadoresController::index');
    $routes->get('/documentos', 'DocumentosController::index');
    $routes->get('/usuarios', 'Usuario02Controller::index');
    $routes->get('/ajuste_empleado', 'AjusteEmpleadoController::index');
    $routes->get('/dashboard', 'DashboardController::index');
    $routes->get('/dashboard_empleado', 'DashboardEmpleadoController::index');
    $routes->get('/perfil_empleado', 'PerfilEmpleadoController::index');
    $routes->get('/subir_documento', 'SubirDocumentoController::index');
    $routes->get('/tareas_empleado', 'TareasEmpleadoController::index');
    $routes->get('/documentos_empleado', 'DocumentosEmpleadoController::index');
    $routes->get('/chat_admin', 'ChatAdminController::index');
    $routes->get('/chat_empleado', 'ChatEmpleadoController::index');
    
// Grupo de rutas para Trabajadores
$routes->group('trabajadores', ['namespace' => 'App\\Controllers'], function($routes) {
    $routes->get('/', 'TrabajadoresController::index');
    $routes->get('listar', 'TrabajadoresController::listar');
    $routes->get('kpis', 'TrabajadoresController::getKpis');
    $routes->post('insertar', 'TrabajadoresController::insertar');
    $routes->get('obtener/(:num)', 'TrabajadoresController::obtener/$1');
    $routes->post('actualizar/(:num)', 'TrabajadoresController::actualizar/$1');
    $routes->post('eliminar/(:num)', 'TrabajadoresController::eliminar/$1');
    $routes->post('enviarWhatsApp', 'TrabajadoresController::enviarWhatsApp');
    
});

// Rutas de Ajustes (Administrador)
$routes->group('ajustes', ['namespace' => 'App\\Controllers'], function($routes) {
    $routes->get('obtener', 'AjusteController::obtener');
    $routes->post('guardar', 'AjusteController::guardar');
});

// Rutas de Tareas
    $routes->group('tareas', ['namespace' => 'App\\Controllers'], function($routes) {
    $routes->get('/', 'TareasController::index');
    $routes->get('listar', 'TareasController::listar');
    $routes->post('insertar', 'TareasController::insertar');
    $routes->post('actualizar', 'TareasController::actualizar');
    $routes->post('eliminar', 'TareasController::eliminar');
    $routes->get('obtener/(:num)', 'TareasController::obtener/$1');
    $routes->get('kpis', 'TareasController::kpis');
    $routes->post('enviarWhatsApp', 'TareasController::enviarWhatsApp'); 
});

// Rutas de Dashboard (datos)
$routes->group('dashboard', ['namespace' => 'App\\Controllers'], function($routes) {
    $routes->get('kpis', 'DashboardController::kpis');
    $routes->get('rendimiento', 'DashboardController::rendimientoEmpleados');
    $routes->get('estado', 'DashboardController::estadoProyectos');
    $routes->get('responsables', 'DashboardController::responsables');
});

// Rutas de Perfil (Administrador)
    $routes->group('perfil', ['namespace' => 'App\\Controllers'], function($routes) {
    $routes->get('obtener', 'PerfilController::obtener');
});

// Rutas de Usuarios
$routes->group('usuarios', ['namespace' => 'App\\Controllers'], function($routes) {
    $routes->get('/', 'Usuario02Controller::index');
    $routes->get('listar', 'Usuario02Controller::listar');
    $routes->get('obtener/(:num)', 'Usuario02Controller::obtener/$1');
    $routes->post('insertar', 'Usuario02Controller::insertar');
    $routes->post('actualizar', 'Usuario02Controller::actualizar');
    $routes->post('eliminar', 'Usuario02Controller::eliminar');
    $routes->get('kpis', 'Usuario02Controller::kpis');
    $routes->post('enviarWhatsApp', 'Usuario02Controller::enviarWhatsApp');
});