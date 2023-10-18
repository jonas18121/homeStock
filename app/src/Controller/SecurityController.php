<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     *@Route("/registration", name="app_registration")
     */
    public function register(Request $request, UserManager $userManager, UserPasswordEncoderInterface $encoder): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            return $userManager->register($user, $encoder);
        }

        return $this->render('security/registration.html.twig', [
            'formRegistration' => $form->createView(),
        ]);
    }



    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('storage_space_all');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // TOOLS

    /**
     *@Route("/registration/email/{email}", name="app_registration_user_exist")
     */
    public function isEmailExist(string $email, UserRepository $userRepository): JsonResponse
    {
        if (null !== $userRepository->isEmailExist($email)) {
            return new JsonResponse(['result' => 'error', 'data' => ['isEmailExist' => true]]);
        }

        return new JsonResponse(['result' => 'success', 'data' => ['isEmailExist' => false]]);
    }
}
