<?php

namespace App\Controller\Admin;

use App\Entity\ReviewPill;
use App\Repository\ReviewPillRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/admin/pill/review", name="admin_pill_review_")
*/
class ReviewPillController extends AbstractController
{
    /**
     * Method displaying all the reviews
     * @Route("/list", name="list")
     */
    public function index(ReviewPillRepository $reviewPillRepository): Response
    {
        $reviews = $reviewPillRepository->findAllOrderedByStatus();

        return $this->render('admin/review_pill/index.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    /**
     * Method displaying all the newest reviews that has to be validated (meaning their status is 0)
     * @Route("/pending/list", name="pending")
     */
    public function indexValidation(ReviewPillRepository $reviewPillRepository): Response
    {
        $reviews = $reviewPillRepository->findByStatus(0);

        return $this->render('admin/review_pill/index.moderation.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    /**
     * Method displaying one review according to its id
     * @Route("/{id}", name="details", methods="GET", requirements={"id"="\d+"})
     */
    public function details(int $id, ReviewPillRepository $reviewPillRepository): Response
    {
       $review = $reviewPillRepository->findWithDetails($id);
            
        return $this->render('admin/review_pill/details.html.twig', [
            'review' => $review,
            
        ]);
    }

    /**
     * Method displaying one review according to its id
     * @Route("/{id}/validate", name="validate", methods="GET", requirements={"id"="\d+"})
     */
    public function validateReview(ReviewPill $review): Response
    {
        // change the status to 1 when clicked on validated,

        $reviewValidate = $review->setStatus(1);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($reviewValidate);
        $em->flush();

        $this->addFlash(
            'success',
            'L\'avis à bien été validé !'
        );

        return $this->redirectToRoute('admin_pill_review_list');
    }

    /**
     * Method removing a review in the DB
     *
     * @Route("/{id}/delete", name="delete", methods={"GET"}, requirements={"id"="\d+"})
     * 
     * @return Response
     */
    public function delete(ReviewPill $review)
    {  
        $em = $this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();

        $this->addFlash(
            'success',
            'L\'avis à bien été supprimé !'
        );

        return $this->redirectToRoute('admin_pill_review_list');
    }

}
