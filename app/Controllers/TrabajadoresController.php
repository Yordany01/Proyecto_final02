<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TrabajadoresController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        return view('trabajadores/index');
    }

    // Listar todos los trabajadores
    public function listar()
    {
        try {
            $builder = $this->db->table('trabajadores');
            $trabajadores = $builder->orderBy('id', 'DESC')->get()->getResultArray();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $trabajadores
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al obtener los trabajadores: ' . $e->getMessage()
            ]);
        }
    }

    // Obtener KPIs
    public function getKpis()
    {
        try {
            $builder = $this->db->table('trabajadores');
            
            $total = $builder->countAll();
            $activos = $builder->where('estado', 'activo')->countAllResults(false);
            $inactivos = max(0, $total - $activos);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'activos' => $activos,
                    'inactivos' => $inactivos
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al obtener los KPIs: ' . $e->getMessage()
            ]);
        }
    }

    // Insertar nuevo trabajador
    public function insertar()
    {
        $nombre = $this->request->getPost('nombre');
        $compania = $this->request->getPost('compania');
        $correo = $this->request->getPost('correo');
        $telefono = $this->request->getPost('telefono');
        $estado = $this->request->getPost('estado');
        $fecha_registro = $this->request->getPost('fecha_registro');

        if (empty($nombre) || empty($compania) || empty($correo) || empty($telefono) || empty($estado) || empty($fecha_registro)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Todos los campos son obligatorios.'
            ]);
        }

        $builder = $this->db->table('trabajadores');
        $existe = $builder->where('correo', $correo)->countAllResults();
        
        if ($existe > 0) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'El correo ingresado ya está registrado.'
            ]);
        }

        $data = [
            'nombre' => $nombre,
            'compania' => $compania,
            'correo' => $correo,
            'telefono' => $telefono,
            'estado' => $estado,
            'fecha_registro' => $fecha_registro,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $builder = $this->db->table('trabajadores');
            $result = $builder->insert($data);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Trabajador registrado exitosamente.',
                    'id' => $this->db->insertID()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'No se pudo registrar el trabajador.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Ocurrió un error al registrar el trabajador: ' . $e->getMessage()
            ]);
        }
    }

    // Obtener un trabajador por ID
    public function obtener($id)
    {
        try {
            $builder = $this->db->table('trabajadores');
            $trabajador = $builder->where('id', $id)->get()->getRowArray();
            
            if ($trabajador) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $trabajador
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Trabajador no encontrado.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al obtener el trabajador: ' . $e->getMessage()
            ]);
        }
    }

    // Actualizar trabajador
    public function actualizar($id)
    {
        $nombre = $this->request->getPost('nombre');
        $compania = $this->request->getPost('compania');
        $correo = $this->request->getPost('correo');
        $telefono = $this->request->getPost('telefono');
        $estado = $this->request->getPost('estado');
        $fecha_registro = $this->request->getPost('fecha_registro');

        if (empty($nombre) || empty($compania) || empty($correo) || empty($telefono) || empty($estado) || empty($fecha_registro)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Todos los campos son obligatorios.'
            ]);
        }

        $builder = $this->db->table('trabajadores');
        $existe = $builder->where('correo', $correo)
                          ->where('id !=', $id)
                          ->countAllResults();
        
        if ($existe > 0) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'El correo ingresado ya está registrado por otro trabajador.'
            ]);
        }

        $data = [
            'nombre' => $nombre,
            'compania' => $compania,
            'correo' => $correo,
            'telefono' => $telefono,
            'estado' => $estado,
            'fecha_registro' => $fecha_registro,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            $builder = $this->db->table('trabajadores');
            $result = $builder->where('id', $id)->update($data);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Trabajador actualizado exitosamente.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'No se pudo actualizar el trabajador.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Ocurrió un error al actualizar el trabajador: ' . $e->getMessage()
            ]);
        }
    }

    // Eliminar trabajador
    public function eliminar($id)
    {
        try {
            $builder = $this->db->table('trabajadores');
            $result = $builder->where('id', $id)->delete();
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Trabajador eliminado exitosamente.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'No se pudo eliminar el trabajador.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Ocurrió un error al eliminar el trabajador: ' . $e->getMessage()
            ]);
        }
    }

    // Enviar mensaje de WhatsApp usando API de Flexbis
    public function enviarWhatsApp()
    {
        $nombre = $this->request->getPost('nombre');
        $telefono = $this->request->getPost('telefono');
        $mensaje = $this->request->getPost('mensaje');

        // Validar campos
        if (empty($nombre) || empty($telefono) || empty($mensaje)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Todos los campos son obligatorios.'
            ]);
        }

        // Configuración de la API de Flexbis (según credenciales proporcionadas)
        $instancia = 'hl5jammw'; // Código de instancia correcto
        $token = 'WQxEZGlH44aJcpvpgzPN'; // Token correcto
        
        // Limpiar el número de teléfono
    $telefonoLimpio = preg_replace('/[^0-9]/', '', $telefono);
    
    // Si el número tiene 9 dígitos, agregar +51 (Perú)
    if (strlen($telefonoLimpio) === 9) {
        $telefonoLimpio = '+51' . $telefonoLimpio;
    } elseif (!str_starts_with($telefono, '+')) {
        $telefonoLimpio = '+' . $telefonoLimpio;
    } else {
        $telefonoLimpio = $telefono;
    }

    // Preparar el mensaje personalizado
    $mensajePersonalizado = "Hola *{$nombre}*,\n\n{$mensaje}\n\n_Mensaje enviado desde el sistema de gestión_";

    // Datos para la API de Flexbis
    $data = [
        'numero_destinatario' => $telefonoLimpio,
        'tipo_destinatario' => 'contacto',
        'tipo_mensaje' => 'texto',
        'texto' => $mensajePersonalizado
    ];

    try {
        // Inicializar cURL
        $ch = curl_init();
        
        // URL de la API de Flexbis
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
        
        // Ejecutar la petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            
            log_message('error', 'Error cURL WhatsApp: ' . $error);
            
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error de conexión: ' . $error
            ]);
        }
        
        curl_close($ch);
        
        // Decodificar respuesta
        $resultado = json_decode($response, true);
        
        // Log para debug
        log_message('info', 'Respuesta WhatsApp API - Código: ' . $httpCode . ' | Respuesta: ' . $response);
        
        // Verificar si fue exitoso
        if ($httpCode === 200 || $httpCode === 201) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Mensaje enviado exitosamente a WhatsApp de ' . $nombre,
                'data' => $resultado
            ]);
        } else {
            // Manejar diferentes códigos de error
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
        log_message('error', 'Excepción WhatsApp: ' . $e->getMessage());
        
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Error al enviar mensaje: ' . $e->getMessage()
        ]);
    }
}
}