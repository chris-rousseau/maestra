<?php

namespace App\Controller\Api;

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
 * @Route("api/contact", name="contact_")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/form", name="form_data", methods={"POST"})
     */
    public function register(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        // We get the content of the request
        $JsonData = $request->getContent();
        
        // transforming the JSON into a php OBJECT
        $dataDecoded = json_decode($JsonData);
        
        // extracting the datas we need
        $userEmail= $dataDecoded->email;
        $messageObject = $dataDecoded->object;
        $message = $dataDecoded->message;
        
        //dd($JsonData, $dataDecoded, $userEmail, $messageObject, $message);

        $errors = [];

        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Cette adresse email n'est pas valide.";
        }

        if (empty($messageObject)) {
            $errors['object'] = "Merci de bien vouloir renseigner un objet.";
        }

        if (empty($message)) {
            $errors['message'] = "Merci de bien vouloir renseigner un message.";
        }

                  
        if (count($errors) > 0) {
            return $this->json(
                $errors,
                500
            );

        } else {
            // Sending the email
            $email = (new Email())
            ->from($userEmail)
            ->to('lce.bouron@gmail.com')
            ->subject($messageObject)
            ->text($message);
        
            $mailer->send($email);
        
            // and a flashMessage so the user knows eveything went smoothly
            $this->addFlash(
                'success',
                'Votre message a bien été envoyé'
            );
        
            return $this->json('Le message a bien été envoyé', 201);
        }
    }
}