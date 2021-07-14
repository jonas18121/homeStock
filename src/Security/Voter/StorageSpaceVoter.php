<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class StorageSpaceVoter extends Voter
{
    const SHOW      = 'show';
    const EDIT      = 'edit';
    const DELETE    = 'delete';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::SHOW, self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\StorageSpace;
    }

    protected function voteOnAttribute($attribute, $storageSpace, TokenInterface $token)
    {
        $user = $token->getUser();
        
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($storageSpace->getOwner() === null){
            return false;
        }

        switch ($attribute) {

            case self::SHOW:
                return $this->isAccess($storageSpace, $user);
                break;

            case self::EDIT:
                return $this->isAccess($storageSpace, $user);
                break;

            case self::DELETE:
                return $this->isAccess($storageSpace, $user);
                break;
        }

        return false;
    }


    /**
    * Verifier si l'user est bien le propriétaire de l'espace de stockage
    */
    protected function isAccess($storageSpace, $user): bool
    {
        return $storageSpace->getOwner() === $user;
    }
}
