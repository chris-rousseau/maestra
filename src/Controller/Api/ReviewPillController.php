<?php

namespace App\Controller\Api;

use App\Entity\ReviewPill;
use App\Entity\User;
use App\Repository\ReviewPillRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("api/pill/review", name="reviews_pills_")
 */
class ReviewPillController extends AbstractController
{
    /**
     * Method adding a new review associated to a pill
     * @Route("/{id}/add", name="add", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, User $user): Response
    {
        $JsonData = $request->getContent();
        // transforming json into an object of ReviewPill
        $review = $serializer->deserialize($JsonData, ReviewPill::class, 'json');

        // Verifying that all validation criterias of entity ReviewPill are okay (Assert\NotBlank, ...)
        // will display an array of violations ("this value should not be blank")
        $errors = $validator->validate($review);

        // Calculation of the user's age according to the current date
        $birthdate = new DateTime($user->getBirthdate());
        $today = new DateTime();

        $age = $birthdate->diff($today);
        $ageInYear = $age->format('%Y');
        $review->setUserAge(intval($ageInYear));

        if (count($errors) > 0) {
            // If there is at least one error
            $errorsList = [];
            foreach ($errors as $erreur) {
                $input = $erreur->getPropertyPath();
                $errorsList[$input] = $erreur->getMessage();
            }

            return $this->json(
                [
                    'error' => $errorsList
                ],
                400
            );
        } else {
            // if there is no error, we can save the new review in the DB (using manager)
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            // Returning a clear response to the client
            return $this->json(
                [
                    'message' => 'L\'avis ' . $review->getTitle() .  ' a bien été créé'
                ],
                201 // 201 - Created https://developer.mozilla.org/fr/docs/Web/HTTP/Status/201
            );
        }
    }

    /**
     * Method removing a review in the DB
     *
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * 
     * @return Response
     */
    public function delete(ReviewPill $review)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();

        // Code 204 : https://developer.mozilla.org/fr/docs/Web/HTTP/Status/204
        return $this->json('Suppression de l\'avis '  . $review->getTitle(), 200);
    }

    /**
     * Method displaying one review according to its id
     * @Route("/{id}", name="details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function details(ReviewPill $review): Response
    {
        return $this->json($review, 200, [], [
            "groups" => "reviews_details"
        ]);
    }

    /**
     * Displays the reviews of the homepage
     * @Route("/home", name="homepage", methods={"GET"})
     */
    public function homepageReviews(ReviewPillRepository $reviewPillRepository): Response
    {
        $allReviews = $reviewPillRepository->findBy([
            "status" => 1
        ], [
            "created_at" => "DESC"
        ], 5);

        return $this->json($allReviews, 200, [], [
            "groups" => "reviews_list"
        ]);
    }

    /**
     * Method displaying the list of all reviews
     * @Route("/", name="list", methods={"GET"}, priority=10)
     */
    public function index(ReviewPillRepository $reviewPillRepository): Response
    {
        $reviews = $reviewPillRepository->findAll();
        return $this->json($reviews, 200, [], [
            "groups" => "reviews_list"
        ]);
    }

    /**
     * Method updating partially (patch) or entirely (put) the review
     * @Route("/{id}", name="update", methods={"PUT","PATCH"}, requirements={"id"="\d+"})
     *
     * @return void
     */
    public function update(ReviewPill $review, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $jsonData = $request->getContent();
        $review = $serializer->deserialize($jsonData, ReviewPill::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $review]);

        $errors = $validator->validate($review);

        if (count($errors) == 0) {

            $this->getDoctrine()->getManager()->flush();

            return $this->json([
                'message' => 'L\'avis ' . $review->getTitle() .  'a bien été mise à jour'
            ]);
        }

        // 400 : code d'erreur BAD request
        // à retourner si le client a mal formatée sa requete
        return $this->json([
            'errors' => (string) $errors
            //(string) $errors => convertir un tableau d'objet en string, 
        ], 400);
    }
}
