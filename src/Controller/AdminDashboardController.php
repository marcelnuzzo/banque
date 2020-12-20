<?php

namespace App\Controller;

use App\Repository\BankaccountRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            "user" => $this->getUser(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/list", name="admin_dashboard_list")
     */
    public function list(UserRepository $userRepository, BankaccountRepository $repo): Response
    {
        $accounts = $repo->findAll();
        $nbAccounts = count($accounts);
        $idUsersAccount = [];
        for($i=0; $i<$nbAccounts; $i++) {
            $idUsersAccount[] = $accounts[$i]->getUsers()->getId();
        }
        $users = $userRepository->findAll();
        $nbUsers = count($users);
        for($i=0; $i<$nbUsers; $i++) {
            $idUsers[] = $users[$i]->getId(); 
        }
        $diff = array_diff($idUsers, $idUsersAccount);
        $nbDiff = count($diff);
        for($i=0; $i<$nbDiff; $i++) {
            $notAccount[] = array_shift($diff);
        }
        $usersNotAccount = [];
        for($i=0; $i<$nbDiff; $i++) {
            $usersNotAccount[] = $userRepository->find($notAccount[$i]);
        }
        $varAux = array_shift($usersNotAccount);
        //dd($usersNotAccount);
        return $this->render('admin/dashboard/list.html.twig', [
            "user" => $this->getUser(),
            "users" => $users,
            "usersNotAccount" => $usersNotAccount,
        ]);
    }
}
