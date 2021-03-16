<?php 

namespace App\EventListener;

use App\Entity\StorageSpace;
use App\Service\StorageSpaceService;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class StorageSpaceListener {

    protected $storageSpaceService;

    protected $endDate;

    public function __construct(
        StorageSpaceService $storage, 
        /*$endDate,*/ 
        StorageSpaceRepository $repoStorage, 
        BookingRepository $repoBooking,
        EntityManagerInterface $manager
    )
    {
        // dd($endDate);
        $this->storageSpaceService = $storage;
        // $this->endDate = new \DateTime($endDate);
        $this->storageSpaceRepository = $repoStorage;
        $this->bookingRepository = $repoBooking;
        $this->manager = $manager;
    }

    public function processStorage(ResponseEvent $event)
    {
        // $checkDate = $this->endDate->diff(new \DateTime())->days;

    // dd($storageSpace);

        // if ($checkDate >= 0) {
        $response = $this->storageSpaceService->emitStorageCheckDate($event->getRequest(), $this->storageSpaceRepository, $this->bookingRepository, $this->manager);
            //$event->setResponse($response);
        // }
        return;
    }
}