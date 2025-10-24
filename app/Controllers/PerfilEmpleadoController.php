<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PerfilEmpleadoController extends BaseController
{
    public function index()
    {
        return view('perfil_empeado/index');
    }
}
