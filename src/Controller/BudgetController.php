<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\Projet;
use App\Entity\Activite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/budgets')]
class BudgetController extends AbstractController
{
    // Lister tous les budgets
    #[Route('', name:'budget_list', methods:['GET'])]
    public function list(EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $budgets = $em->getRepository(Budget::class)->findAll();
        $data = $serializer->serialize($budgets, 'json', ['groups' => ['budget']]);
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    // Créer un budget
    #[Route('', name:'budget_create', methods:['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        $projet = $em->getRepository(Projet::class)->find($data['projet_id'] ?? null);
        $activite = $em->getRepository(Activite::class)->find($data['activite_id'] ?? null);

        if (!$projet || !$activite) {
            return new Response('Projet ou Activité non trouvé', 404);
        }

        $budget = new Budget();
        $budget->setMontantPrevu($data['montant_prevu']);
        $budget->setMontantDepense($data['montant_depense']);
        $budget->setProjet($projet);
        $budget->setActivite($activite);

        $em->persist($budget);
        $em->flush();

        return new Response($serializer->serialize($budget, 'json', ['groups' => ['budget']]), 201, ['Content-Type' => 'application/json']);
    }

    // Mettre à jour un budget
    #[Route('/{id}', name:'budget_update', methods:['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $budget = $em->getRepository(Budget::class)->find($id);
        if (!$budget) {
            return new Response('Budget non trouvé', 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['montant_prevu'])) {
            $budget->setMontantPrevu($data['montant_prevu']);
        }
        if (isset($data['montant_depense'])) {
            $budget->setMontantDepense($data['montant_depense']);
        }
        if (isset($data['projet_id'])) {
            $projet = $em->getRepository(Projet::class)->find($data['projet_id']);
            if ($projet) $budget->setProjet($projet);
        }
        if (isset($data['activite_id'])) {
            $activite = $em->getRepository(Activite::class)->find($data['activite_id']);
            if ($activite) $budget->setActivite($activite);
        }

        $em->flush();

        return new Response($serializer->serialize($budget, 'json', ['groups' => ['budget']]), 200, ['Content-Type' => 'application/json']);
    }

    // Supprimer un budget
    #[Route('/{id}', name:'budget_delete', methods:['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $budget = $em->getRepository(Budget::class)->find($id);
        if (!$budget) {
            return new Response('Budget non trouvé', 404);
        }

        $em->remove($budget);
        $em->flush();

        return new Response(null, 204);
    }
}
