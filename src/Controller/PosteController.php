<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/poste')]
class PosteController extends AbstractController
{
    private PosteRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(PosteRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    // LISTE DE TOUS LES POSTES
    #[Route('/', name: 'poste_index', methods: ['GET'])]
    public function index(): Response
    {
        $postes = $this->repository->findAll();
        return $this->json($postes);
    }

    // AFFICHER UN POSTE
    #[Route('/{id}', name: 'poste_show', methods: ['GET'])]
    public function show(Poste $poste): Response
    {
        return $this->json($poste);
    }

    // CREER UN POSTE
    #[Route('/create', name: 'poste_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $poste = new Poste();
        $poste->setNomPoste($data['nom_poste'] ?? '');
        $poste->setDescriptionPoste($data['description_poste'] ?? '');
        $poste->setNiveauHierarchique($data['niveau_hierarchique'] ?? '');

        $this->em->persist($poste);
        $this->em->flush();

        return $this->json([
            'message' => 'Poste créé avec succès',
            'poste' => $poste
        ], Response::HTTP_CREATED);
    }

    // MODIFIER UN POSTE
    #[Route('/{id}/edit', name: 'poste_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Poste $poste): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nom_poste'])) {
            $poste->setNomPoste($data['nom_poste']);
        }
        if (isset($data['description_poste'])) {
            $poste->setDescriptionPoste($data['description_poste']);
        }
        if (isset($data['niveau_hierarchique'])) {
            $poste->setNiveauHierarchique($data['niveau_hierarchique']);
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Poste modifié avec succès',
            'poste' => $poste
        ]);
    }

    // SUPPRIMER UN POSTE
    #[Route('/{id}/delete', name: 'poste_delete', methods: ['DELETE'])]
    public function delete(Poste $poste): Response
    {
        $this->em->remove($poste);
        $this->em->flush();

        return $this->json([
            'message' => 'Poste supprimé avec succès'
        ]);
    }
}
