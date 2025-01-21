<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Factura;
use App\Entity\Clientes;
use Symfony\Component\HttpFoundation\Request;

class FacturasController extends AbstractController
{
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

    #[Route('/facturas/create', name: 'app_facturas_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $clienteId = $request->request->get('cliente');
        $cliente = $entityManager->getRepository(Clientes::class)->find($clienteId);

        if (!$cliente) {
            throw $this->createNotFoundException('Cliente no encontrado');
        }

        $factura = new Factura();
        $factura->setCliente($cliente);
        $factura->setRangoNumeracion($request->request->get('rangoNumeracion'));
        $factura->setNombreProducto($request->request->get('nombreProducto'));
        $factura->setCantidadProducto($request->request->get('cantidadProducto'));
        $factura->setTotal($request->request->get('total'));

        $entityManager->persist($factura);
        $entityManager->flush();

        $this->addFlash('success', 'Factura creada exitosamente');

        return $this->redirectToRoute('facturas');
    }
}
