<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/inscription', name: 'user_registration', methods: ['GET','POST'])]
    public function register(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);

            if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
                return $this->json(['message' => 'Tous les champs sont requis'], Response::HTTP_BAD_REQUEST);
            }

            $user = new User();
            $user->setUsername($data['username']);
            $user->setEmail($data['email']);
            $user->setDateCreation(new \DateTime());

            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);

            $this->em->persist($user);
            $this->em->flush();

            return $this->json([
                'message' => 'Inscription réussie',
                'user' => [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                ]
            ], Response::HTTP_CREATED);
        }

        // Pour GET, on peut retourner le formulaire Twig
        return $this->render('registration/register.html.twig');
    }
}
