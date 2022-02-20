<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController {

    #[Route('/admin/uzivatele/seznam', name: 'admin_person_list')]
    public function index(): Response {
        return $this->render('Admin/Person/list.html.twig', [
            "section" => "person",
            "page_name" => "Seznam uživatelů",
            "page_path" => array("Domov", "Uživatelé", "Seznam")
        ]);
    }

    #[Route('/admin/uzivatele/nahled/{id}', name: 'admin_person_view')]
    public function view(int $id): Response {
        return $this->render('Admin/home.html.twig', [
            "section" => "person",
            "page_name" => "Uživatel ID: ".$id,
            "page_path" => array("Domov", "Uživatelé", "Náhled uživatele")
        ]);
    }
}