<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projet')]
class ProjetController extends AbstractController
{
    private ProjetRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(ProjetRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    // LISTE TOUS LES PROJETS
    #[Route('/', name: 'projet_index', methods: ['GET'])]
    public function index(): Response
    {
        $projets = $this->repository->findAll();
        return $this->json($projets);
    }

    // AFFICHER UN PROJET
    #[Route('/{id}', name: 'projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->json($projet);
    }

    // CREER UN PROJET
    #[Route('/create', name: 'projet_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $projet = new Projet();
        $projet->setNomProjet($data['nom_projet'] ?? '');
        $projet->setDescription($data['description'] ?? '');

        // Associer Activite si fourni
        if (!empty($data['activite'])) {
            $projet->setActivite($this->em->getReference('App\Entity\Activite', $data['activite']));
        }

        // Ajouter Employes si fournis
        if (!empty($data['employes']) && is_array($data['employes'])) {
            foreach ($data['employes'] as $employeId) {
                $projet->addEmploye($this->em->getReference('App\Entity\Employe', $employeId));
            }
        }

        $this->em->persist($projet);
        $this->em->flush();

        return $this->json([
            'message' => 'Projet créé avec succès',
            'projet' => $projet
        ], Response::HTTP_CREATED);
    }

    // MODIFIER UN PROJET
    #[Route('/{id}/edit', name: 'projet_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Projet $projet): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nom_projet'])) $projet->setNomProjet($data['nom_projet']);
        if (isset($data['description'])) $projet->setDescription($data['description']);
        if (isset($data['activite'])) {
            $projet->setActivite($this->em->getReference('App\Entity\Activite', $data['activite']));
        }

        if (isset($data['employes']) && is_array($data['employes'])) {
            // Clear existing employes
            foreach ($projet->getEmployes() as $employe) {
                $projet->removeEmploye($employe);
            }
            // Add new ones
            foreach ($data['employes'] as $employeId) {
                $projet->addEmploye($this->em->getReference('App\Entity\Employe', $employeId));
            }
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Projet modifié avec succès',
            'projet' => $projet
        ]);
    }

    // SUPPRIMER UN PROJET
    #[Route('/{id}/delete', name: 'projet_delete', methods: ['DELETE'])]
    public function delete(Projet $projet): Response
    {
        $this->em->remove($projet);
        $this->em->flush();

        return $this->json([
            'message' => 'Projet supprimé avec succès'
        ]);
    }
}
