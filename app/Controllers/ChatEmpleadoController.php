<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ChatEmpleadoController extends BaseController
{
    public function index()
    {
        return view('chat_empleado/index');
    }
}
