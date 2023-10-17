<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * User - Manager.
 */
class UserManager extends BaseManager
{ 
    public function updateThenRedirect(User $user): RedirectResponse
    {
        $this->save($user);
        $this->addFlashFromManager('success', 'Votre compte a bien été modifiée.');
        return $this->redirectToRouteFromManager('user_one', ['id' => $user->getId()]);
    }

    public function userDeletesTheirAccount(User $user, bool $disable = false): void
    {
        $this->delete($user, $disable);

        $this->tokenStorage->setToken(null); // $this->get('security.token_storage')->setToken(null); in controller
        $this->session->clear();
        $this->session->invalidate(1); // $this->get('session')->invalidate(); in controller

        $this->addFlashFromManager('success', 'Votre compte a bien été supprimée.');
    }

    public function register(
        User $user, 
        UserPasswordEncoderInterface $encoder
    ): RedirectResponse
    {
        $hash = $encoder->encodePassword($user, $user->getPassword());
        $user->setCreatedAt(new \DateTime());
        $user->setPassword($hash);

        $this->save($user);
        $this->addFlashFromManager('success', 'Votre compte a bien été créé, Connectez-vous !');
        return $this->redirectToRouteFromManager('app_login');
    }

    public function save(User $user): User 
    {
        $em = $this->em();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    public function delete(
        User $user,
        bool $disable = false
    ): void {
        if ($disable) {
            $user->setDeletedAt((new \DateTime('now'))->setTimezone(new \DateTimeZone('UTC')));
            $this->save($user);
        } else {
            $em = $this->em();
            $em->remove($user);
            $em->flush();
        }
    }
}