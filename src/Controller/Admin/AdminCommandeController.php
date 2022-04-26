<?php

namespace App\Controller\Admin;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommandeController extends AbstractController
{
    /**
     * @Route("admin/commandes", name="admin_list_commande")
     */
    public function adminListCommande(CommandeRepository $commandeRepository)
    {
        $commandes = $commandeRepository->findAll();

        return $this->render("admin/commande_list.html.twig", ['commandes' => $commandes]);
    }

    /**
     * @Route("admin/commande/{id}", name="admin_show_commande")
     */
    public function adminShowCommande($id, CommandeRepository $commandeRepository)
    {
        $commande = $commandeRepository->find($id);

        return $this->render("admin/commande_show.html.twig", ['commande' => $commande]);
    }
}
