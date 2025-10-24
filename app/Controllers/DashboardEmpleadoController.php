<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardEmpleadoController extends BaseController
{
    public function index()
    {
        return view('dashboard_empleado/index');
    }
}
