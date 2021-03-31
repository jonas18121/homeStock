<?php

namespace App\Controller;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PayementSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    /**
     * @Route("/commande/success/stripeSessionId={stripeSessionId}", name="payement_success")
     */
    public function index($stripeSessionId): Response
    {
        $booking = $this->entityManager->getRepository(Booking::class)->findOneBy([ 'stripeSessionId' => $stripeSessionId]);

        if (!$booking || $this->getUser() != $booking->getLodger() ) {
            return $this->redirectToRoute('storage_space_all');
        }

        $booking->setPay(true);
        $this->entityManager->persist($booking);
        $this->entityManager->flush();
        
        return $this->render('payement_success/success.html.twig', [
            'booking' => $booking,
        ]);
    }
    
}
