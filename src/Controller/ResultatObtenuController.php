<?php

namespace App\Controller;

use App\Entity\ResultatObtenu;
use App\Repository\ResultatObtenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/resultat-obtenu')]
class ResultatObtenuController extends AbstractController
{
    private ResultatObtenuRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(ResultatObtenuRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    // LISTER TOUS LES RESULTATS OBTENUS
    #[Route('/', name: 'resultat_obtenu_index', methods: ['GET'])]
    public function index(): Response
    {
        $resultats = $this->repository->findAll();
        return $this->json($resultats);
    }

    // AFFICHER UN RESULTAT OBTENU
    #[Route('/{id}', name: 'resultat_obtenu_show', methods: ['GET'])]
    public function show(ResultatObtenu $resultatObtenu): Response
    {
        return $this->json($resultatObtenu);
    }

    // CREER UN RESULTAT OBTENU
    #[Route('/create', name: 'resultat_obtenu_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $resultat = new ResultatObtenu();
        $resultat->setDescription($data['description'] ?? '');
        $resultat->setIndicateur($data['indicateur'] ?? '');
        $resultat->setValeurActuelle($data['valeur_actuelle'] ?? 0);

        if (!empty($data['prevision'])) {
            $resultat->setPrevision(
                $this->em->getReference('App\Entity\Prevision', $data['prevision'])
            );
        }

        $this->em->persist($resultat);
        $this->em->flush();

        return $this->json([
            'message' => 'Résultat obtenu créé avec succès',
            'resultat' => $resultat
        ], Response::HTTP_CREATED);
    }

    // MODIFIER UN RESULTAT OBTENU
    #[Route('/{id}/edit', name: 'resultat_obtenu_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, ResultatObtenu $resultatObtenu): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['description'])) $resultatObtenu->setDescription($data['description']);
        if (isset($data['indicateur'])) $resultatObtenu->setIndicateur($data['indicateur']);
        if (isset($data['valeur_actuelle'])) $resultatObtenu->setValeurActuelle($data['valeur_actuelle']);
        if (!empty($data['prevision'])) {
            $resultatObtenu->setPrevision(
                $this->em->getReference('App\Entity\Prevision', $data['prevision'])
            );
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Résultat obtenu modifié avec succès',
            'resultat' => $resultatObtenu
        ]);
    }

    // SUPPRIMER UN RESULTAT OBTENU
    #[Route('/{id}/delete', name: 'resultat_obtenu_delete', methods: ['DELETE'])]
    public function delete(ResultatObtenu $resultatObtenu): Response
    {
        $this->em->remove($resultatObtenu);
        $this->em->flush();

        return $this->json([
            'message' => 'Résultat obtenu supprimé avec succès'
        ]);
    }
}
