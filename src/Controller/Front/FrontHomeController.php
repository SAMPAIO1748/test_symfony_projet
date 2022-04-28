<?php

namespace App\Controller\Front;

use App\Repository\ChambreRepository;
use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontHomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function Home(SliderRepository $sliderRepository)
    {
        $sliders = $sliderRepository->findAll();

        return $this->render('front/home.html.twig', ['sliders' => $sliders]);
    }

    /**
     * @Route("/search/", name="search")
     */
    public function search(ChambreRepository $chambreRepository, Request $request)
    {
        $term = $request->query->get("search");

        $chambres = $chambreRepository->searchByTerm($term);

        return $this->render("front/search.html.twig", ['chambres' => $chambres, 'term' => $term]);
    }
}
