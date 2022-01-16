<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UvodController extends AbstractController {

    #[Route('/admin/uvod', name: 'admin_uvoduvod')]
    public function index(): Response {
        return $this->render('base.html.twig');
    }
}