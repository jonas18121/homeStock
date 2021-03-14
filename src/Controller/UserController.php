<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_one", requirements={"id": "\d+"}, methods="GET")
     */
    public function get_one_user(User $user): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        return $this->render('user/get_one_user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit", requirements={"id": "\d+"}, methods={"GET", "PUT"})
     */
    public function edit_user(User $user, Request $request,  EntityManagerInterface $manager){

        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $formUser = $this->createForm(UserType::class, $user, [ 'method' => 'PUT' ]);

        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_one', [ 'id' => $user->getId()]);
        }

        return $this->render('user/edit_user.html.twig', [
            'formUser' => $formUser->createView()
        ]);
    }
    
    /**
     * @Route("/user/delete", name="user_delete", requirements={"id": "\d+"})
     */
    public function delete_user( EntityManagerInterface $manager, Request $request){

        
        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }
        
        $user = $this->getUser();

        if($this->isCsrfTokenValid('delete', $request->get('_token'))){

            $manager->remove($user);
            $manager->flush();

            $this->get('security.token_storage')->setToken(null);
            $this->get('session')->invalidate();

            $this->addFlash('success',"Votre compte a été supprimé !");
        }

        return $this->redirectToRoute('app_login');
    }
}
