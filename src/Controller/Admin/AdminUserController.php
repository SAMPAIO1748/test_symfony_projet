<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("admin/users", name="admin_list_user")
     */
    public function adminListUser(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render("admin/user_list.html.twig", ['users' => $users]);
    }

    /**
     * @Route("admin/user/{id}", name="admin_show_user")
     */
    public function adminShowUser($id, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);

        return $this->render("admin/user_show.html.twig", ['user' => $user]);
    }

    /**
     * @Route("admin/create/user", name="admin_create_user")
     */
    public function adminCreateUser(
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

            return $this->redirectToRoute("admin_list_user");
        }

        return $this->render('admin/user_form.html.twig', ['userForm' => $userForm->createView()]);
    }
}
