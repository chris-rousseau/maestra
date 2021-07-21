<?php

namespace App\Controller\Api;

use App\Entity\Users;
use App\Repository\ReviewsPillsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user", name="user_")
 */
class UsersController extends AbstractController
{
    /**
     * Method displaying the infos of a user according to its id
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Users $user): Response
    {
        //dd($user);
        return $this->json($user, 200, [], [
            "groups" => "users"
        ]);
    }

    /**
     * Method displaying the reviews of a user according to its id
     * @Route("/{id}/reviews", name="reviews", methods={"GET"})
     */
    public function reviews(Users $user, ReviewsPillsRepository $reviewsPillsRepository): Response
    {
        $reviewsUser = $reviewsPillsRepository->findBy([
            'user' => $user->getId()
        ]);

        //dd($reviewsUser);

        // Display of only the name of the pill to which is associated the review. 

        return $this->json($reviewsUser, 200, [], [
            "groups" => "user_reviews"
        ]);
    }

   
}
