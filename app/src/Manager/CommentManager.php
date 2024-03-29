<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\StorageSpace;
use App\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Comment - Manager.
 */
class CommentManager extends BaseManager
{
    public function createCommentFromProduct(
        Form $formComment,
        Comment $comment,
        StorageSpace $storageSpace,
        User $user
    ): RedirectResponse {
        $comment->setContent(strip_tags(trim($comment->getContent() ?? '')))
            ->setCreatedAt(new \DateTime())
            ->setStorageSpace($storageSpace)
            ->setOwner($user)
        ;

        // Retrieve the contents of the parentid field
        $parentid = $formComment->get('parentid')->getData();

        // We will look for the corresponding comment
        if (null !== $parentid) {
            $parent = $this->em()->getRepository(Comment::class)->find($parentid);
        }

        // We define the parent comment
        $comment->setParent($parent ?? null);

        $this->save($comment);

        $this->addFlashFromManager('success', 'Votre commentaire a bien été envoyé');

        return $this->redirectToRouteFromManager('storage_space_one', ['id' => $storageSpace->getId()]);
    }

    public function save(Comment $comment): Comment
    {
        $em = $this->em();
        $em->persist($comment);
        $em->flush();

        return $comment;
    }

    public function delete(
        Comment $comment,
        bool $disable = false
    ): void {
        if ($disable) {
            $comment->setDeletedAt((new \DateTime('now'))->setTimezone(new \DateTimeZone('UTC')));
            $this->save($comment);
        } else {
            $em = $this->em();
            $em->remove($comment);
            $em->flush();
        }
    }
}
