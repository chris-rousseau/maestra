<?php

namespace App\Controller\Api;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api", name="register")
 */
class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        // We get the content of the request
        $JsonData = $request->getContent();

        // We transform the json into Users object
        $user = $serializer->deserialize($JsonData, Users::class, 'json');

        $errors = $validator->validate($user);

        // If there is at least one error, we return a 500
        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return $this->json(
                [
                    'error' => $errorsString
                ],
                500
            );
        } else {
            // If there is no error, we add in BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->json('L\'utilisateur ' . $user->getFirstname() . ' ' .  $user->getLastname() . ' a bien été ajouté !', 201);
        }
    }
}
