<?php

namespace App\Controller;

use App\Entity\StorageSpace;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session", name="stripecreate_session")
     */
    public function index(StorageSpace $storageSpace)
    {
        $storage_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

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

        // afficher les info qu'on veut monterer à l'user
        
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                $storage_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);
        // echo json_encode(['id' => $checkout_session->id]);
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
