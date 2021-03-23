<?php

namespace App\Service;

use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingService
{
    
    /**
     * Si le payement est ok, le storage devient indisponible 
     * et on confirme que le check de payement pour la réservation a été fait 
     *  
     * $storageSpace->setAvailable(false);
     * $booking->setCheckForPayement(true);
     */
    public function emitBookingPaymentOk(
        Request $response,
        BookingRepository $repoBooking,
        StorageSpaceRepository $repoStorage,
        EntityManagerInterface $manager
    )
    {
        $bookings = $repoBooking->findAll();

        foreach ($bookings as $key => $booking) {

            if ($booking->getPay() == true && $booking->getFinish() == false && $booking->getCheckForPayement() == false) {

                $storageSpaces = $repoStorage->find_one_booking_in_storage($booking->getId());

                foreach ($storageSpaces as $key => $storageSpace) {
        
                    $storageSpace->setAvailable(false);
                    $manager->persist($storageSpace);

                    $booking->setCheckForPayement(true);
                    $manager->persist($booking);

                    $manager->flush();
                }

            }
        }
    }

    /**
     * si la date du jour est égale ou plus grand que la date du départ de la réservation
     * et que la réservation n'est toujours pas payé, 
     * alors on annule la réservation et on rend disponible le storage  
     */
    public function emitBookingDateStartAtSmall(BookingRepository $repoBooking)
    {
        $bookings = $repoBooking->findAll();

        foreach ($bookings as $key => $booking) {

            $dateCurrent = new \DateTime();
    
            if ($dateCurrent >= $booking->getDateStartAt() && $booking->getPay() == false ) {

            }
        }

    }

    /**
     * si le user décide de ne pas payer sa réservation dans http://127.0.0.1:8000/booking/{id}
     * et change directement d'url on supprime sa réservation
     */
    public function emitBookingPaymentNo(
        Response $response,
        BookingRepository $repoBooking,
        StorageSpaceRepository $repoStorage,
        EntityManagerInterface $manager
    )
    {
        $bookings = $repoBooking->findAll();

        foreach ($bookings as $key => $booking) {

            if ($booking->getPay() == false) {

                // return header('Location: http://127.0.0.1:8000/booking/' . $booking->getId());
                // $abstractController->redirectToRoute('booking_one_for_user', [ 'id' => $booking->getId() ]);

                /* $manager->remove($booking);
                $manager->flush(); */
                /* if (header('Location: http://127.0.0.1:8000/booking/' . $booking->getId())) {
                    break;
                } */
            }
        }
    }
}