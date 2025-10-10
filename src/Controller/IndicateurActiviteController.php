<?php

namespace App\Controller;

use App\Entity\IndicateurActivite;
use App\Repository\IndicateurActiviteRepository;
use App\Repository\ActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/indicateurs', name: 'indicateur_')]
class IndicateurActiviteController extends AbstractController
{
    private EntityManagerInterface $em;
    private IndicateurActiviteRepository $repository;
    private ActiviteRepository $activiteRepository;

    public function __construct(EntityManagerInterface $em, IndicateurActiviteRepository $repository, ActiviteRepository $activiteRepository)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->activiteRepository = $activiteRepository;
    }

    // GET /api/indicateurs
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $indicateurs = $this->repository->findAll();

        $data = array_map(fn(IndicateurActivite $i) => [
            'id' => $i->getId(),
            'nom_indicateur' => $i->getNomIndicateur(),
            'unite' => $i->getUnite(),
            'valeur_actuelle' => $i->getValeurActuelle(),
            'activite_id' => $i->getActivite()?->getId(),
        ], $indicateurs);

        return $this->json($data);
    }

    // GET /api/indicateurs/{id}
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $indicateur = $this->repository->find($id);

        if (!$indicateur) {
            return $this->json(['message' => 'Indicateur non trouvé'], 404);
        }

        $data = [
            'id' => $indicateur->getId(),
            'nom_indicateur' => $indicateur->getNomIndicateur(),
            'unite' => $indicateur->getUnite(),
            'valeur_actuelle' => $indicateur->getValeurActuelle(),
            'activite_id' => $indicateur->getActivite()?->getId(),
        ];

        return $this->json($data);
    }

    // POST /api/indicateurs
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $activite = $this->activiteRepository->find($data['activite_id'] ?? 0);
        if (!$activite) {
            return $this->json(['message' => 'Activité non trouvée'], 404);
        }

        $indicateur = new IndicateurActivite();
        $indicateur->setNomIndicateur($data['nom_indicateur'] ?? '');
        $indicateur->setUnite($data['unite'] ?? '');
        $indicateur->setValeurActuelle((float)($data['valeur_actuelle'] ?? 0));
        $indicateur->setActivite($activite);

        $this->em->persist($indicateur);
        $this->em->flush();

        return $this->json(['message' => 'Indicateur créé', 'id' => $indicateur->getId()], 201);
    }

    // PUT /api/indicateurs/{id}
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $indicateur = $this->repository->find($id);
        if (!$indicateur) {
            return $this->json(['message' => 'Indicateur non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nom_indicateur'])) $indicateur->setNomIndicateur($data['nom_indicateur']);
        if (isset($data['unite'])) $indicateur->setUnite($data['unite']);
        if (isset($data['valeur_actuelle'])) $indicateur->setValeurActuelle((float)$data['valeur_actuelle']);
        if (isset($data['activite_id'])) {
            $activite = $this->activiteRepository->find($data['activite_id']);
            if ($activite) $indicateur->setActivite($activite);
        }

        $this->em->flush();

        return $this->json(['message' => 'Indicateur mis à jour']);
    }

    // DELETE /api/indicateurs/{id}
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $indicateur = $this->repository->find($id);
        if (!$indicateur) {
            return $this->json(['message' => 'Indicateur non trouvé'], 404);
        }

        $this->em->remove($indicateur);
        $this->em->flush();

        return $this->json(['message' => 'Indicateur supprimé']);
    }
}
