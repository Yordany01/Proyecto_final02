<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AjusteEmpleadoController extends BaseController
{
    public function index()
    {
        return view('ajuste_empleado/index');
    }
}
