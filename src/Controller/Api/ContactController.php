<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/contact", name="contact_")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/form", name="form_data", methods={"POST"})
     */
    public function register(Request $request, MailerInterface $mailer): Response
    {
        // We get the content of the request
        $JsonData = $request->getContent();
        
        // transforming the JSON into a php OBJECT
        $dataDecoded = json_decode($JsonData);
        
        // extracting the datas we need
        $userName = $dataDecoded->name;
        $userEmail= $dataDecoded->email;
        $messageObject = $dataDecoded->object;
        $message = $dataDecoded->message;
        
        //dd($JsonData, $dataDecoded, $userEmail, $messageObject, $message);

        $errors = [];

        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Cette adresse email n'est pas valide.";
        }

        if (empty($userName)) {
            $errors['name'] = "Merci de bien vouloir renseigner un prénom.";
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
            ) ; 

        } else {
            // Sending the email
            $email = (new Email())
            ->from($userEmail)
            ->to('maestra@chrisdev.fr')
            ->subject($messageObject . ' de ' . $userName)
            ->text($message);
        
            $mailer->send($email);
        
            return $this->json('Le message a bien été envoyé', 201);
        }
    }
}