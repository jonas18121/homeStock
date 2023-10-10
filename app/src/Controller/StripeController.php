<?php

namespace App\Controller;

use Stripe\Price;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Customer;
use App\Entity\Booking;
use App\Entity\StorageSpace;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Plan;
use Stripe\Subscription;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-checkout-session/{id_storage}/{id_booking}", name="stripe_create_session")
     */
    public function index($id_storage, $id_booking, EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        $storageSpace = $manager->getRepository(StorageSpace::class)->findOneBy([ 'id' => $id_storage]);
        $booking = $manager->getRepository(Booking::class)->findOneBy([ 'id' => $id_booking]);
          
        if (!$storageSpace) {
            new JsonResponse(['error' => 'not_storage']);
        }

        $storage_for_subscription = [];

        //quand on passera en production stripe ira chercher les images dans la vrai adresse
        // https:www/homestock.com/public/images/
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        
        //initialiser stripe
        Stripe::setApiKey('sk_test_51IWMatFt4LI0nktG0r7oE8hshnM9rKoJBqrq5T8wBMGM8Jm5AwJkPloggJNta4KsrZsC3HmRKiDESkevgHMSUXY500UycnbgSo');
      
        // création du client
        $customer = Customer::create([ 'email' => $user->getEmail() ]);
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
        $stripe_price =  Price::create([
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
                $storage_for_subscription
            ],
            'mode' => 'subscription',
            'success_url' => $YOUR_DOMAIN . '/commande/success/stripeSessionId={CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $booking->setStripeSessionId($checkout_session->id);
        $manager->persist($booking);
        $manager->flush();
    
        // echo json_encode(['id' => $checkout_session->id]);
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }

    /**
     * @Route("/customer/portal", name="/customer_portal")
     */
    public function customer_portal(EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        //initialiser stripe
        Stripe::setApiKey('sk_test_51IWMatFt4LI0nktG0r7oE8hshnM9rKoJBqrq5T8wBMGM8Jm5AwJkPloggJNta4KsrZsC3HmRKiDESkevgHMSUXY500UycnbgSo');
        
        $customer = Customer::retrieve($user->getCustomerId());
        $customer->save();
        
        $booking = $manager->getRepository(Booking::class)->findOneBy([ 'lodger' => $user->getId() ], ['id' => 'DESC']);

        $checkout_session = Session::retrieve($booking->getStripeSessionId());
        $stripe_customer_id = $checkout_session->customer;

        $session = \Stripe\BillingPortal\Session::create([
            'customer' => $stripe_customer_id,
            'return_url' => $YOUR_DOMAIN . '/commande/return/' . $checkout_session->subscription . '/' . $booking->getId(),
        ]);

        $response = new JsonResponse(['url' => $session->url]);
        return $response;
    }


    /**
     * @Route("/commande/return/{stripeSubscriptionId}/{bookingId}", name="payement_return")
     */
    public function returnSubscription($stripeSubscriptionId, $bookingId, EntityManagerInterface $manager): Response
    {
        //initialiser stripe
        Stripe::setApiKey('sk_test_51IWMatFt4LI0nktG0r7oE8hshnM9rKoJBqrq5T8wBMGM8Jm5AwJkPloggJNta4KsrZsC3HmRKiDESkevgHMSUXY500UycnbgSo');
        
        $stripe_plan = Subscription::retrieve($stripeSubscriptionId);

        if($stripe_plan->cancel_at_period_end === true){
            $booking = $manager->getRepository(Booking::class)->findOneBy([ 'id' => $bookingId ]);
            $product = $manager->getRepository(StorageSpace::class)->findOneBy([ 'id' => $booking->getStorageSpace()->getId() ]);
            
            $date = new \DateTime();
            $date->setTimestamp($stripe_plan->cancel_at);

            $product->setAvailable(true);
            $manager->persist($product);

            $booking->setDateEndAt($date);
            $booking->setFinish(true);
            $manager->persist($booking);

            $manager->flush();
        }
        else{
            return $this->redirectToRoute('storage_space_all');
        }

        return $this->render('payement_return/return.html.twig'); 
    }

}
    








    /**
     * @              Route("/commande/create-session/{id_storage}/{id_booking}", name="stripe_create_session")
     */
    /* 
    
    
    public function index($id_storage, $id_booking, EntityManagerInterface $manager)
    {
        $storageSpace = $manager->getRepository(StorageSpace::class)->findOneBy([ 'id' => $id_storage]);
        $booking = $manager->getRepository(Booking::class)->findOneBy([ 'id' => $id_booking]);
        //  dd($storageSpace);
        if (!$storageSpace) {
            new JsonResponse(['error' => 'not_storage']);
        }

        /* $storageSpace->setAvailable(false);
        $manager->persist($storageSpace);
        $manager->flush(); * /

        $storage_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        //quand on passera en production stripe ira chercher les images dans la vrai adresse
        // https:www/homestock.com/public/images/

        

        // $storage_for_stripe ira dans line_items qui est dans Session::create
        $storage_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $storageSpace->getPriceByMonth()*100,
                'product_data' => [
                    'name' => $storageSpace->getTitle(),
                    'images' => [ $YOUR_DOMAIN . "/images/storageSpace/" . $storageSpace->getImages() ],
                ],
            ],
            'quantity' => 1,
        ];

        //initialiser stripe
        Stripe::setApiKey('sk_test_51IWMatFt4LI0nktG0r7oE8hshnM9rKoJBqrq5T8wBMGM8Jm5AwJkPloggJNta4KsrZsC3HmRKiDESkevgHMSUXY500UycnbgSo');
      
        header('Content-Type: application/json');

        
        

        // afficher les info qu'on veut monterer à l'user
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $storage_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        
        $booking->setStripeSessionId($checkout_session->id);
        $manager->persist($booking);
        $manager->flush();
        
        

        // echo json_encode(['id' => $checkout_session->id]);
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    } 
    
    */

