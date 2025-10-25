<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'idusuario';
    protected $allowedFields = ['email', 'clave', 'idperfil', 'estado', 'nombre', 'telefono'];
    protected $useTimestamps = false;

    /**
     * Verifica las credenciales del usuario
     * 
     * @param string $email
     * @param string $password
     * @return array|null Retorna los datos del usuario si es válido, null si no
     */
    public function getUserByCredentials($email, $password)
    {
        // Buscar el usuario por email (insensible a mayúsculas/minúsculas)
        // Usamos LOWER(email) = lower(input) y desactivamos escape para permitir la función
        $user = $this
            ->where('LOWER(email) =', strtolower($email))
            ->first();

        // Log para debugging
        log_message('info', 'Buscando usuario: ' . strtoupper($email));
        
        if (!$user) {
            log_message('warning', 'Usuario no encontrado: ' . $email);
            return null;
        }

        log_message('info', 'Usuario encontrado - Estado: ' . $user['estado']);

        // Verificar que el usuario esté activo
        if (strtoupper((string)$user['estado']) !== 'ACTIVO') {
            log_message('warning', 'Usuario inactivo: ' . $email);
            return null;
        }

        // Verificar la contraseña
        if (password_verify($password, $user['clave'])) {
            log_message('info', 'Contraseña verificada correctamente para: ' . $email);
            return $user;
        }

        log_message('warning', 'Contraseña incorrecta para: ' . $email);
        return null;
    }

    /**
     * Obtiene un usuario por ID
     */
    public function getUsuario($id)
    {
        return $this->find($id);
    }

    /**
     * Lista todos los usuarios
     */
    public function listarUsuarios()
    {
        return $this->findAll();
    }

    /**
     * Crea un nuevo usuario con contraseña hasheada
     */
    public function crearUsuario($data)
    {
        if (isset($data['password'])) {
            $data['clave'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        
        return $this->insert($data);
    }
}