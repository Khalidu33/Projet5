<?php

namespace App\Notification;

use App\Entity\Ticket;
use Twig\Environment;

class TicketNotification
{
    /**
     * @var \Swift_Mailer
     */

    private $mailer;

    /**
     * @var Environment
     */

    private $rendere;

    public function __construct(\Swift_Mailer $mailer, Environment $rendere)
    {
        $this->mailer = $mailer;
        $this->rendere = $rendere;
    }

    public function Notify(Ticket $ticket)
    {
        $message = (new \Swift_Message('Confimation de l\'achat'))
            ->setFrom('natsu33dr@gmail.com')
            ->setTo($ticket->getEmail())
            ->setBody(
                $this->rendere->render('ticket/email.html.twig',[
                        'ticket' => $ticket
                    ]),
                
                'text/html'
            );

            $this->mailer->send($message);

    }
}

