<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketingController extends AbstractController
{
    /**
     * @Route("/billet", name="ticketing.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('ticketing/index.html.twig', [
            'c_menu' => 'tickets'
        ]);
    }
}