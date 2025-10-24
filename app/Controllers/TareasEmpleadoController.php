<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TareasEmpleadoController extends BaseController
{
    public function index()
    {
        return view('tareas_empleado/index');
    }
}
