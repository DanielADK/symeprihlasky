<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController {

    #[Route('/admin/akce/seznam', name: 'admin_event_list')]
    public function index(): Response {
        return $this->render('Admin/home.html.twig', [
            "section" => "event",
            "page_name" => "Seznam akcí",
            "page_path" => array("Domov", "Akce", "Seznam")
        ]);
    }

    #[Route('/admin/akce/nahled/{id}', name: 'admin_event_view')]
    public function view(int $id): Response {
        return $this->render('Admin/home.html.twig', [
            "section" => "event",
            "page_name" => "Náhled akce ID: ".$id,
            "page_path" => array("Domov", "Akce", "Náhled akce")
        ]);
    }
}