<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    const SHOW      = 'show';
    const EDIT      = 'edit';
    const DELETE    = 'delete';

    protected function supports($attribute, $comment)
    {
        return in_array($attribute, [self::SHOW, self::EDIT, self::DELETE])
            && $comment instanceof \App\Entity\Comment;
    }

    protected function voteOnAttribute($attribute, $comment, TokenInterface $token)
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($comment->getOwner() === null){
            return false;
        }

        switch ($attribute) {

            case self::SHOW:
                return $this->isAccess($comment, $user);
                break;

            case self::EDIT:
                return $this->isAccess($comment, $user);
                break;

            case self::DELETE:
                return $this->isAccess($comment, $user);
                break;
        }

        return false;
    }

    /**
    * erifier si l'user est bien le propriétaire du commentaire 
    */
    protected function isAccess($comment, $user): bool
    {
        return $comment->getOwner() === $user;
    }
}
