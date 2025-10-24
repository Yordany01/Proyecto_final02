<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SubirDocumentoController extends BaseController
{
    public function index()
    {
        return view('subir_documento/index');
    }
}
