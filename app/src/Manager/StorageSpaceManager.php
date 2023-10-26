<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use App\Entity\StorageSpace;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * StorageSpace - Manager.
 */
class StorageSpaceManager extends BaseManager
{ 
    /**
     * Calculate the price per month
     * 
     * @return int|float
     */
    public function priceByMonth(StorageSpace $storageSpace)
    {
        $firstDayOfThisMonth = new \DateTime('first day of this month');
        $lastDayOfThisMonth = new \DateTime('last day of this month');

        $nbDays = intval($firstDayOfThisMonth->diff($lastDayOfThisMonth)->format('%R%a'));
        $nbDays += 1;

        $priceByMonth = $storageSpace->getPriceByDays() * $nbDays;

        return $priceByMonth;
    }

    public function createStorageSpace(StorageSpace $storageSpace, User $user): RedirectResponse
    {
        // $priceByMonth = $this->priceByMonth($storageSpace);

        $storageSpace->setCreatedAt(new \DateTime())
            ->setOwner($user)
            ->setAvailable(true)
        ;

        $this->save($storageSpace);

        $this->addFlashFromManager('success', 'Votre annonce a bien été crée.');
        return $this->redirectToRouteFromManager('storage_space_all');
    }

    public function updateStorageSpace(StorageSpace $storageSpace): RedirectResponse
    {
        // $priceByMonth = $this->priceByMonth($storageSpace);

        $this->save($storageSpace);

        $this->addFlashFromManager('success', 'Votre annonce a bien été modifiée.');
        return $this->redirectToRouteFromManager('storage_space_all');
    }

    public function save(StorageSpace $storageSpace): StorageSpace 
    {
        $em = $this->em();
        $em->persist($storageSpace);
        $em->flush();

        return $storageSpace;
    }

    public function delete(
        StorageSpace $storageSpace,
        bool $disable = false
    ): void {
        if ($disable) {
            $storageSpace->setDeletedAt((new \DateTime('now'))->setTimezone(new \DateTimeZone('UTC')));
            $this->save($storageSpace);
        } else {
            $em = $this->em();
            $em->remove($storageSpace);
            $em->flush();
        }
    }
}