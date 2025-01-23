<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Clientes;
use Knp\Component\Pager\PaginatorInterface;

class ClientesController extends AbstractController
{
    #[Route('/clientes', name: 'app_clientes')]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
       $queryBuilder = $entityManager->getRepository(Clientes::class)->createQueryBuilder('c'); 
       $query = $queryBuilder->getQuery();
       $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        10
       );

        // Obtener el número total de clientes
        $totalClientes = $entityManager->getRepository(Clientes::class)->createQueryBuilder('c')
            ->select('COUNT(c.id)') //equivale a SELECT COUNT(*)
            ->andWhere('c.Estado = :Estado') //equivale a WHERE estado = true'
            ->setParameter('Estado', true) //equivale a estado = true
            ->getQuery() //equivale a SELECT COUNT(*) FROM clientes WHERE estado = true
            ->getSingleScalarResult();

        // Obtener la fecha de hace un mes
        $fechaInicio = new \DateTime('-1 month');

        // Contar clientes creados en el último mes
        $clientesRecientes = $entityManager->getRepository(Clientes::class)->createQueryBuilder('c')
            ->select('COUNT(c.id)') //equivale a SELECT COUNT(*)
            ->where('c.fechaCreacion >= :fechaInicio') //equivale a WHERE fechaCreacion >= fechaInicio
            ->setParameter('fechaInicio', $fechaInicio) //equivale a fechaInicio
            ->getQuery() //equivale a SELECT COUNT(*) FROM clientes WHERE fechaCreacion >= fechaInicio
            ->getSingleScalarResult();

        // Calcular el porcentaje
        $percentageChange = 0;
        if ($totalClientes > 0) {
            $percentageChange = ($clientesRecientes / $totalClientes) * 100;
        }

        // Obtener todos los clientes para la tabla
        $clientes = $entityManager->getRepository(Clientes::class)->findAll();

        return $this->render('clientes/index.html.twig', [
            'controller_name' => 'ClientesController',
            'Clientes' => $pagination,
            'totalClientes' => $totalClientes,
            'percentageChange' => $percentageChange,
        ]);
    }

    #[Route('/clientes/create', name: 'app_clientes_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cliente = new Clientes();
        $cliente->setNombre($request->request->get('Nombre'));
        $cliente->setDireccion($request->request->get('Direccion'));
        $cliente->setCorreo($request->request->get('Correo'));
        $cliente->setCelular($request->request->get('Celular'));
        $cliente->setEstado($request->request->get('Estado') == 0);
        $cliente->setCivil($request->request->get('Civil'));
        // La fecha de creación se establece automáticamente en el constructor de Clientes

        $entityManager->persist($cliente);
        $entityManager->flush();

        $this->addFlash('success', 'Cliente registrado exitosamente.');

        return $this->redirectToRoute('clientes');
    }

    #[Route('/clientes/edit/{id}', name: 'app_clientes_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $cliente = $entityManager->getRepository(Clientes::class)->find($id);
        $cliente->setNombre($request->request->get('Nombre'));
        $cliente->setDireccion($request->request->get('Direccion'));
        $cliente->setCorreo($request->request->get('Correo'));
        $cliente->setCelular($request->request->get('Celular'));
        if ($cliente->getEstado() == true) {
            $cliente->setEstado(true);
        } else {
            $cliente->setEstado(false);
        }

        $cliente->setCivil($request->request->get('Civil'));

        $entityManager->persist($cliente);
        $entityManager->flush();

        $this->addFlash('success', 'Cliente actualizado exitosamente.');

        return $this->redirectToRoute('clientes');
    }   

    #[Route('/clientes/delete/{id}', name: 'app_clientes_delete')]
    public function delete(EntityManagerInterface $entityManager, $id): Response
    {
        $cliente = $entityManager->getRepository(Clientes::class)->find($id);
        $entityManager->remove($cliente);
        $entityManager->flush();

        $this->addFlash('success', 'Cliente eliminado exitosamente.');
        return $this->redirectToRoute('clientes');
    }

    #[Route('/clientes/activar/{id}', name: 'app_clientes_activar')]
    public function activar(EntityManagerInterface $entityManager, $id): Response
    {
        $cliente = $entityManager->getRepository(Clientes::class)->find($id);
        $cliente->setEstado(true);
        $entityManager->flush();

        $this->addFlash('success', 'Cliente ' . $cliente->getNombre() . ' activado exitosamente.');
        return $this->redirectToRoute('clientes');
    }

    #[Route('/clientes/desactivar/{id}', name: 'app_clientes_desactivar')]
    public function desactivar(EntityManagerInterface $entityManager, $id): Response
    {
        $cliente = $entityManager->getRepository(Clientes::class)->find($id);
        $cliente->setEstado(false);
        $entityManager->flush();

        $this->addFlash('success', 'Cliente ' . $cliente->getNombre() . ' desactivado exitosamente.');
        return $this->redirectToRoute('clientes');
    }
}
