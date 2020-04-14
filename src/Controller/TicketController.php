<?php


namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Notification\TicketNotification;
use App\Repository\TicketRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
     /**
     * @var TicketRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(TicketRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/billet", name="ticket.index")
     * @return Response
     */
    public function index(): Response
    {
        $user = $this->getUser();

        if(null === $user)
        {
            return $this->redirectToRoute('login');
        }
        else
        {
            $username = $user->getUsername(); 
            
            $price = 20;
            
            $ticket = new Ticket($username, $price);
        
            $form = $this->createForm(TicketType::class, $ticket,[
                'action' => $this->generateUrl('ticket.payment'),
                'method' => 'POST',
            ]);

            return $this->render('ticket/index.html.twig', [
                'ticket' => $ticket,
                'form' => $form->createView(),
                'c_menu' => 'tickets'
            ]);
        }
    }

    /**
     * @Route("/billet/payment", name="ticket.payment" , methods="GET|POST")
     * @return Response
     */
    public function payment(Request $request):Response
    {
        $user = $this->getUser();

        if(null === $user)
        {
            return $this->redirectToRoute('login');
        }
        else
        {
            if($request->isMethod('post') && !empty($_POST))
            {
                $ticketInfo = $request->request->all();
                $session = $this->get('session');
                $session->set('ticketInfo', $ticketInfo);

                $ticketInfo = $ticketInfo['ticket'];

                $jourArrivee = strtotime($ticketInfo['day']);
                $jourFin = strtotime($ticketInfo['endday']);
                $diff = abs($jourArrivee - $jourFin);
                $diff = $diff / 3600 / 24;
                $diff++;

                $price = $diff * 20;
                if($price == 40)
                {
                    $price = $price * 0.98;
                }
                elseif($price == 60)
                {
                    $price = $price * 0.95;
                }
                elseif($price >= 80)
                {
                    $price = $price * 0.92;
                }

                $username = $user->getUsername();
                $ticket = new Ticket($username, $price);

                $ticket->setEmail($ticketInfo['email']);
                $ticket->setDay(new DateTime($ticketInfo['day']));
                $ticket->setEndday(new DateTime($ticketInfo['endday']));
            }
            else
            {
                return $this->redirectToRoute('ticket.index');
            }
        }
        return $this->render('ticket/payment.html.twig', [
            'ticket' => $ticket
        ]);
    }

    /**
     * @Route("/billet/paymentSysteme", name="payment.systeme" , methods="GET|POST")
     * @return Response
     */
    public function paymentSysteme(TicketNotification $notification):Response
    {   
        $user = $this->getUser();

        if(null === $user)
        {
            return $this->redirectToRoute('login');
        }
        else
        {
            $session = new Session();
            $ticketInfo = $session->get('ticketInfo');
            
            $ticketInfo = $ticketInfo['ticket'];

            $jourArrivee = strtotime($ticketInfo['day']);
            $jourFin = strtotime($ticketInfo['endday']);
            $diff = abs($jourArrivee - $jourFin);
            $diff = $diff / 3600 / 24;
            $diff++;

            $price = $diff * 20;
            if($price == 40)
            {
                $price = $price * 0.98;
            }
            elseif($price == 60)
            {
                $price = $price * 0.95;
            }
            elseif($price >= 80)
            {
                $price = $price * 0.92;
            }

            $user = $this->getUser();
            $username = $user->getUsername();
            $ticket = new Ticket($username, $price);

            $ticket->setEmail($ticketInfo['email']);
            $ticket->setDay(new DateTime($ticketInfo['day']));
            $ticket->setEndday(new DateTime($ticketInfo['endday']));

            $this->em->persist($ticket);
            $this->em->flush();
            $notification->Notify($ticket);

            return $this->redirectToRoute('ticket.show');
        }
    }

    /**
     * @Route("/billet/billet", name="ticket.show")
     * @return Response
     */
    public function show(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        if(null === $user)
        {
            return $this->redirectToRoute('login');
        }
        else
        {
            $username = $user->getUsername();
            $tickets = $paginator->paginate(
                $this->repository->findAllUsernameQuery($username),
                $request->query->getInt('page', 1),
                5
            );
            return $this->render('ticket/show.html.twig', [
                'tickets' => $tickets,
                'c_menu' => 'ticket'
            ]);
        }
    }

}