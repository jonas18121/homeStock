<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use App\Manager\StripeManager;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    private StripeManager $stripeManager;

    public function __construct(StripeManager $stripeManager)
    {
        $this->stripeManager = $stripeManager;
    }

    /**
     * @Route("/commande/create-checkout-session/{id_storage}/{id_booking}", name="stripe_create_session")
     */
    public function index(
        int $id_storage,
        int $id_booking
    ): Response {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        return $this->stripeManager->createCheckoutSession($user, $id_storage, $id_booking);
    }

    /**
     * @Route("/customer/portal", name="/customer_portal")
     */
    public function customer_portal(): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        return $this->stripeManager->customerPortal($user);
    }

    /**
     * @Route("/commande/return/{stripeSubscriptionId}/{bookingId}", name="payement_return")
     */
    public function returnSubscription(string $stripeSubscriptionId, int $bookingId): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user || false === $this->stripeManager->isReturnSubscriptionCancel($stripeSubscriptionId, $bookingId)) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->addFlash('success', 'Votre abonnement a bien été annulé.');

        return $this->render('payement_return/return.html.twig');
    }

    /**
     * @Route("/commande/erreur/{stripeSessionId}", name="payement_cancel")
     */
    public function payement_cancel(string $stripeSessionId): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user || false === $this->stripeManager->isPayementCancel($stripeSessionId, $user)) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->addFlash('error', 'Votre tentative de paiement a échoué.');

        return $this->render('payement_cancel/cancel.html.twig');
    }

    /**
     * @Route("/commande/success/stripeSessionId={stripeSessionId}", name="payement_success")
     */
    public function payement_success(string $stripeSessionId): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user || false === $booking = $this->stripeManager->isPayementSuccess($stripeSessionId, $user)) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->addFlash('success', 'Votre paiement a bien été réalisé.');

        return $this->render('payement_success/success.html.twig', [
            'booking' => $booking,
        ]);
    }
}
