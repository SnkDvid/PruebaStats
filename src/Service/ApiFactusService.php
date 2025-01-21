<?php

namespace App\Service;

use App\Entity\Factura;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiFactusService
{
    private $token;

    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiUrl,
        private string $apiEmail,
        private string $apiPassword,
        private string $clientId,
        private string $clientSecret
    ) {
        $this->apiUrl = $_ENV['API_BASE_URL'];
        $this->apiEmail = $_ENV['API_EMAIL'];
        $this->apiPassword = $_ENV['API_PASSWORD'];
        $this->clientId = $_ENV['API_CLIENT_ID'];
        $this->clientSecret = $_ENV['API_CLIENT_SECRET'];
    }

    public function createFactura(Factura $factura)
    {
        if (!$this->token) {
            $this->authenticate();
        }

        try {
            $data = [
                'numeracion' => $factura->getRangoNumeracion(),
                'cliente' => [
                    'razonSocial' => $factura->getRazonSocial(),
                    'nombreComercial' => $factura->getNombreComercial(),
                    'organizacion' => $factura->getOrganizacion(),
                    'tipoTributario' => $factura->getTipoTributario(),
                    'tipoMunicipio' => $factura->getTipoMunicipio()
                ],
                'productos' => [
                    [
                        'nombre' => $factura->getNombreProducto(),
                        'cantidad' => $factura->getCantidadProducto(),
                        'total' => $factura->getTotal()
                    ]
                ]
            ];

            $response = $this->httpClient->request('POST', $this->apiUrl . '/facturas', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data
            ]);

            return $response->toArray();
        } catch (\Exception $e) {
            throw new \Exception('Error al crear factura en API: ' . $e->getMessage());
        }
    }

    private function authenticate()
    {
        try {
            $response = $this->httpClient->request('POST', $this->apiUrl . '/auth/login', [
                'json' => [
                    'email' => $this->apiEmail,
                    'password' => $this->apiPassword,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret
                ]
            ]);

            $data = $response->toArray();
            $this->token = $data['access_token'] ?? null;

            if (!$this->token) {
                throw new \Exception('No se pudo obtener el token de autenticación');
            }

            return $this->token;
        } catch (\Exception $e) {
            throw new \Exception('Error de autenticación: ' . $e->getMessage());
        }
    }

    public function makeRequest(string $method, string $endpoint, array $data = [])
    {
        if (!$this->token) {
            $this->authenticate();
        }

        try {
            $response = $this->httpClient->request($method, $this->apiUrl . $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data
            ]);

            return $response->toArray();
        } catch (\Exception $e) {
            throw new \Exception('Error en la petición API: ' . $e->getMessage());
        }
    }
} 