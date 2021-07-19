<?php

namespace App\Controller;

use App\Entity\ReviewsPills;
use App\Repository\ReviewsPillsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
* @Route("/reviews/pills", name="reviews_pills_")
*/
class ReviewsPillsController extends AbstractController
{
    /**
     * Method displaying the list of all reviews
     * @Route("", name="list",  methods="GET")
     */
    public function index(ReviewsPillsRepository $reviewsPillsRepository): Response
    {
        $reviews = $reviewsPillsRepository->findAll();
        return $this->json($reviews, 200, [], [
            "groups" => "reviews"
        ]);
    }

    /**
     * Method displaying one review according to its id
     * @Route("/{id}", name="details", methods="GET")
     */
    public function details(ReviewsPills $review): Response
    {
        //dd($review);
        return $this->json($review, 200, [], [
            "groups" => "reviews"
        ]);
    }
    

}
