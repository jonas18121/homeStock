<?php

namespace App\EventListener;

use App\Entity\Booking;
use App\Service\BookingService;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingListener {

    protected BookingService $bookingService;
    protected BookingRepository $bookingRepository;
    protected StorageSpaceRepository $storageSpaceRepository;
    protected EntityManagerInterface $entityManager;

    public function __construct(
        BookingService $bookingService,
        BookingRepository $bookingRepository,
        StorageSpaceRepository $storageSpaceRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->bookingService = $bookingService;
        $this->bookingRepository = $bookingRepository;
        $this->storageSpaceRepository = $storageSpaceRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param RequestEvent $event
     * @return void
     */
    public function processBooking(RequestEvent $event): void
    {
        $this->bookingService->emitBookingPaymentOk($event->getRequest(), $this->bookingRepository, $this->storageSpaceRepository, $this->entityManager);
    }
}