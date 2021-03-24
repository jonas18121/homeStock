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
}