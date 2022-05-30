<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController {
    #[Route('/admin/zaznamy', name: 'admin_logs')]
    public function index(): Response {
        return $this->render('Admin/home.html.twig', [
            "section" => "logs",
            "page_name" => "Bezpečnostní záznamy",
            "page_path" => array("Domov", "Bezpečnostní záznamy")
        ]);
    }

}