<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Manager\CommentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/delete/{id}", name="comment_delete", requirements={"id": "\d+"})
     */
    public function delete_comment(Comment $comment, CommentManager $commentManager): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        // Voter
        $this->denyAccessUnlessGranted('delete', $comment);

        $commentManager->delete($comment);

        if (null === $comment->getStorageSpace()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->addFlash('success', 'Votre commentaire a bien été supprimée.');

        return $this->redirectToRoute('storage_space_one', ['id' => $comment->getStorageSpace()->getId()]);
    }
}
