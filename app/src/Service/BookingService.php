<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Repository\BookingRepository;
use App\Repository\StorageSpaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BookingService
{
    /**
     * Si le payement est ok, le storage devient indisponible
     * et on confirme que le check de payement pour la réservation a été fait.
     *
     * $storageSpace->setAvailable(false);
     * $booking->setCheckForPayement(true);
     */
    public function emitBookingPaymentOk(
        Request $response,
        BookingRepository $repoBooking,
        StorageSpaceRepository $repoStorage,
        EntityManagerInterface $manager
    ): void {
        $bookings = $repoBooking->findAll();

        foreach ($bookings as $key => $booking) {
            if (true === $booking->getPay() && false === $booking->getFinish() && false === $booking->getCheckForPayement()) {
                $storageSpace = $repoStorage->find_storage_space_from_booking_id($booking->getId());

                $storageSpace->setAvailable(false);
                $manager->persist($storageSpace);

                $booking->setCheckForPayement(true);
                $manager->persist($booking);

                $manager->flush();
            }
        }
    }
}
