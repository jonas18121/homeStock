<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserAcountType;
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

        $this->denyAccessUnlessGranted('show', $user);

        return $this->render('user/get_one_user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * On utilise le groupe de validation 'validation_groups' => ['update_user'], 
     * pour pouvoir modifier le user sans le mot de passe
     * uniquement les champs qui sont dans UserAcountType seront pris en compte 
     * 
     * @Route("/user/edit/{id}", name="user_edit", requirements={"id": "\d+"}, methods={"GET", "PUT"})
     */
    public function edit_user(User $user, Request $request,  EntityManagerInterface $manager){

        if (!$this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $this->denyAccessUnlessGranted('edit', $user);

        $formUser = $this->createForm(UserAcountType::class, $user, [ 'method' => 'PUT', 'validation_groups' => ['update_user']]);

        $formUser->handleRequest($request);
       

        // dd($formUser);
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

        $this->denyAccessUnlessGranted('delete', $user);

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
