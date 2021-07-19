<?php

namespace App\Controller\Api;

use App\Entity\Pills;
use App\Repository\PillsRepository;
use App\Repository\ReviewsPillsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/pill", name="api_pill_")
 */
class PillsController extends AbstractController
{
    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function list(PillsRepository $pillsRepository): Response
    {
        $allPills = $pillsRepository->findAll();

        return $this->json($allPills, 200, [], [
            "groups" => "pills"
        ]);
    }

    /**
     * @Route("/{id}", name="details", methods={"GET"})
     */
    public function details(Pills $pills): Response
    {
        return $this->json($pills, 200, [], [
            "groups" => "pills"
        ]);
    }

    /**
     * @Route("/{id}/review", name="reviews", methods={"GET"})
     */
    public function reviews(Pills $pills, ReviewsPillsRepository $reviewsPillsRepository): Response
    {
        // dd($pills->getId());
        $reviewsPill = $reviewsPillsRepository->findBy([
            'pill' => $pills->getId()
        ]);
        // dd($reviewsPill);
        return $this->json($reviewsPill, 200, [], [
            "groups" => "pill_reviews"
        ]);
    }
}
