<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController {

    #[Route('/admin/uzivatele/seznam', name: 'admin_person_view')]
    public function index(): Response {
        return $this->render('Admin/Person/view.html.twig', [
            "section" => "person",
            "page_name" => "Seznam uživatelů",
            "page_path" => array("Domov", "Uživatelé", "Seznam")
        ]);
    }

    #[Route('/admin/uzivatele/uprava/{id}', name: 'admin_person_edit')]
    public function view(int $id): Response {
        return $this->render('Admin/home.html.twig', [
            "section" => "person",
            "page_name" => "Úprava uživatele ID: ".$id,
            "page_path" => array("Domov", "Uživatelé", "Úprava uživatele")
        ]);
    }
}