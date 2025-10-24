<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PerfilController extends BaseController
{
    public function index()
    {
        return view('perfil/index');
    }
     // Obtener datos del perfil (lee desde ajustes)
    public function obtener()
    {
        try {
            $path = WRITEPATH . 'ajustes/admin.json';
            if (is_file($path)) {
                $json = file_get_contents($path);
                $data = json_decode($json, true) ?? [];
            } else {
                $data = [];
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al obtener perfil: ' . $e->getMessage()
            ]);
        }
    }
}

