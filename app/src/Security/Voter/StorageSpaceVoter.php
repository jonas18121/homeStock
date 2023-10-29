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

use App\Entity\StorageSpace;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class StorageSpaceVoter extends Voter
{
    public const SHOW = 'show';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports($attribute, $storageSpace)
    {
        return \in_array($attribute, [self::SHOW, self::EDIT, self::DELETE], true)
            && $storageSpace instanceof \App\Entity\StorageSpace;
    }

    protected function voteOnAttribute($attribute, $storageSpace, TokenInterface $token)
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$storageSpace instanceof StorageSpace || null === $storageSpace->getOwner()) {
            return false;
        }

        switch ($attribute) {
            case self::SHOW:
                return $this->isAccess($storageSpace, $user);

            case self::EDIT:
                return $this->isAccess($storageSpace, $user);

            case self::DELETE:
                return $this->isAccess($storageSpace, $user);
        }

        return false;
    }

    /**
     * Verifier si l'user est bien le propriétaire de l'espace de stockage.
     */
    protected function isAccess(StorageSpace $storageSpace, UserInterface $user): bool
    {
        return $storageSpace->getOwner() === $user;
    }
}
