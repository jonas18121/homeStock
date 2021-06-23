<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\StorageSpace;
use App\Form\CommentType;
use App\Form\StorageSpaceType;
use App\Repository\CommentRepository;
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
    public function get_all_storage_space_for_user(StorageSpaceRepository $repo): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $user = $this->getUser();

        $storageSpaces = $repo->findBy([ 'owner' => $user ]);

        return $this->render('storage_space/get_all_storage_space_for_user.html.twig', [
            'storageSpaces' => $storageSpaces,
        ]);
    }

    /**
     * @Route("/storageSpace/{id}", name="storage_space_one", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function get_one_product($id, StorageSpaceRepository $repo, Request $request)
    {
        $storageSpace = $repo->find_one_storage($id);

        if (!$storageSpace) {
            return $this->redirectToRoute('storage_space_all');
        }

        // Partie commentaire
        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {

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
     * calcule le prix par mois
     */
    public function price_by_month(StorageSpace $storageSpace)
    {
        $firstDayOfThisMonth = new DateTime('first day of this month');
        $lastDayOfThisMonth = new DateTime('last day of this month');

        $nbDays = $firstDayOfThisMonth->diff($lastDayOfThisMonth)->format('%R%a') ;
        $nbDays += '1';

        $priceByMonth = $storageSpace->getPriceByDays() * $nbDays;

        return $priceByMonth;
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
    public function delete_storage_space(StorageSpace $storageSpace, EntityManagerInterface $manager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }
        
        $manager->remove($storageSpace);
        $manager->flush();

        return $this->redirectToRoute('storage_space_all');
    }
}
