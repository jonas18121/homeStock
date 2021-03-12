<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StorageSpaceController extends AbstractController
{
    /**
     * @Route("/storageSpace", name="storage_space_all")
     */
    public function index(): Response
    {
        return $this->render('storage_space/index.html.twig', [
            'controller_name' => 'StorageSpaceController',
        ]);
    }
}
