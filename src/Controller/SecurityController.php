<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * Method for the user to confirm their email address
     * @Route("/confirm-email/{token}", name="confirm_email", methods={"GET","POST"}, requirements={"token"="\w+"})
     */
    public function confirmEmail(string $token, UserRepository $userRepository): Response
    {
        $user = $userRepository->findSearchByToken($token);
        if ($user == []) {
            return $this->json('Un problème est survenu, êtes-vous sur que le lien est correct ?', 498); // 498 : Token expired/invalid
        } else {
            $user[0]->setEnabled(true);
            $user[0]->setToken(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user[0]);
            $em->flush();

            return $this->redirect('https://maestra.surge.sh/connexion');
        }
    }

    /**
     * Method for the user to receive an email to reset their password
     *
     * @Route("/mot-de-passe-oublie", name="password_lost", methods={"GET","POST"})
     * 
     * @return Response
     */
    public function passwordLost(Request $request, UserRepository $userRepository, MailerInterface $mailer)
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneByEmail($email);
            if ($user === null) {
                $this->addFlash(
                    'danger',
                    'Cette adresse email n\'existe pas.'
                );

                return $this->render('api/security/index.html.twig');
            } else {
                // Creation of the token
                $token = uniqid();
                $user->setToken($token);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                // Sending an email with the link to reset your password
                $email = (new TemplatedEmail())
                    ->from('no-reply@maestra.fr')
                    ->to(new Address($user->getEmail()))
                    ->subject('Réinitialisation de votre mot de passe Maestra')
                    ->htmlTemplate('emails/password_reset.html.twig')
                    ->context([
                        'firstname' => $user->getFirstname(),
                        'lastname' => $user->getLastname(),
                        'token' => $user->getToken(),
                    ]);
                $mailer->send($email);

                $this->addFlash(
                    'success',
                    'Un email avec un lien pour changer votre mot de passe vient d\'être envoyé !'
                );

                return $this->render('api/security/index.html.twig');
            }
        }

        return $this->render('api/security/index.html.twig');
    }

    /**
     * Method for the user to reset the password
     *
     * @Route("/reinitialiser-mot-de-passe/{token}", name="password_reset", methods={"GET","POST"}, requirements={"token"="\w+"})
     * 
     * @return Response
     */
    public function resetPassword(Request $request, string $token, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer)
    {
        if ($request->isMethod('POST')) {
            // If the two passwords are not identical
            if ($request->request->get('password') !== $request->request->get('passwordConfirm')) {
                $this->addFlash(
                    'danger',
                    'La confirmation n\'est pas identique au mot de passe'
                );

                return $this->render('api/security/reset.html.twig');
                // Check if the password contains what is requested
            } elseif (preg_match('@^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$@', $request->request->get('password')) === 0) {
                $this->addFlash(
                    'danger',
                    'Votre mot de passe doit comporter au moins huit caractères, dont des lettres majuscules et minuscules, un chiffre et un symbole.'
                );

                return $this->render('api/security/reset.html.twig');
            } else {
                $userByToken = $userRepository->findSearchByToken($token);
                if ($userByToken == []) {
                    $this->addFlash(
                        'danger',
                        'Un problème est survenu, êtes-vous sur que le lien est correct ?'
                    );

                    return $this->render('api/security/reset.html.twig');
                } else {
                    // If the token is found in the database, we change the password
                    $password = $request->request->get('password');

                    $userByToken['0']->setPassword(
                        $passwordHasher->hashPassword(
                            $userByToken['0'],
                            $password
                        )
                    );

                    $userByToken['0']->setToken(null);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($userByToken['0']);
                    $em->flush();

                    // And we send a confirmation email
                    $email = (new Email())
                        ->from('no-reply@maestra.fr')
                        ->to($userByToken['0']->getEmail())
                        ->subject('Modification de votre mot de passe Maestra')
                        ->text('Bonjour ' . $userByToken['0']->getFirstname() . ', votre mot de passe à bien été modifié !');

                    $mailer->send($email);

                    $this->addFlash(
                        'success',
                        'Votre mot de passe à bien été modifié !'
                    );

                    return $this->render('api/security/reset.html.twig');
                }
            }
        }

        return $this->render('api/security/reset.html.twig', [
            'allUsers' => '',
        ]);
    }
}
