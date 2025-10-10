<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/employees')]
class EmployeeController extends AbstractController
{
    // Lister tous les employés
    #[Route('', name:'employee_list', methods:['GET'])]
    public function list(EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $employees = $em->getRepository(Employee::class)->findAll();
        $data = $serializer->serialize($employees, 'json');
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    // Ajouter un employé
    #[Route('', name:'employee_create', methods:['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        $employee = new Employee();
        $employee->setMatricule($data['matricule']);
        $employee->setDateRecrutement(new \DateTime($data['date_recrutement']));

        $em->persist($employee);
        $em->flush();

        return new Response($serializer->serialize($employee, 'json'), 201, ['Content-Type' => 'application/json']);
    }

    // Mettre à jour un employé
    #[Route('/{id}', name:'employee_update', methods:['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $employee = $em->getRepository(Employee::class)->find($id);
        if (!$employee) {
            return new Response('Employé non trouvé', 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['matricule'])) {
            $employee->setMatricule($data['matricule']);
        }
        if (isset($data['date_recrutement'])) {
            $employee->setDateRecrutement(new \DateTime($data['date_recrutement']));
        }

        $em->flush();

        return new Response($serializer->serialize($employee, 'json'), 200, ['Content-Type' => 'application/json']);
    }

    // Supprimer un employé
    #[Route('/{id}', name:'employee_delete', methods:['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $employee = $em->getRepository(Employee::class)->find($id);
        if (!$employee) {
            return new Response('Employé non trouvé', 404);
        }

        $em->remove($employee);
        $em->flush();

        return new Response(null, 204);
    }
}

