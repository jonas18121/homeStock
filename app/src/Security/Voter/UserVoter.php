<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const SHOW      = 'show';
    const EDIT      = 'edit';
    const DELETE    = 'delete';

    protected function supports($attribute, $myUser)
    {
        return in_array($attribute, [self::SHOW, self::EDIT, self::DELETE])
            && $myUser instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $myUser, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($myUser === null || !$myUser instanceof UserInterface){
            return false;
        }

        switch ($attribute) {

            case self::SHOW:
                return $this->isAccess($myUser, $user);

            case self::EDIT:
                return $this->isAccess($myUser, $user);

            case self::DELETE:
                return $this->isAccess($myUser, $user);
        }

        return false;
    }

    /**
    * Verifier si c'est bien le même user 
    */
    protected function isAccess(UserInterface $myUser, UserInterface $user): bool
    {
        return $myUser === $user;
    }
}
