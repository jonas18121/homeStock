<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\StorageSpace;
use App\Form\CommentType;
use App\Form\StorageSpaceType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StorageSpaceController extends AbstractController
{
    /**
     * @Route("/", name="storage_space_all")
     */
    public function get_all_storage_space(StorageSpaceRepository $repo): Response
    {
        $storageSpaces = $repo->findAll();

        return $this->render('storage_space/get_all_storage_space.html.twig', [
            'storageSpaces' => $storageSpaces,
        ]);
    }

    /**
     * @Route("/storageSpace/{id}", name="storage_space_one", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function get_one_product($id, StorageSpaceRepository $repo, Request $request)
    {

        $storageSpace = $repo->find($id);


        // Partie commentaire

        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {

            // dd($comment);

            $comment->setDateCreatedAt(new DateTime())
                ->setStorageSpace($storageSpace)
                ->setOwner($this->getUser())
            ;

            //récupérer le contenu du champ parentid
            $parentid = $formComment->get("parentid")->getData();
            
            $manager = $this->getDoctrine()->getManager();
            
            //on va chercher le commentaire correspondant
            if ($parentid != null) {
                $parent = $manager->getRepository(Comment::class)->find($parentid);
            }
            
            // On définit le commentaire parent
            $comment->setParent($parent ?? null); 

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('message', 'Votre commentaire a bien été envoyé');
            return $this->redirectToRoute('storage_space_one', [ 'id' => $storageSpace->getId()]);
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
    public function edit_product(StorageSpace $storageSpace, Request $request, EntityManagerInterface $manager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $form = $this->createForm(StorageSpaceType::class, $storageSpace, [ 'method' => 'PUT' ]);

        $form->handleRequest($request);

        
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
    public function delete_product(StorageSpace $storageSpace, EntityManagerInterface $manager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }
        
        $manager->remove($storageSpace);
        $manager->flush();

        return $this->redirectToRoute('storage_space_all');
    }
}
