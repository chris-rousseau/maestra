<?php

namespace App\Controller\Api;

use App\Entity\Pill;
use App\Repository\PillRepository;
use App\Repository\ReviewPillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/pill", name="api_pill_")
 */
class PillController extends AbstractController
{
    /**
     * Displays the details of a pill
     * @Route("/{id}", name="details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function details(Pill $pill): Response
    {
        return $this->json($pill, 200, [], [
            "groups" => "pills_details"
        ]);
    }

    /**
     * Displays the pills of the homepage
     * @Route("/home", name="homepagePills", methods={"GET"})
     */
    public function homepagePills(PillRepository $pillRepository): Response
    {
        $allPills = $pillRepository->findBy([], [
            "count_reviews" => "DESC"
        ], 5);

        return $this->json($allPills, 200, [], [
            "groups" => "pills"
        ]);
    }

    /**
     * Displays all pills
     * @Route("", name="list", methods={"GET"})
     */
    public function list(PillRepository $pillRepository): Response
    {
        $allPills = $pillRepository->findby([], [
            "name" => "ASC"
        ]);

        return $this->json($allPills, 200, [], [
            "groups" => "pills"
        ]);
    }

    /**
     * Displays all the reviews of a pill
     * @Route("/{id}/review", name="reviews", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function reviews(Pill $pill, ReviewPillRepository $reviewPillRepository): Response
    {
        $reviewsPill = $reviewPillRepository->findBy([
            'pill' => $pill->getId(),
            'status' => 1
        ]);

        return $this->json($reviewsPill, 200, [], [
            "groups" => "pill_reviews"
        ]);
    }

    /**
     * Method enabling the search of a particular pill 
     * @Route("/search", name="search", methods={"GET"})
     *
     * @return void
     */
    public function search(Request $request, PillRepository $pillRepository)
    {
        $searchValue = $request->get('query');
        $pillSearch = $pillRepository->findSearchByName($searchValue);

        return $this->json($pillSearch, 200, [], [
            "groups" => "pill_search"
        ]);
    }

    /**
     * Method enabling the search of a particular pill according to what's been selected
     * @Route("/search/sort", name="search_sorting", methods={"POST"})
     *
     * @return void
     */
    public function searchSorted(Request $request, PillRepository $pillRepository)
    {
        $searchValue = $request->getContent(); //1 ou 2 
        $decodeur = json_decode($searchValue);

        $interruption = $decodeur->interruption;
        $type = $decodeur->type;
        $generation = $decodeur->generation;
        $undesirable = $decodeur->undesirable;

        $pillSearch = $pillRepository->findSearchSortedBy($interruption, $type, $generation, $undesirable);

        return $this->json($pillSearch, 200, [], [
            "groups" => "pill_search"
        ]);
    }
}
