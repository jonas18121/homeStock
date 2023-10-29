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

use App\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    public const SHOW = 'show';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports($attribute, $comment)
    {
        return \in_array($attribute, [self::SHOW, self::EDIT, self::DELETE], true)
            && $comment instanceof \App\Entity\Comment;
    }

    protected function voteOnAttribute($attribute, $comment, TokenInterface $token)
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$comment instanceof Comment || null === $comment->getOwner()) {
            return false;
        }

        switch ($attribute) {
            case self::SHOW:
                return $this->isAccess($comment, $user);

            case self::EDIT:
                return $this->isAccess($comment, $user);

            case self::DELETE:
                return $this->isAccess($comment, $user);
        }

        return false;
    }

    /**
     * erifier si l'user est bien le propriétaire du commentaire.
     */
    protected function isAccess(Comment $comment, UserInterface $user): bool
    {
        return $comment->getOwner() === $user;
    }
}
