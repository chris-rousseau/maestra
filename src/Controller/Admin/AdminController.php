<?php

namespace App\Controller\Admin;

use App\Repository\PillRepository;
use App\Repository\ReviewPillRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function home(UserRepository $userRepository, ReviewPillRepository $reviewPillRepository, PillRepository $pillRepository): Response
    {
        $countUsers = count($userRepository->findAll());
        $reviewsWating = count($reviewPillRepository->findBy([
            "status" => 0
        ]));
        $countPills = count($pillRepository->findAll());

        return $this->render('admin/dashboard/home.html.twig', [
            'countUsers' => $countUsers,
            'reviewsWating' => $reviewsWating,
            'countPills' => $countPills,
        ]);
    }
}
