<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    private UserRepository $repository;
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $repository, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    // LISTE TOUS LES USERS
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->repository->findAll();
        $data = array_map(fn(User $u) => [
            'id' => $u->getId(),
            'username' => $u->getUsername(),
            'email' => $u->getEmail(),
            'dateCreation' => $u->getDateCreation()?->format('Y-m-d H:i:s'),
            'employe_id' => $u->getEmploye()?->getId(),
        ], $users);

        return $this->json($data);
    }

    // AFFICHER UN USER
    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $data = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'dateCreation' => $user->getDateCreation()?->format('Y-m-d H:i:s'),
            'employe_id' => $user->getEmploye()?->getId(),
        ];
        return $this->json($data);
    }

    // CREER UN USER
    #[Route('/create', name: 'user_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            return $this->json(['message' => 'Username, email et password sont requis'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setDateCreation(new \DateTime());

        // Hachage du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        if (!empty($data['employe'])) {
            $user->setEmploye($this->em->getReference('App\Entity\Employe', $data['employe']));
        }

        $this->em->persist($user);
        $this->em->flush();

        return $this->json([
            'message' => 'User créé avec succès',
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'dateCreation' => $user->getDateCreation()->format('Y-m-d H:i:s'),
                'employe_id' => $user->getEmploye()?->getId(),
            ]
        ], Response::HTTP_CREATED);
    }

    // MODIFIER UN USER
    #[Route('/{id}/edit', name: 'user_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, User $user): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!empty($data['username'])) $user->setUsername($data['username']);
        if (!empty($data['email'])) $user->setEmail($data['email']);

        if (!empty($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        if (!empty($data['employe'])) {
            $user->setEmploye($this->em->getReference('App\Entity\Employe', $data['employe']));
        }

        $this->em->flush();

        return $this->json([
            'message' => 'User modifié avec succès',
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'dateCreation' => $user->getDateCreation()?->format('Y-m-d H:i:s'),
                'employe_id' => $user->getEmploye()?->getId(),
            ]
        ]);
    }

    // SUPPRIMER UN USER
    #[Route('/{id}/delete', name: 'user_delete', methods: ['DELETE'])]
    public function delete(User $user): Response
    {
        $this->em->remove($user);
        $this->em->flush();

        return $this->json(['message' => 'User supprimé avec succès']);
    }
}
