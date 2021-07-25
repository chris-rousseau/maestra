<?php

namespace App\Controller\Admin;

use App\Entity\Pill;
use App\Form\PillType;
use App\Service\UploadImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/pill", name="admin_pill_")
 */
class PillController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function list(): Response
    {
        return $this->render('admin/pill/list.html.twig', [
            'controller_name' => 'PillController',
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, UploadImage $upload, SluggerInterface $slugger): Response
    {
        $pill = new Pill();

        $form = $this->createForm(PillType::class, $pill);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagePill = $upload->uploadImg($form, 'pillImg');

            $pill->setPicture($imagePill);

            $slug = $slugger->slug($pill->getName());

            $pill->setSlug(strtolower($slug));

            $em = $this->getDoctrine()->getManager();
            $em->persist($pill);
            $em->flush();

            $this->addFlash(
                'success',
                'La pilule à bien été ajoutée !'
            );

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/pill/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
