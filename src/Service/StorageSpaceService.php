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
    public function emitStorageCheckDate(
        Request $response, 
        StorageSpaceRepository $repoStorage, 
        BookingRepository $repoBooking,
        EntityManagerInterface $manager
    )
    {
        $bookings = $repoBooking->findAll();
        
        foreach ($bookings as $key => $booking) {
            
            $dateCurrent = new \DateTime('11/01/2025');
            
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