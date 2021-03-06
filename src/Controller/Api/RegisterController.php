<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
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
     * Route for user registration
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

        // Create a token to validate the account by clicking on the link in the email
        $token = uniqid();
        $user->setToken($token);
        $user->setEnabled(false);

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
            $email = (new TemplatedEmail())
                ->from('no-reply@maestra.fr')
                ->to(new Address($user->getEmail()))
                ->subject('Merci pour votre inscription sur Maestra.fr ???')
                ->htmlTemplate('emails/signup.html.twig')
                ->context([
                    'firstname' => $user->getFirstname(),
                    'lastname' => $user->getLastname(),
                    'token' => $user->getToken(),
                ]);

            $mailer->send($email);

            // If there is no error, we add in BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->json('L\'utilisateur ' . $user->getFirstname() . ' ' .  $user->getLastname() . ' a bien ??t?? ajout?? !', 201);
        }
    }
}
