<?php

namespace App\Controller\Admin;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController {

    #[Route('/admin/uzivatel/seznam', name: 'admin_person_list')]
    public function view(): Response {
        return $this->render('Admin/Person/list.html.twig', [
            "section" => "person",
            "page_name" => "Seznam uživatelů",
            "page_path" => array("Domov", "Uživatelé", "Seznam")
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/admin/uzivatel/zobrazit/{id}', name: 'admin_person_view')]
    public function info(int $id, ManagerRegistry $doctrine, Request $request): Response {
        $usr = $doctrine->getRepository(Person::class)->findOneByID($id);
        if ($usr == null) {
            $this->addFlash('warning', 'Tento uživatel nebyl nalezen!');
            return new RedirectResponse($this->generateUrl("admin_person_list"));
        }
        return $this->render('Admin/Person/view.html.twig', [
            "section" => "person",
            "page_name" => "Seznam uživatelů",
            "page_path" => array("Domov", "Uživatelé", "Seznam")
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/admin/uzivatele/upravit/{id}', name: 'admin_person_edit')]
    public function edit(int $id, ManagerRegistry $doctrine, Request $request): Response {
        $usr = $doctrine->getRepository(Person::class)->findOneByID($id);
        if ($usr == null) {
            $this->addFlash('warning', 'Tento uživatel nebyl nalezen!');
            return new RedirectResponse($this->generateUrl("admin_person_list"));
        }

        return $this->render('Admin/Person/edit.html.twig', [
            "section" => "person",
            "person" => $usr,
            "page_name" => "Úprava uživatele ID: ".$id,
            "page_path" => array("Domov", "Uživatelé", "Úprava uživatele")
        ]);
    }
}