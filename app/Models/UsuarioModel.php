<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'idusuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idusuario',
        'idperfil',
        'nombre',
        'email',
        'estado',
        'clave'];

    public function getUsuarioLogin(){
        return $this->select('idusuario, idperfil, nombre, email, estado, clave')
                    ->where('estado', 'ACTIVO')
                    ->findAll();
    }

    public function getUserByCredentials($email, $clave)
    {
        // Obtener el usuario activo desde la base de datos por su email
        $user = $this->where('email', $email)
                     ->where('estado', 'ACTIVO')
                     ->first();

        // Verificar si el usuario fue encontrado
        if ($user) {
            // Verificar la contraseña hasheada
            if (password_verify($clave, $user['clave'])) {
                return $user; // La contraseña es correcta, devolver datos del usuario
            }
        }

        return null; // Usuario no encontrado, inactivo o contraseña incorrecta
    }
}
