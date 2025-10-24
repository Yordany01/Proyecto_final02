<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ControladorUsuario extends BaseController
{
    public function index($clave = null)
    {
        if (empty($clave)) {
            return "Por favor, proporciona una contraseña en la URL. Ejemplo: /hash/1234";
        }

        $hashedPassword = password_hash($clave, PASSWORD_DEFAULT);
        
        // Devuelve una respuesta JSON, ideal para Postman
        return $this->response->setJSON([
            'original' => $clave,
            'hash'     => $hashedPassword
        ]);
    }
}
