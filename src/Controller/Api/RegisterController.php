<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function register(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        // We get the content of the request
        $JsonData = $request->getContent();

        // We transform the json into Users object
        $user = $serializer->deserialize($JsonData, User::class, 'json');

        $errors = $validator->validate($user);

        // encode the plain password
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            )
        );

        // Selects a random picture in the table
        $pictureArray = ['no-avatar-1.png', 'no-avatar-2.png', 'no-avatar-3.png', 'no-avatar-4.png', 'no-avatar-5.png'];
        $user->setPicture($pictureArray[array_rand($pictureArray, 1)]);

        // If there is at least one error, we return a 400
        if (count($errors) > 0) {
            $errorsList = [];
            foreach ($errors as $erreur) {
                $input = $erreur->getPropertyPath();
                $errorsList[$input] = $erreur->getMessage();
            }

            return $this->json(
                [
                    'error' => $errorsList
                ],
                400
            );
        } else {
            // Sending of a confirmation email for the creation of the account
            $email = (new Email())
                ->from('no-reply@maestra.fr')
                ->to('maestra@chrisdev.fr')
                ->subject('Merci pour votre inscription sur Mestra.fr ♥')
                ->text('Bonjour ' . $user->getFirstname() . ', merci beaucoup pour ton inscription !');

            $mailer->send($email);

            // If there is no error, we add in BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->json('L\'utilisateur ' . $user->getFirstname() . ' ' .  $user->getLastname() . ' a bien été ajouté !', 201);
        }
    }
}
