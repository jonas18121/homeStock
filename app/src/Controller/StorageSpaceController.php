<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\StorageSpace;
use App\Form\StorageSpaceType;
use App\Manager\CommentManager;
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
     * @Route("/", name="home")
     */
    public function index(StorageSpaceRepository $repo)
    {
        return $this->get_all_storage_space($repo);
    }

    /**
     * @Route("/storageSpace", name="storage_space_all")
     */
    public function get_all_storage_space(StorageSpaceRepository $repo): Response
    {
        $storageSpaces = $repo->find_All_storage();

        return $this->render('storage_space/get_all_storage_space.html.twig', [
            'storageSpaces' => $storageSpaces,
        ]);
    }

    /**
     * @Route("/storageSpace/user", name="storage_space_for_user")
     */
    public function get_all_storage_space_for_user(StorageSpaceRepository $storageSpaceRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('storage_space_all');
        }

        $storageSpaces = $storageSpaceRepository->findBy([ 'owner' => $user ]);

        return $this->render('storage_space/get_all_storage_space_for_user.html.twig', [
            'storageSpaces' => $storageSpaces,
        ]);
    }

    /**
     * @Route("/storageSpace/{id}", name="storage_space_one", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function get_one_product(
        StorageSpace $storageSpace, 
        Request $request,
        CommentManager $commentManager
    )
    {
        if (!$storageSpace) {
            return $this->redirectToRoute('storage_space_all');
        }

        // Comment part
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            return $commentManager->createCommentFromProduct(
                $formComment,
                $comment, 
                $storageSpace, 
                $this->getUser()
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
    public function create_storage_space(Request $request, EntityManagerInterface $manager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $storageSpace = new StorageSpace;

        $form = $this->createForm(StorageSpaceType::class, $storageSpace);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // $priceByMonth = $this->price_by_month($storageSpace);
            // dd($storageSpace);

            $storageSpace->setDateCreatedAt(new \DateTime())
                ->setOwner($this->getUser())
                ->setAvailable(true)
            ;

            $manager->persist($storageSpace);
            $manager->flush();

            return $this->redirectToRoute('storage_space_all');
        }

        return $this->render('storage_space/create_storage_space.html.twig', [
            'formStorageSpace' => $form->createView()
        ]);
    }

    /**
     * @Route("/storageSpace/edit/{id}", name="storage_space_edit", requirements={"id": "\d+"}, methods={"GET", "PUT"})
     */
    public function edit_storage_space(StorageSpace $storageSpace, Request $request, EntityManagerInterface $manager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->denyAccessUnlessGranted('edit', $storageSpace);

        $form = $this->createForm(StorageSpaceType::class, $storageSpace, [ 'method' => 'PUT' ]);

        $form->handleRequest($request);
        
        //faire les voter https://symfony.com/doc/current/security/voters.html
        // if($this->getUser()->getId() !== $form->getViewData()->getOwner()->getId()){
        //     // return $this->redirectToRoute('storage_space_all');
        //     throw $this->createAccessDeniedException();
        // }

        
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($storageSpace);
            $manager->flush();

            return $this->redirectToRoute('storage_space_all');
        }

        return $this->render('storage_space/edit_storage_space.html.twig', [
            'formStorageSpace' => $form->createView()
        ]);
    }

    /**
     * @Route("/storageSpace/delete/{id}", name="storage_space_delete", requirements={"id": "\d+"})
     */
    public function delete_storage_space(StorageSpace $storageSpace, EntityManagerInterface $manager)
    {
        // if (!$this->getUser() || $this->getUser()->getId() !== $storageSpace->getOwner()->getId()) {
        //     return $this->redirectToRoute('storage_space_all');
        // }

        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->denyAccessUnlessGranted('delete', $storageSpace);
        
        $manager->remove($storageSpace);
        $manager->flush();

        return $this->redirectToRoute('storage_space_all');
    }
}
