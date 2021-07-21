<?php

namespace App\Controller\Api;

use App\Entity\ReviewsPills;
use App\Repository\ReviewsPillsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\EntityNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
* @Route("api/pill/review", name="reviews_pills_")
*/
class ReviewsPillsController extends AbstractController
{
    /**
     * Method displaying the list of all reviews
     * @Route("", name="list", methods="GET")
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
     * @Route("/{id}", name="details", methods="GET", requirements={"id"="\d+"})
     */
    public function details(ReviewsPills $review): Response
    {
        //dd($review);
        return $this->json($review, 200, [], [
            "groups" => "reviews"
        ]);
    }

    /**
     * Method adding a new review associated to a pill
     * @Route("/add", name="add", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $JsonData = $request->getContent();
        // transforming json into an object of ReviewsPills
        $review = $serializer->deserialize($JsonData, ReviewsPills::class, 'json');
        //dd($review);

        // Verifying that all validation criterias of entity ReviewsPills are okay (Assert\NotBlank, ...)
        // will display an array of violations ("this value should not be blank")
        $errors = $validator->validate($review);
        
        if (count($errors) > 0) {
            // If there is at least one error
            $errorsString = (string) $errors;
            return $this->json(
                [
                    'error' => $errorsString
                ],
                500
            );
        } else {
            // if there is no error, we can save the new review in the DB (using manager)
        
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            // Returning a clear response to the client
            return $this->json(
                [
                    'message' => 'L\'avis a bien été créé'
                ],
                201 // 201 - Created https://developer.mozilla.org/fr/docs/Web/HTTP/Status/201
            );
        }
    }

    /**
     * Method updating partially (patch) or entirely (put) the review
     * @Route("/{id}", name="update", methods={"PUT|PATCH"}, requirements={"id"="\d+"})
     *
     * @return void
     */
    public function update(ReviewsPills $review, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $jsonData = $request->getContent();
        $review = $serializer->deserialize($jsonData, ReviewsPills::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $review]);
        //dd($review);

        $errors = $validator->validate($review);

        if (count($errors) == 0) {
         
            $this->getDoctrine()->getManager()->flush();

            return $this->json([
                'message' => 'La review a bien été mise à jour'
            ]);
        }

        // 400 : code d'erreur BAD request
        // à retourner si le client a mal formatée sa requete
        return $this->json([
            'errors' => (string) $errors
            //(string) $errors => convertir un tableau d'objet en string, 
        ], 400);

    }

    /**
     * Method removing a review in the DB
     *
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * 
     * @return Response
     */
    public function delete(ReviewsPills $review)
    {  
        $em = $this->getDoctrine()->getManager();
        $em->remove($review);
        $em->flush();

        // Code 204 : https://developer.mozilla.org/fr/docs/Web/HTTP/Status/204
        return $this->json('Suppression de l\'avis', 200);
    }
}
