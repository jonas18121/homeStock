<?php 

namespace App\EventListener;

use App\Entity\StorageSpace;
use App\Service\StorageSpaceService;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use Symfony\Component\HttpKernel\Event\RequestEvent;
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

    /**
     * Pour réagir à une reponse ResponseEvent $event
     * Pour réagir à une request RequestEvent $event
     */
    public function processStorage(RequestEvent $event)
    {
        $this->storageSpaceService->emitStorageCheckDateEndAt($event->getRequest(), $this->storageSpaceRepository, $this->bookingRepository, $this->manager);
    }

    /**
     * Pour réagir à une reponse ResponseEvent $event
     * Pour réagir à une request RequestEvent $event
     */
    public function calculPriceByMonth(RequestEvent $event)
    {
        $this->storageSpaceService->emitStorageCalculPriceByMonth($event->getRequest(), $this->storageSpaceRepository, $this->manager);
    }
}