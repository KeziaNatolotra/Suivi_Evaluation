<?php

namespace App\Controller;

use App\Entity\EtatCivil;
use App\Repository\EtatCivilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/etat-civils', name: 'etat_civil_')]
class EtatCivilController extends AbstractController
{
    private EntityManagerInterface $em;
    private EtatCivilRepository $repository;

    public function __construct(EntityManagerInterface $em, EtatCivilRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    // GET /api/etat-civils
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $etatCivils = $this->repository->findAll();

        $data = array_map(fn(EtatCivil $ec) => [
            'id' => $ec->getId(),
            'nom' => $ec->getNom(),
            'prenom' => $ec->getPrenom(),
            'date_naissance' => $ec->getDateNaissance()?->format('Y-m-d'),
            'lieu_naissance' => $ec->getLieuNaissance(),
            'sexe' => $ec->getSexe(),
            'adresse' => $ec->getAdresse(),
            'telephone' => $ec->getTelephone(),
        ], $etatCivils);

        return $this->json($data);
    }

    // GET /api/etat-civils/{id}
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $etatCivil = $this->repository->find($id);

        if (!$etatCivil) {
            return $this->json(['message' => 'EtatCivil non trouvé'], 404);
        }

        $data = [
            'id' => $etatCivil->getId(),
            'nom' => $etatCivil->getNom(),
            'prenom' => $etatCivil->getPrenom(),
            'date_naissance' => $etatCivil->getDateNaissance()?->format('Y-m-d'),
            'lieu_naissance' => $etatCivil->getLieuNaissance(),
            'sexe' => $etatCivil->getSexe(),
            'adresse' => $etatCivil->getAdresse(),
            'telephone' => $etatCivil->getTelephone(),
        ];

        return $this->json($data);
    }

    // POST /api/etat-civils
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $etatCivil = new EtatCivil();
        $etatCivil->setNom($data['nom'] ?? '');
        $etatCivil->setPrenom($data['prenom'] ?? '');
        $etatCivil->setDateNaissance(new \DateTime($data['date_naissance'] ?? 'now'));
        $etatCivil->setLieuNaissance($data['lieu_naissance'] ?? '');
        $etatCivil->setSexe($data['sexe'] ?? '');
        $etatCivil->setAdresse($data['adresse'] ?? '');
        $etatCivil->setTelephone($data['telephone'] ?? '');

        $this->em->persist($etatCivil);
        $this->em->flush();

        return $this->json(['message' => 'EtatCivil créé', 'id' => $etatCivil->getId()], 201);
    }

    // PUT /api/etat-civils/{id}
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $etatCivil = $this->repository->find($id);
        if (!$etatCivil) {
            return $this->json(['message' => 'EtatCivil non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nom'])) $etatCivil->setNom($data['nom']);
        if (isset($data['prenom'])) $etatCivil->setPrenom($data['prenom']);
        if (isset($data['date_naissance'])) $etatCivil->setDateNaissance(new \DateTime($data['date_naissance']));
        if (isset($data['lieu_naissance'])) $etatCivil->setLieuNaissance($data['lieu_naissance']);
        if (isset($data['sexe'])) $etatCivil->setSexe($data['sexe']);
        if (isset($data['adresse'])) $etatCivil->setAdresse($data['adresse']);
        if (isset($data['telephone'])) $etatCivil->setTelephone($data['telephone']);

        $this->em->flush();

        return $this->json(['message' => 'EtatCivil mis à jour']);
    }

    // DELETE /api/etat-civils/{id}
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $etatCivil = $this->repository->find($id);
        if (!$etatCivil) {
            return $this->json(['message' => 'EtatCivil non trouvé'], 404);
        }

        $this->em->remove($etatCivil);
        $this->em->flush();

        return $this->json(['message' => 'EtatCivil supprimé']);
    }
}
