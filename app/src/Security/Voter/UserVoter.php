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

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public const SHOW = 'show';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports($attribute, $myUser)
    {
        return \in_array($attribute, [self::SHOW, self::EDIT, self::DELETE], true)
            && $myUser instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $myUser, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (null === $myUser || !$myUser instanceof UserInterface) {
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
     * Verifier si c'est bien le même user.
     */
    protected function isAccess(UserInterface $myUser, UserInterface $user): bool
    {
        return $myUser === $user;
    }
}
