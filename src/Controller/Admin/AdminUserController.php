<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
}
