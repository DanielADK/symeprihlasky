<?php

namespace App\Controller\Admin;

use App\Repository\PersonRepository;
use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class PersonController extends AbstractController {

    #[Route('/admin/uzivatele/seznam', name: 'admin_person_view')]
    public function view(): Response {
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
    public function edit(int $id, EntityManagerInterface $em, Request $request): Response {
        $usr = $em->getRepository("App:Person")->findOneByID($id);
        if ($usr == null) {
            $this->addFlash('warning', 'Tento uživatel nebyl nalezen.');
            return new RedirectResponse($this->generateUrl("admin_person_view"));
        }


        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                "label" => "Křestní jméno",
                "required" => true,
            ])
            ->add('surname', TextType::class, [
                "label" => "Příjmení",
                "required" => true,
            ])
            ->add('email', TextType::class, [
                "label" => "Email",
                "required" => true,
            ])
            ->add('birth_date', DateType::class, [
                "label" => "Datum narození",
            ])
            ->add('sex', )
        ;

        return $this->render('Admin/Person/edit.html.twig', [
            "section" => "person",
            "editedPerson" => $usr,
            "page_name" => "Úprava uživatele ID: ".$id,
            "page_path" => array("Domov", "Uživatelé", "Úprava uživatele")
        ]);
    }
}