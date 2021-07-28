<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * Method for the user to receive an email to reset their password
     *
     * @Route("/mot-de-passe-oublie", name="password_lost", methods={"GET", "POST"})
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
                $token = uniqid();
                $user->setToken($token);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                // Sending an email with the link to reset your password
                $email = (new Email())
                    ->from('no-reply@maestra.fr')
                    ->to('maestra@chrisdev.fr')
                    ->subject('Réinitialisation de votre mot de passe Maestra')
                    ->text('Bonjour ' . $user->getFirstname() . ', voici le lien pour réinitialiser votre mot de passe : http://localhost:8080/reinitialiser-mot-de-passe/' . $user->getToken() . ' En espérant que ça fonctionne !');

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
     * @Route("/reinitialiser-mot-de-passe/{token}", name="password_reset", methods={"GET", "POST"}, requirements={"token"="\w+"})
     * 
     * @return Response
     */
    public function resetPassword(Request $request, string $token, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer)
    {
        if ($request->isMethod('POST')) {
            if ($request->request->get('password') !== $request->request->get('passwordConfirm')) {
                $this->addFlash(
                    'danger',
                    'La confirmation n\'est pas identique au mot de passe'
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
                    $password = $request->request->get('password');

                    $userByToken['0']->setPassword(
                        $passwordHasher->hashPassword(
                            $userByToken['0'],
                            $password
                        )
                    );

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($userByToken['0']);
                    $em->flush();

                    $email = (new Email())
                        ->from('no-reply@maestra.fr')
                        ->to('maestra@chrisdev.fr')
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
