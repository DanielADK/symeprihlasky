<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    #[Route('/admin/domov', name: 'admin_home')]
    public function index(): Response {
        return $this->render('Admin/home.html.twig');
    }
}