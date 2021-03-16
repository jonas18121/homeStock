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
    protected $storageSpaceRepository;
    protected $bookingRepository;
    protected $manager;

    public function __construct(
        StorageSpaceService $storage, 
        StorageSpaceRepository $repoStorage, 
        BookingRepository $repoBooking,
        EntityManagerInterface $manager
    )
    {
        $this->storageSpaceService = $storage;
        $this->storageSpaceRepository = $repoStorage;
        $this->bookingRepository = $repoBooking;
        $this->manager = $manager;
    }

    public function processStorage(ResponseEvent $event)
    {
        $this->storageSpaceService->emitStorageCheckDate($event->getRequest(), $this->storageSpaceRepository, $this->bookingRepository, $this->manager);
    }
}