<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DocumentosController extends BaseController
{
    public function index()
    {
        return view('documentos/index');
    }
}
