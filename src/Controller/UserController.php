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
    public function index(BankaccountRepository $BankRepo, UserRepository $userRepo): Response
    {
        // On récupère l'id de l'utilisateur connecté
        $user = $this->getUser();
        $idUser = $this->getUser()->getId();  
        // On regarde s'il est donateur 
        $donator = $BankRepo->findAccountUser($idUser);
        if($donator) {
            // On récupère son enregistrement
            $donator = $BankRepo->findAccountUser($idUser)[0];
            // On regarde s'il a des bénéficiaires
            $tabBenef = $BankRepo->findBeneficiaries($idUser);
            $beneficiary = null;
        } else {
            // On regarde s'il est bénéficiaire
            $beneficiary = $BankRepo->findBenficiary($idUser);
            $tabBenef = null;
        }
        $str = chr(240) . chr(159) . chr(144) . chr(152);
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'donator' => $donator,
            'beneficiary' => $beneficiary,
            'tabBenef' => $tabBenef,
            'str' => $str,
        ]);
    }

    /**
     * @Route("/transfert", name="user_transfert")
     * 
     * @return Response
     */
    public function transfert() 
    {
        return $this->render('user/transfert.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/newBeneficiary", name="user_newBeneficiary", methods={"GET","POST"})
     * 
     * @return Response
     */
    public function newBeneficiary(Request $request, BankaccountRepository $repo, UserPasswordEncoderInterface $encoder, NewUserAccount $newUserAccount, \Swift_Mailer $mailer,  EnvoiMail $envoiMail)
    {
        // on récupère l'identifiant du nouveau bénéficiaire
        $userOrigin = $this->getUser();
        $idTestator = $userOrigin->getId();
        $withoutPw = false;
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            "withoutPw" => $withoutPw,
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {       
            $entityManager = $this->getDoctrine()->getManager();
            $hash = $encoder->encodePassword($user, '1234');
            $user->setPassword($hash);
            $entityManager->persist($user);
            $entityManager->flush();
             // trouver tout les iban de la banque
             $accounts = $repo->findAll();
             // App\Service\NewUserAccount, création iban unique
             $newIban = $newUserAccount->getNewUserAccount($accounts);
             // création d'un compte avec un iban pour ce nouveau client
             $account = new Bankaccount();
             $account->setAmount(10)
                     ->setIban($newIban)
                     ->setUsers($user)
                     ->setTestator($idTestator)
                     ->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')))
                     ;
                     $entityManager->persist($account);
                     $entityManager->flush();  
                     $compte = $account->getIban();
                     $montant = $account->getAmount();
            
            // envoi message à l'admin au lieue du bénéficiaire
            $mes = $envoiMail->envoi($user, $compte, $montant, $userOrigin);
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
