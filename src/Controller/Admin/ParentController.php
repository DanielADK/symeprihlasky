<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParentController extends AbstractController {
    #[Route("/admin/rodic/seznam", name: 'admin_parent_list')]
    public function view(): Response {
        return $this->render("Admin/Parent/list.html.twig", [
            "section" => "parent",
            "page_name" => "Seznam rodičů",
            "page_path" => array("Domov", "Rodiče", "Seznam")
        ]);
    }
}