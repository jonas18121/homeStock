<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Booking;
use App\Form\BookingType;
use Stripe\Checkout\Session;
use App\Form\BookingFinishType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/booking", name="booking_all")
     */
    public function get_all_booking(BookingRepository $repo): Response
    {
        $bookings = $repo->findAll();

        return $this->render('booking/get_all_booking.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_one", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function get_one_booking(Booking $booking){

        return $this->render('booking/get_one_booking.html.twig', [
            'booking' => $booking
        ]);
    }

    /**
     * rediriger vers stripe pour le paiment
     * 
     * @Route("/booking/add/storageSpace/{id}", name="booking_add")
     */
    public function create_booking($id, StorageSpaceRepository $repo, Request $request, EntityManagerInterface $manager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }


        $booking = new Booking;

        $storageSpace = $repo->find($id);

        // $booking->setStorageSpace($storageSpace);

        $formBooking = $this->createForm(BookingType::class, $booking);

        $formBooking->handleRequest($request);

        if ($formBooking->isSubmitted() && $formBooking->isValid()) {
            
            $storageSpace->addBooking($booking)
                // ->setAvailable(false) à mettre lorsque le payement est valider
            ;

            $booking->setDateCreatedAt(new \DateTime())
                ->setLodger($this->getUser())
            ;
            $manager->persist($booking);

            $manager->persist($storageSpace);

            // $manager->flush();



            // STRIPE   

            /* $storage_for_stripe = [];
            $YOUR_DOMAIN = 'http://127.0.0.1:8000'; */


            /* // $storage_for_stripe ira dans line_items qui est dans Session::create
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
            ]; */
            

            /* //initialiser stripe
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
            // echo json_encode(['id' => $checkout_session->id]); */

            // dump($checkout_session->id);
            // dd($checkout_session);
            // dump($storageSpace);

            // return $this->redirectToRoute('booking_pay');
        }

        return $this->render('booking/create_booking.html.twig', [
            'formBooking' => $formBooking->createView(),
            'storageSpace' => $storageSpace,
            // 'stripe_checkout_session' => $checkout_session->id ?? null
        ]);
    }

    /**
     * @Route("/booking/pay/{id}", name="booking_pay")
     */
    public function booking_pay(Booking $booking){

        dd('ok');
    }

    /**
     * @Route("/booking/user", name="booking_for_user")
     */
    public function get_all_booking_for_user(BookingRepository $repo): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $user = $this->getUser();

        $bookings = $repo->findBy([ 'lodger' => $user ]);

        return $this->render('booking/get_all_booking_for_user.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * @Route("/booking/form/finish/{id}", name="booking_finish")
     */
    public function get_form_booking_finish(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }


        // $booking = new Booking;

        // $booking->setStorageSpace($storageSpace);
        $formBooking = $this->createForm(BookingFinishType::class, $booking);
        
        $formBooking->handleRequest($request);

        // dd($booking);
        
        if ($formBooking->isSubmitted() && $formBooking->isValid()) {
            // dd($booking);

            /* $booking->setDateCreatedAt(new \DateTime())
                ->setLodger($this->getUser())
            ; */

            $manager->persist($booking);
            $manager->flush();

            return $this->redirectToRoute('storage_space_all');
        }

        return $this->render('booking/finish_booking.html.twig', [
            'formBooking' => $formBooking->createView()
        ]);
    } 

    /**
     * @Route("/booking/delete/{id}", name="booking_delete", requirements={"id": "\d+"})
     */
    public function delete_booking(
        $id,
        BookingRepository $repoBooking, 
        EntityManagerInterface $manager, 
        Request $request
    )
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $booking = $repoBooking->find_one_storage_in_booking($id);
        
        if($this->isCsrfTokenValid('delete', $request->get('_token'))){

            $booking->getStorageSpace()->setAvailable(true);

            $manager->remove($booking);
            $manager->flush();

            $this->addFlash('success',"Votre réservation a été supprimé !");
        }

        return $this->redirectToRoute('storage_space_all');
    }
}
