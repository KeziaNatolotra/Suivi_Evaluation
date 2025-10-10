<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Entity\Mission;
use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/departements')]
class DepartementController extends AbstractController
{
    // Lister tous les départements
    #[Route('', name:'departement_list', methods:['GET'])]
    public function list(EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $departements = $em->getRepository(Departement::class)->findAll();
        $data = $serializer->serialize($departements, 'json', ['groups' => ['departement']]);
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    // Créer un département
    #[Route('', name:'departement_create', methods:['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        $mission = $em->getRepository(Mission::class)->find($data['mission_id'] ?? null);
        if (!$mission) {
            return new Response('Mission non trouvée', 404);
        }

        $departement = new Departement();
        $departement->setNomDirection($data['nom_direction']);
        $departement->setDescription($data['description']);
        $departement->setResponsableDirection($data['responsable_direction']);
        $departement->setFKMission($data['FK_mission']);
        $departement->setMission($mission);

        $em->persist($departement);
        $em->flush();

        return new Response($serializer->serialize($departement, 'json', ['groups' => ['departement']]), 201, ['Content-Type' => 'application/json']);
    }

    // Mettre à jour un département
    #[Route('/{id}', name:'departement_update', methods:['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $departement = $em->getRepository(Departement::class)->find($id);
        if (!$departement) {
            return new Response('Département non trouvé', 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nom_direction'])) $departement->setNomDirection($data['nom_direction']);
        if (isset($data['description'])) $departement->setDescription($data['description']);
        if (isset($data['responsable_direction'])) $departement->setResponsableDirection($data['responsable_direction']);
        if (isset($data['FK_mission'])) $departement->setFKMission($data['FK_mission']);
        if (isset($data['mission_id'])) {
            $mission = $em->getRepository(Mission::class)->find($data['mission_id']);
            if ($mission) $departement->setMission($mission);
        }

        $em->flush();

        return new Response($serializer->serialize($departement, 'json', ['groups' => ['departement']]), 200, ['Content-Type' => 'application/json']);
    }

    // Supprimer un département
    #[Route('/{id}', name:'departement_delete', methods:['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $departement = $em->getRepository(Departement::class)->find($id);
        if (!$departement) {
            return new Response('Département non trouvé', 404);
        }

        $em->remove($departement);
        $em->flush();

        return new Response(null, 204);
    }
}
