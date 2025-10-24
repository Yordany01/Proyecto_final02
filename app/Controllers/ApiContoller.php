<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiContoller extends BaseController
{
    private $token;
    private $flexsenderToken = 'WQxEZGlH44aJcpvpgzPN';
    private $flexsenderUrl = 'https://fleximessenger.com/api/v1/messages';

    public function __construct()
    {
        date_default_timezone_set("America/Lima");
        $this->token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNjEiLCJuYW1lIjoiVmljdG9yIEFuZHJlZSBDYW1wb3ZlcmRlIFZlZ2EiLCJlbWFpbCI6ImFuZHJlZWNhbXBvdmVyZGUuYWNAZ21haWwuY29tIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiY29uc3VsdG9yIn0.q7r1NsoO9aqubwt9rTWB6yYEXvvcAO6Wp5Pny1jX-d0';
    }

    public function buscarDni()
    {
        $dni = $this->request->getPost('dni');

        // Validar que el DNI no esté vacío
        if (empty($dni)) {
            return $this->response->setJSON(['error' => 'DNI es requerido'])->setStatusCode(400);
        }

        // Iniciar llamada a API
        $curl = curl_init();

        // Configurar CURL
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.factiliza.com/pe/v1/dni/info/' . $dni,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        // Procesar respuesta
        $persona = json_decode($response);

        if ($persona->status != "200") {
            return $this->response->setJSON(['error' => 'NO ENCONTRADO'])->setStatusCode(404);
        } else {
            return $this->response->setJSON($persona->data);
        }
    }

    public function enviarWhatsApp($numero, $mensaje)
    {
        // Validar que el número y el mensaje no estén vacíos
        if (empty($numero) || empty($mensaje)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Número de teléfono y mensaje son requeridos'
            ])->setStatusCode(400);
        }

        // Asegurar que el número tenga el formato correcto (eliminar espacios, guiones, etc.)
        $numero = preg_replace('/[^0-9]/', '', $numero);
        
        // Asegurar que el número tenga el código de país
        if (strlen($numero) === 9) {
            $numero = '51' . $numero; // Asumimos que es un número peruano
        }

        // Preparar los datos del mensaje
        $data = [
            'instance' => 'hl5jammw',
            'phone' => $numero,
            'body' => $mensaje
        ];

        // Opciones de configuración para probar
        $configsToTry = [
            // Intento 1: URL sin prefijo api
            [
                'url' => 'https://fleximessenger.com/api/v1/messages',
                'options' => [
                    'timeout' => 30.0,
                    'verify' => false,
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => 'application/json'
                    ]
                ]
            ],
            // Intento 2: URL con prefijo api
            [
                'url' => 'https://api.fleximessenger.com/api/v1/messages',
                'options' => [
                    'timeout' => 30.0,
                    'verify' => false,
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => 'application/json'
                    ]
                ]
            ],
            // Intento 3: Usar Google DNS para la resolución
            [
                'url' => 'https://fleximessenger.com/api/v1/messages',
                'options' => [
                    'timeout' => 30.0,
                    'verify' => false,
                    'curl' => [
                        CURLOPT_DNS_SERVERS => '8.8.8.8,8.8.4.4' // Usar Google DNS
                    ],
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => 'application/json'
                    ]
                ]
            ]
        ];

        $lastError = null;
        
        // Probar cada configuración hasta que una funcione
        foreach ($configsToTry as $config) {
            try {
                $client = new Client([
                    'base_uri' => $config['url'],
                    'timeout' => $config['options']['timeout'],
                    'verify' => $config['options']['verify'],
                    'headers' => $config['options']['headers'],
                    'curl' => $config['options']['curl'] ?? [],
                    'http_errors' => false
                ]);

                log_message('debug', 'Probando con URL: ' . $config['url']);
                
                $response = $client->post('', [
                    'form_params' => $data
                ]);

                $responseBody = $response->getBody()->getContents();
                $responseData = json_decode($responseBody, true) ?: $responseBody;

                log_message('debug', 'Respuesta de ' . $config['url'] . ': ' . print_r($responseData, true));

                if ($response->getStatusCode() === 200 || $response->getStatusCode() === 201) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'data' => $responseData,
                        'used_config' => $config['url']
                    ]);
                } else {
                    $lastError = [
                        'status' => 'error',
                        'message' => is_array($responseData) ? ($responseData['message'] ?? 'Error al enviar el mensaje') : $responseData,
                        'data' => $responseData,
                        'status_code' => $response->getStatusCode(),
                        'used_config' => $config['url']
                    ];
                    log_message('error', 'Error en la configuración ' . $config['url'] . ': ' . print_r($lastError, true));
                }
            } catch (\Exception $e) {
                $lastError = [
                    'status' => 'error',
                    'message' => 'Error con la configuración ' . $config['url'] . ': ' . $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ];
                log_message('error', $lastError['message'] . '\n' . $e->getTraceAsString());
            }
        }

        // Si llegamos aquí, ninguna configuración funcionó
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No se pudo conectar al servicio de mensajería después de varios intentos',
            'last_error' => $lastError,
            'suggestions' => [
                'Verifica tu conexión a Internet',
                'Verifica que el dominio api.fleximessenger.com sea accesible desde este servidor',
                'Contacta al soporte de Flexsender para confirmar la URL correcta de la API',
                'Verifica que no haya un firewall bloqueando la conexión'
            ]
        ])->setStatusCode(500);
    }

    /**
     * Endpoint para enviar mensajes de WhatsApp
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function enviarMensajeWhatsApp()
    {
        // Obtener datos de la petición
        $numero = $this->request->getPost('numero');
        $mensaje = $this->request->getPost('mensaje');

        // Validar que se hayan proporcionado los datos requeridos
        if (empty($numero) || empty($mensaje)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Número de teléfono y mensaje son requeridos'
            ])->setStatusCode(400);
        }

        // Llamar al método para enviar el mensaje
        return $this->enviarWhatsApp($numero, $mensaje);
    }
}