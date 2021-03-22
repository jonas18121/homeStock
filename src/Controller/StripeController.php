<?php

namespace App\Controller;

use App\Entity\Booking;
use Stripe\Stripe;
use App\Entity\StorageSpace;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{id_storage}/{id_booking}", name="stripe_create_session")
     */
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
        $manager->flush(); */

        $storage_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        //quand on passera en production stripe ira chercher les images dans la vrai adresse
        // https:www/homestock.com/public/images/

        

        // $storage_for_stripe ira dans line_items qui est dans Session::create
        $storage_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $storageSpace->getPrice()*100,
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
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        
        $booking->setStripeSessionId($checkout_session->id);
        $manager->persist($booking);
        $manager->flush();
        
        

        // echo json_encode(['id' => $checkout_session->id]);
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
