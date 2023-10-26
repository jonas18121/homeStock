<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use App\Entity\Booking;
use App\Entity\StorageSpace;
use App\Repository\UserRepository;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Booking - Manager.
 */
class BookingManager extends BaseManager
{ 
    private StorageSpaceManager $storageSpaceManager;
    private BookingRepository $bookingRepository;

    /**
     * @required
     */
    public function setStorageSpaceManager(
        StorageSpaceManager $storageSpaceManager
    ): void {
        $this->storageSpaceManager = $storageSpaceManager;
    }

    /**
     * @required
     */
    public function setBookingRepository(
        BookingRepository $bookingRepository
    ): void {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * @return array<int, Booking>
     */
    public function getAllBookingsForUser(
        User $user
    ): array
    {
        $bookings = $this->bookingRepository->findBy(['lodger' => $user]);

        // s'il y a une réservation qui n'a pas été payé,
        // on le supprime de la bdd et du tableau $bookings
        $newBookings = [];
        foreach ($bookings as $booking) {
            if ($booking->getPay() === false) {
                $this->delete($booking);
                unset($booking);
            }

            if (isset($booking)) {
                $newBookings[] = $booking;
            }
        }

        return $newBookings;
    }

    public function createdBooking(
        Booking $booking, 
        StorageSpace $storageSpace, 
        User $user
    ): RedirectResponse
    {
        $storageSpace->addBooking($booking)
            ->setUpdatedAt(new \DateTime())
            // ->setAvailable(false) //à mettre lorsque le payement est valider
        ;

        $booking->setCreatedAt(new \DateTime())
            ->setLodger($user)
        ;

        $this->save($booking);
        $this->storageSpaceManager->save($storageSpace);

        $this->addFlashFromManager('success', 'Votre reservation a bien été crée.');
        return $this->redirectToRouteFromManager('booking_one_for_user', ['id' => $booking->getId()]);
    }

    /**
     * vérifier s'il y a une réservation qui est en cours avec l'user courant
     * afin de l'empéché de souscrire à un autre abonnement 
     * plus tard dans le code
     */
    public function verifBookingTrue(User $userCurrent): bool
    {        
        $tabBooking = [];

        foreach ($userCurrent->getBookings() as $bookingOfUser) {
            $tabBooking[] = $bookingOfUser->getFinish() === false && $bookingOfUser->getCheckForPayement() === true;
        }

        if (in_array(true, $tabBooking)) {
            return true;
        }
        
        return false;
    }

    public function deleteBooking(Booking $booking, bool $disable = false): void
    {
        $storageSpace = $booking->getStorageSpace();

        if (null === $storageSpace) {
            throw new \Exception("StorageSpace don't exist.");
        }

        $storageSpace->setAvailable(true);

        $this->delete($booking, $disable);
        $this->addFlashFromManager('success', 'Votre réservation a bien été supprimée.');
    }

    public function save(Booking $booking): Booking 
    {
        $em = $this->em();
        $em->persist($booking);
        $em->flush();

        return $booking;
    }

    public function delete(
        Booking $booking,
        bool $disable = false
    ): void {
        if ($disable) {
            $booking->setDeletedAt((new \DateTime('now'))->setTimezone(new \DateTimeZone('UTC')));
            $this->save($booking);
        } else {
            $em = $this->em();
            $em->remove($booking);
            $em->flush();
        }
    }
}