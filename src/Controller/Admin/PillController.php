<?php

namespace App\Controller\Admin;

use App\Entity\Pill;
use App\Form\PillType;
use App\Repository\PillRepository;
use App\Service\UploadImage;
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route("/add", name="add", methods={"GET","POST"})
     */
    public function add(Request $request, UploadImage $upload, SluggerInterface $slugger): Response
    {
        $pill = new Pill();

        $form = $this->createForm(PillType::class, $pill);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagePill = $upload->uploadImg($form, 'pillImg');

            if ($imagePill !== null) {
                $pill->setPicture($imagePill);
            }

            $slug = $slugger->slug($pill->getName());

            $pill->setSlug(strtolower($slug));

            $em = $this->getDoctrine()->getManager();
            $em->persist($pill);
            $em->flush();

            $this->addFlash(
                'success',
                'La pilule à bien été ajoutée !'
            );

            return $this->redirectToRoute('admin_pill_list');
        }

        return $this->render('admin/pill/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id"="\d+"})
     */
    public function delete(Pill $pill): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pill);
        $em->flush();

        $this->addFlash(
            'danger',
            'La pilule à bien été supprimée !'
        );

        return $this->redirectToRoute('admin_pill_list');
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Pill $pill, UploadImage $upload, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PillType::class, $pill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imagePill = $upload->uploadImg($form, 'pillImg');
            // If there is no image uploaded, we do not change the picture
            if ($imagePill !== null) {
                $pill->setPicture($imagePill);
            }

            $slug = $slugger->slug($pill->getName());
            $pill->setSlug(strtolower($slug));

            $em = $this->getDoctrine()->getManager();

            $em->persist($pill);
            $em->flush();

            $this->addFlash(
                'success',
                'La pilule à bien été modifiée !'
            );

            return $this->redirectToRoute('admin_pill_list');
        }

        return $this->render('admin/pill/edit.html.twig', [
            'pill' => $pill,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function list(Request $request, PillRepository $pillRepository, PaginatorInterface $paginator): Response
    {
        $pills = $pillRepository->findAll();

        $allPills = $paginator->paginate(
            $pills,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/pill/list.html.twig', [
            'allPills' => $allPills,
        ]);
    }

    /**
     * @Route("/search", name="search", methods={"GET"})
     */
    public function search(Request $request, PillRepository $pillRepository): Response
    {
        $searchValue = $request->get('q');

        $pills = $pillRepository->findSearchByName($searchValue);
        return $this->render('admin/pill/search.html.twig', [
            'searchValue' => $searchValue,
            'pills' => $pills,
        ]);
    }
}
