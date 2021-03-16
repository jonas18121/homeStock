<?php

namespace App\Service;

use App\Kernel;
use App\Entity\StorageSpace;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StorageSpaceService 
{
    /**
     * Lorsque StorageSpaceListener réagi à l'évènnement kernel.request
     * StorageSpaceListener fait fonctionner StorageSpaceService::emitStorageCheckDate()
     * qui va rendre un espace de stockage disponible si la date de fin de réservation est passé
     * 
     * Si la date d'aujourd'hui est plus grand que la date de fin de réservation,
     * on met la propriété available de StorageSpace en true , pour qu'il soit disponible aux autre user
     */
    public function emitStorageCheckDate(
        Request $response, 
        StorageSpaceRepository $repoStorage, 
        BookingRepository $repoBooking,
        EntityManagerInterface $manager
    )
    {
        $bookings = $repoBooking->findAll();
        
        foreach ($bookings as $key => $booking) {
            
            $dateCurrent = new \DateTime();
            
            if ($dateCurrent > $booking->getDateStartAt()) {

                $storageSpaces = $repoStorage->find_one_booking_in_storage($booking->getId());

                foreach ($storageSpaces as $key => $storageSpace) {

                    $storageSpace->setAvailable(true);

                    $manager->persist($storageSpace);
                    $manager->flush();
                }
            }
        }
        
    }
}