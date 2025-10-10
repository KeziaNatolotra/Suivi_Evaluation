<?php

namespace App\Controller;

use App\Entity\Prevision;
use App\Repository\PrevisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prevision')]
class PrevisionController extends AbstractController
{
    private PrevisionRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(PrevisionRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    // LISTE DE TOUTES LES PREVISIONS
    #[Route('/', name: 'prevision_index', methods: ['GET'])]
    public function index(): Response
    {
        $previsions = $this->repository->findAll();
        return $this->json($previsions);
    }

    // AFFICHER UNE PREVISION
    #[Route('/{id}', name: 'prevision_show', methods: ['GET'])]
    public function show(Prevision $prevision): Response
    {
        return $this->json($prevision);
    }

    // CREER UNE PREVISION
    #[Route('/create', name: 'prevision_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $prevision = new Prevision();
        $prevision->setT1($data['T1'] ?? 0);
        $prevision->setT2($data['T2'] ?? 0);
        $prevision->setT3($data['T3'] ?? 0);
        $prevision->setT4($data['T4'] ?? 0);
        $prevision->setTotal($data['total'] ?? 0);
        $prevision->setLFRLFI($data['LFR_LFI'] ?? '');
        $prevision->setDatePhysique(new \DateTime($data['date_physique'] ?? 'now'));

        // Associer l'indicateurActivite si fourni
        if (isset($data['indicateurActivite'])) {
            $prevision->setIndicateurActivite($this->em->getReference('App\Entity\IndicateurActivite', $data['indicateurActivite']));
        }

        $this->em->persist($prevision);
        $this->em->flush();

        return $this->json([
            'message' => 'Prévision créée avec succès',
            'prevision' => $prevision
        ], Response::HTTP_CREATED);
    }

    // MODIFIER UNE PREVISION
    #[Route('/{id}/edit', name: 'prevision_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Prevision $prevision): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['T1'])) $prevision->setT1($data['T1']);
        if (isset($data['T2'])) $prevision->setT2($data['T2']);
        if (isset($data['T3'])) $prevision->setT3($data['T3']);
        if (isset($data['T4'])) $prevision->setT4($data['T4']);
        if (isset($data['total'])) $prevision->setTotal($data['total']);
        if (isset($data['LFR_LFI'])) $prevision->setLFRLFI($data['LFR_LFI']);
        if (isset($data['date_physique'])) $prevision->setDatePhysique(new \DateTime($data['date_physique']));
        if (isset($data['indicateurActivite'])) {
            $prevision->setIndicateurActivite($this->em->getReference('App\Entity\IndicateurActivite', $data['indicateurActivite']));
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Prévision modifiée avec succès',
            'prevision' => $prevision
        ]);
    }

    // SUPPRIMER UNE PREVISION
    #[Route('/{id}/delete', name: 'prevision_delete', methods: ['DELETE'])]
    public function delete(Prevision $prevision): Response
    {
        $this->em->remove($prevision);
        $this->em->flush();

        return $this->json([
            'message' => 'Prévision supprimée avec succès'
        ]);
    }
}
