<?php

namespace App\Controller;

use App\Entity\StorageSpace;
use App\Form\StorageSpaceType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StorageSpaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StorageSpaceController extends AbstractController
{
    /**
     * @Route("/storageSpace", name="storage_space_all")
     */
    public function get_all_storage_space(StorageSpaceRepository $repo): Response
    {
        $storageSpaces = $repo->findAll();

        return $this->render('storage_space/get_all_storage_space.html.twig', [
            'storageSpaces' => $storageSpaces,
        ]);
    }

    /**
     * @Route("/storageSpace/{id}", name="storage_space_one", requirements={"id": "\d+"}, methods="GET")
     */
    public function get_one_product($id, StorageSpaceRepository $repo)
    {

        $storageSpace = $repo->find($id);

        /* dump($product[0]);
        dd($product); */

        return $this->render('storage_space/get_one_storage_space.html.twig', [
            'storageSpace' => $storageSpace
        ]);
    }

    /**
     * @Route("/storageSpace/add", name="storage_space_add")
     */
    public function create_storage_space(Request $request, EntityManagerInterface $manager)
    {
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
        $manager->remove($storageSpace);
        $manager->flush();

        return $this->redirectToRoute('storage_space_all');
    }
}
