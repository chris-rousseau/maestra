<?php

namespace App\Controller\Api;

use App\Entity\ReviewPill;
use App\Entity\User;
use App\Repository\ReviewPillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/user/account", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * Method displaying the infos of a user according to its id
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(User $user): Response
    {
        return $this->json($user, 200, [], [
            "groups" => "users"
        ]);
    }

    /**
     * Method updating partially (patch) the user
     * @Route("/{id}/edit", name="update", methods={"PATCH"}, requirements={"id"="\d+"})
     *
     * @return void
     */
    public function update(User $user, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher)
    {
        $jsonData = $request->getContent();
        $user = $serializer->deserialize($jsonData, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $errors = $validator->validate($user);

        if (count($errors) == 0) {
            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->getDoctrine()->getManager()->flush();
            return $this->json([
                'message' => 'L\'utilisateur ' .  $user->getFirstname() . ' ' .  $user->getLastname() . ' a bien été mis à jour'
            ]);
        }

        $errorsList = [];
        foreach ($errors as $erreur) {
            $input = $erreur->getPropertyPath();
            $errorsList[$input] = $erreur->getMessage();
        }

        // 400 : error code BAD request
        // to return if there is an error
        return $this->json([
            'errors' => $errorsList
            //(string) $errors => transform an array to string, 
        ], 400);
    }

    /**
     * Method changes the user's password
     * @Route("/{id}/password-edit", name="password_edit", methods={"PATCH"}, requirements={"id"="\d+"})
     *
     * @return void
     */
    public function passwordEdit(User $user, Request $request, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer)
    {
        $jsonData = $request->getContent();
        $passwordObj = json_decode($jsonData);

        // We check if the password contains the minimum required
        if (!preg_match('@^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$@', $passwordObj->newPassword)) {
            return $this->json([
                'message' => 'Votre mot de passe doit comporter au moins huit caractères, dont au moins une majuscule et minuscule, un chiffre et un symbole.'
            ], 400);
        } else {
            // We check if the password entered by the user is the same as the one in the database
            if (password_verify($passwordObj->oldPassword, $user->getPassword())) {
                $user->setPassword($passwordHasher->hashPassword(
                    $user,
                    $passwordObj->newPassword
                ));

                $email = (new Email())
                    ->from('no-reply@maestra.fr')
                    ->to($user->getEmail())
                    ->subject('Modification du mot de passe - Maestra')
                    ->text('Bonjour ' . $user->getFirstname() . ', votre mot de passe à bien été modifié !');

                $mailer->send($email);

                // If all is good, we hash and modify the user's password, and we send him an email to warn him
                $user->setUpdatedAt(new \DateTimeImmutable());
                $this->getDoctrine()->getManager()->flush();
                return $this->json([
                    'message' => 'Le mot de passe a bien été mis à jour.'
                ]);
            } else {
                return $this->json([
                    'message' => 'Le mot de passe actuel est incorrect.'
                ], 400);
            }
        }
    }

    /**
     * Method displaying the reviews of a user according to its id
     * @Route("/{id}/review", name="reviews", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function reviews(User $user, ReviewPillRepository $reviewPillRepository): Response
    {
        $reviewsUser = $reviewPillRepository->findBy([
            'user' => $user->getId()
        ]);

        //dd($reviewsUser);
        // Display of only the name of the pill to which is associated the review. 

        return $this->json($reviewsUser, 200, [], [
            "groups" => "user_reviews"
        ]);
    }

    /**
     * Method enabling the user to delete one of its review according to the review id
     *
     * @Route("/review/{id}", name="review_delete", methods={"DELETE"})
     * 
     * @return Response
     */
    public function reviewsDelete(ReviewPill $reviewPill)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($reviewPill);
        $em->flush();

        return $this->json('La suppression de l\'avis ' . $reviewPill->getTitle() . ' a bien été prise en compte', 200);

        // getting the id of review of said user to delete

    }

    /**
     * Method enabling the user to delete its account
     *
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * 
     * @return Response
     */
    public function delete(User $user)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->json('La suppression du compte a bien été prise en compte', 200);
    }
}
