<?php

namespace App\Controller\Api;

use App\Entity\Staff;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api", name="users_")
 */
class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request, SerializerInterface $serializer, UsersRepository $usersRepository): Response
    {
        // We get the content of the request
        $JsonData = $request->getContent();

        // We transform the json into Users object
        $userLogin = $serializer->deserialize($JsonData, Users::class, 'json');

        $allUsers = $usersRepository->findAll();

        foreach ($allUsers as $user) {
            if ($userLogin->getEmail() === $user->getEmail()) {
                if ($userLogin->getPassword() === $user->getPassword()) {
                    return $this->json($user, 200, [], [
                        "groups" => "login"
                    ]);
                }
            }
        }
        return $this->json("L'adresse mail ou le mot de passe est incorrect", 401);
    }
}
