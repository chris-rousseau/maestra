<?php

namespace App\Controller\Api;

use App\Entity\ReviewPill;
use App\Entity\User;
use App\Repository\ReviewPillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * Method updating partially (patch) or entirely (put) the user
     * @Route("/{id}/edit", name="update", methods={"PUT|PATCH"}, requirements={"id"="\d+"})
     *
     * @return void
     */
    public function update(User $user, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher)
    {
        $jsonData = $request->getContent();
        $user = $serializer->deserialize($jsonData, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        //dd($user);

        $errors = $validator->validate($user);

        if (count($errors) == 0) {

            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            );
            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->getDoctrine()->getManager()->flush();
            return $this->json([
                'message' => 'L\'utilisateur ' .  $user->getFirstname() . ' ' .  $user->getLastname() . ' a bien été mis à jour'
            ]);
        }

        // 400 : error code BAD request
        // to return if there is an error
        return $this->json([
            'errors' => (string) $errors
            //(string) $errors => transform an array to string, 
        ], 400);
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
