<?php

namespace App\Controller\Front;

use App\Entity\Commande;
use App\Entity\Like;
use App\Form\FrontCommandeType;
use App\Repository\ChambreRepository;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontChambreController extends AbstractController
{
    /**
     * @Route("chambres", name="front_chambre_list")
     */
    public function listChambre(ChambreRepository $chambreRepository)
    {
        $chambres = $chambreRepository->findAll();

        return $this->render("front/chambre_list.html.twig", ['chambres' => $chambres]);
    }

    /**
     * @Route("chambre/{id}", name="front_chambre_show")
     */
    public function showChambre($id, ChambreRepository $chambreRepository, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $chambre = $chambreRepository->find($id);

        $commande = new Commande();

        $commandeForm = $this->createForm(FrontCommandeType::class, $commande);

        $commandeForm->handleRequest($request);

        if ($commandeForm->isSubmitted() && $commandeForm->isValid()) {
            $date_arrivee = $commandeForm->get('date_arrivee')->getData();
            $date_depart = $commandeForm->get('date_depart')->getData();

            $interval = $date_arrivee->diff($date_depart);

            $prix_journalier = $chambre->getPrixJournalier();
            $prix_total = $interval->days * $prix_journalier;
            $commande->setPrixTotal($prix_total);
            $commande->setDateEnregistrement(new \DateTime("NOW"));
            $commande->setChambre($chambre);

            $entityManagerInterface->persist($commande);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("front_chambre_list");
        }

        return $this->render("front/chambre_show.html.twig", [
            'chambre' => $chambre,
            'commandeForm' => $commandeForm->createView()
        ]);
    }

    /**
     * @Route("like/chambre/{id}", name="chambre_like")
     */
    public function likeChambre(
        $id,
        ChambreRepository $chambreRepository,
        EntityManagerInterface $entityManagerInterface,
        LikeRepository $likeRepository
    ) {
        $chambre = $chambreRepository->find($id);
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'code' => 403,
                'message' => "Vous devez être connecté"
            ], 403);
        }

        if ($chambre->isLikeByUser($user)) {
            $like = $likeRepository->findOneBy(
                [
                    'chambre' => $chambre,
                    'user' => $user
                ]
            );

            $entityManagerInterface->remove($like);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "Le like a bien été supprimé.",
                'likes' => $likeRepository->count(['chambre' => $chambre])
            ], 200);
        }

        $like = new Like();

        $like->setChambre($chambre);
        $like->setUser($user);

        $entityManagerInterface->persist($like);
        $entityManagerInterface->flush();

        return $this->json([
            'code' => 200,
            'message' => "Nouveau like bien enregistré",
            'likes' => $likeRepository->count(['chambre' => $chambre])
        ], 200);
    }
}
