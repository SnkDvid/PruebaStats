<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Factura;
use App\Entity\Clientes;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ApiFactusService;

class FacturasController extends AbstractController
{
    public function __construct(private ApiFactusService $apiService)
    {
    }

    #[Route('/facturas', name: 'app_facturas')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $clientes = $entityManager->getRepository(Clientes::class)->findAll();
        $facturas = $entityManager->getRepository(Factura::class)->findAll();
        return $this->render('facturas/index.html.twig', [
            'controller_name' => 'FacturasController',
            'facturas' => $facturas,
            'clientes' => $clientes,
        ]);
    }

    #[Route('/facturas/create', name: 'facturas_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $clienteId = $request->request->get('cliente');
            $cliente = $entityManager->getRepository(Clientes::class)->find($clienteId);

            if (!$cliente) {
                throw $this->createNotFoundException('Cliente no encontrado');
            }

            $factura = new Factura();
            $factura->setCliente($cliente);
            $factura->setRangoNumeracion((int)$request->request->get('rangoNumeracion'));
            $factura->setNombreProducto($request->request->get('nombreProducto'));
            $factura->setCantidadProducto($request->request->get('cantidadProducto'));
            $factura->setTotal($request->request->get('total'));
            $factura->setRazonSocial($request->request->get('razonSocial'));
            $factura->setNombreComercial($request->request->get('nombreComercial'));
            $factura->setOrganizacion($request->request->get('organizacion'));
            $factura->setTipoTributario($request->request->get('tipoTributario'));
            $factura->setTipoMunicipio($request->request->get('tipoMunicipio'));

            // Enviar a la API
            $apiResponse = $this->apiService->createFactura($factura);

            // Si la API responde correctamente, guardamos en nuestra base de datos
            $entityManager->persist($factura);
            $entityManager->flush();

            $this->addFlash('success', 'Factura creada exitosamente en API y base de datos local');

            return $this->json([
                'success' => true,
                'message' => 'Factura creada exitosamente',
                'apiResponse' => $apiResponse
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/facturas/api', name: 'facturas_api')]
    public function apiExample(): Response
    {
        try {
            // Ejemplo de uso de la API
            $resultado = $this->apiService->makeRequest('GET', '/endpoint-especifico');
            
            return $this->json([
                'success' => true,
                'data' => $resultado
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
