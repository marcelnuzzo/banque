<?php

namespace App\Controller;

use App\Entity\History;
use App\Repository\UserRepository;
use App\Repository\HistoryRepository;
use App\Repository\BankaccountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminHistoryController extends AbstractController
{
    /**
     * @Route("/admin/history/", name="admin_history_index", methods={"GET"})
     */
    public function index(HistoryRepository $historyRepository): Response
    {
        return $this->render('admin/history/index.html.twig', [
            'histories' => $historyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/history/{id}", name="admin_history_show", methods={"GET"})
     */
    public function show(History $history): Response
    {
        return $this->render('admin/history/show.html.twig', [
            'history' => $history,
        ]);
    }

    /**
     * @Route("/admin/history/{id}", name="admin_history_delete", methods={"DELETE"})
     */
    public function delete(Request $request, History $history): Response
    {
        if ($this->isCsrfTokenValid('delete'.$history->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($history);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_history_index');
    }
}