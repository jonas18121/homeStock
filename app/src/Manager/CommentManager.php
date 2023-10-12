<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\StorageSpace;
use Symfony\Component\Form\Form;

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
    )
    {
        $comment->setContent(strip_tags(trim($comment->getContent())))
            ->setDateCreatedAt(new \DateTime())
            ->setStorageSpace($storageSpace)
            ->setOwner($user)
        ;

        // Retrieve the contents of the parentid field
        $parentid = $formComment->get("parentid")->getData();
        
        // We will look for the corresponding comment
        if ($parentid != null) {
            $parent = $this->em()->getRepository(Comment::class)->find($parentid);
        }

        // We define the parent comment
        $comment->setParent($parent ?? null); 

        $this->save($comment);

        $this->addFlashFromManager('success', 'Votre commentaire a bien été envoyé');
        return $this->redirectToRouteFromManager ('storage_space_one', [ 'id' => $storageSpace->getId()]);
    }

    public function save(Comment $comment): Comment 
    {
        $em = $this->em();
        $em->persist($comment);
        $em->flush();

        return $comment;
    }
}