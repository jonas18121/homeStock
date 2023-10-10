<?php

namespace App\Controller;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PayementCancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    /**
     * @Route("/commande/erreur/{stripeSessionId}", name="payement_cancel")
     */
    public function index($stripeSessionId): Response
    {
        $booking = $this->entityManager->getRepository(Booking::class)->findOneBy([ 'stripeSessionId' => $stripeSessionId]);

        if (!$booking || $this->getUser() != $booking->getLodger() ) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->entityManager->remove($booking);
        $this->entityManager->flush();


        return $this->render('payement_cancel/cancel.html.twig');
    }
}
