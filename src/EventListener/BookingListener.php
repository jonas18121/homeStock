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

    protected $bookingService;
    protected $repoBooking;
    protected $repoStorage;
    protected $manager;

    public function __construct(
        BookingService $bookingService,
        BookingRepository $repoBooking,
        StorageSpaceRepository $repoStorage,
        EntityManagerInterface $manager
    )
    {
        $this->bookingService = $bookingService;
        $this->repoBooking = $repoBooking;
        $this->repoStorage = $repoStorage;
        $this->manager = $manager;
    }

    public function processBooking(RequestEvent $event)
    {
        $this->bookingService->emitBookingPaymentOk($event->getRequest(), $this->repoBooking, $this->repoStorage, $this->manager);
    }

    public function processBookingPayementNo(ResponseEvent $event)
    {
        $this->bookingService->emitBookingPaymentNo($event->getResponse(), $this->repoBooking, $this->repoStorage, $this->manager);
    }
}