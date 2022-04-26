<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    /**
     * @Route("admin/update/commande/{id}", name="admin_update_commande")
     */
    public function adminUpdateCommande(
        $id,
        CommandeRepository $commandeRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $commande = $commandeRepository->find($id);

        $commandeForm = $this->createForm(CommandeType::class, $commande);

        $commandeForm->handleRequest($request);

        if ($commandeForm->isSubmitted() && $commandeForm->isValid()) {
            $date_arrivee = $commandeForm->get('date_arrivee')->getData();
            $date_depart = $commandeForm->get("date_depart")->getData();

            $interval = $date_arrivee->diff($date_depart);

            $commandeChambre = $commandeForm->get('chambre')->getData();
            $price_journalier = $commandeChambre->getPrixJournalier();
            $price_total = $interval->days * $price_journalier;
            $commande->setPrixTotal($price_total);
            $entityManagerInterface->persist($commande);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_commande");
        }

        return $this->render("admin/commande_form.html.twig", ['commandeForm' => $commandeForm->createView()]);
    }

    /**
     * @Route("admin/create/commande", name="admin_create_commande")
     */
    public function adminCreateCommande(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $commande = new Commande();

        $commandeForm = $this->createForm(CommandeType::class, $commande);

        $commandeForm->handleRequest($request);

        if ($commandeForm->isSubmitted() && $commandeForm->isValid()) {
            $commande->setDateEnregistrement(new \DateTime("NOW"));
            $date_arrivee = $commandeForm->get('date_arrivee')->getData();
            $date_depart = $commandeForm->get("date_depart")->getData();

            $interval = $date_arrivee->diff($date_depart);

            $commandeChambre = $commandeForm->get('chambre')->getData();
            $price_journalier = $commandeChambre->getPrixJournalier();
            $price_total = $interval->days * $price_journalier;
            $commande->setPrixTotal($price_total);

            $entityManagerInterface->persist($commande);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_commande");
        }

        return $this->render("admin/commande_form.html.twig", ["commandeForm" => $commandeForm->createView()]);
    }
}
