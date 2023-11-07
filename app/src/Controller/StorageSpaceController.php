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
use App\Entity\StorageSpace;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\StorageSpaceType;
use App\Manager\CommentManager;
use App\Manager\StorageSpaceManager;
use App\Repository\StorageSpaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StorageSpaceController extends AbstractController
{
    /**
     * @Route("/storageSpace", name="storage_space_all")
     */
    public function getAllStorageSpace(StorageSpaceRepository $storageSpaceRepository): Response
    {
        return $this->render('storage_space/get_all_storage_space.html.twig', [
            'storageSpaces' => $storageSpaceRepository->find_All_storage(),
        ]);
    }

    /**
     * @Route("/storageSpace/user", name="storage_space_for_user")
     */
    public function getAllStorageSpaceForUser(StorageSpaceRepository $storageSpaceRepository): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        return $this->render('storage_space/get_all_storage_space_for_user.html.twig', [
            'storageSpaces' => $storageSpaceRepository->findBy(['owner' => $user]),
        ]);
    }

    /**
     * @Route("/storageSpace/{id}", name="storage_space_one", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function getOneProduct(
        StorageSpace $storageSpace,
        Request $request,
        CommentManager $commentManager
    ): Response {
        // Comment part
        /** @var Comment */
        $comment = new Comment();

        /** @var Form */
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            /** @var User|null */
            $user = $this->getUser();

            if (!$user) {
                return $this->redirectToRoute('storage_space_all');
            }

            return $commentManager->createCommentFromProduct(
                $formComment,
                $comment,
                $storageSpace,
                $user
            );
        }

        return $this->render('storage_space/get_one_storage_space.html.twig', [
            'storageSpace' => $storageSpace,
            'formComment' => $formComment->createView(),
        ]);
    }

    /**
     * @Route("/storageSpace/add", name="storage_space_add")
     */
    public function createStorageSpace(
        Request $request,
        StorageSpaceManager $storageSpaceManager
    ): Response {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        $storageSpace = new StorageSpace();
        $form = $this->createForm(StorageSpaceType::class, $storageSpace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $storageSpaceManager->createStorageSpace($storageSpace, $user);
        }

        return $this->render('storage_space/create_storage_space.html.twig', [
            'formStorageSpace' => $form->createView(),
        ]);
    }

    /**
     * @Route("/storageSpace/edit/{id}", name="storage_space_edit", requirements={"id": "\d+"}, methods={"GET", "PUT"})
     */
    public function editStorageSpace(
        StorageSpace $storageSpace,
        Request $request,
        StorageSpaceManager $storageSpaceManager
    ): Response {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        // voter
        $this->denyAccessUnlessGranted('edit', $storageSpace);

        $form = $this->createForm(StorageSpaceType::class, $storageSpace, ['method' => 'PUT']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $storageSpaceManager->updateStorageSpace($storageSpace);
        }

        return $this->render('storage_space/edit_storage_space.html.twig', [
            'formStorageSpace' => $form->createView(),
        ]);
    }

    /**
     * @Route("/storageSpace/delete/{id}", name="storage_space_delete", requirements={"id": "\d+"})
     */
    public function deleteStorageSpace(
        StorageSpace $storageSpace,
        StorageSpaceManager $storageSpaceManager
    ): Response {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        // voter
        $this->denyAccessUnlessGranted('delete', $storageSpace);

        $storageSpaceManager->delete($storageSpace);

        $this->addFlash('success', 'Votre annonce a bien été supprimée.');

        return $this->redirectToRoute('storage_space_all');
    }
}
