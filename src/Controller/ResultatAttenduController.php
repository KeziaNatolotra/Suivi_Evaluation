<?php

namespace App\Controller;

use App\Entity\ResultatAttendu;
use App\Repository\ResultatAttenduRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/resultat-attendu')]
class ResultatAttenduController extends AbstractController
{
    private ResultatAttenduRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(ResultatAttenduRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    // LISTE TOUS LES RESULTATS ATTENDUS
    #[Route('/', name: 'resultat_attendu_index', methods: ['GET'])]
    public function index(): Response
    {
        $resultats = $this->repository->findAll();
        return $this->json($resultats);
    }

    // AFFICHER UN RESULTAT ATTENDU
    #[Route('/{id}', name: 'resultat_attendu_show', methods: ['GET'])]
    public function show(ResultatAttendu $resultatAttendu): Response
    {
        return $this->json($resultatAttendu);
    }

    // CREER UN RESULTAT ATTENDU
    #[Route('/create', name: 'resultat_attendu_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $resultat = new ResultatAttendu();
        $resultat->setDescription($data['description'] ?? '');
        $resultat->setIndicateur($data['indicateur'] ?? '');
        $resultat->setValeurCible($data['valeur_cible'] ?? 0);
        $resultat->setManqueAGagner($data['manque_a_gagner'] ?? 0);

        if (!empty($data['date_evaluation'])) {
            $resultat->setDateEvaluation(new \DateTime($data['date_evaluation']));
        }

        // Associer la Prevision
        if (!empty($data['prevision'])) {
            $resultat->setPrevision($this->em->getReference('App\Entity\Prevision', $data['prevision']));
        }

        $this->em->persist($resultat);
        $this->em->flush();

        return $this->json([
            'message' => 'Résultat attendu créé avec succès',
            'resultat' => $resultat
        ], Response::HTTP_CREATED);
    }

    // MODIFIER UN RESULTAT ATTENDU
    #[Route('/{id}/edit', name: 'resultat_attendu_edit', methods: ['PUT','PATCH'])]
    public function edit(Request $request, ResultatAttendu $resultatAttendu): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['description'])) $resultatAttendu->setDescription($data['description']);
        if (isset($data['indicateur'])) $resultatAttendu->setIndicateur($data['indicateur']);
        if (isset($data['valeur_cible'])) $resultatAttendu->setValeurCible($data['valeur_cible']);
        if (isset($data['manque_a_gagner'])) $resultatAttendu->setManqueAGagner($data['manque_a_gagner']);
        if (!empty($data['date_evaluation'])) $resultatAttendu->setDateEvaluation(new \DateTime($data['date_evaluation']));
        if (!empty($data['prevision'])) {
            $resultatAttendu->setPrevision($this->em->getReference('App\Entity\Prevision', $data['prevision']));
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Résultat attendu modifié avec succès',
            'resultat' => $resultatAttendu
        ]);
    }

    // SUPPRIMER UN RESULTAT ATTENDU
    #[Route('/{id}/delete', name: 'resultat_attendu_delete', methods: ['DELETE'])]
    public function delete(ResultatAttendu $resultatAttendu): Response
    {
        $this->em->remove($resultatAttendu);
        $this->em->flush();

        return $this->json([
            'message' => 'Résultat attendu supprimé avec succès'
        ]);
    }
}
