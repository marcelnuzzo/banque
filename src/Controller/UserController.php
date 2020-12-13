<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Bankaccount;
use App\Service\EnvoiMail;
use App\Service\NewUserAccount;
use App\Repository\UserRepository;
use App\Repository\BankaccountRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/newBeneficiary", name="user_newBeneficiary", methods={"GET","POST"})
     */
    public function newBeneficiary(Request $request, BankaccountRepository $repo, UserPasswordEncoderInterface $encoder, NewUserAccount $newUserAccount, \Swift_Mailer $mailer,  EnvoiMail $envoiMail)
    {
        $userOrigin = $this->getUser();
        $withoutPw = false;
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
             $account = $repo->findAll();
             // App\Service\NewUserAccount, création iban unique
             $newIban = $newUserAccount->getNewUserAccount($account);
             $account = new Bankaccount();
             $account->setAmount(0)
                     ->setIban($newIban)
                     ->setUsers($user)
                     ;
                     $entityManager->persist($account);
                     $entityManager->flush();  
            // envoi message à l'admin au lieue du bénéficiaire
            $mes = $envoiMail->envoi($user, $account, $userOrigin);
            $mailer->send($mes);
            $this->addFlash(
                'success',
                "Le bénéficiaire a été enregistrér et un email lui a été envoyé"
            );
            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/newBeneficiary.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                "L'utilisateur n°{$user->getId()} a bien été modifiée"
            );
            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
