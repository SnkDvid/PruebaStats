<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Clientes;

class ClientesController extends AbstractController
{
    #[Route('/clientes', name: 'app_clientes')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $clientesRepository = $entityManager->getRepository(Clientes::class); 

        // Obtener el número total de clientes
        $totalClientes = $clientesRepository->count([]);

        // Obtener la fecha de hace un mes
        $fechaInicio = new \DateTime('-1 month');

        // Contar clientes creados en el último mes
        $clientesRecientes = $clientesRepository->createQueryBuilder('c')
            ->select('COUNT(c.id)') //equivale a SELECT COUNT(*)
            ->where('c.fechaCreacion >= :fechaInicio') //equivale a WHERE fechaCreacion >= fechaInicio
            ->setParameter('fechaInicio', $fechaInicio) //equivale a fechaInicio
            ->getQuery() //equivale a SELECT COUNT(*) FROM clientes WHERE fechaCreacion >= fechaInicio
            ->getSingleScalarResult();

        // Calcular el porcentaje
        $percentageChange = 0;
        if ($totalClientes > 0) {
            $percentageChange = ($clientesRecientes / $totalClientes) * $totalClientes;
        }

        // Obtener todos los clientes para la tabla
        $clientes = $clientesRepository->findAll();

        return $this->render('clientes/index.html.twig', [
            'controller_name' => 'ClientesController',
            'Clientes' => $clientes,
            'totalClientes' => $totalClientes,
            'percentageChange' => $percentageChange,
        ]);
    }

    #[Route('/clientes/create', name: 'app_clientes_create')]
    public function create(Request $request): Response
    {
        $cliente = new Clientes();
        $cliente->setNombre($request->request->get('Nombre'));
        $cliente->setCorreo($request->request->get('Correo'));
        $cliente->setCelular($request->request->get('Celular'));
        $cliente->setEstado($request->request->get('Estado') == 0);
        $cliente->setCivil($request->request->get('Civil'));
        // La fecha de creación se establece automáticamente en el constructor de Clientes

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cliente);
        $entityManager->flush();

        $this->addFlash('success', 'Cliente registrado exitosamente.');

        return $this->redirectToRoute('clientes');
    }
}
