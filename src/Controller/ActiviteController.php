<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Objectif;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/activites')]
class ActiviteController extends AbstractController
{
    // Lister toutes les activités
    #[Route('', name:'activite_list', methods:['GET'])]
    public function list(EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $activites = $em->getRepository(Activite::class)->findAll();
        $data = $serializer->serialize($activites, 'json', ['groups' => ['activite']]);
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    // Créer une activité
    #[Route('', name:'activite_create', methods:['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        $objectif = $em->getRepository(Objectif::class)->find($data['objectif_id'] ?? null);
        if (!$objectif) {
            return new Response('Objectif non trouvé', 404);
        }

        $activite = new Activite();
        $activite->setNomActivite($data['nom_activite']);
        $activite->setDescritpion($data['descritpion']);
        $activite->setObjectif($objectif);

        $em->persist($activite);
        $em->flush();

        return new Response($serializer->serialize($activite, 'json', ['groups' => ['activite']]), 201, ['Content-Type' => 'application/json']);
    }

    // Mettre à jour une activité
    #[Route('/{id}', name:'activite_update', methods:['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $activite = $em->getRepository(Activite::class)->find($id);
        if (!$activite) {
            return new Response('Activité non trouvée', 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nom_activite'])) {
            $activite->setNomActivite($data['nom_activite']);
        }
        if (isset($data['descritpion'])) {
            $activite->setDescritpion($data['descritpion']);
        }
        if (isset($data['objectif_id'])) {
            $objectif = $em->getRepository(Objectif::class)->find($data['objectif_id']);
            if ($objectif) {
                $activite->setObjectif($objectif);
            }
        }

        $em->flush();

        return new Response($serializer->serialize($activite, 'json', ['groups' => ['activite']]), 200, ['Content-Type' => 'application/json']);
    }

    // Supprimer une activité
    #[Route('/{id}', name:'activite_delete', methods:['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $activite = $em->getRepository(Activite::class)->find($id);
        if (!$activite) {
            return new Response('Activité non trouvée', 404);
        }

        $em->remove($activite);
        $em->flush();

        return new Response(null, 204);
    }
}
