<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ChambreRepository;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscriotion(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ) {
        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setRoles(["ROLE_ADMIN"]);
            $user->setDateEnregistrement(new \DateTime("NOW"));

            $plainPassword = $userForm->get('password')->getData();

            $hashedPassword = $userPasswordHasherInterface->hashPassword($user, $plainPassword);

            $user->setPassword($hashedPassword);

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('front/user_form.html.twig', ['userForm' => $userForm->createView()]);
    }

    /**
     * @Route("contact", name="contact")
     */
    public function contact()
    {
        return $this->render("front/contact.html.twig");
    }
}
