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
     * StorageSpaceListener fait fonctionner StorageSpaceService::emitStorageCheckDateEndAt()
     * qui va rendre un espace de stockage disponible si la date de fin de réservation est passé
     * 
     * Si la date d'aujourd'hui est plus grand ou égale à la date de fin de réservation,
     * on met la propriété available de l'entité StorageSpace en true , pour qu'il soit disponible aux autres user
     */
    public function emitStorageCheckDateEndAt(
        Request $request, 
        StorageSpaceRepository $repoStorage, 
        BookingRepository $repoBooking,
        EntityManagerInterface $manager
    )
    {
        $bookings = $repoBooking->findAll();
        
        foreach ($bookings as $key => $booking) {
            
            $dateCurrent = new \DateTime();

            if ($booking->getDateEndAt()) {

                if ($dateCurrent >= $booking->getDateEndAt()) {
                    
                    /*                              17/03/2021                                 >                                   16/03/2021        retourne +1 jour
                        soit la date du jour ->diff(new \DateTime()) a au minimun 1 jour de plus que la date en comparaison $booking->getDateEndAt() 

                                                    17/03/2021                                <                                    18/03/2021         retourne -1 jour
                        soit la date du jour ->diff(new \DateTime()) a au minimun 1 jour de moins que la date en comparaison $booking->getDateEndAt() 

                                                    17/03/2021                         ==                    17/03/2021          retourne +0 jour
                        soit la date du jour ->diff(new \DateTime()) est égale à la date en comparaison $booking->getDateEndAt() 
                    */
                    $nb_days_positif_or_negatif = $booking->getDateEndAt()->diff(new \DateTime());

                    $nb_days = $nb_days_positif_or_negatif->format('%R%a'); //exemple retourne +1 ou -1 ou +0

                    if ($nb_days == 0 && $booking->getFinish() == false) {

                        $storageSpaces = $repoStorage->find_one_booking_in_storage($booking->getId());
                        
                        foreach ($storageSpaces as $key => $storageSpace) {
        
                            $storageSpace->setAvailable(true);
                            $manager->persist($storageSpace);

                            $booking->setFinish(true);
                            $manager->persist($booking);

                            $manager->flush();
                        }
                    }
                }
            }
        } 
    }

    /**
     * calcule le prix par mois de chaque espace de stockage 
     * après chaque création ou chaque modification, 
     * que ce soit dans EasyAdmin ou dans l'interface normale 
     */
    public function emitStorageCalculPriceByMonth(
        Request $request,
        StorageSpaceRepository $repoStorage,
        EntityManagerInterface $manager
    )
    {
        $storageSpaces = $repoStorage->findAll();

        foreach ($storageSpaces as $storageSpace) {

            $priceByMonth = $this->price_by_month($storageSpace);
            
            if ($storageSpace->getPriceByMonth() === null || $priceByMonth != $storageSpace->getPriceByMonth()) {
                
                $storageSpace->setPriceByMonth($priceByMonth);
                
                $manager->persist($storageSpace);
                $manager->flush();
            }
        }
    }

    /**
     * calcule le prix par mois
     */
    public function price_by_month(StorageSpace $storageSpace)
    {
        $firstDayOfThisMonth = new \DateTime('first day of this month');
        $lastDayOfThisMonth = new \DateTime('last day of this month');

        $nbDays = $firstDayOfThisMonth->diff($lastDayOfThisMonth)->format('%R%a') ;
        $nbDays += '1';

        $priceByMonth = $storageSpace->getPriceByDays() * $nbDays;

        return $priceByMonth;
    }
}