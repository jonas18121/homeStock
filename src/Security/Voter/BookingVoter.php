<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingVoter extends Voter
{
    const SHOW      = 'show';
    const EDIT      = 'edit';
    const DELETE    = 'delete';

    protected function supports($attribute, $booking)
    {
        return in_array($attribute, [self::SHOW, self::EDIT, self::DELETE])
            && $booking instanceof \App\Entity\Booking;
    }

    protected function voteOnAttribute($attribute, $booking, TokenInterface $token)
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($booking->getLodger() === null){
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            
            case self::SHOW:
                return $this->isAccess($booking, $user);
                break;

            case self::EDIT:
                return $this->isAccess($booking, $user);
                break;

            case self::DELETE:
                return $this->isAccess($booking, $user);
                break;

        }

        return false;
    }

    /**
    * Verifier si l'user est bien le propriétaire de la réservation
    */
    protected function isAccess($booking, $user): bool
    {
        return $booking->getLodger() === $user;
    }
}
