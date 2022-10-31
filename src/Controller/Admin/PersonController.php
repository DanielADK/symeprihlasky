<?php

namespace App\Controller\Admin;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController {

    #[Route('/admin/dospeli/seznam', name: 'admin_person_list')]
    public function index(): Response {
        return $this->render('Admin/Person/list.html.twig', [
            "section" => "person",
            "page_name" => "Seznam dospělých",
            "page_path" => array("Domov", "Dospělých", "Seznam")
        ]);
    }

    /**
     */
    #[Route('/admin/dospeli/zobrazit/{id}', name: 'admin_person_view')]
    public function view(int $id, EntityManagerInterface $em, Request $request): Response {
        $usr = $em->getRepository(Person::class)->findOneBy(array("id" =>$id));
        if ($usr == null) {
            $this->addFlash('warning', 'Tento člověk nebyl nalezen!');
            return new RedirectResponse($this->generateUrl("admin_person_list"));
        }
        return $this->render('Admin/Person/view.html.twig', [
            "section" => "person",
            "person" => $usr,
            "page_name" => "Seznam dospělých",
            "page_path" => array("Domov", "Dospělí", "Seznam")
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/admin/dospeli/upravit/{id}', name: 'admin_person_edit')]
    public function edit(int $id, ManagerRegistry $doctrine, Request $request): Response {
        $usr = $doctrine->getRepository(Person::class)->findOneByID($id);
        if ($usr == null) {
            $this->addFlash('warning', 'Tento uživatel nebyl nalezen!');
            return new RedirectResponse($this->generateUrl("admin_person_list"));
        }

        return $this->render('Admin/Person/edit.html.twig', [
            "section" => "person",
            "person" => $usr,
            "page_name" => "Úprava ID: ".$id,
            "page_path" => array("Domov", "Dospělí", "Úprava")
        ]);
    }
}