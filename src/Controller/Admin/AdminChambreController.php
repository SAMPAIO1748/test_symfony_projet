<?php

namespace App\Controller\Admin;

use App\Repository\ChambreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminChambreController extends AbstractController
{
    /**
     * @Route("admin/chambres", name="admin_list_chambres")
     */
    public function adminListChambre(ChambreRepository $chambreRepository)
    {
        $chambres = $chambreRepository->findAll();

        return $this->render("admin/chambre_list.html.twig", ['chambres' => $chambres]);
    }

    /**
     * @Route("admin/chambre/{id}", name="admin_show_chambres")
     */
    public function adminShowChambre($id, ChambreRepository $chambreRepository)
    {
        $chambre = $chambreRepository->find($id);

        return $this->render("admin/chambre_show.html.twig", ['chambre' => $chambre]);
    }
}
