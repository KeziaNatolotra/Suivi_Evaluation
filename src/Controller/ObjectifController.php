<?php

namespace App\Controller;

use App\Entity\Objectif;
use App\Repository\ObjectifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/objectif')]
class ObjectifController extends AbstractController
{
    private ObjectifRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(ObjectifRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    // LISTE DE TOUS LES OBJECTIFS
    #[Route('/', name: 'objectif_index', methods: ['GET'])]
    public function index(): Response
    {
        $objectifs = $this->repository->findAll();
        return $this->json($objectifs);
    }

    // AFFICHER UN OBJECTIF
    #[Route('/{id}', name: 'objectif_show', methods: ['GET'])]
    public function show(Objectif $objectif): Response
    {
        return $this->json($objectif);
    }

    // CREER UN OBJECTIF
    #[Route('/create', name: 'objectif_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $objectif = new Objectif();
        $objectif->setNomObjectif($data['nom_objectif'] ?? '');
        $objectif->setDescription($data['description'] ?? '');

        // Associer à une mission si l'id est fourni
        if (!empty($data['mission_id'])) {
            $mission = $this->em->getRepository(\App\Entity\Mission::class)->find($data['mission_id']);
            if ($mission) {
                $objectif->setMission($mission);
            }
        }

        $this->em->persist($objectif);
        $this->em->flush();

        return $this->json([
            'message' => 'Objectif créé avec succès',
            'objectif' => $objectif
        ], Response::HTTP_CREATED);
    }

    // MODIFIER UN OBJECTIF
    #[Route('/{id}/edit', name: 'objectif_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Objectif $objectif): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nom_objectif'])) {
            $objectif->setNomObjectif($data['nom_objectif']);
        }
        if (isset($data['description'])) {
            $objectif->setDescription($data['description']);
        }

        // Changer la mission si nécessaire
        if (!empty($data['mission_id'])) {
            $mission = $this->em->getRepository(\App\Entity\Mission::class)->find($data['mission_id']);
            if ($mission) {
                $objectif->setMission($mission);
            }
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Objectif modifié avec succès',
            'objectif' => $objectif
        ]);
    }

    // SUPPRIMER UN OBJECTIF
    #[Route('/{id}/delete', name: 'objectif_delete', methods: ['DELETE'])]
    public function delete(Objectif $objectif): Response
    {
        $this->em->remove($objectif);
        $this->em->flush();

        return $this->json([
            'message' => 'Objectif supprimé avec succès'
        ]);
    }
}
