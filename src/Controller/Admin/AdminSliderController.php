<?php

namespace App\Controller\Admin;

use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSliderController extends AbstractController
{
    /**
     * @Route("admin/sliders/", name="admin_list_slider")
     */
    public function adminListSlider(SliderRepository $sliderRepository)
    {
        $sliders = $sliderRepository->findAll();

        return $this->render("admin/sliders_list.html.twig", ['sliders' => $sliders]);
    }

    /**
     * @Route("admin/slider/{id}", name="admin_show_slider")
     */
    public function adminShowSLider($id, SliderRepository $sliderRepository)
    {
        $slider = $sliderRepository->find($id);

        return $this->render("admin/slider_show.html.twig", ['slider' => $slider]);
    }
}
