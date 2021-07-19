<?php

namespace App\Controller;

use App\Entity\ReviewsPills;
use App\Repository\ReviewsPillsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
* @Route("/reviews/pills", name="reviews_pills_")
*/
class ReviewsPillsController extends AbstractController
{
    /**
     * Method displaying the list of all reviews
     * @Route("", name="list")
     */
    public function index(ReviewsPillsRepository $reviewsPillsRepository): Response
    {
        $reviews = $reviewsPillsRepository->findAll();
        //dd($reviews);
        return $this->json($reviews, 200, [], [
            "groups" => "reviews"
        ]);
    }


}
