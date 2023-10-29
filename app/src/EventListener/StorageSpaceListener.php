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

use App\Manager\StorageSpaceManager;
use App\Repository\BookingRepository;
use App\Repository\StorageSpaceRepository;
use App\Service\StorageSpaceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class StorageSpaceListener
{
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
    ) {
        $this->storageSpaceService = $storageSpaceService;
        $this->storageSpaceRepository = $storageSpaceRepository;
        $this->bookingRepository = $bookingRepository;
        $this->entityManager = $entityManager;
        $this->storageSpaceManager = $storageSpaceManager;
    }

    /**
     * Pour réagir à une reponse ResponseEvent $event
     * Pour réagir à une request RequestEvent $event.
     */
    public function processStorage(RequestEvent $event): void
    {
        $this->storageSpaceService->emitStorageCheckDateEndAt($event->getRequest(), $this->storageSpaceRepository, $this->bookingRepository, $this->entityManager);
    }

    /**
     * Pour réagir à une reponse ResponseEvent $event
     * Pour réagir à une request RequestEvent $event.
     */
    public function calculPriceByMonth(RequestEvent $event): void
    {
        $this->storageSpaceService->emitStorageCalculPriceByMonth($event->getRequest(), $this->storageSpaceRepository, $this->entityManager, $this->storageSpaceManager);
    }
}
