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
     * @Route("", name="home", methods={"GET"})
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

    /**
     * @Route("/delete-image", name="delete_image", methods={"GET"})
     */
    public function removeImage(PillRepository $pillRepository): Response
    {
        // We get all the pictures of the pills in an array
        $allPills = $pillRepository->findAll();

        $picturesPillArray = [];
        foreach ($allPills as $image) {
            $picturesPillArray[] = $image->getPicture();
        }

        // We get all the uploaded pictures in an array
        $directory = $this->getParameter('pills_directory');
        $picturesUploadedArray = glob($directory . "/*");

        // If the image $uploadedPicture is not in $picturesPillArray then we delete it
        foreach ($picturesUploadedArray as $uploadedPicture) {
            if (!in_array(basename($uploadedPicture), $picturesPillArray)) {
                unlink($uploadedPicture);
            }
        }

        $this->addFlash(
            'success',
            'Les images des pilules non utilisées ont bien été supprimées !'
        );

        return $this->redirectToRoute('admin_home');
    }
}
