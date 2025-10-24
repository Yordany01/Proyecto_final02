<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AjusteController extends BaseController
{
    public function index()
    {
        return view('ajuste/index');
    }

    // Obtener datos de ajustes del administrador
    public function obtener()
    {
        try {
            $path = $this->getDataFilePath();
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
                'error' => 'Error al obtener ajustes: ' . $e->getMessage()
            ]);
        }
    }

    // Guardar/actualizar datos de ajustes del administrador (con soporte de foto)
    public function guardar()
    {
        try {
            $data = [
                'nombre' => trim((string)$this->request->getPost('nombre')),
                'apellidos' => trim((string)$this->request->getPost('apellidos')),
                'dni' => trim((string)$this->request->getPost('dni')),
                'email' => trim((string)$this->request->getPost('email')),
                'telefono' => trim((string)$this->request->getPost('telefono')),
                'direccion' => trim((string)$this->request->getPost('direccion')),
                'fecha_nacimiento' => trim((string)$this->request->getPost('fecha_nacimiento')),
            ];

            // Validaciones mínimas
            if (empty($data['nombre']) || empty($data['email'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Nombre y Email son obligatorios'
                ]);
            }

            // Manejo de foto de perfil opcional (guardar en public/uploads para acceso web directo)
            $file = $this->request->getFile('foto');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $uploadDir = FCPATH . 'uploads'; // carpeta pública
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0775, true);
                }
                $file->move($uploadDir, $newName);
                // URL accesible públicamente
                $data['foto_url'] = base_url('uploads/' . $newName);
            } else {
                // Si no se envía nueva foto, se conservará la anterior por el merge con $prev
            }

            // Persistir en JSON
            $path = $this->getDataFilePath();
            $dir = dirname($path);
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }

            // Si hay datos previos, mezclarlos para no perder campos no enviados
            $prev = [];
            if (is_file($path)) {
                $prev = json_decode(file_get_contents($path), true) ?? [];
            }
            $toSave = array_merge($prev, array_filter($data, fn($v) => $v !== ''));
            file_put_contents($path, json_encode($toSave, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Ajustes guardados correctamente',
                'data' => $toSave
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al guardar ajustes: ' . $e->getMessage()
            ]);
        }
    }

    private function getDataFilePath(): string
    {
        return WRITEPATH . 'ajustes/admin.json';
    }
}
