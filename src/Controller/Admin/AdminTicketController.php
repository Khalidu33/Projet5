<?php

namespace App\Controller\Admin;

use App\Repository\TicketRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTicketController extends AbstractController
{
    /**
     * @Route("/admin/ticket", name="admin.ticket.index")
     * @return Response
     */
    public function index(TicketRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $tickets = $paginator->paginate(
            $repository->findAllQuery(),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('admin/ticket/index.html.twig', [
            'tickets' => $tickets,
            'c_menu' => 'tickets',
        ]);
    }
}