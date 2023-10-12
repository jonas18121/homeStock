<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
// use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack; // from Symfony 5.3 session is in RequestStack
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface; // from Symfony 5.3 session is in RequestStack
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Base - Manager.
 */
class BaseManager
{
    protected RequestStack $requestStack;
    protected EntityManagerInterface $em;
    protected ValidatorInterface $validator;
    // protected LoggerInterface $logger;
    protected TranslatorInterface $translator;
    protected ParameterBagInterface $parameters;
    protected TokenStorageInterface $tokenStorage;
    protected UrlGeneratorInterface $urlGenerator;
    protected SessionInterface $session;



    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        TranslatorInterface $translator,
        ParameterBagInterface $parameters,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        UrlGeneratorInterface $urlGenerator,
        SessionInterface $session
    ) {
        $this->em = $entityManager;
        $this->validator = $validator;
        $this->translator = $translator;
        $this->parameters = $parameters;
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
    }

    public function em(): EntityManagerInterface {
        if (false === $this->em->getConnection()->isConnected()) {
            $this->em->getConnection()->close();
            $this->em->getConnection()->connect();
        }

        return $this->em;
    }

    public function getCurrentUser(): ?User
    {
        if (null !== $token = $this->tokenStorage->getToken()) {
            $user = $token->getUser();
            if ($user instanceof User) {
                return $user;
            }
        }

        return null;
    }

    public function redirectToRouteFromManager (string $pathName, array $paramUrl = []): RedirectResponse {
        return new RedirectResponse(
            $this->urlGenerator->generate(
                $pathName, 
                $paramUrl, 
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        );
    }

    public function addFlashFromManager (string $status, string $text) {
        /* @phpstan-ignore-next-line */
        // return $this->requestStack->getSession()->getFlashBag()->add($status, $text); symfony 5.3
        return $this->session->getFlashBag()->add($status, $text);
    }  
}