<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\StorageSpace;
use App\Form\StorageSpaceType;
use App\Manager\CommentManager;
use Symfony\Component\Form\Form;
use App\Manager\StorageSpaceManager;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StorageSpaceController extends AbstractController
{
     /** 
     * TODO : Create HomePageController
     * 
     * @Route("/", name="home")
     */
    public function index(StorageSpaceRepository $storageSpaceRepository): Response
    {
        return $this->get_all_storage_space($storageSpaceRepository);
    }

    /**
     * @Route("/storageSpace", name="storage_space_all")
     */
    public function get_all_storage_space(StorageSpaceRepository $storageSpaceRepository): Response
    {
        return $this->render('storage_space/get_all_storage_space.html.twig', [
            'storageSpaces' => $storageSpaceRepository->find_All_storage(),
        ]);
    }

    /**
     * @Route("/storageSpace/user", name="storage_space_for_user")
     */
    public function get_all_storage_space_for_user(StorageSpaceRepository $storageSpaceRepository): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        return $this->render('storage_space/get_all_storage_space_for_user.html.twig', [
            'storageSpaces' => $storageSpaceRepository->findBy([ 'owner' => $user ]),
        ]);
    }

    /**
     * @Route("/storageSpace/{id}", name="storage_space_one", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function get_one_product(
        StorageSpace $storageSpace, 
        Request $request,
        CommentManager $commentManager
    ): Response
    {
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
            'formComment' => $formComment->createView()
        ]);
    }

    /**
     * @Route("/storageSpace/add", name="storage_space_add")
     */
    public function create_storage_space(
        Request $request, 
        StorageSpaceManager $storageSpaceManager
    ): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        $storageSpace = new StorageSpace;
        $form = $this->createForm(StorageSpaceType::class, $storageSpace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $storageSpaceManager->createStorageSpace($storageSpace, $user);
        }

        return $this->render('storage_space/create_storage_space.html.twig', [
            'formStorageSpace' => $form->createView()
        ]);
    }

    /**
     * @Route("/storageSpace/edit/{id}", name="storage_space_edit", requirements={"id": "\d+"}, methods={"GET", "PUT"})
     */
    public function edit_storage_space(
        StorageSpace $storageSpace, 
        Request $request, 
        StorageSpaceManager $storageSpaceManager
    ): Response
    {
        /** @var User|null */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        // voter
        $this->denyAccessUnlessGranted('edit', $storageSpace);

        $form = $this->createForm(StorageSpaceType::class, $storageSpace, [ 'method' => 'PUT' ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $storageSpaceManager->updateStorageSpace($storageSpace);
        }

        return $this->render('storage_space/edit_storage_space.html.twig', [
            'formStorageSpace' => $form->createView()
        ]);
    }

    /**
     * @Route("/storageSpace/delete/{id}", name="storage_space_delete", requirements={"id": "\d+"})
     */
    public function delete_storage_space(
        StorageSpace $storageSpace, 
        StorageSpaceManager $storageSpaceManager
    ): Response
    {
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
