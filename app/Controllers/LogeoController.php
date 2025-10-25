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

        // CORREGIDO: El formulario envía 'usuario' y 'password', no 'clave'
        $email = $this->request->getPost('usuario');
        $password = $this->request->getPost('password'); // Cambiado de 'clave' a 'password'

        // Log para debugging
        log_message('info', 'Intento de login - Email: ' . $email);

        // Llama al método del modelo para verificar las credenciales
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
            
            log_message('info', 'Login exitoso - Usuario: ' . $user['email']);
            
            return $this->response->setJSON(['success' => true]);
        } else {
            log_message('warning', 'Login fallido - Email: ' . $email);
            
            // Si el usuario no es válido
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Usuario o contraseña incorrectos.'
            ]);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // Método auxiliar para generar hash (para desarrollo)
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}