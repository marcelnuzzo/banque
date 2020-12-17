<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }


    /**
     * Permet de se déconnecter
     *
     * @Route("/admin/logout", name="admin_account_logout")
     * 
     * @return void
     */
    public function logout()
    {
        // ...
    }

    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     * 
     * @Route("/admin/account/index", name="admin_account_index")
     * 
     * @return Response
     */
    public function index()
    {
        return $this->render('admin/account/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
