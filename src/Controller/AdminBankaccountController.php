<?php

namespace App\Controller;

use App\Entity\Bankaccount;
use App\Form\BankaccountType;
use App\Repository\BankaccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/bankaccount")
 */
class AdminBankaccountController extends AbstractController
{
    /**
     * @Route("/", name="admin_bankaccount", methods={"GET"})
     */
    public function index(BankaccountRepository $bankaccountRepository): Response
    {
        return $this->render('admin/bankaccount/index.html.twig', [
            'bankaccounts' => $bankaccountRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_bankaccount_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bankaccount = new Bankaccount();
        $form = $this->createForm(BankaccountType::class, $bankaccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bankaccount);
            $entityManager->flush();

            return $this->redirectToRoute('admin_bankaccount');
        }

        return $this->render('admin/bankaccount/new.html.twig', [
            'bankaccount' => $bankaccount,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_bankaccount_show", methods={"GET"})
     */
    public function show(Bankaccount $bankaccount): Response
    {
        return $this->render('admin/bankaccount/show.html.twig', [
            'bankaccount' => $bankaccount,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_bankaccount_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bankaccount $bankaccount): Response
    {
        $form = $this->createForm(BankaccountType::class, $bankaccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_bankaccount');
        }

        return $this->render('admin/bankaccount/edit.html.twig', [
            'bankaccount' => $bankaccount,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_bankaccount_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bankaccount $bankaccount): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bankaccount->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bankaccount);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_bankaccount_index');
    }
}
