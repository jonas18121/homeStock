<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Manager;

use App\Entity\User;
// use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface; // from Symfony 5.3 session is in RequestStack
use Symfony\Contracts\Translation\TranslatorInterface; // from Symfony 5.3 session is in RequestStack

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

    public function em(): EntityManagerInterface
    {
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

    /**
     * @param array<string, string|int> $paramUrl
     */
    public function redirectToRouteFromManager(string $pathName, array $paramUrl = []): RedirectResponse
    {
        return new RedirectResponse(
            $this->urlGenerator->generate(
                $pathName,
                $paramUrl,
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        );
    }

    public function addFlashFromManager(string $status, string $text): void
    {
        /* @phpstan-ignore-next-line */
        $this->session->getFlashBag()->add($status, $text);
        // return $this->requestStack->getSession()->getFlashBag()->add($status, $text); symfony 5.3
    }
}
