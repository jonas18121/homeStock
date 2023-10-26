<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Booking;
use App\Form\BookingType;
use Stripe\Checkout\Session;
use App\Form\BookingFinishType;
use App\Manager\BookingManager;
use App\Repository\UserRepository;
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
    public function get_all_booking(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/get_all_booking.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_one_for_user", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function get_one_booking_for_user(Booking $booking): Response
    {
        if (!$this->getUser() || $this->getUser() != $booking->getLodger()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->denyAccessUnlessGranted('edit', $booking);

        return $this->render('booking/get_one_booking_for_user.html.twig', [
            'booking' => $booking
        ]);
    }

    /**
     * rediriger vers stripe pour le paiement
     * 
     * @Route("/booking/add/storageSpace/{id}", name="booking_add", requirements={"id": "\d+"})
     */
    public function create_booking(
        int $id, 
        StorageSpaceRepository $storageSpaceRepository, 
        Request $request, 
        BookingManager $bookingManager
    ): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        $booking = new Booking;

        $storageSpace = $storageSpaceRepository->find_one_storage($id);

        $formBooking = $this->createForm(BookingType::class, $booking);

        $formBooking->handleRequest($request);

        if ($formBooking->isSubmitted() && $formBooking->isValid()) {
            return $bookingManager->createdBooking($booking, $storageSpace, $user);
        }

        return $this->render('booking/create_booking.html.twig', [
            'formBooking' => $formBooking->createView(),
            'storageSpace' => $storageSpace,
            'oneBookingTrue' => $bookingManager->verifBookingTrue($user)
        ]);
    }

    /**
     * @Route("/booking/user", name="booking_for_user")
     */
    public function get_all_booking_for_user(
        BookingManager $bookingManager
    ): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        return $this->render('booking/get_all_booking_for_user.html.twig', [
            'bookings' => $bookingManager->getAllBookingsForUser($user)
        ]);
    }

    /**
     * TODO : A supprimer
     * 
     * N'est pas utiliser, comme stripe s'occupe de finir une reservation
     * 
     * @Route("/booking/form/finish/{id}", name="booking_finish")
     */
    public function get_form_booking_finish(Booking $booking, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->denyAccessUnlessGranted('show', $booking);

        $formBooking = $this->createForm(BookingFinishType::class, $booking);
        
        $formBooking->handleRequest($request);
        
        if ($formBooking->isSubmitted() && $formBooking->isValid()) {

            /* $booking->setCreatedAt(new \DateTime())
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
        Booking $booking,
        BookingManager $bookingManager, 
        Request $request
    ): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        /** @var string|null */
        $token = $request->get('_token');

        if (!$user || null === $token) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->denyAccessUnlessGranted('delete', $booking);
        
        if($this->isCsrfTokenValid('delete', $token)){
            $bookingManager->deleteBooking($booking);
        }

        return $this->redirectToRoute('booking_for_user');
    }
}
