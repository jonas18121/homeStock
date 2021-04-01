<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Booking;
use App\Form\BookingType;
use Stripe\Checkout\Session;
use App\Form\BookingFinishType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{

    protected $booking_trouves;
    protected EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

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
     * @Route("/booking/{id}", name="booking_one_for_user", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function get_one_booking_for_user(Booking $booking){

        if (!$this->getUser() || $this->getUser() != $booking->getLodger()) {
            return $this->redirectToRoute('storage_space_all');
        }

        return $this->render('booking/get_one_booking_for_user.html.twig', [
            'booking' => $booking
        ]);
    }

    /**
     * rediriger vers stripe pour le paiment
     * 
     * @Route("/booking/add/storageSpace/{id}", name="booking_add")
     */
    public function create_booking(
        $id, 
        StorageSpaceRepository $repo, 
        Request $request, 
        EntityManagerInterface $manager,
        UserRepository $repoUser
    )
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $userCurrent =  $repoUser->findUser($this->getUser()->getId());
        
        $tabBooking = [];

        foreach ($userCurrent->getBookings() as $bookingOfUser) {
            $tabBooking[] = $bookingOfUser->getFinish() == false && $bookingOfUser->getCheckForPayement() == true;
        }


        if (in_array(true,$tabBooking)) {
            $oneBookingTrue = true;
        }else{
            $oneBookingTrue = false;
        }

        $booking = new Booking;

        $storageSpace = $repo->find_one_storage($id);

        $formBooking = $this->createForm(BookingType::class, $booking);

        $formBooking->handleRequest($request);

        if ($formBooking->isSubmitted() && $formBooking->isValid()) {
            
            $storageSpace->addBooking($booking)
                // ->setAvailable(false) //à mettre lorsque le payement est valider
            ;

            $booking->setDateCreatedAt(new \DateTime())
                ->setLodger($this->getUser())
            ;
            $manager->persist($booking);

            $manager->persist($storageSpace);

            $manager->flush();

            return $this->redirectToRoute('booking_one_for_user', ['id' => $booking->getId()]);
        }

        return $this->render('booking/create_booking.html.twig', [
            'formBooking' => $formBooking->createView(),
            'storageSpace' => $storageSpace,
            'oneBookingTrue' => $oneBookingTrue
        ]);
    }

    /**
     * @Route("/booking/user", name="booking_for_user")
     */
    public function get_all_booking_for_user(
        BookingRepository $repoBooking, 
        StorageSpaceRepository $repoStorageSpace,
        EntityManagerInterface $manager
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $user = $this->getUser();

        $bookings = $repoBooking->findBy([ 'lodger' => $user ]);

        // s'il y a une réservation qui n'a pas été payé,
        // on le supprime de la bdd et du tableau $bookings
        $newBookings = [];
        foreach ($bookings as $booking) {

            if ($booking->getPay() == false) {
                
                $manager->remove($booking);
                $manager->flush();

                unset($booking);
            }
            if (isset($booking)) {
                $newBookings[] = $booking;
            }
        }

        return $this->render('booking/get_all_booking_for_user.html.twig', [
            // 'bookings' => $bookings,
            'bookings' => $newBookings
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

        return $this->redirectToRoute('booking_for_user');
    }
}
