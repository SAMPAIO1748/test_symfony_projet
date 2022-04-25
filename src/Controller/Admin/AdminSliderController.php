<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    /**
     * @Route("admin/create/slider", name="admin_create_slider")
     */
    public function adminCreateSlider(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        SluggerInterface $sluggerInterface
    ) {
        $slider = new Slider();

        $sliderForm = $this->createForm(SliderType::class, $slider);

        $sliderForm->handleRequest($request);

        if ($sliderForm->isSubmitted() && $sliderForm->isValid()) {

            $imageFile = $sliderForm->get('photo')->getData();

            if ($imageFile) {
                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $sluggerInterface->slug($originalFileName);

                $newFileName = $safeFileName . "-" . uniqid() . "." . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $slider->setPhoto($newFileName);
            }

            $slider->setDateEnregistrement(new \DateTime("NOW"));

            $entityManagerInterface->persist($slider);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_list_slider');
        }

        return $this->render("admin/slider_form.html.twig", ['sliderForm' => $sliderForm->createView()]);
    }
}
