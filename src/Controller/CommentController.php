<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{

    /**
     * @Route("/comment/delete/{id}", name="comment_delete", requirements={"id": "\d+"})
     */
    public function delete_comment(Comment $comment, EntityManagerInterface $manager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $user = $this->getUser();

        $this->denyAccessUnlessGranted('delete', $comment);

        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute('storage_space_one', [ 'id' => $comment->getStorageSpace()->getId()]);
    }
}
