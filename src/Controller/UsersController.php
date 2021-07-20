<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api", name="users_")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $JsonData = $request->getContent();

        $user = $serializer->deserialize($JsonData, Users::class, 'json');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return $this->json(
                [
                    'error' => $errorsString
                ],
                500
            );
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->json('L\'utilisateur ' . $user->getFirstname() . ' ' .  $user->getLastname() . ' a bien été ajouté !', 201);
        }

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsersController.php',
        ]);
    }
}
