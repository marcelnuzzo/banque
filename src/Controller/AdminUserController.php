<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Bankaccount;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BankaccountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\NewUserAccount;


class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

     /**
     * @Route("/admin/user/new", name="admin_user_new")
     *
     * @return Response
     */
    public function new(Request $request, BankaccountRepository $repo, UserPasswordEncoderInterface $encoder, NewUserAccount $newUserAccount) {
        $withoutPw = true;
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            "withoutPw" => $withoutPw,
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $entityManager->persist($user);
            $entityManager->flush();
            // création d'un compte avec un iban pour ce nouveau client
            $accounts = $repo->findAll();
            // App\Service\NewUserAccount, création iban unique
            $newIban = $newUserAccount->getNewUserAccount($accounts);
            $account = new Bankaccount();
            $account->setAmount(0)
                    ->setIban($newIban)
                    ->setUsers($user)
                    ->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
                    ;
                    $entityManager->persist($account);
                    $entityManager->flush();  
            $this->addFlash(
                'success',
                "Le client a été créer ainsi que son compte banquaire"
            );
            return $this->redirectToRoute('admin_user');      
        }
        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     * 
     * @return Response
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager) {
        $withoutPw = false;
        $form = $this->createForm(UserType::class, $user, [
            "withoutPw" => $withoutPw,
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Les données client {$user->getFullname() } ont bien été modifié"
            );
            return $this->redirectToRoute('admin_user');
        }
        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

     /**
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     * 
     * @return Response
     */
    public function delete(User $user, Request $request) {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        $this->addFlash(
            'success',
            "L'utilisateur a bien été supprimée"
        );
        return $this->redirectToRoute("admin_user");
    }
}
