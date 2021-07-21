<?php

namespace App\Controller\Api;

use App\Entity\ReviewsPills;
use App\Entity\Users;
use App\Repository\ReviewsPillsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
* @Route("/api/user/account", name="user_")
*/
class UsersController extends AbstractController
{
    /**
     * Method displaying the infos of a user according to its id
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Users $user): Response
    {
        
        return $this->json($user, 200, [], [
            "groups" => "users"
        ]);
    }

    /**
    * Method updating partially (patch) or entirely (put) the user
    * @Route("/{id}", name="update", methods={"PUT|PATCH"}, requirements={"id"="\d+"})
    *
    * @return void
    */
    public function update(Users $user, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $jsonData = $request->getContent();
        $user = $serializer->deserialize($jsonData, Users::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        //dd($user);

        $errors = $validator->validate($user);

        if (count($errors) == 0) {
         
            $this->getDoctrine()->getManager()->flush();
            return $this->json([
                'message' => 'L\'utilisateur ' .  $user->getFirstname() . ' ' .  $user->getLastname() . ' a bien été mis à jour'
            ]);
        }

        // 400 : error code BAD request
        // to return if there is an error
        return $this->json([
            'errors' => (string) $errors
            //(string) $errors => transform an array to string, 
        ], 400);

    }

    /**
    * Method displaying the reviews of a user according to its id
    * @Route("/{id}/review", name="reviews", methods={"GET"}, requirements={"id"="\d+"})
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

    /**
     * Method enabling the user to delete one of its review according to the review id
     *
     * @Route("/review/{id}", name="review_delete", methods={"DELETE"})
     * 
     * @return Response
     */
    public function reviewsDelete(Users $user, ReviewsPillsRepository $reviewsPillsRepository, ReviewsPills $reviewsPills)
    {  
        // getting the reviews of one user thanks to its id
        $reviewsUser = $reviewsPillsRepository->findBy([
            'user' => $user->getId()
        ]);

        // getting the id of review of said user to delete

        dd($reviewsUser, $reviewsPills);
    }

    /**
     * Method enabling the user to delete its account
     *
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * 
     * @return Response
     */
    public function delete(Users $user)
    {  
       
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
       
        return $this->json('La suppression du compte a bien été prise en compte', 200);
    }

   
}
