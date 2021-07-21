<?php

namespace App\Controller\Api;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/user", name="user_")
 */
class UsersController extends AbstractController
{
    /**
     * Method displaying the infos of a user according to its id
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Users $user): Response
    {
        //dd($user);
        return $this->json($user, 200, [], [
            "groups" => "users"
        ]);
    }

   
}
