<?php 

namespace App\EventListener;

use App\Entity\StorageSpace;
use App\Manager\StorageSpaceManager;
use App\Service\StorageSpaceService;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class StorageSpaceListener {

    protected StorageSpaceService $storageSpaceService;
    protected StorageSpaceRepository $storageSpaceRepository;
    protected BookingRepository $bookingRepository;
    protected EntityManagerInterface $entityManager;
    protected StorageSpaceManager $storageSpaceManager;

    public function __construct(
        StorageSpaceService $storageSpaceService, 
        StorageSpaceRepository $storageSpaceRepository, 
        BookingRepository $bookingRepository,
        EntityManagerInterface $entityManager,
        StorageSpaceManager $storageSpaceManager
    )
    {
        $this->storageSpaceService = $storageSpaceService;
        $this->storageSpaceRepository = $storageSpaceRepository;
        $this->bookingRepository = $bookingRepository;
        $this->entityManager = $entityManager;
        $this->storageSpaceManager = $storageSpaceManager;
    }

    /**
     * Pour réagir à une reponse ResponseEvent $event
     * Pour réagir à une request RequestEvent $event
     * 
     * @param RequestEvent $event
     */
    public function processStorage(RequestEvent $event): void
    {
        $this->storageSpaceService->emitStorageCheckDateEndAt($event->getRequest(), $this->storageSpaceRepository, $this->bookingRepository, $this->entityManager);
    }

    /**
     * Pour réagir à une reponse ResponseEvent $event
     * Pour réagir à une request RequestEvent $event
     * 
     * @param RequestEvent $event
     */
    public function calculPriceByMonth(RequestEvent $event): void
    {
        $this->storageSpaceService->emitStorageCalculPriceByMonth($event->getRequest(), $this->storageSpaceRepository, $this->entityManager, $this->storageSpaceManager);
    }
}