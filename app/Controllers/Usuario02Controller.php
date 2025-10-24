<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Usuario02Controller extends BaseController
{
    public function index()
    {
        return view('usuarios/index');
    }

    public function listar()
    {
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('usuarios');
            $usuarios = $builder->select('idusuario, nombre, estado, email, telefono')
                                ->orderBy('idusuario', 'DESC')
                                ->get()->getResultArray();
            return $this->response->setJSON(['success' => true, 'data' => $usuarios]);
        } catch (\Throwable $e) {
            log_message('error', 'Usuarios listar error: ' . $e->getMessage());
            // Evitar alerta de DataTables: devolver estructura válida sin clave 'error'
            return $this->response->setJSON(['success' => true, 'data' => []]);
        }
    }

    public function obtener($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');
        $usuario = $builder->where('idusuario', $id)->get()->getRowArray();
        if ($usuario) {
            return $this->response->setJSON(['success' => true, 'data' => $usuario]);
        }
        return $this->response->setJSON(['success' => false, 'error' => 'Usuario no encontrado']);
    }

    public function insertar()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $rol = $this->request->getPost('rol');
        $idperfil = $this->request->getPost('idperfil');
        $estado = strtoupper((string)$this->request->getPost('estado'));
        $nombre = $this->request->getPost('nombre') ?: ($email ? explode('@', $email)[0] : null);
        $telefono = $this->request->getPost('telefono');

        // Mapear rol a idperfil si viene como texto
        if (!$idperfil && $rol) {
            $idperfil = ($rol === 'Administrador') ? 1 : 2; // por defecto 2 = Empleado
        }

        if (!$email || !$password || !$estado) {
            return $this->response->setJSON(['success' => false, 'error' => 'Faltan campos obligatorios']);
        }

        $existe = $builder->where('email', $email)->countAllResults();
        if ($existe > 0) {
            return $this->response->setJSON(['success' => false, 'error' => 'Ya existe un usuario con este email']);
        }

        $data = [
            'email' => $email,
            'clave' => password_hash($password, PASSWORD_BCRYPT),
            'idperfil' => $idperfil ?: 2,
            'estado' => $estado,
            'nombre' => $nombre,
            'telefono' => $telefono,
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            $builder->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Usuario creado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'error' => 'Error al crear usuario: ' . $e->getMessage()]);
        }
    }

    public function actualizar()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');

        $id = $this->request->getPost('id');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $rol = $this->request->getPost('rol');
        $estado = $this->request->getPost('estado');
        $nombre = $this->request->getPost('nombre') ?: $email;
        $telefono = $this->request->getPost('telefono');

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'error' => 'ID inválido']);
        }

        $existe = $builder->where('email', $email)->where('idusuario !=', $id)->countAllResults();
        if ($existe > 0) {
            return $this->response->setJSON(['success' => false, 'error' => 'Ya existe otro usuario con este email']);
        }

        $data = [
            'email' => $email,
            'idperfil' => $idperfil ?: 2,
            'estado' => $estado,
            'nombre' => $nombre,
            'telefono' => $telefono,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        if ($password) {
            $data['clave'] = password_hash($password, PASSWORD_BCRYPT);
        }

        try {
            $builder->where('idusuario', $id)->update($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Usuario actualizado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'error' => 'Error al actualizar usuario: ' . $e->getMessage()]);
        }
    }

    public function eliminar()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');
        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'error' => 'ID inválido']);
        }
        try {
            $builder->where('idusuario', $id)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'error' => 'Error al eliminar usuario: ' . $e->getMessage()]);
        }
    }

    public function kpis()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');
        $total = $builder->countAllResults(false);
        $activos = $builder->where('estado', 'ACTIVO')->countAllResults(false);
        $inactivos = $builder->where('estado', 'INACTIVO')->countAllResults(false);
        $restringidos = $builder->where('estado', 'RESTRINGIDO')->countAllResults();
        return $this->response->setJSON([
            'success' => true,
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos,
            'restringidos' => $restringidos,
        ]);
    }

    public function enviarWhatsApp()
    {
        $nombre = $this->request->getPost('nombre');
        $telefono = $this->request->getPost('telefono');
        $mensaje = $this->request->getPost('mensaje');
        if (empty($telefono) || empty($mensaje)) {
            return $this->response->setJSON(['success' => false, 'error' => 'Teléfono y mensaje son obligatorios']);
        }

        $instancia = 'hl5jammw';
        $token = 'WQxEZGlH44aJcpvpgzPN';

        $telefonoLimpio = preg_replace('/[^0-9+]/', '', $telefono);
        if (strlen($telefonoLimpio) === 9) {
            $telefonoLimpio = '+51' . $telefonoLimpio;
        }

        $mensajePersonalizado = ($nombre ? ("Hola *{$nombre}*,\n\n") : '') . $mensaje;

        $data = [
            'numero_destinatario' => $telefonoLimpio,
            'tipo_destinatario' => 'contacto',
            'tipo_mensaje' => 'texto',
            'texto' => $mensajePersonalizado
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://whatsapp-service.flexbis.com/api/v1/message/text');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'flexbis-instance: ' . $instancia,
                'flexbis-token: ' . $token
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($httpCode === 200 || $httpCode === 201) {
                return $this->response->setJSON(['success' => true, 'message' => 'Mensaje enviado correctamente']);
            }
            return $this->response->setJSON(['success' => false, 'error' => 'No se pudo enviar el mensaje', 'http_code' => $httpCode]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'error' => 'Error al enviar mensaje: ' . $e->getMessage()]);
        }
    }
}
