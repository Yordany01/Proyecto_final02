<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TareasAdminController extends BaseController
{
    public function index()
    {
        return view('tareas_admin/index');
    }
}
