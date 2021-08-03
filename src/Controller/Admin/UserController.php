<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\PillRepository;
use App\Repository\ReviewPillRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/user", name="admin_user_")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin/{id}", name="admin", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function admin(User $user): Response
    {
        $user->setRoles(['ROLE_ADMIN']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash(
            'success',
            'L\'utilisateur à bien été upgradé en admin !'
        );

        return $this->redirectToRoute('admin_user_list');
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function delete(User $user, ReviewPillRepository $reviewPillRepository, PillRepository $pillRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        // We get the user's reviews
        $userReviews = $reviewPillRepository->findBy([
            "user" => $user->getId()
        ]);
        // For each review, we get the ID of the pill then we remove one to count_review
        foreach ($userReviews as $review) {
            $pillId = $review->getPill()->getId();
            $pill = $pillRepository->findOneBy([
                "id" => $pillId
            ]);
            $pill->setCountReviews($pill->getCountReviews() - 1);
            $em->persist($pill);
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'danger',
            'L\'utilisateur à bien été supprimé !'
        );

        return $this->redirectToRoute('admin_user_list');
    }

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function list(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $users = $userRepository->findAll();

        $allUsers = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/user/list.html.twig', [
            'allUsers' => $allUsers,
        ]);
    }

    /**
     * @Route("/moderator/{id}", name="moderator", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function moderator(User $user): Response
    {
        $user->setRoles(['ROLE_MODERATOR']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash(
            'success',
            'L\'utilisateur à bien été upgradé en modérateur !'
        );

        return $this->redirectToRoute('admin_user_list');
    }

    /**
     * @Route("/search", name="search", methods={"GET"})
     */
    public function search(Request $request, UserRepository $userRepository): Response
    {
        $searchValue = $request->get('q');

        $users = $userRepository->findSearchByEmail($searchValue);
        return $this->render('admin/user/search.html.twig', [
            'searchValue' => $searchValue,
            'users' => $users,
        ]);
    }

    /**
     * @Route("/unmoderator/{id}", name="unmoderator", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function unmoderator(User $user): Response
    {
        $user->setRoles([]);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash(
            'success',
            'L\'utilisateur à bien été rétrogradé en utilisateur classique !'
        );

        return $this->redirectToRoute('admin_user_list');
    }
}
