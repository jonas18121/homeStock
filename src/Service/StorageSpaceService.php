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
        // $storageSpaces = $repoStorage->find_All_storage();
        $bookings = $repoBooking->findAll();

        foreach ($bookings as $key => $booking) {
            // $value->getDateStartAt()->format('d/m/Y')
            // $dateCurrent > $value->getDateStartAt()
            $dateCurrent = new \DateTime('11/05/2022');
            if ($dateCurrent > $booking->getDateStartAt()) {

                $storageSpaces = $repoStorage->findBy(['booking' => $booking->getId()]);

                foreach ($storageSpaces as $key => $storageSpace) {

                    $storageSpace->setAvailable(true);

                    $manager->persist($storageSpace);
                    $manager->flush();

                    // dd($storageSpace);
                }
                
            }

        }
        //dd('ok');
    }
}