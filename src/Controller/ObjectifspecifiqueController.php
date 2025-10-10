<?php

namespace App\Controller;

use App\Entity\Objectifspecifique;
use App\Repository\ObjectifspecifiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/objectif-specifique')]
class ObjectifspecifiqueController extends AbstractController
{
    private ObjectifspecifiqueRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(ObjectifspecifiqueRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    // LISTE DE TOUS LES OBJECTIFS SPECIFIQUES
    #[Route('/', name: 'objectif_specifique_index', methods: ['GET'])]
    public function index(): Response
    {
        $objectifsSpecifiques = $this->repository->findAll();
        return $this->json($objectifsSpecifiques);
    }

    // AFFICHER UN OBJECTIF SPECIFIQUE
    #[Route('/{id}', name: 'objectif_specifique_show', methods: ['GET'])]
    public function show(Objectifspecifique $objectifSpecifique): Response
    {
        return $this->json($objectifSpecifique);
    }

    // CREER UN OBJECTIF SPECIFIQUE
    #[Route('/create', name: 'objectif_specifique_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $objectifSpecifique = new Objectifspecifique();
        $objectifSpecifique->setNomObjectifSpecifique($data['nom_objectif_specifique'] ?? '');
        $objectifSpecifique->setDescritpion($data['descritpion'] ?? '');
        $objectifSpecifique->setTaux($data['taux'] ?? 0);

        // Associer à un objectif si l'id est fourni
        if (!empty($data['objectif_id'])) {
            $objectif = $this->em->getRepository(\App\Entity\Objectif::class)->find($data['objectif_id']);
            if ($objectif) {
                $objectifSpecifique->setObjectif($objectif);
            }
        }

        $this->em->persist($objectifSpecifique);
        $this->em->flush();

        return $this->json([
            'message' => 'Objectif spécifique créé avec succès',
            'objectif_specifique' => $objectifSpecifique
        ], Response::HTTP_CREATED);
    }

    // MODIFIER UN OBJECTIF SPECIFIQUE
    #[Route('/{id}/edit', name: 'objectif_specifique_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Objectifspecifique $objectifSpecifique): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nom_objectif_specifique'])) {
            $objectifSpecifique->setNomObjectifSpecifique($data['nom_objectif_specifique']);
        }
        if (isset($data['descritpion'])) {
            $objectifSpecifique->setDescritpion($data['descritpion']);
        }
        if (isset($data['taux'])) {
            $objectifSpecifique->setTaux($data['taux']);
        }

        if (!empty($data['objectif_id'])) {
            $objectif = $this->em->getRepository(\App\Entity\Objectif::class)->find($data['objectif_id']);
            if ($objectif) {
                $objectifSpecifique->setObjectif($objectif);
            }
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Objectif spécifique modifié avec succès',
            'objectif_specifique' => $objectifSpecifique
        ]);
    }

    // SUPPRIMER UN OBJECTIF SPECIFIQUE
    #[Route('/{id}/delete', name: 'objectif_specifique_delete', methods: ['DELETE'])]
    public function delete(Objectifspecifique $objectifSpecifique): Response
    {
        $this->em->remove($objectifSpecifique);
        $this->em->flush();

        return $this->json([
            'message' => 'Objectif spécifique supprimé avec succès'
        ]);
    }
}
