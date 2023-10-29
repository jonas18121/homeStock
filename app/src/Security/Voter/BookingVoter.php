<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Voter;

use App\Entity\Booking;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingVoter extends Voter
{
    public const SHOW = 'show';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports($attribute, $booking)
    {
        return \in_array($attribute, [self::SHOW, self::EDIT, self::DELETE], true)
            && $booking instanceof \App\Entity\Booking;
    }

    protected function voteOnAttribute($attribute, $booking, TokenInterface $token)
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$booking instanceof Booking || null === $booking->getLodger()) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::SHOW:
                return $this->isAccess($booking, $user);

            case self::EDIT:
                return $this->isAccess($booking, $user);

            case self::DELETE:
                return $this->isAccess($booking, $user);
        }

        return false;
    }

    /**
     * Verifier si l'user est bien le propriétaire de la réservation.
     */
    protected function isAccess(Booking $booking, UserInterface $user): bool
    {
        return $booking->getLodger() === $user;
    }
}
