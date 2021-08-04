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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/pill", name="admin_pill_")
 * @IsGranted("ROLE_ADMIN")
 */
class PillController extends AbstractController
{
    /**
     * Route to add a new pill
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
            } else {
                $pill->setPicture('no-pill.jpg');
            }

            $slug = $slugger->slug($pill->getName());

            $pill->setSlug(strtolower($slug));

            $pill->setCountReviews(0);
            $pill->setScoreAcne(0);
            $pill->setScoreLibido(0);
            $pill->setScoreMigraine(0);
            $pill->setScoreWeight(0);
            $pill->setScoreBreastPain(0);
            $pill->setScoreNausea(0);
            $pill->setScorePms(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($pill);
            $em->flush();

            $this->addFlash(
                'success',
                'La pilule a bien été ajoutée !'
            );

            return $this->redirectToRoute('admin_pill_list');
        }

        return $this->render('admin/pill/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Route to remove a pill
     * @Route("/{id}/delete", name="delete", requirements={"id"="\d+"}, methods={"GET","POST"})
     */
    public function delete(Pill $pill): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pill);
        $em->flush();

        $this->addFlash(
            'danger',
            'La pilule a bien été supprimée !'
        );

        return $this->redirectToRoute('admin_pill_list');
    }

    /**
     * Route to display the details of a pill
     * @Route("/details/{id}", name="details", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function details(Pill $pill): Response
    {
        return $this->render('admin/pill/details.html.twig', [
            'pill' => $pill,
        ]);
    }

    /**
     * Route for editing a pill
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Pill $pill, UploadImage $upload, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PillType::class, $pill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Upload an image
            $imagePill = $upload->uploadImg($form, 'pillImg');
            // If there is no image uploaded, we do not change the picture
            if ($imagePill !== null) {
                $pill->setPicture($imagePill);
            }

            // Creation of the slug with the name of the pill
            $slug = $slugger->slug($pill->getName());
            $pill->setSlug(strtolower($slug));

            $em = $this->getDoctrine()->getManager();

            $em->persist($pill);
            $em->flush();

            $this->addFlash(
                'success',
                'La pilule a bien été modifiée !'
            );

            return $this->redirectToRoute('admin_pill_list');
        }

        return $this->render('admin/pill/edit.html.twig', [
            'pill' => $pill,
            'form' => $form->createView()
        ]);
    }

    /**
     * Route to list all pills
     * @Route("", name="list", methods={"GET"})
     */
    public function list(Request $request, PillRepository $pillRepository, PaginatorInterface $paginator): Response
    {
        $pills = $pillRepository->findBy([], [
            'created_at' => "DESC"
        ]);

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
     * Route to search for a pill by name
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
