<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsuarioModel;

class LogeoController extends BaseController
{
    public function login()
    { 
        $usuarioModel = new UsuarioModel();
        $session = session();

        $email = $this->request->getPost('usuario');
        $password = $this->request->getPost('clave');

        // Llama al nuevo método del modelo para verificar las credenciales
        $user = $usuarioModel->getUserByCredentials($email, $password);

        if ($user) {
            // Si el usuario es válido, crea la sesión
            $sessionData = [
                'idusuario' => $user['idusuario'],
                'nombre'    => $user['nombre'],
                'email'     => $user['email'],
                'idperfil'  => $user['idperfil'],
                'is_logged' => true,
            ];
            $session->set($sessionData);
            return $this->response->setJSON(['success' => true]);
        } else {
            // Si el usuario no es válido (no existe, está inactivo o la contraseña es incorrecta)
            return $this->response->setJSON(['success' => false, 'message' => 'Usuario o contraseña incorrectos.']);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
