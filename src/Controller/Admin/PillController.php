<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PillController extends AbstractController
{
    /**
     * @Route("/admin/pill", name="admin_pill")
     */
    public function index(): Response
    {
        return $this->render('admin/pill/index.html.twig', [
            'controller_name' => 'PillController',
        ]);
    }
}
