<?php

namespace App\Controller\Front;

use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
