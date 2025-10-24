<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TareasController extends BaseController
{
    public function index()
    {
        return view('tareas_admin/index');
    }

    public function listar()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tareas');
        
        $tareas = $builder->orderBy('id', 'DESC')->get()->getResultArray();
        
        return $this->response->setJSON(['success' => true, 'data' => $tareas]);
    }

    public function insertar()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tareas');
        
        $titulo = $this->request->getPost('titulo');
        $asignado_a = $this->request->getPost('asignado_a');
        $fecha_limite = $this->request->getPost('fecha_limite');
        $prioridad = $this->request->getPost('prioridad');
        $estado = $this->request->getPost('estado');

        // Validar que no exista una tarea con el mismo título
        $existe = $builder->where('titulo', $titulo)->countAllResults();
        
        if ($existe > 0) {
            return $this->response->setJSON(['success' => false, 'error' => 'Ya existe una tarea con este título.']);
        }

        $data = [
            'titulo' => $titulo,
            'asignado_a' => $asignado_a,
            'fecha_limite' => $fecha_limite,
            'prioridad' => $prioridad,
            'estado' => $estado,
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            $builder->insert($data);
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Tarea registrada exitosamente.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Ocurrió un error al registrar la tarea: ' . $e->getMessage()
            ]);
        }
    }

    public function actualizar()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tareas');
        
        $id = $this->request->getPost('id');
        $titulo = $this->request->getPost('titulo');
        $asignado_a = $this->request->getPost('asignado_a');
        $fecha_limite = $this->request->getPost('fecha_limite');
        $prioridad = $this->request->getPost('prioridad');
        $estado = $this->request->getPost('estado');

        // Validar que no exista otra tarea con el mismo título
        $existe = $builder->where('titulo', $titulo)
                         ->where('id !=', $id)
                         ->countAllResults();
        
        if ($existe > 0) {
            return $this->response->setJSON(['success' => false, 'error' => 'Ya existe otra tarea con este título.']);
        }

        $data = [
            'titulo' => $titulo,
            'asignado_a' => $asignado_a,
            'fecha_limite' => $fecha_limite,
            'prioridad' => $prioridad,
            'estado' => $estado,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $builder->where('id', $id)->update($data);
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Tarea actualizada exitosamente.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Ocurrió un error al actualizar la tarea: ' . $e->getMessage()
            ]);
        }
    }

    public function eliminar()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tareas');
        
        $id = $this->request->getPost('id');

        try {
            $builder->where('id', $id)->delete();
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Tarea eliminada exitosamente.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Ocurrió un error al eliminar la tarea: ' . $e->getMessage()
            ]);
        }
    }

    public function obtener($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tareas');
        
        $tarea = $builder->where('id', $id)->get()->getRowArray();
        
        if ($tarea) {
            return $this->response->setJSON(['success' => true, 'data' => $tarea]);
        } else {
            return $this->response->setJSON(['success' => false, 'error' => 'Tarea no encontrada.']);
        }
    }

    public function kpis()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tareas');
        
        $total = $builder->countAllResults(false);
        $completadas = $builder->where('estado', 'completada')->countAllResults();
        
        return $this->response->setJSON([
            'success' => true,
            'total' => $total,
            'completadas' => $completadas
        ]);
    }
    public function enviarWhatsApp()
    {
        $nombre = $this->request->getPost('nombre');
        $telefono = $this->request->getPost('telefono');
        $mensaje = $this->request->getPost('mensaje');

        if (empty($nombre) || empty($telefono) || empty($mensaje)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Todos los campos son obligatorios.'
            ]);
        }

        $instancia = 'hl5jammw';
        $token = 'WQxEZGlH44aJcpvpgzPN';

        $telefonoLimpio = preg_replace('/[^0-9]/', '', $telefono);
        if (strlen($telefonoLimpio) === 9) {
            $telefonoLimpio = '+51' . $telefonoLimpio;
        } elseif (!str_starts_with($telefono, '+')) {
            $telefonoLimpio = '+' . $telefonoLimpio;
        } else {
            $telefonoLimpio = $telefono;
        }

        $mensajePersonalizado = "Hola *{$nombre}*,\n\n{$mensaje}\n\n_Mensaje enviado desde el sistema de gestión_";

        $data = [
            'numero_destinatario' => $telefonoLimpio,
            'tipo_destinatario' => 'contacto',
            'tipo_mensaje' => 'texto',
            'texto' => $mensajePersonalizado
        ];

        try {
            $ch = curl_init();
            $apiUrl = "https://whatsapp-service.flexbis.com/api/v1/message/text";
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
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

            if (curl_errno($ch)) {
                $error = curl_error($ch);
                curl_close($ch);
                log_message('error', 'Error cURL WhatsApp (tareas): ' . $error);
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Error de conexión: ' . $error
                ]);
            }

            curl_close($ch);

            $resultado = json_decode($response, true);
            log_message('info', 'Respuesta WhatsApp API (tareas) - Código: ' . $httpCode . ' | Respuesta: ' . $response);

            if ($httpCode === 200 || $httpCode === 201) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Mensaje enviado exitosamente a WhatsApp de ' . $nombre,
                    'data' => $resultado
                ]);
            } else {
                $errorMsg = 'Error desconocido';
                if ($httpCode === 401) {
                    $errorMsg = 'Error de autenticación. Verifica las credenciales de la API.';
                } elseif ($httpCode === 400) {
                    $errorMsg = $resultado['message'] ?? 'Datos inválidos en la solicitud.';
                } elseif ($httpCode === 403) {
                    $errorMsg = 'Acceso prohibido. Verifica los permisos de la API.';
                } elseif ($httpCode === 404) {
                    $errorMsg = 'Endpoint no encontrado.';
                } elseif ($httpCode >= 500) {
                    $errorMsg = 'Error en el servidor de WhatsApp. Intenta más tarde.';
                } elseif (isset($resultado['message'])) {
                    $errorMsg = $resultado['message'];
                } elseif (isset($resultado['error'])) {
                    $errorMsg = $resultado['error'];
                }

                return $this->response->setJSON([
                    'success' => false,
                    'error' => $errorMsg,
                    'http_code' => $httpCode,
                    'response' => $resultado
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción WhatsApp (tareas): ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al enviar mensaje: ' . $e->getMessage()
            ]);
        }
    }
}
