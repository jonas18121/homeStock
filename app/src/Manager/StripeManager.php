<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Manager;

use App\Entity\Booking;
use App\Entity\StorageSpace;
use App\Entity\User;
use App\Repository\BookingRepository;
use App\Repository\StorageSpaceRepository;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Plan;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\Subscription;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Stripe - Manager.
 */
class StripeManager extends BaseManager
{
    private StorageSpaceManager $storageSpaceManager;
    private StorageSpaceRepository $storageSpaceRepository;
    private BookingManager $bookingManager;
    private BookingRepository $bookingRepository;
    private ParameterBagInterface $parameterBag;

    /**
     * @required
     */
    public function setParameterBag(
        ParameterBagInterface $parameterBag
    ): void {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @required
     */
    public function setStorageSpaceManager(
        StorageSpaceManager $storageSpaceManager
    ): void {
        $this->storageSpaceManager = $storageSpaceManager;
    }

    /**
     * @required
     */
    public function setStorageSpaceRepository(
        StorageSpaceRepository $storageSpaceRepository
    ): void {
        $this->storageSpaceRepository = $storageSpaceRepository;
    }

    /**
     * @required
     */
    public function setBookingManager(
        BookingManager $bookingManager
    ): void {
        $this->bookingManager = $bookingManager;
    }

    /**
     * @required
     */
    public function setBookingRepository(
        BookingRepository $bookingRepository
    ): void {
        $this->bookingRepository = $bookingRepository;
    }

    public function createCheckoutSession(User $user, int $id_storage, int $id_booking): JsonResponse
    {
        $storageSpace = $this->storageSpaceRepository->findOneBy(['id' => $id_storage]);
        $booking = $this->bookingRepository->findOneBy(['id' => $id_booking]);

        if (!$storageSpace) {
            return new JsonResponse(['error' => 'not_storage']);
        }

        if (!$booking) {
            return new JsonResponse(['error' => 'not_booking']);
        }

        $storage_for_subscription = [];

        // Quand on passera en production stripe ira chercher les images dans la vrai adresse
        // https:www/homestock.com/public/images/
        // $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        /** @var string */
        $YOUR_DOMAIN = $this->parameterBag->get('app.domain');

        // initialiser stripe
        Stripe::setApiKey('sk_test_51IWMatFt4LI0nktG0r7oE8hshnM9rKoJBqrq5T8wBMGM8Jm5AwJkPloggJNta4KsrZsC3HmRKiDESkevgHMSUXY500UycnbgSo');

        // création du client
        $customer = Customer::create(['email' => $user->getEmail()]);
        $user->setCustomerId($customer->id);

        // création du produit
        $stripe_product = Product::create([
            'name' => $storageSpace->getTitle(),
            'type' => 'service',
        ]);

        // créer un plan
        /* $stripe_plan = Plan::create([
            'amount' => $storageSpace->getPriceByMonth()*100,
            'currency' => 'eur',
            'interval' => 'month',
            'product' => $stripe_product->id,
        ]); */

        // création du prix
        $stripe_price = Price::create([
            'nickname' => 'prélèvement tous les mois',
            'product' => $stripe_product->id,
            'unit_amount' => $storageSpace->getPriceByMonth(),
            'currency' => 'eur',
            'recurring' => [
                'interval' => 'month',
                'usage_type' => 'licensed',
            ],
        ]);

        // $storage_for_subscription ira dans line_items qui est dans Session::create
        $storage_for_subscription[] = [
            'price' => $stripe_price->id,
            'quantity' => 1,
        ];

        // creer un abonnement
        /* $subscription = \Stripe\Subscription::create([
           'customer' => $customer->id,
           'items' => [[
             'price_data' => [
               'unit_amount' => $storageSpace->getPriceByMonth()*100,
               'currency' => 'eur',
               'product' => $stripe_product->id,
               'recurring' => [
                 'interval' => 'month',
               ],
             ],
           ]],
         ]); */

        // afficher les info qu'on veut montrer à l'user
        // création de la session
        $checkout_session = Session::create([
            // 'customer_email' => $this->getUser()->getEmail(),
            'customer' => $customer->id,
            'payment_method_types' => ['card'],
            'line_items' => [
                $storage_for_subscription,
            ],
            'mode' => 'subscription',
            'success_url' => $YOUR_DOMAIN.'/commande/success/stripeSessionId={CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN.'/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $booking->setStripeSessionId($checkout_session->id);
        $this->bookingManager->save($booking);

        // echo json_encode(['id' => $checkout_session->id]);
        return new JsonResponse(['id' => $checkout_session->id]);
    }

    public function customerPortal(User $user): JsonResponse
    {
        // $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        /** @var string */
        $YOUR_DOMAIN = $this->parameterBag->get('app.domain');

        // initialiser stripe
        Stripe::setApiKey('sk_test_51IWMatFt4LI0nktG0r7oE8hshnM9rKoJBqrq5T8wBMGM8Jm5AwJkPloggJNta4KsrZsC3HmRKiDESkevgHMSUXY500UycnbgSo');

        /** @var string */
        $customerId = $user->getCustomerId();
        $customer = Customer::retrieve($customerId);
        $customer->save();

        $booking = $this->bookingRepository->findOneBy(['lodger' => $user->getId()], ['id' => 'DESC']);

        if (null === $booking) {
            throw new \Exception('Booking don\'t exist.');
        }

        /** @var string */
        $stripeSessionId = $booking->getStripeSessionId();
        $checkout_session = Session::retrieve($stripeSessionId);
        $stripe_customer_id = $checkout_session->customer;

        $session = \Stripe\BillingPortal\Session::create([
            'customer' => $stripe_customer_id,
            'return_url' => $YOUR_DOMAIN.'/commande/return/'.$checkout_session->subscription.'/'.$booking->getId(),
        ]);

        return new JsonResponse(['url' => $session->url]);
    }

    public function isReturnSubscriptionCancel(string $stripeSubscriptionId, int $bookingId): bool
    {
        // initialiser stripe
        Stripe::setApiKey('sk_test_51IWMatFt4LI0nktG0r7oE8hshnM9rKoJBqrq5T8wBMGM8Jm5AwJkPloggJNta4KsrZsC3HmRKiDESkevgHMSUXY500UycnbgSo');

        $stripe_plan = Subscription::retrieve($stripeSubscriptionId);

        $booking = $this->bookingRepository->findOneBy(['id' => $bookingId]);

        if (null === $booking) {
            throw new \Exception('Booking don\'t exist.');
        }

        if (true === $stripe_plan->cancel_at_period_end && true !== $booking->getFinish()) {
            /** @var StorageSpace */
            $product = $booking->getStorageSpace();
            // /** @var StorageSpace */
            // $product = $this->storageSpaceRepository->findOneBy(['id' => $storageSpace->getId()]);

            if (null === $stripe_plan->cancel_at) {
                throw new \Exception('Subscription is not cancel.');
            }

            $date = new \DateTime();
            $date->setTimestamp($stripe_plan->cancel_at);

            $product->setAvailable(true);
            $this->storageSpaceManager->save($product);

            $booking->setDateEndAt($date);
            $booking->setFinish(true);
            $this->bookingManager->save($booking);

            return true;
        }

        return false;
    }

    public function isPayementCancel(string $stripeSessionId, User $user): bool
    {
        $booking = $this->bookingRepository->findOneBy(['stripeSessionId' => $stripeSessionId]);

        if (!$booking || $user !== $booking->getLodger()) {
            return false;
        }

        $this->bookingManager->delete($booking);

        return true;
    }

    /**
     * @return bool|Booking
     */
    public function isPayementSuccess(string $stripeSessionId, User $user)
    {
        $booking = $this->bookingRepository->findOneBy(['stripeSessionId' => $stripeSessionId]);

        if (!$booking || $user !== $booking->getLodger()) {
            return false;
        }

        $booking->setPay(true);
        $this->bookingManager->save($booking);

        return $booking;
    }
}
