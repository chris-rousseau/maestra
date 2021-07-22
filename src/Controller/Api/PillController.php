<?php

namespace App\Controller\Api;

use App\Entity\Pill;
use App\Repository\PillRepository;
use App\Repository\ReviewPillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/pill", name="api_pill_")
 */
class PillController extends AbstractController
{
    /**
     * Displays all pills
     * @Route("", name="list", methods={"GET"})
     */
    public function list(PillRepository $pillRepository): Response
    {
        $allPills = $pillRepository->findAll();

        return $this->json($allPills, 200, [], [
            "groups" => "pills"
        ]);
    }

    /**
     * Displays the details of a pill
     * @Route("/{id}", name="details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function details(Pill $pill): Response
    {
        return $this->json($pill, 200, [], [
            "groups" => "pills"
        ]);
    }

    /**
     * Displays all the reviews of a pill
     * @Route("/{id}/review", name="reviews", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function reviews(Pill $pill, ReviewPillRepository $reviewPillRepository): Response
    {
        // dd($pills->getId());
        $reviewsPill = $reviewPillRepository->findBy([
            'pill' => $pill->getId()
        ]);
        // dd($reviewsPill);
        return $this->json($reviewsPill, 200, [], [
            "groups" => "pill_reviews"
        ]);
    }
}
