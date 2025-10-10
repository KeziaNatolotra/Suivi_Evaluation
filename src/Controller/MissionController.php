<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mission')]
class MissionController extends AbstractController
{
    private MissionRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(MissionRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    // LISTE DE TOUTES LES MISSIONS
    #[Route('/', name: 'mission_index', methods: ['GET'])]
    public function index(): Response
    {
        $missions = $this->repository->findAll();
        return $this->json($missions);
    }

    // AFFICHER UNE MISSION
    #[Route('/{id}', name: 'mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->json($mission);
    }

    // CREER UNE MISSION
    #[Route('/create', name: 'mission_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $mission = new Mission();
        $mission->setNomMission($data['nom_mission'] ?? '');
        $mission->setDescription($data['description'] ?? '');

        $this->em->persist($mission);
        $this->em->flush();

        return $this->json([
            'message' => 'Mission créée avec succès',
            'mission' => $mission
        ], Response::HTTP_CREATED);
    }

    // MODIFIER UNE MISSION
    #[Route('/{id}/edit', name: 'mission_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Mission $mission): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nom_mission'])) {
            $mission->setNomMission($data['nom_mission']);
        }
        if (isset($data['description'])) {
            $mission->setDescription($data['description']);
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Mission modifiée avec succès',
            'mission' => $mission
        ]);
    }

    // SUPPRIMER UNE MISSION
    #[Route('/{id}/delete', name: 'mission_delete', methods: ['DELETE'])]
    public function delete(Mission $mission): Response
    {
        $this->em->remove($mission);
        $this->em->flush();

        return $this->json([
            'message' => 'Mission supprimée avec succès'
        ]);
    }
}
