<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Repository\BookingRepository;
use App\Repository\StorageSpaceRepository;
use App\Service\BookingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class BookingListener
{
    protected BookingService $bookingService;
    protected BookingRepository $bookingRepository;
    protected StorageSpaceRepository $storageSpaceRepository;
    protected EntityManagerInterface $entityManager;

    public function __construct(
        BookingService $bookingService,
        BookingRepository $bookingRepository,
        StorageSpaceRepository $storageSpaceRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->bookingService = $bookingService;
        $this->bookingRepository = $bookingRepository;
        $this->storageSpaceRepository = $storageSpaceRepository;
        $this->entityManager = $entityManager;
    }

    public function processBooking(RequestEvent $event): void
    {
        $this->bookingService->emitBookingPaymentOk($event->getRequest(), $this->bookingRepository, $this->storageSpaceRepository, $this->entityManager);
    }
}
