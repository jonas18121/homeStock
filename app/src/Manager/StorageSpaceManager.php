<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\StorageSpace;

/**
 * StorageSpace - Manager.
 */
class StorageSpaceManager extends BaseManager
{ 
    /**
     * Calculate the price per month
     */
    public function priceByMonth(StorageSpace $storageSpace)
    {
        $firstDayOfThisMonth = new \DateTime('first day of this month');
        $lastDayOfThisMonth = new \DateTime('last day of this month');

        $nbDays = $firstDayOfThisMonth->diff($lastDayOfThisMonth)->format('%R%a') ;
        $nbDays += '1';

        $priceByMonth = $storageSpace->getPriceByDays() * $nbDays;

        return $priceByMonth;
    }
}