<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Manager\CommentManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/delete/{id}", name="comment_delete", requirements={"id": "\d+"})
     */
    public function delete_comment(Comment $comment, CommentManager $commentManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        // Voter
        $this->denyAccessUnlessGranted('delete', $comment);

        $commentManager->delete($comment);

        $this->addFlash('success', 'Votre commentaire a bien été supprimée.');
        return $this->redirectToRoute('storage_space_one', [ 'id' => $comment->getStorageSpace()->getId()]);
    }
}
