<?php

namespace App\Controller\Admin;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminChambreController extends AbstractController
{
    /**
     * @Route("admin/chambres", name="admin_list_chambre")
     */
    public function adminListChambre(ChambreRepository $chambreRepository)
    {
        $chambres = $chambreRepository->findAll();

        return $this->render("admin/chambre_list.html.twig", ['chambres' => $chambres]);
    }

    /**
     * @Route("admin/chambre/{id}", name="admin_show_chambre")
     */
    public function adminShowChambre($id, ChambreRepository $chambreRepository)
    {
        $chambre = $chambreRepository->find($id);

        return $this->render("admin/chambre_show.html.twig", ['chambre' => $chambre]);
    }

    /**
     * @Route("admin/create/chambre", name="admin_create_chambre")
     */
    public function adminCreateChambre(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        SluggerInterface $sluggerInterface
    ) {
        $chambre = new Chambre();

        $chambreForm = $this->createForm(ChambreType::class, $chambre);

        $chambreForm->handleRequest($request);

        if ($chambreForm->isSubmitted() && $chambreForm->isValid()) {

            $imageFile = $chambreForm->get('photo')->getData();

            if ($imageFile) {
                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $sluggerInterface->slug($originalFileName);

                $newFileName = $safeFileName . "-" . uniqid() . "." . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $chambre->setPhoto($newFileName);
            }

            $chambre->setDateEnregitrement(new \DateTime("NOW"));

            $entityManagerInterface->persist($chambre);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_list_chambre');
        }

        return $this->render("admin/chambre_form.html.twig", ['chambreForm' => $chambreForm->createView()]);
    }

    /**
     * @Route("admin/update/chambre/{id}", name="admin_update_chambre")
     */
    public function adminUpdateChambre(
        $id,
        ChambreRepository $chambreRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request,
        SluggerInterface $sluggerInterface
    ) {
        $chambre = $chambreRepository->find($id);

        $chambreForm = $this->createForm(ChambreType::class, $chambre);

        $chambreForm->handleRequest($request);

        if ($chambreForm->isSubmitted() && $chambreForm->isValid()) {

            $imageFile = $chambreForm->get('photo')->getData();

            if ($imageFile) {
                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $sluggerInterface->slug($originalFileName);

                $newFileName = $safeFileName . "-" . uniqid() . "." . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $chambre->setPhoto($newFileName);
            }

            $entityManagerInterface->persist($chambre);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_list_chambre');
        }

        return $this->render("admin/chambre_form.html.twig", ['chambreForm' => $chambreForm->createView()]);
    }

    /**
     * @Route("admin/delete/chambre/{id}", name="admin_delete_chambre")
     */
    public function adminDeletetChambre($id, ChambreRepository $chambreRepository, EntityManagerInterface $entityManagerInterface)
    {
        $chambre = $chambreRepository->find($id);

        $entityManagerInterface->remove($chambre);

        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_list_chambre');
    }
}
