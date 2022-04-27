<?php

namespace App\Controller\Front;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontAvisController extends AbstractController
{
    /**
     * @Route("avis", name="list_avis")
     */
    public function listAvis(
        AvisRepository $avisRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {
        $avis = $avisRepository->findAll();

        $avi = new Avis();

        $aviForm = $this->createForm(AvisType::class, $avi);

        $aviForm->handleRequest($request);

        if ($aviForm->isSubmitted() && $aviForm->isValid()) {
            $entityManagerInterface->persist($avi);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("list_avis");
        }

        return $this->render("front/avis_list.html.twig", [
            'avis' => $avis,
            'aviForm' => $aviForm->createView()
        ]);
    }

    /**
     * @Route("avis/search", name="avis_search")
     */
    public function avisSearch(AvisRepository $avisRepository, Request $request)
    {
        $term = $request->query->get('search');

        $avis = $avisRepository->searchByTerm($term);

        return $this->render("front/search_avis.html.twig", ['avis' => $avis, 'term' => $term]);
    }
}
