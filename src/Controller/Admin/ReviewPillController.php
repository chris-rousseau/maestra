<?php

namespace App\Controller\Admin;

use App\Entity\ReviewPill;
use App\Repository\PillRepository;
use App\Repository\ReviewPillRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

/**
 * @Route("/admin/pill/review", name="admin_pill_review_")
 * @IsGranted("ROLE_MODERATOR")
 */
class ReviewPillController extends AbstractController
{
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

    /**
     * Method displaying one review according to its id
     * @Route("/{id}", name="details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function details(int $id, ReviewPillRepository $reviewPillRepository): Response
    {
        $review = $reviewPillRepository->findWithDetails($id);

        return $this->render('admin/review_pill/details.html.twig', [
            'review' => $review,

        ]);
    }

    /**
     * Method displaying all the reviews
     * @Route("/list", name="list", methods={"GET"})
     */
    public function index(ReviewPillRepository $reviewPillRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $reviews = $reviewPillRepository->findAllOrderedByStatus();

        $data = $paginator->paginate(
            $reviews,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/review_pill/index.html.twig', [
            'data' => $data

        ]);
    }

    /**
     * Method displaying all the newest reviews that has to be validated (meaning their status is 0)
     * @Route("/pending/list", name="pending", methods={"GET"})
     */
    public function indexValidation(ReviewPillRepository $reviewPillRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $reviews = $reviewPillRepository->findByStatus(0);

        $data = $paginator->paginate(
            $reviews,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/review_pill/index.moderation.html.twig', [
            'data' => $data
        ]);
    }

    /**
     * Method to validate one review (will go from status : 0 to status:1)
     * @Route("/{id}/validate/", name="validate", methods="GET", requirements={"id"="\d+"})
     */
    public function validateReview(ReviewPill $review, PillRepository $pill, MailerInterface $mailer): Response
    {
        // change the status to 1 when clicked on validated,
        $reviewValidate = $review->setStatus(1);

        // get the current score given in the Review entity

        $reviewAcne = $review->getAcne();
        $reviewLibido = $review->getLibido();
        $reviewMigraine = $review->getMigraine();
        $reviewWeight = $review->getWeight();
        $reviewBreastPain = $review->getBreastPain();
        $reviewNausea = $review->getNausea();
        $reviewPms = $review->getPms();

        // get the current score linked to the Pill entity stored in the DB
        $pill = $review->getPill();

        $scoreAcne = $pill->getScoreAcne();
        $scoreLibido = $pill->getScoreLibido();
        $scoreMigraine = $pill->getScoreMigraine();
        $scoreWeight = $pill->getScoreWeight();
        $scoreBreastPain = $pill->getScoreBreastPain();
        $scoreNausea = $pill->getScoreNausea();
        $scorePms = $pill->getScorePms();

        // change the score (by adding the current score of the pill to the score given in the review) in the pill entity
        $pillScoreAcne = $pill->setScoreAcne($reviewAcne + $scoreAcne);
        $pillScoreLibido = $pill->setScoreLibido($reviewLibido + $scoreLibido);
        $pillScoreMigraine = $pill->setScoreMigraine($reviewMigraine + $scoreMigraine);
        $pillScoreWeight = $pill->setScoreWeight($reviewWeight + $scoreWeight);
        $pillScoreBreastPain = $pill->setScoreBreastPain($reviewBreastPain + $scoreBreastPain);
        $pillScoreNausea = $pill->setScoreNausea($reviewNausea + $scoreNausea);
        $pillScorePms = $pill->setScorePms($reviewPms + $scorePms);

        // Add +1 to the reviews counter
        $pillCountReviews = $pill->getCountReviews();
        $pill->setCountReviews($pillCountReviews + 1);

        $user = $review->getUser();

        $email = (new TemplatedEmail())
            ->from('no-reply@maestra.fr')
            ->to(new Address($user->getEmail()))
            ->subject('Avis validé sur Mestra.fr ♥')
            ->htmlTemplate('emails/review_validation.html.twig')
            ->context([
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'review' => $review->getTitle(),
                'pill' => $pill->getName()
            ]);

        $mailer->send($email);

        $em = $this->getDoctrine()->getManager();
        $em->persist($reviewValidate);
        $em->persist($pillScoreAcne);
        $em->persist($pillScoreLibido);
        $em->persist($pillScoreMigraine);
        $em->persist($pillScoreWeight);
        $em->persist($pillScoreBreastPain);
        $em->persist($pillScoreNausea);
        $em->persist($pillScorePms);
        $em->flush();

        $this->addFlash(
            'success',
            'L\'avis à bien été validé !'
        );

        return $this->redirectToRoute('admin_pill_review_list');
    }
}
